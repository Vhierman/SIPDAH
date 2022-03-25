<?php

namespace App\Exports;

use Carbon\Carbon;
use App\Models\Admin\Employees;
use App\Models\Admin\EmployeesOuts;
use App\Models\Admin\Companies;
use App\Models\Admin\HistoryFamilies;
use App\Models\Admin\Areas;
use App\Models\Admin\Divisions;
use App\Models\Admin\Positions;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use DB;

class EmployeesOutExport implements FromCollection, WithHeadings, WithMapping
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        $employeesouts = EmployeesOuts::with([
            'companies',
            'areas',
            'divisions',
            'positions'
            ])->orderBy('divisions_id')->orderBy('nama_karyawan_keluar')->get();

        return $employeesouts ;
    }
    public function map($employeesouts): array
    {
        
        $items = [];
        foreach($employeesouts as $item){
            array_push($items);
        }

        $employees_id                           = "'".$employeesouts->employees_id;
        $nomor_kartu_keluarga_karyawan_keluar   = "'".$employeesouts->nomor_kartu_keluarga_karyawan_keluar;
        $nomor_jkn_karyawan_keluar              = "'".$employeesouts->nomor_jkn_karyawan_keluar;
        $nomor_jht_karyawan_keluar              = "'".$employeesouts->nomor_jht_karyawan_keluar;
        $rt_karyawan_keluar                     = "'".$employeesouts->rt_karyawan_keluar;
        $rw_karyawan_keluar                     = "'".$employeesouts->rw_karyawan_keluar;
        $nomor_npwp_karyawan_keluar             = "'".$employeesouts->nomor_npwp_karyawan_keluar;
        $nomor_rekening_karyawan_keluar         = "'".$employeesouts->nomor_rekening_karyawan_keluar;
        $nomor_handphone_karyawan_keluar        = "'".$employeesouts->nomor_handphone_karyawan_keluar;
        $nomor_absen_karyawan_keluar            = "'".$employeesouts->nomor_absen_karyawan_keluar;
        
        return [
            [
                $employeesouts->id,
                $employeesouts->companies->nama_perusahaan,
                $employeesouts->areas->area,
                $employeesouts->divisions->penempatan,
                $employeesouts->positions->jabatan,
                $employees_id,
                $employeesouts->nama_karyawan_keluar,
                $nomor_npwp_karyawan_keluar,
                $nomor_handphone_karyawan_keluar,
                $employeesouts->email_karyawan_keluar,
                $employeesouts->tempat_lahir_karyawan_keluar,
                \Carbon\Carbon::parse($employeesouts->tanggal_lahir_karyawan_keluar)->isoformat('DD-MM-Y'),
                $employeesouts->jenis_kelamin_karyawan_keluar,
                $employeesouts->pendidikan_terakhir_karyawan_keluar,
                $employeesouts->agama_karyawan_keluar,
                $employeesouts->golongan_darah_karyawan_keluar,
                $nomor_rekening_karyawan_keluar,
                $nomor_jht_karyawan_keluar,
                $nomor_jkn_karyawan_keluar,
                $nomor_absen_karyawan_keluar,
                $nomor_kartu_keluarga_karyawan_keluar,
                $employeesouts->status_nikah_karyawan_keluar,
                $employeesouts->nama_ayah_karyawan_keluar,
                $employeesouts->nama_ibu_karyawan_keluar,
                $employeesouts->status_kerja_karyawan_keluar,
                \Carbon\Carbon::parse($employeesouts->tanggal_masuk_karyawan_keluar)->isoformat('DD-MM-Y'),
                \Carbon\Carbon::parse($employeesouts->tanggal_keluar_karyawan_keluar)->isoformat('DD-MM-Y'),
                $employeesouts->keterangan_keluar,
                $employeesouts->alamat_karyawan_keluar,
                $employeesouts->rt_karyawan_keluar,
                $employeesouts->rw_karyawan_keluar,
                $employeesouts->kelurahan_karyawan_keluar,
                $employeesouts->kecamatan_karyawan_keluar,
                $employeesouts->kota_karyawan_keluar,
                $employeesouts->provinsi_karyawan_keluar,
                $employeesouts->kode_pos_karyawan_keluar,
                $items
            ],

        ];

    }
    
    public function headings(): array
    {
        return [
            'ID',
            'Perusahaan',
            'Area',
            'Divisi',
            'Jabatan',
            'NIK Karyawan',
            'Nama Karyawan',
            'Nomor NPWP',
            'Nomor Handphone',
            'Email',
            'Tempat Lahir',
            'Tanggal Lahir',
            'Jenis Kelamin',
            'Pendidikan Terakhir',
            'Agama',
            'Golongan Darah',
            'Nomor Rekening',
            'Nomor JHT',
            'Nomor JKN',
            'Nomor Absen',
            'Nomor KK',
            'Status Nikah',
            'Nama Ayah',
            'Nama Ibu',
            'Status Kerja',
            'Tanggal Masuk',
            'Tanggal Keluar',
            'Keterangan Keluar',
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
