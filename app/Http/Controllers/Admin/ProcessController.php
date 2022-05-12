<?php

namespace App\Http\Controllers\Admin;

use App\Exports\RekonSalaryExport;
use Maatwebsite\Excel\Facades\Excel;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Codedge\Fpdf\Fpdf\Fpdf;
use App\Models\Admin\Employees;
use App\Models\Admin\Areas;
use App\Models\Admin\Divisions;
use App\Models\Admin\Positions;
use App\Models\Admin\HistoryContracts;
use App\Models\Admin\HistorySalaries;
use App\Models\Admin\RekapSalaries;
use App\Models\Admin\MaksimalBpjskesehatans;
use App\Models\Admin\MaksimalBpjsketenagakerjaans;
use App\Http\Requests\Admin\ProcessRequest;
use App\Http\Requests\Admin\ProcessPerpanjanganPKWTHarianRequest;
use App\Http\Requests\Admin\ProcessPerpanjanganPKWTKontrakRequest;
use App\Http\Requests\Admin\ProcessCetakPKWTMagangRequest;
use App\Http\Requests\Admin\RekonSalaryRequest;
use App\Http\Requests\Admin\EditSalaryRequest;
use Illuminate\Http\Request;
use Carbon\Carbon;
use File;
use Storage;
use Alert;

class ProcessController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function process_pkwt_harian()
    {
        if (auth()->user()->roles != 'ADMIN' && auth()->user()->roles != 'HRD') {
            abort(403);
        }
        return view('pages.admin.process.pkwt_harian.index');
    }

    public function tampil_pkwt_harian(ProcessRequest $request)
    {
        if (auth()->user()->roles != 'ADMIN' && auth()->user()->roles != 'HRD') {
            abort(403);
        }
        $akhir_kontrak  = $request->input('akhir_kontrak');

        $items = Employees::with([
            'divisions',
            'positions'
            ])->where('status_kerja','Harian')
            ->where('tanggal_akhir_kerja',$akhir_kontrak)->get();
        
        return view('pages.admin.process.pkwt_harian.tampil',[
            'items' => $items,
            'akhir_kontrak' => $akhir_kontrak
        ]);
     
    }

    public function prosess_pkwt_harian($akhir_kontrak)
    {   
        if (auth()->user()->roles != 'ADMIN' && auth()->user()->roles != 'HRD') {
            abort(403);
        }
        $items = Employees::where('tanggal_akhir_kerja', $akhir_kontrak)->where('status_kerja','Harian')->get();

        return view('pages.admin.process.pkwt_harian.prosess',[
            'items'         => $items,
            'akhir_kontrak' => $akhir_kontrak
        ]);
        
    }

    public function perpanjang_pkwt_harian(ProcessPerpanjanganPKWTHarianRequest $request)
    {
        //
        if (auth()->user()->roles != 'ADMIN' && auth()->user()->roles != 'HRD') {
            abort(403);
        }
        $akhirkontrak       = $request->input('akhirkontrak');
        $awal_kontrak       = $request->input('awal_kontrak');
        $akhir_kontrak      = $request->input('akhir_kontrak');

        $items              = Employees::where('tanggal_akhir_kerja', $akhirkontrak)->where('status_kerja','Harian')->get();

        foreach ($items as $item) {
            HistoryContracts::create([
                'employees_id'                  => $item->nik_karyawan,
                'tanggal_awal_kontrak'          => $awal_kontrak,
                'tanggal_akhir_kontrak'         => $akhir_kontrak,
                'status_kontrak_kerja'          => 'Harian',
                'masa_kontrak'                  => '1 Bulan',
                'jumlah_kontrak'                => 1
            ]);

            $employees  = Employees::where('nik_karyawan', $item->nik_karyawan)->where('status_kerja','Harian')->first();
            $employees->update([
                'tanggal_akhir_kerja'   => $akhir_kontrak
            ]); 
        }
        Alert::success('Success Proses PKWT Harian','Oleh '.auth()->user()->name);
        return view('pages.admin.process.pkwt_harian.index');

    }

    public function process_pkwt_kontrak()
    {
        if (auth()->user()->roles != 'ADMIN' && auth()->user()->roles != 'HRD') {
            abort(403);
        }
        return view('pages.admin.process.pkwt_kontrak.index');
    }

    public function tampil_pkwt_kontrak(ProcessRequest $request)
    {
        if (auth()->user()->roles != 'ADMIN' && auth()->user()->roles != 'HRD') {
            abort(403);
        }
        $akhir_kontrak  = $request->input('akhir_kontrak');

        $items = Employees::with([
            'divisions',
            'positions'
            ])->where('status_kerja','PKWT')
            ->where('tanggal_akhir_kerja',$akhir_kontrak)->get();
        
        return view('pages.admin.process.pkwt_kontrak.tampil',[
            'items' => $items,
            'akhir_kontrak' => $akhir_kontrak
        ]);
    }

    public function prosess_pkwt_kontrak($akhir_kontrak)
    {   
        if (auth()->user()->roles != 'ADMIN' && auth()->user()->roles != 'HRD') {
            abort(403);
        }
        $items = Employees::where('tanggal_akhir_kerja', $akhir_kontrak)->where('status_kerja','PKWT')->get();

        return view('pages.admin.process.pkwt_kontrak.prosess',[
            'items'         => $items,
            'akhir_kontrak' => $akhir_kontrak
        ]);
    }

    public function perpanjang_pkwt_kontrak(ProcessPerpanjanganPKWTKontrakRequest $request)
    {
        //
        if (auth()->user()->roles != 'ADMIN' && auth()->user()->roles != 'HRD') {
            abort(403);
        }
        $akhirkontrak       = $request->input('akhirkontrak');
        $awal_kontrak       = $request->input('awal_kontrak');
        $akhir_kontrak      = $request->input('akhir_kontrak');

        $items              = Employees::where('tanggal_akhir_kerja', $akhirkontrak)->where('status_kerja','PKWT')->get();

        foreach ($items as $item) {
            HistoryContracts::create([
                'employees_id'                  => $item->nik_karyawan,
                'tanggal_awal_kontrak'          => $awal_kontrak,
                'tanggal_akhir_kontrak'         => $akhir_kontrak,
                'status_kontrak_kerja'          => 'PKWT',
                'masa_kontrak'                  => '1 Bulan',
                'jumlah_kontrak'                => 1
            ]);

            $employees  = Employees::where('nik_karyawan', $item->nik_karyawan)->where('status_kerja','PKWT')->first();
            $employees->update([
                'tanggal_akhir_kerja'   => $akhir_kontrak
            ]); 
        }
        Alert::success('Success Proses PKWT Kontrak','Oleh '.auth()->user()->name);
        return view('pages.admin.process.pkwt_kontrak.index');

    }

    public function process_magang()
    {
        if (auth()->user()->roles != 'ADMIN' && auth()->user()->roles != 'HRD') {
            abort(403);
        }
        return view('pages.admin.process.pkwt_harian_lepas.index');
    }

    public function hasil_cetak_pkwt_magang(ProcessCetakPKWTMagangRequest $request)
    {
        if (auth()->user()->roles != 'ADMIN' && auth()->user()->roles != 'HRD') {
            abort(403);
        }
        $nik_magang                     = $request->input('nik_magang');
        $nama_magang                    = $request->input('nama_magang');
        $jabatan_magang                 = $request->input('jabatan_magang');
        $penempatan_magang              = $request->input('penempatan_magang');
        $tempat_lahir_magang            = $request->input('tempat_lahir_magang');
        $tanggal_lahir_magang           = $request->input('tanggal_lahir_magang');
        $pendidikan_terakhir_magang     = $request->input('pendidikan_terakhir_magang');
        $jenis_kelamin_magang           = $request->input('jenis_kelamin_magang');
        $agama_magang                   = $request->input('agama_magang');
        $alamat_magang                  = $request->input('alamat_magang');
        $rt_magang                      = $request->input('rt_magang');
        $rw_magang                      = $request->input('rw_magang');
        $kelurahan_magang               = $request->input('kelurahan_magang');
        $kecamatan_magang               = $request->input('kecamatan_magang');
        $kota_magang                    = $request->input('kota_magang');
        $provinsi_magang                = $request->input('provinsi_magang');
        $cetak_surat_magang             = $request->input('cetak_surat_magang');
        $akhir_magang                   = $request->input('akhir_magang');

        //Create Nomor DOkumen
        $nik                            = substr($nik_magang, 12);
        $mytime                         = $cetak_surat_magang;
        $bulan                          = substr($mytime, 5, -3);
        $tahun                          = substr($mytime, 0,4);
        if ($bulan == 1) {
            $romawi = 'I';
        }
        elseif ($bulan == 2) {
            $romawi = 'II';
        } 
        elseif ($bulan == 3) {
            $romawi = 'III';
        } 
        elseif ($bulan == 4) {
            $romawi = 'IV';
        } 
        elseif ($bulan == 5) {
            $romawi = 'V';
        } 
        elseif ($bulan == 6) {
            $romawi = 'VI';
        } 
        elseif ($bulan == 7) {
            $romawi = 'VII';
        } 
        elseif ($bulan == 8) {
            $romawi = 'VIII';
        } 
        elseif ($bulan == 9) {
            $romawi = 'IX';
        } 
        elseif ($bulan == 10) {
            $romawi = 'X';
        } 
        elseif ($bulan == 11) {
            $romawi = 'XI';
        } 
        elseif ($bulan == 12) {
            $romawi = 'XII';
        } 
        else {
            $romawi = 'SALAH';
        }
        //Create Nomor DOkumen

        $this->fpdf = new FPDF('P', 'mm', 'A4');
        $this->fpdf->setTopMargin(10);
        $this->fpdf->setLeftMargin(4);
        $this->fpdf->SetAutoPageBreak(true);
        $this->fpdf->AddPage();
        $this->fpdf->SetFont('Arial', 'BU', '12');
        $this->fpdf->Cell(190, 10, 'SURAT PERJANJIAN KERJA HARIAN LEPAS', 0, 0, 'C');
        $this->fpdf->Ln(5);

        $this->fpdf->SetFont('Arial', 'B', '11');
        $this->fpdf->Cell(190, 10, 'No : ' . $nik . '/ PK / HRD / ' . $romawi . ' / ' . $tahun . '.', 0, 0, 'C');

        $this->fpdf->Ln(15);

        $this->fpdf->SetFont('Arial', '', '11');
        $this->fpdf->Cell(20);
        $this->fpdf->Cell(170, 5, 'Yang bertanda tangan di bawah ini :', 0, 0, 'L');

        $this->fpdf->Ln(10);

        $this->fpdf->Cell(10);
        $this->fpdf->Cell(10, 7, '1. ', 0, 0, 'L');
        $this->fpdf->Cell(50, 7, 'Nama', 0, 0, 'L');
        $this->fpdf->Cell(5, 7, ' : ', 0, 0, 'C');
        $this->fpdf->Cell(115, 7, ' Rudiyanto', 0, 0, 'L');

        $this->fpdf->Ln();

        $this->fpdf->Cell(20);
        $this->fpdf->Cell(50, 7, 'Jabatan', 0, 0, 'L');
        $this->fpdf->Cell(5, 7, ' : ', 0, 0, 'C');
        $this->fpdf->Cell(115, 7, ' Manager HRD-GA PT Prima Komponen Indonesia', 0, 0, 'L');

        $this->fpdf->Ln(10);
        $this->fpdf->Cell(20);
        $this->fpdf->Cell(170, 5, 'Dalam hal  ini  bertindak atas nama Manager HRD-GA PT Prima Komponen  Indonesia  yang', 0, 0, 'L');

        $this->fpdf->Ln();
        $this->fpdf->Cell(20);
        $this->fpdf->Cell(170, 5, 'berkedudukan di Kawasan Industri Pergudangan Taman Tekno Blok F2 No.10-11, Kelurahan', 0, 0, 'L');

        $this->fpdf->Ln();
        $this->fpdf->Cell(20);
        $this->fpdf->Cell(120, 5, 'Setu, Kecamatan Setu, Tangerang Selatan. Dan selanjutnya disebut', 0, 0, 'L');
        $this->fpdf->SetFont('Arial', 'B', '11');
        $this->fpdf->Cell(50, 5, ' PIHAK  PERTAMA (I).', 0, 0, 'L');

        $this->fpdf->Ln(10);

        $this->fpdf->SetFont('Arial', '', '11');
        $this->fpdf->Cell(10);
        $this->fpdf->Cell(10, 8, '2. ', 0, 0, 'L');
        $this->fpdf->Cell(50, 8, 'No.KTP/SIM', 0, 0, 'L');
        $this->fpdf->Cell(5, 8, ' : ', 0, 0, 'C');
        $this->fpdf->Cell(115, 8, ' '.$nik_magang, 0, 0, 'L');

        $this->fpdf->Ln();

        $this->fpdf->Cell(20);
        $this->fpdf->Cell(50, 8, 'Nama', 0, 0, 'L');
        $this->fpdf->Cell(5, 8, ' : ', 0, 0, 'C');
        $this->fpdf->Cell(115, 8, ' '.$nama_magang, 0, 0, 'L');

        $this->fpdf->Ln();

        $this->fpdf->Cell(20);
        $this->fpdf->Cell(50, 8, 'Tempat,Tanggal Lahir', 0, 0, 'L');
        $this->fpdf->Cell(5, 8, ' : ', 0, 0, 'C');
        $this->fpdf->Cell(115, 8, ' '.$tempat_lahir_magang.', '.\Carbon\Carbon::parse($tanggal_lahir_magang)->isoformat('D MMMM Y'), 0, 0, 'L');

        $this->fpdf->Ln();

        $this->fpdf->Cell(20);
        $this->fpdf->Cell(50, 8, 'Pendidikan Terakhir', 0, 0, 'L');
        $this->fpdf->Cell(5, 8, ' : ', 0, 0, 'C');
        $this->fpdf->Cell(115, 8, ' '.$pendidikan_terakhir_magang, 0, 0, 'L');

        $this->fpdf->Ln();

        $this->fpdf->Cell(20);
        $this->fpdf->Cell(50, 8, 'Jenis Kelamin', 0, 0, 'L');
        $this->fpdf->Cell(5, 8, ' : ', 0, 0, 'C');
        $this->fpdf->Cell(115, 8, ' '.$jenis_kelamin_magang, 0, 0, 'L');

        $this->fpdf->Ln();

        $this->fpdf->Cell(20);
        $this->fpdf->Cell(50, 8, 'Agama', 0, 0, 'L');
        $this->fpdf->Cell(5, 8, ' : ', 0, 0, 'C');
        $this->fpdf->Cell(115, 8, ' '.$agama_magang, 0, 0, 'L');

        $this->fpdf->Ln();

        $this->fpdf->Cell(20);
        $this->fpdf->Cell(50, 8, 'Alamat', 0, 0, 'L');
        $this->fpdf->Cell(5, 8, ' : ', 0, 0, 'C');
        $this->fpdf->Cell(115, 6, ' '.$alamat_magang.', '.$rt_magang.'/'.$rw_magang.', Kelurahan.'.$kelurahan_magang, 0, 0, 'L');

        $this->fpdf->Ln();
        $this->fpdf->Cell(75);
        $this->fpdf->Cell(115, 6, ' Kecamatan.'.$kecamatan_magang.', Kota.'.$kota_magang.', '.$provinsi_magang, 0, 0, 'L');

        $this->fpdf->Ln(10);
        $this->fpdf->Cell(20);
        $this->fpdf->Cell(144, 8, 'Dalam  hal  ini  bertindak  untuk dan atas nama dari pribadi dan selanjutnya disebut', 0, 0, 'L');
        $this->fpdf->SetFont('Arial', 'B', '11');
        $this->fpdf->Cell(27, 8, '  PIHAK', 0, 0, 'L');
        $this->fpdf->Ln();
        $this->fpdf->Cell(20);
        $this->fpdf->Cell(30, 8, 'KEDUA (II).', 0, 0, 'L');

        $this->fpdf->SetFont('Arial', 'B', '11');

        $this->fpdf->Ln(5);
        $this->fpdf->Cell(20);
        $this->fpdf->Cell(170, 5, 'PASAL 1', 0, 0, 'C');

        $this->fpdf->Ln();
        $this->fpdf->Cell(20);
        $this->fpdf->Cell(170, 5, 'PERNYATAAN - PERNYATAAN', 0, 0, 'C');

        $this->fpdf->SetFont('Arial', 'B', '11');
        $this->fpdf->Ln(10);
        $this->fpdf->Cell(20);
        $this->fpdf->Cell(170, 5, 'Ayat 1', 0, 0, 'C');

        $this->fpdf->Ln();
        $this->fpdf->Cell(20);
        $this->fpdf->Cell(35, 6, 'PIHAK PERTAMA ', 0, 0, 'L');

        $this->fpdf->SetFont('Arial', '', '11');
        $this->fpdf->Cell(96, 6, ' telah   menyatakan   persetujuannya  untuk   menerima', 0, 0, 'L');
        
        $this->fpdf->SetFont('Arial', 'B', '11');
        $this->fpdf->Cell(30, 6, ' PIHAK  KEDUA', 0, 0, 'L');
        
        $this->fpdf->SetFont('Arial', '', '11');
        $this->fpdf->Ln();
        $this->fpdf->Cell(20);
        $this->fpdf->Cell(170, 6, 'selaku pekerja harian lepas.', 0, 0, 'L');

        $this->fpdf->SetFont('Arial', 'B', '11');
        $this->fpdf->Ln(10);
        $this->fpdf->Cell(20);
        $this->fpdf->Cell(170, 5, 'Ayat 2', 0, 0, 'C');

        $this->fpdf->Ln();
        $this->fpdf->Cell(20);
        $this->fpdf->Cell(28, 6, 'PIHAK KEDUA ', 0, 0, 'L');

        $this->fpdf->SetFont('Arial', '', '11');
        $this->fpdf->Cell(96, 6, 'menyatakan kesediannya selaku pekerja harian lepas yang tunduk pada tata,', 0, 0, 'L');
        $this->fpdf->Ln();
        $this->fpdf->Cell(20);
        $this->fpdf->Cell(115, 6, 'tertib, peraturan, dan sistem kerja yang berlaku pada perusahaan', 0, 0, 'L');
        $this->fpdf->SetFont('Arial', 'B', '11');
        $this->fpdf->Cell(28, 6, 'PIHAK PERTAMA ', 0, 0, 'L');

        $this->fpdf->SetFont('Arial', 'B', '11');

        $this->fpdf->Ln(10);
        $this->fpdf->Cell(20);
        $this->fpdf->Cell(170, 5, 'PASAL 2', 0, 0, 'C');

        $this->fpdf->Ln();
        $this->fpdf->Cell(20);
        $this->fpdf->Cell(170, 5, 'RUANG LINGKUP PEKERJAAN', 0, 0, 'C');

        $this->fpdf->SetFont('Arial', 'B', '11');
        $this->fpdf->Ln(10);
        $this->fpdf->Cell(20);
        $this->fpdf->Cell(170, 5, 'Ayat 1', 0, 0, 'C');
        
        $this->fpdf->SetFont('Arial', '', '11');
        $this->fpdf->Ln();
        $this->fpdf->Cell(20);
        $this->fpdf->Cell(57, 6, 'Pekerjaan yang harus dilakukan', 0, 0, 'L');
        
        $this->fpdf->SetFont('Arial', 'B', '11');
        $this->fpdf->Cell(28, 6, 'PIHAK KEDUA ', 0, 0, 'L');

        $this->fpdf->SetFont('Arial', '', '11');
        $this->fpdf->Cell(58, 6, 'selaku pekerja harian lepas pada', 0, 0, 'L');
        $this->fpdf->SetFont('Arial', 'B', '11');
        $this->fpdf->Cell(28, 6, 'PIHAK', 0, 0, 'L');

        $this->fpdf->Ln();
        $this->fpdf->Cell(20);
        $this->fpdf->Cell(21, 6, 'PERTAMA', 0, 0, 'L');
        $this->fpdf->SetFont('Arial', '', '11');
        $this->fpdf->Cell(16, 6, ' adalah ', 0, 0, 'L');
        $this->fpdf->SetFont('Arial', 'BU', '11');
        $this->fpdf->Cell(60, 6, ''.$penempatan_magang.' / '.$jabatan_magang, 0, 0, 'L');

        $this->fpdf->SetFont('Arial', 'B', '11');
        $this->fpdf->Ln(10);
        $this->fpdf->Cell(20);
        $this->fpdf->Cell(170, 5, 'Ayat 2', 0, 0, 'C');

        $this->fpdf->Ln();
        $this->fpdf->Cell(20);
        $this->fpdf->Cell(28, 6, 'PIHAK KEDUA ', 0, 0, 'L');

        $this->fpdf->SetFont('Arial', '', '11');
        $this->fpdf->Cell(96, 6, 'tidak diperkenankan mengerjakan pekerjaan lain selain yang disebutkan pada,', 0, 0, 'L');
        $this->fpdf->Ln();
        $this->fpdf->Cell(20);
        $this->fpdf->Cell(116, 6, 'ayat 1 tersebut di atas, kecuali atas persetujuan dan perintah dari', 0, 0, 'L');
        $this->fpdf->SetFont('Arial', 'B', '11');
        $this->fpdf->Cell(36, 6, ' PIHAK  PERTAMA ', 0, 0, 'L');
        $this->fpdf->SetFont('Arial', '', '11');
        $this->fpdf->Cell(10, 6, ' atau ', 0, 0, 'L');
        $this->fpdf->Ln();
        $this->fpdf->Cell(20);
        $this->fpdf->Cell(15, 6, 'atasan', 0, 0, 'L');
        $this->fpdf->SetFont('Arial', 'B', '11');
        $this->fpdf->Cell(36, 6, 'PIHAK KEDUA.', 0, 0, 'L');



        $this->fpdf->Ln(100);

        $this->fpdf->Ln(50);
        $this->fpdf->Cell(20);
        $this->fpdf->Cell(170, 5, '', 0, 0, 'C');


        $this->fpdf->Ln(20);
        $this->fpdf->SetFont('Arial', 'B', '11');
        $this->fpdf->Cell(20);
        $this->fpdf->Cell(170, 5, 'PASAL 3', 0, 0, 'C');

        $this->fpdf->Ln();
        $this->fpdf->Cell(20);
        $this->fpdf->Cell(170, 5, 'MASA BERLAKU PERJANJIAN KERJA', 0, 0, 'C');

        $this->fpdf->SetFont('Arial', 'B', '11');
        $this->fpdf->Ln(10);
        $this->fpdf->Cell(20);
        $this->fpdf->Cell(170, 5, 'Ayat 1', 0, 0, 'C');
        
        $this->fpdf->SetFont('Arial', '', '11');
        $this->fpdf->Ln();
        $this->fpdf->Cell(20);
        $this->fpdf->Cell(170, 6, 'Perjanjian kerja ini berlaku untuk jangka waktu 21 hari(dua puluh satu hari), terhitung sejak tanggal', 0, 0, 'L');
        $this->fpdf->Ln();
        $this->fpdf->Cell(20);
        $this->fpdf->Cell(135, 6, 'penandatanganan surat perjanjian kerja ini dan akan berakhir pada tanggal : ', 0, 0, 'L');
        $this->fpdf->SetFont('Arial', 'BU', '11');
        $this->fpdf->Cell(30, 6, ' '.\Carbon\Carbon::parse($akhir_magang)->isoformat('D MMMM Y').'.', 0, 0, 'L');

        $this->fpdf->SetFont('Arial', 'B', '11');
        $this->fpdf->Ln(10);
        $this->fpdf->Cell(20);
        $this->fpdf->Cell(170, 5, 'Ayat 2', 0, 0, 'C');
        
        $this->fpdf->SetFont('Arial', '', '11');
        $this->fpdf->Ln();
        $this->fpdf->Cell(20);
        $this->fpdf->Cell(117, 6, 'Setelah berakhirnya jangka waktu tersebut. Hubungan kerja antara', 0, 0, 'L');
        $this->fpdf->SetFont('Arial', 'B', '11');
        $this->fpdf->Cell(35, 6, 'PIHAK PERTAMA.', 0, 0, 'L');
        $this->fpdf->Ln();
        $this->fpdf->Cell(20);
        $this->fpdf->SetFont('Arial', '', '11');
        $this->fpdf->Cell(15, 6, 'dengan', 0, 0, 'L');
        $this->fpdf->SetFont('Arial', 'B', '11');
        $this->fpdf->Cell(35, 6, 'PIHAK KEDUA', 0, 0, 'C');
        $this->fpdf->SetFont('Arial', '', '11');
        $this->fpdf->Cell(115, 6, 'menjadi putus dengan sendirinya tanpa perlu pemberitahuan dari', 0, 0, 'L');
        $this->fpdf->Ln();
        $this->fpdf->Cell(20);
        $this->fpdf->SetFont('Arial', 'B', '11');
        $this->fpdf->Cell(35, 6, 'PIHAK PERTAMA', 0, 0, 'L');
        $this->fpdf->SetFont('Arial', '', '11');
        $this->fpdf->Cell(10, 6, 'pada', 0, 0, 'L');
        $this->fpdf->SetFont('Arial', 'B', '11');
        $this->fpdf->Cell(35, 6, 'PIHAK KEDUA.', 0, 0, 'L');

        $this->fpdf->Ln(10);
        $this->fpdf->SetFont('Arial', 'B', '11');
        $this->fpdf->Cell(20);
        $this->fpdf->Cell(170, 5, 'PASAL 4', 0, 0, 'C');

        $this->fpdf->Ln();
        $this->fpdf->Cell(20);
        $this->fpdf->Cell(170, 5, 'CARA KERJA', 0, 0, 'C');
        
        $this->fpdf->Ln(5);
        $this->fpdf->Cell(20);
        $this->fpdf->Cell(35, 6, 'PIHAK PERTAMA', 0, 0, 'L');
        $this->fpdf->SetFont('Arial', '', '11');
        $this->fpdf->Cell(95, 6, 'atau wakil perusahaan PT Prima Komponen Indonesia akan memberikan', 0, 0, 'L');
        $this->fpdf->Ln();
        $this->fpdf->Cell(20);
        $this->fpdf->Cell(70, 6, 'pengarahan perihal cara kerja sebelum', 0, 0, 'L');
        $this->fpdf->SetFont('Arial', 'B', '11');
        $this->fpdf->Cell(30, 6, 'PIHAK KEDUA', 0, 0, 'L');
        $this->fpdf->SetFont('Arial', '', '11');
        $this->fpdf->Cell(65, 6, 'memulai pekerjaannya.', 0, 0, 'L');

        $this->fpdf->Ln(10);
        $this->fpdf->SetFont('Arial', 'B', '11');
        $this->fpdf->Cell(20);
        $this->fpdf->Cell(170, 5, 'PASAL 5', 0, 0, 'C');

        $this->fpdf->Ln();
        $this->fpdf->Cell(20);
        $this->fpdf->Cell(170, 5, 'JAM KERJA', 0, 0, 'C');

        $this->fpdf->SetFont('Arial', 'B', '11');
        $this->fpdf->Ln(10);
        $this->fpdf->Cell(20);
        $this->fpdf->Cell(170, 5, 'Ayat 1', 0, 0, 'C');

        $this->fpdf->SetFont('Arial', '', '11');
        $this->fpdf->Ln();
        $this->fpdf->Cell(20);
        $this->fpdf->Cell(170, 5, 'Berdasarkan peraturan ketenagakerjaan yang berlaku, jam kerja efektif perusahaan ditetapkan', 0, 0, 'L');
        $this->fpdf->Ln();
        $this->fpdf->Cell(20);
        $this->fpdf->Cell(170, 5, '8 (delapan) jam perhari, 40 (empat puluh) jam perminggu, dengan jumlah hari kerja 5 (lima) hari', 0, 0, 'L');
        $this->fpdf->Ln();
        $this->fpdf->Cell(20);
        $this->fpdf->Cell(170, 5, 'dalam seminggu.', 0, 0, 'L');

        $this->fpdf->SetFont('Arial', 'B', '11');
        $this->fpdf->Ln(10);
        $this->fpdf->Cell(20);
        $this->fpdf->Cell(170, 5, 'Ayat 2', 0, 0, 'C');

        $this->fpdf->SetFont('Arial', '', '11');
        $this->fpdf->Ln();
        $this->fpdf->Cell(20);
        $this->fpdf->Cell(170, 5, 'Jam masuk adalah jam 08:00 (delapan) pagi dan jam pulang adalah jam (17:00) (lima sore).', 0, 0, 'L');

        $this->fpdf->SetFont('Arial', 'B', '11');
        $this->fpdf->Ln(10);
        $this->fpdf->Cell(20);
        $this->fpdf->Cell(170, 5, 'Ayat 3', 0, 0, 'C');

        $this->fpdf->SetFont('Arial', '', '11');
        $this->fpdf->Ln();
        $this->fpdf->Cell(30);
        $this->fpdf->Cell(160, 5, '1.  Waktu istirahat pada hari Senin hingga Kamis ditetapkan selama 1 (satu) jam, yaitu', 0, 0, 'L');
        $this->fpdf->Ln();
        $this->fpdf->Cell(30);
        $this->fpdf->Cell(160, 5, '     pada pukul 12:00 (dua belas siang) hingga pukul 13:00 (satu siang).', 0, 0, 'L');

        $this->fpdf->Ln(10);
        $this->fpdf->Cell(30);
        $this->fpdf->Cell(160, 5, '2.  Waktu istirahat pada hari Jumat ditetapkan selama 1,5 (satu koma lima) jam, yaitu', 0, 0, 'L');
        $this->fpdf->Ln();
        $this->fpdf->Cell(30);
        $this->fpdf->Cell(160, 5, '     pada pukul 11:30 (sebelas tiga puluh siang) hingga pukul 13:00 (satu siang).', 0, 0, 'L');

        $this->fpdf->Ln(10);
        $this->fpdf->SetFont('Arial', 'B', '11');
        $this->fpdf->Cell(20);
        $this->fpdf->Cell(170, 5, 'PASAL 6', 0, 0, 'C');

        $this->fpdf->Ln();
        $this->fpdf->Cell(20);
        $this->fpdf->Cell(170, 5, 'UPAH DAN PEMBAYARAN', 0, 0, 'C');

        $this->fpdf->SetFont('Arial', 'B', '11');
        $this->fpdf->Ln(10);
        $this->fpdf->Cell(20);
        $this->fpdf->Cell(170, 5, 'Ayat 1', 0, 0, 'C');

        $this->fpdf->SetFont('Arial', 'B', '11');
        $this->fpdf->Ln();
        $this->fpdf->Cell(20);
        $this->fpdf->Cell(35, 5, 'PIHAK PERTAMA', 0, 0, 'L');
        $this->fpdf->SetFont('Arial', '', '11');
        $this->fpdf->Cell(57, 5, 'akan memberikan upah sebesar ', 0, 0, 'L');
        $this->fpdf->SetFont('Arial', 'B', '11');
        $this->fpdf->Cell(70, 5, 'Rp.203.824,- (dua ratus tiga ribu ', 0, 0, 'L');
        $this->fpdf->Ln();
        $this->fpdf->Cell(20);
        $this->fpdf->Cell(65, 5, 'delapan ratus dua puluh empat) ', 0, 0, 'L');
        $this->fpdf->SetFont('Arial', '', '11');
        $this->fpdf->Cell(50, 5, 'rupiah setiap hari kehadiran', 0, 0, 'L');
        $this->fpdf->SetFont('Arial', 'B', '11');
        $this->fpdf->Cell(35, 5, 'PIHAK KEDUA.', 0, 0, 'L');

        $this->fpdf->SetFont('Arial', 'B', '11');
        $this->fpdf->Ln(10);
        $this->fpdf->Cell(20);
        $this->fpdf->Cell(170, 5, 'Ayat 2', 0, 0, 'C');

        $this->fpdf->SetFont('Arial', '', '11');
        $this->fpdf->Ln();
        $this->fpdf->Cell(20);
        $this->fpdf->Cell(170, 5, 'Pembayaran  upah  akan  dibayarkan kurang lebih  14  (empatbelas) hari  kerja setelah masa ', 0, 0, 'L');
        $this->fpdf->Ln();
        $this->fpdf->Cell(20);
        $this->fpdf->Cell(170, 5, 'kontrak kerja berakhir.', 0, 0, 'L');


        $this->fpdf->Ln(100);

        $this->fpdf->Ln(50);
        $this->fpdf->Cell(20);
        $this->fpdf->Cell(170, 5, '', 0, 0, 'C');


        $this->fpdf->Ln(20);
        $this->fpdf->SetFont('Arial', 'B', '11');
        $this->fpdf->Cell(20);
        $this->fpdf->Cell(170, 5, 'PASAL 7', 0, 0, 'C');

        $this->fpdf->Ln();
        $this->fpdf->Cell(20);
        $this->fpdf->Cell(170, 5, 'LEMBUR', 0, 0, 'C');

        $this->fpdf->SetFont('Arial', 'B', '11');
        $this->fpdf->Ln(10);
        $this->fpdf->Cell(20);
        $this->fpdf->Cell(170, 5, 'Ayat 1', 0, 0, 'C');
        
        $this->fpdf->SetFont('Arial', '', '11');
        $this->fpdf->Ln();
        $this->fpdf->Cell(20);
        $this->fpdf->SetFont('Arial', 'B', '11');
        $this->fpdf->Cell(30, 6, 'PIHAK KEDUA', 0, 0, 'L');
        $this->fpdf->SetFont('Arial', '', '11');
        $this->fpdf->Cell(140, 6, 'diharuskan masuk kerja lembur jika tersedia pekerjaan yang harus segera', 0, 0, 'L');
        $this->fpdf->Ln();
        $this->fpdf->Cell(20);
        $this->fpdf->Cell(140, 6, 'diselesaikan atau bersifat mendesak (URGENT).', 0, 0, 'L');

        $this->fpdf->SetFont('Arial', 'B', '11');
        $this->fpdf->Ln(10);
        $this->fpdf->Cell(20);
        $this->fpdf->Cell(170, 5, 'Ayat 2', 0, 0, 'C');
        
        $this->fpdf->SetFont('Arial', '', '11');
        $this->fpdf->Ln();
        $this->fpdf->Cell(20);
        $this->fpdf->Cell(77, 6, 'Sebagai imbalan kerja lembur sesuai ayat 1,', 0, 0, 'L');
        
        $this->fpdf->SetFont('Arial', 'B', '11');
        $this->fpdf->Cell(33, 6, 'PIHAK PERTAMA ', 0, 0, 'L');

        $this->fpdf->SetFont('Arial', '', '11');
        $this->fpdf->Cell(28, 6, 'akan membayar', 0, 0, 'L');
        $this->fpdf->SetFont('Arial', 'B', '11');
        $this->fpdf->Cell(35, 6, ' PIHAK KEDUA', 0, 0, 'L');

        $this->fpdf->Ln();
        $this->fpdf->Cell(20);
        $this->fpdf->SetFont('Arial', '', '11');
        $this->fpdf->Cell(16, 6, 'sebesar', 0, 0, 'L');

        $this->fpdf->SetFont('Arial', 'B', '11');
        $this->fpdf->Cell(117, 6, 'Rp.24.742,- (dua puluh empat ribu tujuh ratus empat puluh dua)', 0, 0, 'L');

        $this->fpdf->SetFont('Arial', '', '11');
        $this->fpdf->Cell(40, 6, 'rupiah setiap jam lembur.', 0, 0, 'L');

        $this->fpdf->SetFont('Arial', 'B', '11');
        $this->fpdf->Ln(10);
        $this->fpdf->Cell(20);
        $this->fpdf->Cell(170, 5, 'Ayat 3', 0, 0, 'C');
        
        $this->fpdf->SetFont('Arial', '', '11');
        $this->fpdf->Ln();
        $this->fpdf->Cell(20);
        $this->fpdf->Cell(150, 6, 'Pembayaran upah lembur akan di satukan dengan pembayaran upah yang akan diterima', 0, 0, 'L');
        $this->fpdf->Ln();
        $this->fpdf->Cell(20);
        $this->fpdf->SetFont('Arial', 'B', '11');
        $this->fpdf->Cell(30, 6, 'PIHAK KEDUA', 0, 0, 'L');
        $this->fpdf->SetFont('Arial', '', '11');
        $this->fpdf->Cell(105, 6, 'sesuai Pasal 6 ayat 2 perjanjian ini.', 0, 0, 'L');

        $this->fpdf->Ln(20);
        $this->fpdf->SetFont('Arial', 'B', '11');
        $this->fpdf->Cell(20);
        $this->fpdf->Cell(170, 5, 'PASAL 8', 0, 0, 'C');

        $this->fpdf->Ln();
        $this->fpdf->Cell(20);
        $this->fpdf->Cell(170, 5, 'BERAKHIRNYA PERJANJIAN', 0, 0, 'C');

        $this->fpdf->SetFont('Arial', 'B', '11');
        $this->fpdf->Ln(10);
        $this->fpdf->Cell(20);
        $this->fpdf->Cell(170, 5, 'Ayat 1', 0, 0, 'C');

        $this->fpdf->SetFont('Arial', '', '11');
        $this->fpdf->Ln();
        $this->fpdf->Cell(20);
        $this->fpdf->Cell(80, 6, 'Setiap saat hubungan kerja dapat diakhiri jika', 0, 0, 'L');
        $this->fpdf->SetFont('Arial', 'B', '11');
        $this->fpdf->Cell(30, 6, 'PIHAK KEDUA', 0, 0, 'L');
        $this->fpdf->SetFont('Arial', '', '11');
        $this->fpdf->Cell(50, 6, 'melanggar tata tertib, peraturan,', 0, 0, 'L');
        $this->fpdf->Ln();
        $this->fpdf->Cell(20);
        $this->fpdf->Cell(85, 6, 'dan sistem kerja yang berlaku pada perusahaan', 0, 0, 'L');
        $this->fpdf->SetFont('Arial', 'B', '11');
        $this->fpdf->Cell(35, 6, 'PIHAK PERTAMA.', 0, 0, 'L');

        $this->fpdf->SetFont('Arial', 'B', '11');
        $this->fpdf->Ln(10);
        $this->fpdf->Cell(20);
        $this->fpdf->Cell(170, 5, 'Ayat 2', 0, 0, 'C');

        $this->fpdf->SetFont('Arial', '', '11');
        $this->fpdf->Ln();
        $this->fpdf->Cell(20);
        $this->fpdf->Cell(80, 6, 'Pelanggaran yang dimaksud pada ayat 1 tersebut diatas, adalah :', 0, 0, 'L');

        $this->fpdf->Ln(10);
        $this->fpdf->Cell(30);
        $this->fpdf->Cell(160, 5, '1.   Tidak masuk kerja selama 1 (satu) hari kerja tanpa keterangan tertulis atau alasan sah', 0, 0, 'L');
        $this->fpdf->Ln();
        $this->fpdf->Cell(30);
        $this->fpdf->Cell(160, 5, '      yang dapat dibenarkan oleh atasan atau pihak perusahaan.', 0, 0, 'L');

        $this->fpdf->Ln();
        $this->fpdf->Cell(30);
        $this->fpdf->Cell(160, 5, '2.   Melakukan tindak penipuan, pencurian, penggelapan, atau tindak-tindak melawan', 0, 0, 'L');
        $this->fpdf->Ln();
        $this->fpdf->Cell(30);
        $this->fpdf->Cell(160, 5, '      hukum lainnya.', 0, 0, 'L');

        $this->fpdf->Ln();
        $this->fpdf->Cell(30);
        $this->fpdf->Cell(160, 5, '3.   Menyalahgunakan wewenang dan jabatan untuk kepentingan pribadi.', 0, 0, 'L');

        $this->fpdf->Ln();
        $this->fpdf->Cell(30);
        $this->fpdf->Cell(125, 5, '4.   Melakukan perusakan dengan sengaja yang menimbulkan kerugian ', 0, 0, 'L');
        $this->fpdf->SetFont('Arial', 'B', '11');
        $this->fpdf->Cell(35, 5, 'PIHAK PERTAMA.', 0, 0, 'L');
        $this->fpdf->SetFont('Arial', '', '11');

        $this->fpdf->Ln();
        $this->fpdf->Cell(30);
        $this->fpdf->Cell(125, 5, '5.   Melakukan hal-hal lain karena kecerobohannya yang mengakibatkan', 0, 0, 'L');
        $this->fpdf->SetFont('Arial', 'B', '11');
        $this->fpdf->Cell(35, 5, 'PIHAK PERTAMA', 0, 0, 'L');
        $this->fpdf->SetFont('Arial', '', '11');
        $this->fpdf->Ln();
        $this->fpdf->Cell(30);
        $this->fpdf->Cell(160, 5, '      mengalami kerugian.', 0, 0, 'L');

        $this->fpdf->Ln();
        $this->fpdf->Cell(30);
        $this->fpdf->Cell(160, 5, '6.   Melakukan perjudian di tempat kerja.', 0, 0, 'L');

        $this->fpdf->Ln();
        $this->fpdf->Cell(30);
        $this->fpdf->Cell(160, 5, '7.   Mabuk-mabukan atau mengkonsumsi narkotika dan obat-obatan terlarang di lingkungan', 0, 0, 'L');
        $this->fpdf->Ln();
        $this->fpdf->Cell(30);
        $this->fpdf->Cell(160, 5, '      kerja perusahaan.', 0, 0, 'L');

        $this->fpdf->Ln();
        $this->fpdf->Cell(30);
        $this->fpdf->Cell(160, 5, '8.   Melakukan keributan atau keonaran yang mengganggu suasana kerja di lingkungan kerja', 0, 0, 'L');
        $this->fpdf->Ln();
        $this->fpdf->Cell(30);
        $this->fpdf->Cell(160, 5, '      perusahaan.', 0, 0, 'L');

        $this->fpdf->Ln();
        $this->fpdf->Cell(30);
        $this->fpdf->Cell(160, 5, '9.   Melakukan perkelahian atau penganiayaan terhadap pekerja lain.', 0, 0, 'L');

        $this->fpdf->Ln();
        $this->fpdf->Cell(29);
        $this->fpdf->Cell(160, 5,'10.  Menghasut para pekerja lain untuk melakukan mogok kerja.', 0, 0, 'L');

        $this->fpdf->Ln();
        $this->fpdf->Cell(29);
        $this->fpdf->Cell(160, 5,'11.  Merokok ditempat kerja atau membawa rokok dan korek api dalam lingkungan kerja.', 0, 0, 'L');

        $this->fpdf->Ln();
        $this->fpdf->Cell(29);
        $this->fpdf->Cell(160, 5,'12.  Masuk jam kerja tidak tepat waktu selama 2 (dua) kali.', 0, 0, 'L');

        $this->fpdf->Ln();
        $this->fpdf->Cell(29);
        $this->fpdf->Cell(160, 5,'13.  Tidak menggunakan alat keselamatan kerja yang sudah ditetapkan.', 0, 0, 'L');

        $this->fpdf->Ln();
        $this->fpdf->Cell(29);
        $this->fpdf->Cell(160, 5,'14.  Tidak menggunakan alat keselamatan dalam berkendara baik berangkat maupun pulang', 0, 0, 'L');
        $this->fpdf->Ln();
        $this->fpdf->Cell(30);
        $this->fpdf->Cell(160, 5, '      kerja.', 0, 0, 'L');

        $this->fpdf->Ln(100);

        $this->fpdf->Ln(50);
        $this->fpdf->Cell(20);
        $this->fpdf->Cell(170, 5, '', 0, 0, 'C');


        $this->fpdf->Ln(20);
        $this->fpdf->SetFont('Arial', 'B', '11');
        $this->fpdf->Cell(20);
        $this->fpdf->Cell(170, 5, 'PASAL 9', 0, 0, 'C');

        $this->fpdf->Ln();
        $this->fpdf->Cell(20);
        $this->fpdf->Cell(170, 5, 'KEADAAN DARURAT (FORCE MAJEUR)', 0, 0, 'C');

        $this->fpdf->SetFont('Arial', '', '11');
        $this->fpdf->Ln(10);
        $this->fpdf->Cell(20);
        $this->fpdf->Cell(170, 5, 'Perjanjian kerja ini batal dengan sendirinya jika karena keadaan atau situasi yang memaksa,', 0, 0, 'L');
        $this->fpdf->Ln();
        $this->fpdf->Cell(20);
        $this->fpdf->Cell(170, 5, 'Seperti : Bencana Alam, Pemberontakan, Perang, Huru-hara, Kerusuhan, Peraturan Pemerintah', 0, 0, 'L');
        $this->fpdf->Ln();
        $this->fpdf->Cell(20);
        $this->fpdf->Cell(170, 5, 'atau apapun.', 0, 0, 'L');

        $this->fpdf->Ln(10);
        $this->fpdf->SetFont('Arial', 'B', '11');
        $this->fpdf->Cell(20);
        $this->fpdf->Cell(170, 5, 'PASAL 10', 0, 0, 'C');

        $this->fpdf->Ln();
        $this->fpdf->Cell(20);
        $this->fpdf->Cell(170, 5, 'PENYELESAIAN PERSELISIHAN', 0, 0, 'C');

        $this->fpdf->SetFont('Arial', 'B', '11');
        $this->fpdf->Ln(10);
        $this->fpdf->Cell(20);
        $this->fpdf->Cell(170, 5, 'Ayat 1', 0, 0, 'C');
        
        $this->fpdf->SetFont('Arial', '', '11');
        $this->fpdf->Ln();
        $this->fpdf->Cell(20);
        $this->fpdf->Cell(170, 5, 'Apabila terjadi perselisihan antara kedua belah pihak, akan diselesaikan secara musyawarah ', 0, 0, 'L');
        $this->fpdf->Ln();
        $this->fpdf->Cell(20);
        $this->fpdf->Cell(170, 5, 'untuk mencapai mufakat.', 0, 0, 'L');

        $this->fpdf->SetFont('Arial', 'B', '11');
        $this->fpdf->Ln(10);
        $this->fpdf->Cell(20);
        $this->fpdf->Cell(170, 5, 'Ayat 2', 0, 0, 'C');
        
        $this->fpdf->SetFont('Arial', '', '11');
        $this->fpdf->Ln();
        $this->fpdf->Cell(20);
        $this->fpdf->Cell(170, 5, 'Apabila dengan cara ayat 1 pasal ini tidak tercapai kata sepakat, maka kedua belah pihak sepakat', 0, 0, 'L');
        $this->fpdf->Ln();
        $this->fpdf->Cell(20);
        $this->fpdf->Cell(170, 5, 'untuk menyelesaikan permasalahan tersebut dilakukan melalui prosedur hukum.', 0, 0, 'L');

        $this->fpdf->Ln(10);
        $this->fpdf->SetFont('Arial', 'B', '11');
        $this->fpdf->Cell(20);
        $this->fpdf->Cell(170, 5, 'PASAL 11', 0, 0, 'C');

        $this->fpdf->Ln();
        $this->fpdf->Cell(20);
        $this->fpdf->Cell(170, 5, 'PENUTUP', 0, 0, 'C');

        $this->fpdf->SetFont('Arial', '', '11');
        $this->fpdf->Ln();
        $this->fpdf->Cell(20);
        $this->fpdf->Cell(170, 5, 'Demikianlah perjanjian ini dibuat, disetujui, dan ditandatangani dalam rangkap dua, asli, dan ', 0, 0, 'L');
        $this->fpdf->Ln();
        $this->fpdf->Cell(20);
        $this->fpdf->Cell(170, 5, 'tembusan bermaterai cukup dan berkekuatan hukum yang sama. Satu dipegang oleh', 0, 0, 'L');

        $this->fpdf->SetFont('Arial', 'B', '11');
        $this->fpdf->Ln();
        $this->fpdf->Cell(20);
        $this->fpdf->Cell(35, 5, 'PIHAK PERTAMA', 0, 0, 'L');
        
        $this->fpdf->SetFont('Arial', '', '11');
        $this->fpdf->Cell(35, 5, 'dan lainnya untuk', 0, 0, 'L');
        $this->fpdf->SetFont('Arial', 'B', '11');
        $this->fpdf->Cell(35, 5, 'PIHAK KEDUA.', 0, 0, 'L');

        $this->fpdf->Ln(30);
        $this->fpdf->SetFont('Arial', '', '11');
        $this->fpdf->Cell(20);
        $this->fpdf->Cell(170, 5, 'Tangerang Selatan, '.\Carbon\Carbon::parse($cetak_surat_magang)->isoformat('D MMMM Y'), 0, 0, 'C');

        $this->fpdf->Ln(10);
        $this->fpdf->SetFont('Arial', 'B', '11');
        $this->fpdf->Cell(20);
        $this->fpdf->Cell(50, 5, 'PIHAK PERTAMA', 0, 0, 'C');
        $this->fpdf->Cell(70, 5, '', 0, 0, 'C');
        $this->fpdf->Cell(50, 5, 'PIHAK KEDUA', 0, 0, 'C');

        $this->fpdf->Ln(40);
        $this->fpdf->SetFont('Arial', 'B', '11');
        $this->fpdf->Cell(20);
        $this->fpdf->Cell(50, 5, '( Rudiyanto )', 0, 0, 'C');
        $this->fpdf->Cell(70, 5, '', 0, 0, 'C');
        $this->fpdf->Cell(50, 5, '( '.$nama_magang.' )', 0, 0, 'C');
        $this->fpdf->Ln(100);
        $this->fpdf->Output();
        exit;

    }

    public function index()
    {
        //
        if (auth()->user()->roles != 'ADMIN' && auth()->user()->roles != 'HRD') {
            abort(403);
        }
    }

    public function process_rekon_salary()
    {
        //
        if (auth()->user()->roles != 'ADMIN' && auth()->user()->roles != 'HRD') {
            abort(403);
        }
        return view('pages.admin.salary.rekon_salary');
    }

    public function tampil_rekon_salary(RekonSalaryRequest $request)
    {
        //
        if (auth()->user()->roles != 'ADMIN' && auth()->user()->roles != 'HRD') {
            abort(403);
        }

        $awal       = $request->input('awal');
        $akhir      = $request->input('akhir');

        $items = 
        DB::table('history_salaries')
        ->join('employees', 'employees.nik_karyawan', '=', 'history_salaries.employees_id')
        ->join('divisions', 'divisions.id', '=', 'employees.divisions_id')
        ->join('areas', 'areas.id', '=', 'employees.areas_id')
        ->join('positions', 'positions.id', '=', 'employees.positions_id')
        ->where('history_salaries.deleted_at', '=', null)
        ->get();

        $salary = RekapSalaries::where('periode_awal', $awal)->first();

        // dd($salary);

        if ($salary != null) {
            Alert::error('Data Sudah Di Rekon');
            //Redirect
            return redirect()->route('process.process_rekon_salary');
        } else {
            return view('pages.admin.salary.tampil_rekon_salary',[
                'awal'  => $awal,
                'akhir' => $akhir,
                'items' => $items
            ]);
        }

    }

    public function export_excell_rekon_salary()
    {
        if (auth()->user()->roles != 'ADMIN' && auth()->user()->roles != 'HRD') {
            abort(403);
        }
        return Excel::download(new RekonSalaryExport, 'rekonsiliasisalary.xlsx');
    }

    public function hasil_rekon_salary(RekonSalaryRequest $request)
    {
        if (auth()->user()->roles != 'ADMIN' && auth()->user()->roles != 'HRD') {
            abort(403);
        }

        $awal       = $request->input('awal');
        $akhir      = $request->input('akhir');
        $input_oleh = $request->input('input_oleh');

        $items = HistorySalaries::all();
        
        foreach ($items as $item) {
            
            RekapSalaries::create([
                'periode_awal'                      => $awal,
                'periode_akhir'                     => $akhir,
                'employees_id'                      => $item->employees_id,
                'gaji_pokok'                        => $item->gaji_pokok,
                'uang_makan'                        => $item->uang_makan,
                'uang_transport'                    => $item->uang_transport,
                'tunjangan_tugas'                   => $item->tunjangan_tugas,
                'tunjangan_pulsa'                   => $item->tunjangan_pulsa,
                'tunjangan_jabatan'                 => $item->tunjangan_jabatan,
                'jumlah_upah'                       => $item->jumlah_upah,
                'upah_lembur_perjam'                => $item->upah_lembur_perjam,
                'potongan_bpjsks_perusahaan'        => $item->potongan_bpjsks_perusahaan,
                'potongan_jht_perusahaan'           => $item->potongan_jht_perusahaan,
                'potongan_jp_perusahaan'            => $item->potongan_jp_perusahaan,
                'potongan_jkm_perusahaan'           => $item->potongan_jkm_perusahaan,
                'potongan_jkk_perusahaan'           => $item->potongan_jkk_perusahaan,
                'jumlah_bpjstk_perusahaan'          => $item->jumlah_bpjstk_perusahaan,
                'potongan_bpjsks_karyawan'          => $item->potongan_bpjsks_karyawan,
                'potongan_jht_karyawan'             => $item->potongan_jht_karyawan,
                'potongan_jp_karyawan'              => $item->potongan_jp_karyawan,
                'jumlah_bpjstk_karyawan'            => $item->jumlah_bpjstk_karyawan,
                'take_home_pay'                     => $item->take_home_pay,
                'edit_oleh'                         => $item->edit_oleh,
                'input_oleh'                        => $input_oleh
            ]);
        }

        Alert::success('Success Rekonsiliasi Data Salary','Oleh '.auth()->user()->name);
        return redirect()->route('dashboard');
    }

    public function edit_salary($employees_id)
    {
        if (auth()->user()->roles != 'ADMIN' && auth()->user()->roles != 'HRD') {
            abort(403);
        }
        $item = 
        DB::table('history_salaries')
        ->join('employees', 'employees.nik_karyawan', '=', 'history_salaries.employees_id')
        ->join('divisions', 'divisions.id', '=', 'employees.divisions_id')
        ->join('areas', 'areas.id', '=', 'employees.areas_id')
        ->join('positions', 'positions.id', '=', 'employees.positions_id')
        ->where('history_salaries.employees_id', '=', $employees_id)
        ->first();
    
        return view('pages.admin.salary.edit_salary',[
            'item' => $item
        ]);
    }

    public function hasil_edit_salary(EditSalaryRequest $request, $employees_id)
    {
        if (auth()->user()->roles != 'ADMIN' && auth()->user()->roles != 'HRD') {
            abort(403);
        }

        $gaji_pokok         = $request->input('gaji_pokok');
        $uang_makan         = $request->input('uang_makan');
        $uang_transport     = $request->input('uang_transport');
        $tunjangan_tugas    = $request->input('tunjangan_tugas');
        $tunjangan_pulsa    = $request->input('tunjangan_pulsa');
        $tunjangan_jabatan  = $request->input('tunjangan_jabatan');

        $jht                = $request->input('jht');
        $jp                 = $request->input('jp');
        $jkk                = $request->input('jkk');
        $jkm                = $request->input('jkm');
        $jkn                = $request->input('jkn');

        //Ikut Semua Kepesertaan BPJS Ketenagakerjaan Dan Kesehatan
        if ($jht != 0 && $jp != 0 && $jkk != 0 && $jkm != 0 && $jkn != 0) {       
            //End Rumus
            $jumlah_upah                        = $gaji_pokok+$uang_makan+$uang_transport+$tunjangan_tugas+$tunjangan_pulsa+$tunjangan_jabatan;
            $upah_lembur_perjam                 = $jumlah_upah/173;
            $hasil_upah_lembur_perjam           = round($upah_lembur_perjam);

            $itemBpjskesehatans                 = MaksimalBpjskesehatans::where('id',1)->first();
            $itemBpjsketenagakerjaans           = MaksimalBpjsketenagakerjaans::where('id',1)->first();
            $maksimal_upah_bpjs_kesehatan       = $itemBpjskesehatans->maksimalupah_bpjskesehatan;
            $maksimal_upah_bpjs_ketenagakerjaan = $itemBpjsketenagakerjaans->maksimalupah_bpjsketenagakerjaan;

            if ($jumlah_upah <= $maksimal_upah_bpjs_kesehatan && $jumlah_upah <= $maksimal_upah_bpjs_ketenagakerjaan) {

                $potongan_bpjsks_perusahaan     = $jumlah_upah*4/100;
                $potongan_bpjsks_karyawan       = $jumlah_upah*1/100;
    
                $potongan_jht_perusahaan        = $jumlah_upah*3.7/100;
                $potongan_jp_perusahaan         = $jumlah_upah*2/100;
                $potongan_jkm_perusahaan        = $jumlah_upah*0.3/100;
                $potongan_jkk_perusahaan        = $jumlah_upah*0.24/100;
                $potongan_jht_karyawan          = $jumlah_upah*2/100;
                $potongan_jp_karyawan           = $jumlah_upah*1/100;
            }
            elseif ($jumlah_upah <= $maksimal_upah_bpjs_kesehatan && $jumlah_upah > $maksimal_upah_bpjs_ketenagakerjaan) {
                $potongan_bpjsks_perusahaan     = $jumlah_upah*4/100;
                $potongan_bpjsks_karyawan       = $jumlah_upah*1/100;
    
                $potongan_jht_perusahaan        = $jumlah_upah*3.7/100;
                $potongan_jkm_perusahaan        = $jumlah_upah*0.3/100;
                $potongan_jkk_perusahaan        = $jumlah_upah*0.24/100;
                $potongan_jht_karyawan          = $jumlah_upah*2/100;
                $potongan_jp_perusahaan         = $maksimal_upah_bpjs_ketenagakerjaan*2/100;
                $potongan_jp_karyawan           = $maksimal_upah_bpjs_ketenagakerjaan*1/100;
            }
            elseif ($jumlah_upah > $maksimal_upah_bpjs_kesehatan && $jumlah_upah > $maksimal_upah_bpjs_ketenagakerjaan) {
                $potongan_bpjsks_perusahaan     = $maksimal_upah_bpjs_kesehatan*4/100;
                $potongan_bpjsks_karyawan       = $maksimal_upah_bpjs_kesehatan*1/100;
    
                $potongan_jht_perusahaan        = $jumlah_upah*3.7/100;
                $potongan_jkm_perusahaan        = $jumlah_upah*0.3/100;
                $potongan_jkk_perusahaan        = $jumlah_upah*0.24/100;
                $potongan_jht_karyawan          = $jumlah_upah*2/100;
                $potongan_jp_perusahaan         = $maksimal_upah_bpjs_ketenagakerjaan*2/100;
                $potongan_jp_karyawan           = $maksimal_upah_bpjs_ketenagakerjaan*1/100;
            }
            else{
                dd('Salah');
            }
            
            $hasil_potongan_bpjsks_perusahaan   = round($potongan_bpjsks_perusahaan,0);
            $hasil_potongan_bpjsks_karyawan     = round($potongan_bpjsks_karyawan,0);
            $hasil_potongan_jht_perusahaan      = round($potongan_jht_perusahaan,0);
            $hasil_potongan_jp_perusahaan       = round($potongan_jp_perusahaan,0);
            $hasil_potongan_jkm_perusahaan      = round($potongan_jkm_perusahaan,0);
            $hasil_potongan_jkk_perusahaan      = round($potongan_jkk_perusahaan,0);
            $hasil_potongan_jht_karyawan        = round($potongan_jht_karyawan,0);
            $hasil_potongan_jp_karyawan         = round($potongan_jp_karyawan,0);

            $jumlah_bpjstk_perusahaan           = $hasil_potongan_jht_perusahaan+$hasil_potongan_jp_perusahaan+$hasil_potongan_jkm_perusahaan+$hasil_potongan_jkk_perusahaan;
            $jumlah_bpjstk_karyawan             = $hasil_potongan_jht_karyawan+$hasil_potongan_jp_karyawan;
            $take_home_pay                      = $jumlah_upah-$jumlah_bpjstk_karyawan-$hasil_potongan_bpjsks_karyawan;
            //End Rumus
        } 

        //Tidak Ikut Semua Kepesertaan BPJS Ketenagakerjaan Dan Kesehatan
        elseif ($jht == 0 && $jp == 0 && $jkk == 0 && $jkm == 0 && $jkn == 0) {
            
            //End Rumus
            $jumlah_upah                        = $gaji_pokok+$uang_makan+$uang_transport+$tunjangan_tugas+$tunjangan_pulsa+$tunjangan_jabatan;
            $upah_lembur_perjam                 = $jumlah_upah/173;
            $hasil_upah_lembur_perjam           = round($upah_lembur_perjam);

            $hasil_potongan_bpjsks_perusahaan   = 0;
            $hasil_potongan_bpjsks_karyawan     = 0;
            $hasil_potongan_jht_perusahaan      = 0;
            $hasil_potongan_jp_perusahaan       = 0;
            $hasil_potongan_jkm_perusahaan      = 0;
            $hasil_potongan_jkk_perusahaan      = 0;
            $hasil_potongan_jht_karyawan        = 0;
            $hasil_potongan_jp_karyawan         = 0;

            $jumlah_bpjstk_perusahaan           = $hasil_potongan_jht_perusahaan+$hasil_potongan_jp_perusahaan+$hasil_potongan_jkm_perusahaan+$hasil_potongan_jkk_perusahaan;
            $jumlah_bpjstk_karyawan             = $hasil_potongan_jht_karyawan+$hasil_potongan_jp_karyawan;
            $take_home_pay                      = $jumlah_upah-$jumlah_bpjstk_karyawan-$hasil_potongan_bpjsks_karyawan;
            //End Rumus
        } 

        //Ikut Kepesertaan BPJS Ketenagakerjaan Dan Tidak Ikut Kepesertaan BPJS Kesehatan
        elseif ($jht != 0 && $jp != 0 && $jkk != 0 && $jkm != 0 && $jkn == 0) {
            //End Rumus
            $jumlah_upah                        = $gaji_pokok+$uang_makan+$uang_transport+$tunjangan_tugas+$tunjangan_pulsa+$tunjangan_jabatan;
            $upah_lembur_perjam                 = $jumlah_upah/173;
            $hasil_upah_lembur_perjam           = round($upah_lembur_perjam);

            $itemBpjskesehatans                 = MaksimalBpjskesehatans::where('id',1)->first();
            $itemBpjsketenagakerjaans           = MaksimalBpjsketenagakerjaans::where('id',1)->first();
            $maksimal_upah_bpjs_kesehatan       = $itemBpjskesehatans->maksimalupah_bpjskesehatan;
            $maksimal_upah_bpjs_ketenagakerjaan = $itemBpjsketenagakerjaans->maksimalupah_bpjsketenagakerjaan;

            if ($jumlah_upah <= $maksimal_upah_bpjs_ketenagakerjaan) {
                $potongan_jht_perusahaan        = $jumlah_upah*3.7/100;
                $potongan_jp_perusahaan         = $jumlah_upah*2/100;
                $potongan_jkm_perusahaan        = $jumlah_upah*0.3/100;
                $potongan_jkk_perusahaan        = $jumlah_upah*0.24/100;
                $potongan_jht_karyawan          = $jumlah_upah*2/100;
                $potongan_jp_karyawan           = $jumlah_upah*1/100;
            }
            elseif ($jumlah_upah > $maksimal_upah_bpjs_ketenagakerjaan) {
                $potongan_jht_perusahaan        = $jumlah_upah*3.7/100;
                $potongan_jkm_perusahaan        = $jumlah_upah*0.3/100;
                $potongan_jkk_perusahaan        = $jumlah_upah*0.24/100;
                $potongan_jht_karyawan          = $jumlah_upah*2/100;
                $potongan_jp_perusahaan         = $maksimal_upah_bpjs_ketenagakerjaan*2/100;
                $potongan_jp_karyawan           = $maksimal_upah_bpjs_ketenagakerjaan*1/100;
            }
            else{
                dd('Salah');
            }
            
            $hasil_potongan_bpjsks_perusahaan   = 0;
            $hasil_potongan_bpjsks_karyawan     = 0;
            $hasil_potongan_jht_perusahaan      = round($potongan_jht_perusahaan,0);
            $hasil_potongan_jp_perusahaan       = round($potongan_jp_perusahaan,0);
            $hasil_potongan_jkm_perusahaan      = round($potongan_jkm_perusahaan,0);
            $hasil_potongan_jkk_perusahaan      = round($potongan_jkk_perusahaan,0);
            $hasil_potongan_jht_karyawan        = round($potongan_jht_karyawan,0);
            $hasil_potongan_jp_karyawan         = round($potongan_jp_karyawan,0);

            $jumlah_bpjstk_perusahaan           = $hasil_potongan_jht_perusahaan+$hasil_potongan_jp_perusahaan+$hasil_potongan_jkm_perusahaan+$hasil_potongan_jkk_perusahaan;
            $jumlah_bpjstk_karyawan             = $hasil_potongan_jht_karyawan+$hasil_potongan_jp_karyawan;
            $take_home_pay                      = $jumlah_upah-$jumlah_bpjstk_karyawan-$hasil_potongan_bpjsks_karyawan;
            //End Rumus
        } 

        //Ikut Kepesertaan BPJS Kesehatan Dan Tidak Ikut Kepesertaan BPJS Ketenagakerjaan 
        elseif ($jht == 0 && $jp == 0 && $jkk == 0 && $jkm == 0 && $jkn != 0) {
            //End Rumus
            $jumlah_upah                        = $gaji_pokok+$uang_makan+$uang_transport+$tunjangan_tugas+$tunjangan_pulsa+$tunjangan_jabatan;
            $upah_lembur_perjam                 = $jumlah_upah/173;
            $hasil_upah_lembur_perjam           = round($upah_lembur_perjam);

            $itemBpjskesehatans                 = MaksimalBpjskesehatans::where('id',1)->first();
            $itemBpjsketenagakerjaans           = MaksimalBpjsketenagakerjaans::where('id',1)->first();
            $maksimal_upah_bpjs_kesehatan       = $itemBpjskesehatans->maksimalupah_bpjskesehatan;
            $maksimal_upah_bpjs_ketenagakerjaan = $itemBpjsketenagakerjaans->maksimalupah_bpjsketenagakerjaan;

            if ($jumlah_upah <= $maksimal_upah_bpjs_kesehatan) {
                $potongan_bpjsks_perusahaan     = $jumlah_upah*4/100;
                $potongan_bpjsks_karyawan       = $jumlah_upah*1/100;
            }
            elseif ($jumlah_upah > $maksimal_upah_bpjs_kesehatan && $jumlah_upah > $maksimal_upah_bpjs_ketenagakerjaan) {
                $potongan_bpjsks_perusahaan     = $maksimal_upah_bpjs_kesehatan*4/100;
                $potongan_bpjsks_karyawan       = $maksimal_upah_bpjs_kesehatan*1/100;
            }
            else{
                dd('Salah');
            }
            
            $hasil_potongan_bpjsks_perusahaan   = round($potongan_bpjsks_perusahaan,0);
            $hasil_potongan_bpjsks_karyawan     = round($potongan_bpjsks_karyawan,0);
            $hasil_potongan_jht_perusahaan      = 0;
            $hasil_potongan_jp_perusahaan       = 0;
            $hasil_potongan_jkm_perusahaan      = 0;
            $hasil_potongan_jkk_perusahaan      = 0;
            $hasil_potongan_jht_karyawan        = 0;
            $hasil_potongan_jp_karyawan         = 0;

            $jumlah_bpjstk_perusahaan           = $hasil_potongan_jht_perusahaan+$hasil_potongan_jp_perusahaan+$hasil_potongan_jkm_perusahaan+$hasil_potongan_jkk_perusahaan;
            $jumlah_bpjstk_karyawan             = $hasil_potongan_jht_karyawan+$hasil_potongan_jp_karyawan;
            $take_home_pay                      = $jumlah_upah-$jumlah_bpjstk_karyawan-$hasil_potongan_bpjsks_karyawan;
            //End Rumus
        } 

        //Tidak Ikut JHT Dan JP, Hanya Ikut JKK Dan JKM Dan Ikut Kepesertaan BPJS Kesehatan
        elseif ($jht == 0 && $jp == 0 && $jkk != 0 && $jkm != 0 && $jkn != 0) {
            //End Rumus
            $jumlah_upah                        = $gaji_pokok+$uang_makan+$uang_transport+$tunjangan_tugas+$tunjangan_pulsa+$tunjangan_jabatan;
            $upah_lembur_perjam                 = $jumlah_upah/173;
            $hasil_upah_lembur_perjam           = round($upah_lembur_perjam);

            $itemBpjskesehatans                 = MaksimalBpjskesehatans::where('id',1)->first();
            $itemBpjsketenagakerjaans           = MaksimalBpjsketenagakerjaans::where('id',1)->first();
            $maksimal_upah_bpjs_kesehatan       = $itemBpjskesehatans->maksimalupah_bpjskesehatan;
            $maksimal_upah_bpjs_ketenagakerjaan = $itemBpjsketenagakerjaans->maksimalupah_bpjsketenagakerjaan;

            if ($jumlah_upah <= $maksimal_upah_bpjs_kesehatan && $jumlah_upah <= $maksimal_upah_bpjs_ketenagakerjaan) {
                $potongan_bpjsks_perusahaan     = $jumlah_upah*4/100;
                $potongan_bpjsks_karyawan       = $jumlah_upah*1/100;
    
                $potongan_jkm_perusahaan        = $jumlah_upah*0.3/100;
                $potongan_jkk_perusahaan        = $jumlah_upah*0.24/100;
            }
            elseif ($jumlah_upah <= $maksimal_upah_bpjs_kesehatan && $jumlah_upah > $maksimal_upah_bpjs_ketenagakerjaan) {
                $potongan_bpjsks_perusahaan     = $jumlah_upah*4/100;
                $potongan_bpjsks_karyawan       = $jumlah_upah*1/100;
    
                $potongan_jkm_perusahaan        = $jumlah_upah*0.3/100;
                $potongan_jkk_perusahaan        = $jumlah_upah*0.24/100;
            }
            elseif ($jumlah_upah > $maksimal_upah_bpjs_kesehatan && $jumlah_upah > $maksimal_upah_bpjs_ketenagakerjaan) {
                $potongan_bpjsks_perusahaan     = $maksimal_upah_bpjs_kesehatan*4/100;
                $potongan_bpjsks_karyawan       = $maksimal_upah_bpjs_kesehatan*1/100;

                $potongan_jkm_perusahaan        = $jumlah_upah*0.3/100;
                $potongan_jkk_perusahaan        = $jumlah_upah*0.24/100;
            }
            else{
                dd('Salah');
            }
            
            $hasil_potongan_bpjsks_perusahaan   = round($potongan_bpjsks_perusahaan,0);
            $hasil_potongan_bpjsks_karyawan     = round($potongan_bpjsks_karyawan,0);
            $hasil_potongan_jht_perusahaan      = 0;
            $hasil_potongan_jp_perusahaan       = 0;
            $hasil_potongan_jkm_perusahaan      = round($potongan_jkm_perusahaan,0);
            $hasil_potongan_jkk_perusahaan      = round($potongan_jkk_perusahaan,0);
            $hasil_potongan_jht_karyawan        = 0;
            $hasil_potongan_jp_karyawan         = 0;

            $jumlah_bpjstk_perusahaan           = $hasil_potongan_jht_perusahaan+$hasil_potongan_jp_perusahaan+$hasil_potongan_jkm_perusahaan+$hasil_potongan_jkk_perusahaan;
            $jumlah_bpjstk_karyawan             = $hasil_potongan_jht_karyawan+$hasil_potongan_jp_karyawan;
            $take_home_pay                      = $jumlah_upah-$jumlah_bpjstk_karyawan-$hasil_potongan_bpjsks_karyawan;
            //End Rumus
        } 

        //Tidak Ikut JHT, JP, dan BPJS Kesehatan, Hanya Ikut JKK Dan JKM
        elseif ($jht == 0 && $jp == 0 && $jkk != 0 && $jkm != 0 && $jkn == 0) {       
            //End Rumus
            $jumlah_upah                        = $gaji_pokok+$uang_makan+$uang_transport+$tunjangan_tugas+$tunjangan_pulsa+$tunjangan_jabatan;
            $upah_lembur_perjam                 = $jumlah_upah/173;
            $hasil_upah_lembur_perjam           = round($upah_lembur_perjam);

            $itemBpjsketenagakerjaans           = MaksimalBpjsketenagakerjaans::where('id',1)->first();
            $maksimal_upah_bpjs_ketenagakerjaan = $itemBpjsketenagakerjaans->maksimalupah_bpjsketenagakerjaan;

            if ($jumlah_upah <= $maksimal_upah_bpjs_ketenagakerjaan) {    
                $potongan_jkm_perusahaan        = $jumlah_upah*0.3/100;
                $potongan_jkk_perusahaan        = $jumlah_upah*0.24/100;
            }
            elseif ($jumlah_upah > $maksimal_upah_bpjs_ketenagakerjaan) {
                $potongan_jkm_perusahaan        = $jumlah_upah*0.3/100;
                $potongan_jkk_perusahaan        = $jumlah_upah*0.24/100;
            }
            else{
                dd('Salah');
            }
            
            $hasil_potongan_bpjsks_perusahaan   = 0;
            $hasil_potongan_bpjsks_karyawan     = 0;
            $hasil_potongan_jht_perusahaan      = 0;
            $hasil_potongan_jp_perusahaan       = 0;
            $hasil_potongan_jkm_perusahaan      = round($potongan_jkm_perusahaan,0);
            $hasil_potongan_jkk_perusahaan      = round($potongan_jkk_perusahaan,0);
            $hasil_potongan_jht_karyawan        = 0;
            $hasil_potongan_jp_karyawan         = 0;

            $jumlah_bpjstk_perusahaan           = $hasil_potongan_jht_perusahaan+$hasil_potongan_jp_perusahaan+$hasil_potongan_jkm_perusahaan+$hasil_potongan_jkk_perusahaan;
            $jumlah_bpjstk_karyawan             = $hasil_potongan_jht_karyawan+$hasil_potongan_jp_karyawan;
            $take_home_pay                      = $jumlah_upah-$jumlah_bpjstk_karyawan-$hasil_potongan_bpjsks_karyawan;
            //End Rumus
        }

        //Tidak Ikut JP, Hanya Ikut JHT, JKK Dan JKM Dan Ikut Kepesertaan BPJS Kesehatan
        elseif ($jht != 0 && $jp == 0 && $jkk != 0 && $jkm != 0 && $jkn != 0) {
            //End Rumus
            $jumlah_upah                        = $gaji_pokok+$uang_makan+$uang_transport+$tunjangan_tugas+$tunjangan_pulsa+$tunjangan_jabatan;
            $upah_lembur_perjam                 = $jumlah_upah/173;
            $hasil_upah_lembur_perjam           = round($upah_lembur_perjam);

            $itemBpjskesehatans                 = MaksimalBpjskesehatans::where('id',1)->first();
            $itemBpjsketenagakerjaans           = MaksimalBpjsketenagakerjaans::where('id',1)->first();
            $maksimal_upah_bpjs_kesehatan       = $itemBpjskesehatans->maksimalupah_bpjskesehatan;
            $maksimal_upah_bpjs_ketenagakerjaan = $itemBpjsketenagakerjaans->maksimalupah_bpjsketenagakerjaan;

            if ($jumlah_upah <= $maksimal_upah_bpjs_kesehatan && $jumlah_upah <= $maksimal_upah_bpjs_ketenagakerjaan) {
                $potongan_bpjsks_perusahaan     = $jumlah_upah*4/100;
                $potongan_bpjsks_karyawan       = $jumlah_upah*1/100;
    
                $potongan_jht_perusahaan        = $jumlah_upah*3.7/100;
                $potongan_jkm_perusahaan        = $jumlah_upah*0.3/100;
                $potongan_jkk_perusahaan        = $jumlah_upah*0.24/100;
                $potongan_jht_karyawan          = $jumlah_upah*2/100;
            }
            elseif ($jumlah_upah <= $maksimal_upah_bpjs_kesehatan && $jumlah_upah > $maksimal_upah_bpjs_ketenagakerjaan) {
                $potongan_bpjsks_perusahaan     = $jumlah_upah*4/100;
                $potongan_bpjsks_karyawan       = $jumlah_upah*1/100;
    
                $potongan_jht_perusahaan        = $jumlah_upah*3.7/100;
                $potongan_jkm_perusahaan        = $jumlah_upah*0.3/100;
                $potongan_jkk_perusahaan        = $jumlah_upah*0.24/100;
                $potongan_jht_karyawan          = $jumlah_upah*2/100;
            }
            elseif ($jumlah_upah > $maksimal_upah_bpjs_kesehatan && $jumlah_upah > $maksimal_upah_bpjs_ketenagakerjaan) {
                $potongan_bpjsks_perusahaan     = $maksimal_upah_bpjs_kesehatan*4/100;
                $potongan_bpjsks_karyawan       = $maksimal_upah_bpjs_kesehatan*1/100;
    
                $potongan_jht_perusahaan        = $jumlah_upah*3.7/100;
                $potongan_jkm_perusahaan        = $jumlah_upah*0.3/100;
                $potongan_jkk_perusahaan        = $jumlah_upah*0.24/100;
                $potongan_jht_karyawan          = $jumlah_upah*2/100;
            }
            else{
                dd('Salah');
            }
            
            $hasil_potongan_bpjsks_perusahaan   = round($potongan_bpjsks_perusahaan,0);
            $hasil_potongan_bpjsks_karyawan     = round($potongan_bpjsks_karyawan,0);
            $hasil_potongan_jht_perusahaan      = round($potongan_jht_perusahaan,0);
            $hasil_potongan_jp_perusahaan       = 0;
            $hasil_potongan_jkm_perusahaan      = round($potongan_jkm_perusahaan,0);
            $hasil_potongan_jkk_perusahaan      = round($potongan_jkk_perusahaan,0);
            $hasil_potongan_jht_karyawan        = round($potongan_jht_karyawan,0);
            $hasil_potongan_jp_karyawan         = 0;

            $jumlah_bpjstk_perusahaan           = $hasil_potongan_jht_perusahaan+$hasil_potongan_jp_perusahaan+$hasil_potongan_jkm_perusahaan+$hasil_potongan_jkk_perusahaan;
            $jumlah_bpjstk_karyawan             = $hasil_potongan_jht_karyawan+$hasil_potongan_jp_karyawan;
            $take_home_pay                      = $jumlah_upah-$jumlah_bpjstk_karyawan-$hasil_potongan_bpjsks_karyawan;
            //End Rumus
        } 

        //Tidak Ikut JP, dan BPJS Kesehatan, Hanya Ikut JHT, JKK , Dan JKM
        elseif ($jht != 0 && $jp == 0 && $jkk != 0 && $jkm != 0 && $jkn == 0) {       
            //End Rumus
            $jumlah_upah                        = $gaji_pokok+$uang_makan+$uang_transport+$tunjangan_tugas+$tunjangan_pulsa+$tunjangan_jabatan;
            $upah_lembur_perjam                 = $jumlah_upah/173;
            $hasil_upah_lembur_perjam           = round($upah_lembur_perjam);

            $itemBpjsketenagakerjaans           = MaksimalBpjsketenagakerjaans::where('id',1)->first();
            $maksimal_upah_bpjs_ketenagakerjaan = $itemBpjsketenagakerjaans->maksimalupah_bpjsketenagakerjaan;

            if ($jumlah_upah <= $maksimal_upah_bpjs_ketenagakerjaan) {
                $potongan_jht_perusahaan        = $jumlah_upah*3.7/100;
                $potongan_jkm_perusahaan        = $jumlah_upah*0.3/100;
                $potongan_jkk_perusahaan        = $jumlah_upah*0.24/100;
                $potongan_jht_karyawan          = $jumlah_upah*2/100;
            }
            elseif ($jumlah_upah > $maksimal_upah_bpjs_ketenagakerjaan) {
                $potongan_jht_perusahaan        = $jumlah_upah*3.7/100;
                $potongan_jkm_perusahaan        = $jumlah_upah*0.3/100;
                $potongan_jkk_perusahaan        = $jumlah_upah*0.24/100;
                $potongan_jht_karyawan          = $jumlah_upah*2/100;
            }
            else{
                dd('Salah');
            }
            
            $hasil_potongan_bpjsks_perusahaan   = 0;
            $hasil_potongan_bpjsks_karyawan     = 0;
            $hasil_potongan_jht_perusahaan      = round($potongan_jht_perusahaan,0);
            $hasil_potongan_jp_perusahaan       = 0;
            $hasil_potongan_jkm_perusahaan      = round($potongan_jkm_perusahaan,0);
            $hasil_potongan_jkk_perusahaan      = round($potongan_jkk_perusahaan,0);
            $hasil_potongan_jht_karyawan        = round($potongan_jht_karyawan,0);
            $hasil_potongan_jp_karyawan         = 0;

            $jumlah_bpjstk_perusahaan           = $hasil_potongan_jht_perusahaan+$hasil_potongan_jp_perusahaan+$hasil_potongan_jkm_perusahaan+$hasil_potongan_jkk_perusahaan;
            $jumlah_bpjstk_karyawan             = $hasil_potongan_jht_karyawan+$hasil_potongan_jp_karyawan;
            $take_home_pay                      = $jumlah_upah-$jumlah_bpjstk_karyawan-$hasil_potongan_bpjsks_karyawan;
            //End Rumus
        } 
        
        //Kondisi Salah
        else {
            dd('Kondisi Salah');
        }
        
        $salary = HistorySalaries::where('employees_id', $employees_id)->first();
        $salary->update([
            'gaji_pokok'                    => $gaji_pokok,
            'uang_makan'                    => $uang_makan,
            'uang_transport'                => $uang_transport,
            'tunjangan_tugas'               => $tunjangan_tugas,
            'tunjangan_pulsa'               => $tunjangan_pulsa,
            'tunjangan_jabatan'             => $tunjangan_jabatan,
            'jumlah_upah'                   => $jumlah_upah,
            // 'upah_lembur_perjam'            => $hasil_upah_lembur_perjam,
            'potongan_bpjsks_perusahaan'    => $hasil_potongan_bpjsks_perusahaan,
            'potongan_jht_perusahaan'       => $hasil_potongan_jht_perusahaan,
            'potongan_jp_perusahaan'        => $hasil_potongan_jp_perusahaan,
            'potongan_jkm_perusahaan'       => $hasil_potongan_jkm_perusahaan,
            'potongan_jkk_perusahaan'       => $hasil_potongan_jkk_perusahaan,
            'jumlah_bpjstk_perusahaan'      => $jumlah_bpjstk_perusahaan,
            'potongan_bpjsks_karyawan'      => $hasil_potongan_bpjsks_karyawan,
            'potongan_jht_karyawan'         => $hasil_potongan_jht_karyawan,
            'potongan_jp_karyawan'          => $hasil_potongan_jp_karyawan,
            'jumlah_bpjstk_karyawan'        => $jumlah_bpjstk_karyawan,
            'take_home_pay'                 => $take_home_pay,
            'edit_oleh'                     => $request->input('edit_oleh')
        ]);
       
        Alert::info('Success Edit Data Salary','Oleh '.auth()->user()->name);
        return redirect()->route('process.process_rekon_salary');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
        if (auth()->user()->roles != 'ADMIN' && auth()->user()->roles != 'HRD') {
            abort(403);
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
        if (auth()->user()->roles != 'ADMIN' && auth()->user()->roles != 'HRD') {
            abort(403);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
        if (auth()->user()->roles != 'ADMIN' && auth()->user()->roles != 'HRD') {
            abort(403);
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
        if (auth()->user()->roles != 'ADMIN' && auth()->user()->roles != 'HRD') {
            abort(403);
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
        if (auth()->user()->roles != 'ADMIN' && auth()->user()->roles != 'HRD') {
            abort(403);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
        if (auth()->user()->roles != 'ADMIN' && auth()->user()->roles != 'HRD') {
            abort(403);
        }
    }
}
