<?php

namespace App\Exports;

use Carbon\Carbon;
use App\Models\Admin\Employees;
use App\Models\Admin\Companies;
use App\Models\Admin\Overtimes;
use App\Models\Admin\Areas;
use App\Models\Admin\Divisions;
use App\Models\Admin\Positions;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use DB;

class OvertimeExport implements FromCollection, WithHeadings, WithMapping
{
    /**
    * @return \Illuminate\Support\Collection
    */

    protected $awal,$akhir,$divisions_id,$status_kerja;

    function __construct($awal,$akhir,$divisions_id,$status_kerja) {
        $this->awal         = $awal;
        $this->akhir        = $akhir;
        $this->divisions_id = $divisions_id;
        $this->status_kerja = $status_kerja;
}

    public function collection()
    {
        $divisi = '';

        if ($this->divisions_id == 'Produksi') {
            $divisi = array('11');
        }
        elseif ($this->divisions_id == 'Office') {
            $divisi = array('1','2','3','4','5','6','7','9','10','17');
        } 
        elseif ($this->divisions_id == 'Warehouse') {
            $divisi = array('12','13','14','15','18');
        } 
        elseif ($this->divisions_id == 'Quality') {
            $divisi = array('8');
        } 
        elseif ($this->divisions_id == 'PDC') {
            $divisi = array('19','20','21','22');
        } 
        else {
            abort(403);
        }

        $overtimes = DB::table('overtimes')
        ->join('employees', 'employees.nik_karyawan', '=', 'overtimes.employees_id')
        ->groupBy('employees_id','nama_karyawan','status_kerja')
        ->select('employees_id','nama_karyawan','status_kerja', DB::raw('sum(jumlah_jam_pertama) as jumlah_jam_pertama'),DB::raw('sum(jumlah_jam_kedua) as jumlah_jam_kedua'),DB::raw('sum(jumlah_jam_ketiga) as jumlah_jam_ketiga'),DB::raw('sum(jumlah_jam_keempat) as jumlah_jam_keempat'),DB::raw('sum(uang_makan_lembur) as uang_makan_lembur'))
        ->whereIn('divisions_id',$divisi)
        ->where('overtimes.acc_hrd','<>',NULL)
        ->where('overtimes.deleted_at',NULL)
        ->where('status_kerja',$this->status_kerja)
        ->whereBetween('tanggal_lembur', [$this->awal, $this->akhir])
        ->orderBy('nama_karyawan')
        ->get();

        return $overtimes ;
    }

    public function map($overtimes): array
    {
        
        $items = [];
        foreach($overtimes as $item){
            array_push($items);
        }

        $employees_id   = "'".$overtimes->employees_id;
        $nama_karyawan  = "'".$overtimes->nama_karyawan;
        
        return [
            [
                $employees_id,
                $nama_karyawan,
            ],
        ];

    }
    public function headings(): array
    {
        return [
            'NIK',
            'Jam Masuk'
        ];
    }
}
