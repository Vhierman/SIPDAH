<?php

namespace App\Exports;

use Carbon\Carbon;
use App\Models\Admin\Employees;
use App\Models\Admin\Companies;
use App\Models\Admin\HistoryFamilies;
use App\Models\Admin\Areas;
use App\Models\Admin\Divisions;
use App\Models\Admin\Positions;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use DB;

class EmployeesExport implements FromCollection, WithHeadings, WithMapping
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        $employees = Employees::with([
            'companies',
            'areas',
            'divisions',
            'positions'
            ])->orderBy('divisions_id')->get();
        return $employees ;
    }
    public function map($employees): array
    {
        
        $items = [];
        foreach($employees as $item){
            array_push($items);

            $hitungkeluarga = HistoryFamilies::with([
                'employees'
                ])->where('employees_id', $employees->nik_karyawan)->count();
            if ($hitungkeluarga == null) {
                $jumlahkeluarga = 0;
            }
            else{
                $jumlahkeluarga = $hitungkeluarga-1;
            }

            if ($employees->status_nikah == "Single") {
                $statuspajak = "tk/";
            }
            else{
                $statuspajak = "k/";
            }

            $statusptkp = $statuspajak.$jumlahkeluarga;

        }

        $nik_karyawan           = "'".$employees->nik_karyawan;
        $nomor_kartu_keluarga   = "'".$employees->nomor_kartu_keluarga;
        $nomor_jkn              = "'".$employees->nomor_jkn;
        $nomor_jht              = "'".$employees->nomor_jht;
        $rt                     = "'".$employees->rt;
        $rw                     = "'".$employees->rw;
        $nomor_npwp             = "'".$employees->nomor_npwp;
        $nomor_rekening             = "'".$employees->nomor_rekening;
        
        $tanggal_akhir_kerja = $employees->tanggal_akhir_kerja;

        if ($employees->status_kerja == "PKWTT") {
            $tanggalakhirkerja = "PKWTT";
        } else {
            $tanggalakhirkerja = \Carbon\Carbon::parse($tanggal_akhir_kerja)->isoformat('D MMMM Y');
        }
        

        return [
            [
                $employees->id,
                $statusptkp,
                $employees->companies->id,
                $employees->companies->nama_perusahaan,
                $employees->areas->id,
                $employees->areas->area,
                $employees->divisions->id,
                $employees->divisions->penempatan,
                $employees->positions->id,
                $employees->positions->jabatan,
                $nik_karyawan,
                $employees->nama_karyawan,
                $employees->email_karyawan,
                $employees->nomor_handphone,
                $employees->nomor_absen,
                $nomor_npwp,
                $employees->tempat_lahir,
                \Carbon\Carbon::parse($employees->tanggal_lahir)->isoformat('D MMMM Y'),
                $employees->agama,
                $employees->jenis_kelamin,
                $employees->pendidikan_terakhir,
                $employees->golongan_darah,
                $employees->status_kerja,
                \Carbon\Carbon::parse($employees->tanggal_mulai_kerja)->isoformat('Y-MM-D'),
                $tanggalakhirkerja,
                $nomor_rekening,
                $nomor_kartu_keluarga,
                $employees->status_nikah,
                $employees->nama_ayah,
                $employees->nama_ibu,
                $nomor_jkn,
                $nomor_jht,
                $employees->alamat,
                $employees->rt,
                $employees->rw,
                $employees->kelurahan,
                $employees->kecamatan,
                $employees->kota,
                $employees->provinsi,
                $employees->kode_pos,
                $items
            ],

        ];

    }
    public function headings(): array
    {
        return [
            'ID',
            'Status Pajak ACC',
            'Kode Perusahaan',
            'Perusahaan',
            'Kode Area',
            'Area',
            'Kode Penempatan',
            'Penempatan',
            'Kode Jabatan',
            'Jabatan',
            'NIK Karyawan',
            'Nama Karyawan',
            'Email',
            'No Handphone',
            'No Absen',
            'No NPWP',
            'Tempat Lahir',
            'Tanggal Lahir',
            'Agama',
            'Jenis Kelamin',
            'Pendidikan Terakhir',
            'Golongan Darah',
            'Status Kerja',
            'Tanggal Mulai Kerja',
            'Tanggal Akhir Kerja',
            'No Rekening',
            'Nomor KK',
            'Status Nikah',
            'Nama Ayah',
            'Nama Ibu',
            'No JKN',
            'No JHT',
            'Alamat',
            'RT',
            'RW',
            'Kelurahan',
            'Kecamatan',
            'Kabupaten/Kota',
            'Provinsi',
            'Kode POS'
        ];
    }
}
