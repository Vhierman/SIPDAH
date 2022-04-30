<?php

namespace App\Exports;

use Carbon\Carbon;
use App\Models\Admin\RekapSalaries;
use App\Models\Admin\Employees;
use App\Models\Admin\Areas;
use App\Models\Admin\Divisions;
use App\Models\Admin\Positions;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use DB;

class RekapSalaryExport implements FromCollection, WithHeadings, WithMapping
{
    /**
    * @return \Illuminate\Support\Collection
    */

    protected $awal;

    function __construct($awal) {
            $this->awal = $awal;
    }

    public function collection()
    {
        $rekapsalaries = 
        DB::table('rekap_salaries')
        ->join('employees', 'employees.nik_karyawan', '=', 'rekap_salaries.employees_id')
        ->join('divisions', 'divisions.id', '=', 'employees.divisions_id')
        ->join('areas', 'areas.id', '=', 'employees.areas_id')
        ->join('positions', 'positions.id', '=', 'employees.positions_id')
        ->where('periode_awal','=', $this->awal)
        ->where('rekap_salaries.deleted_at','=',null)
        ->get();


        return $rekapsalaries ;
    }

    public function map($rekapsalaries): array
    {    
        $items = [];
        foreach($rekapsalaries as $item){
            array_push($items);
        }

        //Menambahkan ' di depan huruf agar ketika di export excell tidak berantakan
        $periode_awal               = "'".$rekapsalaries->periode_awal;
        $periode_akhir              = "'".$rekapsalaries->periode_akhir;
        $nik_karyawan               = "'".$rekapsalaries->nik_karyawan;
        $nama_karyawan              = "'".$rekapsalaries->nama_karyawan;
        $area                       = "'".$rekapsalaries->area;
        $jabatan                    = "'".$rekapsalaries->jabatan;
        $penempatan                 = "'".$rekapsalaries->penempatan;
        $gaji_pokok                 = "'".$rekapsalaries->gaji_pokok;
        $uang_makan                 = "'".$rekapsalaries->uang_makan;
        $uang_transport             = "'".$rekapsalaries->uang_transport;
        $tunjangan_tugas            = "'".$rekapsalaries->tunjangan_tugas;
        $tunjangan_pulsa            = "'".$rekapsalaries->tunjangan_pulsa;
        $tunjangan_jabatan          = "'".$rekapsalaries->tunjangan_jabatan;
        $jumlah_upah                = "'".$rekapsalaries->jumlah_upah;
        $upah_lembur_perjam         = "'".$rekapsalaries->upah_lembur_perjam;
        $potongan_bpjsks_perusahaan = "'".$rekapsalaries->potongan_bpjsks_perusahaan;
        $potongan_jht_perusahaan    = "'".$rekapsalaries->potongan_jht_perusahaan;
        $potongan_jp_perusahaan     = "'".$rekapsalaries->potongan_jp_perusahaan;
        $potongan_jkm_perusahaan    = "'".$rekapsalaries->potongan_jkm_perusahaan;
        $potongan_jkk_perusahaan    = "'".$rekapsalaries->potongan_jkk_perusahaan;
        $jumlah_bpjstk_perusahaan   = "'".$rekapsalaries->jumlah_bpjstk_perusahaan;
        $potongan_bpjsks_karyawan   = "'".$rekapsalaries->potongan_bpjsks_karyawan;
        $potongan_jht_karyawan      = "'".$rekapsalaries->potongan_jht_karyawan;
        $potongan_jp_karyawan       = "'".$rekapsalaries->potongan_jp_karyawan;
        $jumlah_bpjstk_karyawan     = "'".$rekapsalaries->jumlah_bpjstk_karyawan;
        $take_home_pay              = "'".$rekapsalaries->take_home_pay;
        //Menambahkan ' di depan huruf agar ketika di export excell tidak berantakan
        
        return [
            [
                $periode_awal,
                $periode_akhir,
                $nik_karyawan,
                $nama_karyawan,
                $area,
                $jabatan,
                $penempatan,
                $gaji_pokok,
                $uang_makan,
                $uang_transport,
                $tunjangan_tugas,
                $tunjangan_pulsa,
                $tunjangan_jabatan,
                $jumlah_upah,
                $upah_lembur_perjam,
                $potongan_bpjsks_perusahaan,
                $potongan_jht_perusahaan,
                $potongan_jp_perusahaan,
                $potongan_jkm_perusahaan,
                $potongan_jkk_perusahaan,
                $jumlah_bpjstk_perusahaan,
                $potongan_bpjsks_karyawan,
                $potongan_jht_karyawan,
                $potongan_jp_karyawan,
                $jumlah_bpjstk_karyawan,
                $take_home_pay
            ],

        ];

    }

    //Pengaturan Heading/JUDUL Excell
    public function headings(): array
    {
        return [
            'Periode Awal',
            'Periode Akhir',
            'NIK',
            'Nama Karyawan',
            'Area',
            'Jabatan',
            'Penempatan',
            'Gaji Pokok',
            'Uang Makan',
            'Uang Transport',
            'Tunjangan Tugas',
            'Tunjangan Pulsa',
            'Tunjangan Jabatan',
            'Jumlah Upah',
            'Upah Lembur Perjam',
            'Pot.BPJSKS Perusahaan',
            'Pot.JHT Perusahaan',
            'Pot.JP Perusahaan',
            'Pot.JKM Perusahaan',
            'Pot.JKK Perusahaan',
            'Jumlah BPJSTK Perusahaan',
            'Pot.BPJSKS Karyawan',
            'Pot.JHT Karyawan',
            'Pot.JP Karyawan',
            'Jumlah BPJSTK Karyawan',
            'Take Home Pay'
        ];
    }
}
