<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Codedge\Fpdf\Fpdf\Fpdf;
use App\Models\Admin\Attendances;
use App\Models\Admin\Employees;
use App\Models\Admin\Areas;
use App\Models\Admin\Divisions;
use App\Models\Admin\Positions;
use App\Models\Admin\EmployeesOuts;
use App\Models\Admin\InventoryLaptops;
use App\Models\Admin\InventoryMotorcycles;
use App\Models\Admin\InventoryCars;
use Illuminate\Http\Request;
use App\Http\Requests\Admin\LaporanKaryawanMasukRequest;
use App\Http\Requests\Admin\LaporanAbsensiKaryawanRequest;
use Carbon\Carbon;
use Storage;

class ReportsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function absensi_karyawan()
    {
        if (auth()->user()->roles != 'ADMIN' && auth()->user()->roles != 'HRD') {
            abort(403);
        }

        $items = Employees::with([
        'companies',
        'areas',
        'divisions',
        'positions'
        ])->get();

        return view ('pages.admin.laporan.absensi_karyawan.index',[
            'items'     => $items
        ]);
    }

    public function tampil_absensi_karyawan(LaporanAbsensiKaryawanRequest $request)
    {
        if (auth()->user()->roles != 'ADMIN' && auth()->user()->roles != 'HRD') {
            abort(403);
        }
        $employees_id   = $request->input('employees_id');
        $awal           = $request->input('tanggal_awal');
        $akhir          = $request->input('tanggal_akhir');

        $item = Attendances::with([
            'employees'
            ])
            ->where('employees_id',$employees_id)
            ->first();

        $absens = Attendances::with([
            'employees'
            ])
            ->where('employees_id',$employees_id)
            ->whereBetween('tanggal_absen', [$awal, $akhir])
            ->get();

        $cutitahunan = DB::table('attendances')
                ->join('employees', 'employees.nik_karyawan', '=', 'attendances.employees_id')
                ->groupBy('keterangan_absen','lama_absen','employees_id')
                ->select('keterangan_absen','employees_id','lama_absen', DB::raw('sum(lama_absen) as lama_absen'))
                ->where('employees_id',$employees_id)
                ->whereBetween('tanggal_absen', [$awal, $akhir])
                ->where('attendances.deleted_at',NULL)
                ->where('keterangan_absen','Cuti Tahunan')
                ->count();
        $cutikhusus = DB::table('attendances')
                ->join('employees', 'employees.nik_karyawan', '=', 'attendances.employees_id')
                ->groupBy('keterangan_absen','lama_absen','employees_id')
                ->select('keterangan_absen','employees_id','lama_absen', DB::raw('sum(lama_absen) as lama_absen'))
                ->where('employees_id',$employees_id)
                ->whereBetween('tanggal_absen', [$awal, $akhir])
                ->where('attendances.deleted_at',NULL)
                ->where('keterangan_absen','Cuti Khusus')
                ->count();
        $sakit = DB::table('attendances')
                ->join('employees', 'employees.nik_karyawan', '=', 'attendances.employees_id')
                ->groupBy('keterangan_absen','lama_absen','employees_id')
                ->select('keterangan_absen','employees_id','lama_absen', DB::raw('sum(lama_absen) as lama_absen'))
                ->where('employees_id',$employees_id)
                ->whereBetween('tanggal_absen', [$awal, $akhir])
                ->where('attendances.deleted_at',NULL)
                ->where('keterangan_absen','Sakit')
                ->count();
        $ijin = DB::table('attendances')
                ->join('employees', 'employees.nik_karyawan', '=', 'attendances.employees_id')
                ->groupBy('keterangan_absen','lama_absen','employees_id')
                ->select('keterangan_absen','employees_id','lama_absen', DB::raw('sum(lama_absen) as lama_absen'))
                ->where('employees_id',$employees_id)
                ->whereBetween('tanggal_absen', [$awal, $akhir])
                ->where('attendances.deleted_at',NULL)
                ->where('keterangan_absen','Ijin')
                ->count();
        $alpa = DB::table('attendances')
                ->join('employees', 'employees.nik_karyawan', '=', 'attendances.employees_id')
                ->groupBy('keterangan_absen','lama_absen','employees_id')
                ->select('keterangan_absen','employees_id','lama_absen', DB::raw('sum(lama_absen) as lama_absen'))
                ->where('employees_id',$employees_id)
                ->whereBetween('tanggal_absen', [$awal, $akhir])
                ->where('attendances.deleted_at',NULL)
                ->where('keterangan_absen','Alpa')
                ->count();
        

        $this->fpdf = new FPDF('P', 'mm', 'A4');
        $this->fpdf->AddPage();

        $this->fpdf->Ln(10);
        $this->fpdf->SetFont('Arial', 'B', '18');
        $this->fpdf->Cell(190, 5, 'DATA ABSEN KARYAWAN', 0, 1, 'C');
        $this->fpdf->Ln(5);

        $this->fpdf->Cell(190, 5, $item->employees->nama_karyawan, 0, 1, 'C');
        $this->fpdf->Ln(5);

        $this->fpdf->Cell(190, 5, \Carbon\Carbon::parse($awal)->isoformat(' D MMMM Y') . ' s/d ' . \Carbon\Carbon::parse($akhir)->isoformat(' D MMMM Y') . '', 0, 1, 'C');

        $this->fpdf->Ln(10);
        $this->fpdf->SetFont('Arial', 'B', '11');
        $this->fpdf->Cell(25, 10, 'Sakit', 0, 0, 'L');
        $this->fpdf->Cell(5, 10, ' : ', 0, 0, 'C');
        $this->fpdf->Cell(15, 10, $sakit.' Hari', 0, 0, 'L');
        $this->fpdf->Ln();

        $this->fpdf->Cell(25, 10, 'Ijin', 0, 0, 'L');
        $this->fpdf->Cell(5, 10, ' : ', 0, 0, 'C');
        $this->fpdf->Cell(15, 10, $ijin.' Hari', 0, 0, 'L');
        $this->fpdf->Ln();

        $this->fpdf->Cell(25, 10, 'Alpa', 0, 0, 'L');
        $this->fpdf->Cell(5, 10, ' : ', 0, 0, 'C');
        $this->fpdf->Cell(15, 10, $alpa.' Hari', 0, 0, 'L');
        $this->fpdf->Ln();

        $this->fpdf->Cell(25, 10, 'Cuti Tahunan', 0, 0, 'L');
        $this->fpdf->Cell(5, 10, ' : ', 0, 0, 'C');
        $this->fpdf->Cell(15, 10, $cutitahunan.' Hari', 0, 0, 'L');
        $this->fpdf->Ln();

        $this->fpdf->Cell(25, 10, 'Cuti Khusus', 0, 0, 'L');
        $this->fpdf->Cell(5, 10, ' : ', 0, 0, 'C');
        $this->fpdf->Cell(15, 10, $cutikhusus.' Hari', 0, 0, 'L');


        $this->fpdf->Ln(10);
        $this->fpdf->Cell(1);
        $this->fpdf->SetFont('Arial', 'B', '12');
        $this->fpdf->SetFillColor(192, 192, 192); // Warna sel tabel header
        $this->fpdf->Cell(10, 10, 'No', 1, 0, 'C', 1);
        $this->fpdf->Cell(60, 10, 'Tanggal Absen', 1, 0, 'C', 1);
        $this->fpdf->Cell(60, 10, 'Jenis', 1, 0, 'C', 1);
        $this->fpdf->Cell(60, 10, 'Keterangan', 1, 0, 'C', 1);

        $no = 1;

        foreach ($absens as $absen) {
            $this->fpdf->Ln();
            $this->fpdf->Cell(1);
            $this->fpdf->SetFont('Arial', '', '11');
            $this->fpdf->Cell(10, 8, $no, 1, 0, 'C');
            $this->fpdf->Cell(60, 8, \Carbon\Carbon::parse($absen->tanggal_absen)->isoformat(' D MMMM Y'), 1, 0, 'C');
            $this->fpdf->Cell(60, 8, $absen->keterangan_absen, 1, 0, 'C');
            $this->fpdf->Cell(60, 8, $absen->keterangan_cuti_khusus, 1, 0, 'C');
            $no++;
        }

        $this->fpdf->Output();
        exit;

    }

    public function karyawan_masuk()
    {
        if (auth()->user()->roles != 'ADMIN' && auth()->user()->roles != 'HRD') {
            abort(403);
        }
        return view('pages.admin.laporan.karyawan_masuk.index');
    }

    public function tampil_karyawan_masuk(LaporanKaryawanMasukRequest $request)
    {
        if (auth()->user()->roles != 'ADMIN' && auth()->user()->roles != 'HRD') {
            abort(403);
        }
        $awal               = $request->input('tanggal_awal');
        $akhir              = $request->input('tanggal_akhir');

        $employees = Employees::with([
            'divisions',
            'positions'
            ])->whereBetween('tanggal_mulai_kerja', [$awal, $akhir])->get();
        
        // dd($employees);

        $this->fpdf = new FPDF('P', 'mm', 'A4');
        $this->fpdf->AddPage();

        $this->fpdf->Ln(10);
        $this->fpdf->SetFont('Arial', 'B', '18');
        $this->fpdf->Cell(190, 5, 'DATA KARYAWAN MASUK', 0, 1, 'C');
        $this->fpdf->Ln(5);

        $this->fpdf->Cell(190, 5, 'PERIODE', 0, 1, 'C');
        $this->fpdf->Ln(5);

        $this->fpdf->Cell(190, 5, \Carbon\Carbon::parse($awal)->isoformat(' D MMMM Y') . ' s/d ' . \Carbon\Carbon::parse($akhir)->isoformat(' D MMMM Y') . '', 0, 1, 'C');

        $this->fpdf->Ln(10);

        $this->fpdf->Cell(1);
        $this->fpdf->SetFont('Arial', 'B', '12');
        $this->fpdf->SetFillColor(192, 192, 192); // Warna sel tabel header
        $this->fpdf->Cell(10, 10, 'No', 1, 0, 'C', 1);
        $this->fpdf->Cell(55, 10, 'Nama Karyawan', 1, 0, 'C', 1);
        $this->fpdf->Cell(40, 10, 'Mulai Kerja', 1, 0, 'C', 1);
        $this->fpdf->Cell(40, 10, 'No Rekening', 1, 0, 'C', 1);
        $this->fpdf->Cell(50, 10, 'Penempatan', 1, 0, 'C', 1);

        $no = 1;

        foreach ($employees as $employee) {
            $this->fpdf->Ln();
            $this->fpdf->Cell(1);
            $this->fpdf->SetFont('Arial', '', '11');
            $this->fpdf->Cell(10, 8, $no, 1, 0, 'C');
            $this->fpdf->Cell(55, 8, $employee->nama_karyawan, 1, 0, 'L');
            $this->fpdf->Cell(40, 8, \Carbon\Carbon::parse($employee->tanggal_mulai_kerja)->isoformat(' D MMMM Y'), 1, 0, 'C');
            $this->fpdf->Cell(40, 8, $employee->nomor_rekening, 1, 0, 'C');
            $this->fpdf->Cell(50, 8, $employee->divisions->penempatan, 1, 0, 'C');
            $no++;
        }

        $this->fpdf->Output();
        exit;
    }

    public function karyawan_keluar()
    {
        if (auth()->user()->roles != 'ADMIN' && auth()->user()->roles != 'HRD') {
            abort(403);
        }
        return view('pages.admin.laporan.karyawan_keluar.index');
    }

    public function tampil_karyawan_keluar(LaporanKaryawanMasukRequest $request)
    {
        if (auth()->user()->roles != 'ADMIN' && auth()->user()->roles != 'HRD') {
            abort(403);
        }
        $awal               = $request->input('tanggal_awal');
        $akhir              = $request->input('tanggal_akhir');

        $employeesouts = EmployeesOuts::with([
            'divisions',
            'positions'
            ])->whereBetween('tanggal_keluar_karyawan_keluar', [$awal, $akhir])->get();
        
        // dd($employees);

        $this->fpdf = new FPDF('P', 'mm', 'A4');
        $this->fpdf->AddPage();

        $this->fpdf->Ln(10);
        $this->fpdf->SetFont('Arial', 'B', '18');
        $this->fpdf->Cell(190, 5, 'DATA KARYAWAN KELUAR', 0, 1, 'C');
        $this->fpdf->Ln(5);

        $this->fpdf->Cell(190, 5, 'PERIODE', 0, 1, 'C');
        $this->fpdf->Ln(5);

        $this->fpdf->Cell(190, 5, \Carbon\Carbon::parse($awal)->isoformat(' D MMMM Y') . ' s/d ' . \Carbon\Carbon::parse($akhir)->isoformat(' D MMMM Y') . '', 0, 1, 'C');

        $this->fpdf->Ln(10);

        $this->fpdf->Cell(1);
        $this->fpdf->SetFont('Arial', 'B', '12');
        $this->fpdf->SetFillColor(192, 192, 192); // Warna sel tabel header
        $this->fpdf->Cell(10, 10, 'No', 1, 0, 'C', 1);
        $this->fpdf->Cell(55, 10, 'Nama Karyawan', 1, 0, 'C', 1);
        $this->fpdf->Cell(40, 10, 'Tanggal Masuk', 1, 0, 'C', 1);
        $this->fpdf->Cell(40, 10, 'Tanggal Keluar', 1, 0, 'C', 1);
        $this->fpdf->Cell(50, 10, 'Penempatan', 1, 0, 'C', 1);

        $no = 1;

        foreach ($employeesouts as $employeesout) {
            $this->fpdf->Ln();
            $this->fpdf->Cell(1);
            $this->fpdf->SetFont('Arial', '', '11');
            $this->fpdf->Cell(10, 8, $no, 1, 0, 'C');
            $this->fpdf->Cell(55, 8, $employeesout->nama_karyawan_keluar, 1, 0, 'L');
            $this->fpdf->Cell(40, 8, \Carbon\Carbon::parse($employeesout->tanggal_masuk_karyawan_keluar)->isoformat(' D MMMM Y'), 1, 0, 'C');
            $this->fpdf->Cell(40, 8, \Carbon\Carbon::parse($employeesout->tanggal_keluar_karyawan_keluar)->isoformat(' D MMMM Y'), 1, 0, 'C');
            $this->fpdf->Cell(50, 8, $employeesout->divisions->penempatan, 1, 0, 'C');
            $no++;
        }

        $this->fpdf->Output();
        exit;
    }

    public function karyawan_kontrak()
    {
        if (auth()->user()->roles != 'ADMIN' && auth()->user()->roles != 'HRD') {
            abort(403);
        }
        $employees = Employees::with([
            'divisions',
            'positions'
            ])->where('status_kerja','PKWT')->orderBy('tanggal_akhir_kerja', 'ASC')->get();

        $this->fpdf = new FPDF('P', 'mm', 'A4');
        $this->fpdf->AddPage();

        $this->fpdf->Ln(10);
        $this->fpdf->SetFont('Arial', 'B', '18');
        $this->fpdf->Cell(190, 5, 'DATA KARYAWAN KONTRAK', 0, 1, 'C');
        $this->fpdf->Ln(10);

        $this->fpdf->Cell(1);
        $this->fpdf->SetFont('Arial', 'B', '12');
        $this->fpdf->SetFillColor(192, 192, 192); // Warna sel tabel header
        $this->fpdf->Cell(10, 10, 'No', 1, 0, 'C', 1);
        $this->fpdf->Cell(55, 10, 'Nama Karyawan', 1, 0, 'C', 1);
        $this->fpdf->Cell(40, 10, 'Penempatan', 1, 0, 'C', 1);
        $this->fpdf->Cell(40, 10, 'Jabatan', 1, 0, 'C', 1);
        $this->fpdf->Cell(50, 10, 'Akhir Kerja', 1, 0, 'C', 1);

        $no = 1;

        foreach ($employees as $employee) {
            $this->fpdf->Ln();
            $this->fpdf->Cell(1);
            $this->fpdf->SetFont('Arial', '', '11');
            $this->fpdf->Cell(10, 8, $no, 1, 0, 'C');
            $this->fpdf->Cell(55, 8, $employee->nama_karyawan, 1, 0, 'L');
            $this->fpdf->Cell(40, 8, $employee->divisions->penempatan, 1, 0, 'C');
            $this->fpdf->Cell(40, 8, $employee->positions->jabatan, 1, 0, 'C');
            $this->fpdf->Cell(50, 8, \Carbon\Carbon::parse($employee->tanggal_akhir_kerja)->isoformat(' D MMMM Y'), 1, 0, 'C');
            $no++;
        }

        $this->fpdf->Output();
        exit;
    }

    public function karyawan_tetap()
    {
        if (auth()->user()->roles != 'ADMIN' && auth()->user()->roles != 'HRD') {
            abort(403);
        }
        $employees = Employees::with([
            'areas',
            'divisions',
            'positions'
            ])->where('status_kerja','PKWTT')->orderBy('divisions_id', 'ASC')->get();

        $this->fpdf = new FPDF('P', 'mm', 'A4');
        $this->fpdf->AddPage();

        $this->fpdf->Ln(10);
        $this->fpdf->SetFont('Arial', 'B', '18');
        $this->fpdf->Cell(190, 5, 'DATA KARYAWAN TETAP', 0, 1, 'C');
        $this->fpdf->Ln(10);

        $this->fpdf->Cell(1);
        $this->fpdf->SetFont('Arial', 'B', '12');
        $this->fpdf->SetFillColor(192, 192, 192); // Warna sel tabel header
        $this->fpdf->Cell(10, 10, 'No', 1, 0, 'C', 1);
        $this->fpdf->Cell(55, 10, 'Nama Karyawan', 1, 0, 'C', 1);
        $this->fpdf->Cell(40, 10, 'Area', 1, 0, 'C', 1);
        $this->fpdf->Cell(40, 10, 'Penempatan', 1, 0, 'C', 1);
        $this->fpdf->Cell(50, 10, 'Jabatan', 1, 0, 'C', 1);

        $no = 1;

        foreach ($employees as $employee) {
            $this->fpdf->Ln();
            $this->fpdf->Cell(1);
            $this->fpdf->SetFont('Arial', '', '11');
            $this->fpdf->Cell(10, 8, $no, 1, 0, 'C');
            $this->fpdf->Cell(55, 8, $employee->nama_karyawan, 1, 0, 'L');
            $this->fpdf->Cell(40, 8, $employee->areas->area, 1, 0, 'C');
            $this->fpdf->Cell(40, 8, $employee->divisions->penempatan, 1, 0, 'C');
            $this->fpdf->Cell(50, 8, $employee->positions->jabatan, 1, 0, 'C');
            $no++;
        }

        $this->fpdf->Output();
        exit;
    }

    public function karyawan_harian()
    {
        if (auth()->user()->roles != 'ADMIN' && auth()->user()->roles != 'HRD') {
            abort(403);
        }
        $employees = Employees::with([
            'divisions',
            'positions'
            ])->where('status_kerja','Harian')->orderBy('tanggal_akhir_kerja', 'ASC')->get();

        $this->fpdf = new FPDF('P', 'mm', 'A4');
        $this->fpdf->AddPage();

        $this->fpdf->Ln(10);
        $this->fpdf->SetFont('Arial', 'B', '18');
        $this->fpdf->Cell(190, 5, 'DATA KARYAWAN HARIAN', 0, 1, 'C');
        $this->fpdf->Ln(10);

        $this->fpdf->Cell(1);
        $this->fpdf->SetFont('Arial', 'B', '12');
        $this->fpdf->SetFillColor(192, 192, 192); // Warna sel tabel header
        $this->fpdf->Cell(10, 10, 'No', 1, 0, 'C', 1);
        $this->fpdf->Cell(55, 10, 'Nama Karyawan', 1, 0, 'C', 1);
        $this->fpdf->Cell(40, 10, 'Penempatan', 1, 0, 'C', 1);
        $this->fpdf->Cell(40, 10, 'Jabatan', 1, 0, 'C', 1);
        $this->fpdf->Cell(50, 10, 'Akhir Kerja', 1, 0, 'C', 1);

        $no = 1;

        foreach ($employees as $employee) {
            $this->fpdf->Ln();
            $this->fpdf->Cell(1);
            $this->fpdf->SetFont('Arial', '', '11');
            $this->fpdf->Cell(10, 8, $no, 1, 0, 'C');
            $this->fpdf->Cell(55, 8, $employee->nama_karyawan, 1, 0, 'L');
            $this->fpdf->Cell(40, 8, $employee->divisions->penempatan, 1, 0, 'C');
            $this->fpdf->Cell(40, 8, $employee->positions->jabatan, 1, 0, 'C');
            $this->fpdf->Cell(50, 8, \Carbon\Carbon::parse($employee->tanggal_akhir_kerja)->isoformat(' D MMMM Y'), 1, 0, 'C');
            $no++;
        }

        $this->fpdf->Output();
        exit;
    }

    public function karyawan_outsourcing()
    {
        if (auth()->user()->roles != 'ADMIN' && auth()->user()->roles != 'HRD') {
            abort(403);
        }
        $employees = Employees::with([
            'divisions',
            'positions'
            ])->where('status_kerja','Outsourcing')->orderBy('tanggal_akhir_kerja', 'ASC')->get();

        $this->fpdf = new FPDF('P', 'mm', 'A4');
        $this->fpdf->AddPage();

        $this->fpdf->Ln(10);
        $this->fpdf->SetFont('Arial', 'B', '18');
        $this->fpdf->Cell(190, 5, 'DATA KARYAWAN OUTSOURCING', 0, 1, 'C');
        $this->fpdf->Ln(10);

        $this->fpdf->Cell(1);
        $this->fpdf->SetFont('Arial', 'B', '12');
        $this->fpdf->SetFillColor(192, 192, 192); // Warna sel tabel header
        $this->fpdf->Cell(10, 10, 'No', 1, 0, 'C', 1);
        $this->fpdf->Cell(55, 10, 'Nama Karyawan', 1, 0, 'C', 1);
        $this->fpdf->Cell(40, 10, 'Penempatan', 1, 0, 'C', 1);
        $this->fpdf->Cell(40, 10, 'Jabatan', 1, 0, 'C', 1);
        $this->fpdf->Cell(50, 10, 'Akhir Kerja', 1, 0, 'C', 1);

        $no = 1;

        foreach ($employees as $employee) {
            $this->fpdf->Ln();
            $this->fpdf->Cell(1);
            $this->fpdf->SetFont('Arial', '', '11');
            $this->fpdf->Cell(10, 8, $no, 1, 0, 'C');
            $this->fpdf->Cell(55, 8, $employee->nama_karyawan, 1, 0, 'L');
            $this->fpdf->Cell(40, 8, $employee->divisions->penempatan, 1, 0, 'C');
            $this->fpdf->Cell(40, 8, $employee->positions->jabatan, 1, 0, 'C');
            $this->fpdf->Cell(50, 8, \Carbon\Carbon::parse($employee->tanggal_akhir_kerja)->isoformat(' D MMMM Y'), 1, 0, 'C');
            $no++;
        }

        $this->fpdf->Output();
        exit;
    }

    public function inventaris_laptop()
    {
        if (auth()->user()->roles != 'ADMIN' && auth()->user()->roles != 'HRD') {
            abort(403);
        }
        $inventorylaptops = InventoryLaptops::with([
            'employees'
            ])->orderBy('tanggal_penyerahan_laptop', 'ASC')->get();
        
        $this->fpdf = new FPDF('P', 'mm', 'A4');
        $this->fpdf->AddPage();

        $this->fpdf->Ln(10);
        $this->fpdf->SetFont('Arial', 'B', '18');
        $this->fpdf->Cell(190, 5, 'DATA INVENTARIS LAPTOP', 0, 1, 'C');
        $this->fpdf->Ln(10);

        $this->fpdf->Cell(0.2);
        $this->fpdf->SetFont('Arial', 'B', '12');
        $this->fpdf->SetFillColor(192, 192, 192); // Warna sel tabel header
        $this->fpdf->Cell(10, 10, 'No', 1, 0, 'C', 1);
        $this->fpdf->Cell(55, 10, 'Nama Karyawan', 1, 0, 'C', 1);
        $this->fpdf->Cell(45, 10, 'Merk/Type', 1, 0, 'C', 1);
        $this->fpdf->Cell(35, 10, 'Sistem Operasi', 1, 0, 'C', 1);
        $this->fpdf->Cell(50, 10, 'Tanggal Penyerahan', 1, 0, 'C', 1);

        $no = 1;

        foreach ($inventorylaptops as $inventorylaptop) {
            $this->fpdf->Ln();
            $this->fpdf->Cell(0.2);
            $this->fpdf->SetFont('Arial', '', '11');
            $this->fpdf->Cell(10, 8, $no, 1, 0, 'C');
            $this->fpdf->Cell(55, 8, $inventorylaptop->employees->nama_karyawan, 1, 0, 'L');
            $this->fpdf->Cell(45, 8, $inventorylaptop->merk_laptop.'/'.$inventorylaptop->type_laptop, 1, 0, 'C');
            $this->fpdf->Cell(35, 8, $inventorylaptop->sistem_operasi, 1, 0, 'C');
            $this->fpdf->Cell(50, 8, \Carbon\Carbon::parse($inventorylaptop->tanggal_penyerahan_laptop)->isoformat(' D MMMM Y'), 1, 0, 'C');
            $no++;
        }

        $this->fpdf->Output();
        exit;
    }

    public function inventaris_motor()
    {
        if (auth()->user()->roles != 'ADMIN' && auth()->user()->roles != 'HRD') {
            abort(403);
        }
        $inventorymotors = InventoryMotorcycles::with([
            'employees'
            ])->orderBy('tanggal_akhir_pajak_motor', 'ASC')->get();
        
        $this->fpdf = new FPDF('P', 'mm', 'A4');
        $this->fpdf->AddPage();

        $this->fpdf->Ln(10);
        $this->fpdf->SetFont('Arial', 'B', '18');
        $this->fpdf->Cell(190, 5, 'DATA INVENTARIS MOTOR', 0, 1, 'C');
        $this->fpdf->Ln(10);

        $this->fpdf->Cell(0.1);
        $this->fpdf->SetFont('Arial', 'B', '12');
        $this->fpdf->SetFillColor(192, 192, 192); // Warna sel tabel header
        $this->fpdf->Cell(8, 10, 'No', 1, 0, 'C', 1);
        $this->fpdf->Cell(55, 10, 'Nama Karyawan', 1, 0, 'C', 1);
        $this->fpdf->Cell(45, 10, 'Merk/Type', 1, 0, 'C', 1);
        $this->fpdf->Cell(25, 10, 'No Polisi', 1, 0, 'C', 1);
        $this->fpdf->Cell(32, 10, 'Akhir Pajak', 1, 0, 'C', 1);
        $this->fpdf->Cell(32, 10, 'Akhir Plat', 1, 0, 'C', 1);

        $no = 1;

        foreach ($inventorymotors as $inventorymotor) {
            $this->fpdf->Ln();
            $this->fpdf->Cell(0.1);
            $this->fpdf->SetFont('Arial', '', '11');
            $this->fpdf->Cell(8, 8, $no, 1, 0, 'C');
            $this->fpdf->Cell(55, 8, $inventorymotor->employees->nama_karyawan, 1, 0, 'L');
            $this->fpdf->Cell(45, 8, $inventorymotor->merk_motor.'/'.$inventorymotor->type_motor, 1, 0, 'C');
            $this->fpdf->Cell(25, 8, $inventorymotor->nomor_polisi, 1, 0, 'C');
            $this->fpdf->Cell(32, 8, \Carbon\Carbon::parse($inventorymotor->tanggal_akhir_pajak_motor)->isoformat(' D MMMM Y'), 1, 0, 'C');
            $this->fpdf->Cell(32, 8, \Carbon\Carbon::parse($inventorymotor->tanggal_akhir_plat_motor)->isoformat(' D MMMM Y'), 1, 0, 'C');
            $no++;
        }

        $this->fpdf->Output();
        exit;
    }

    public function inventaris_mobil()
    {
        if (auth()->user()->roles != 'ADMIN' && auth()->user()->roles != 'HRD') {
            abort(403);
        }
        $inventorymobils = InventoryCars::with([
            'employees'
            ])->orderBy('tanggal_akhir_pajak_mobil', 'ASC')->get();
        
        $this->fpdf = new FPDF('P', 'mm', 'A4');
        $this->fpdf->AddPage();

        $this->fpdf->Ln(10);
        $this->fpdf->SetFont('Arial', 'B', '18');
        $this->fpdf->Cell(190, 5, 'DATA INVENTARIS MOBIL', 0, 1, 'C');
        $this->fpdf->Ln(10);

        $this->fpdf->Cell(0.1);
        $this->fpdf->SetFont('Arial', 'B', '12');
        $this->fpdf->SetFillColor(192, 192, 192); // Warna sel tabel header
        $this->fpdf->Cell(8, 10, 'No', 1, 0, 'C', 1);
        $this->fpdf->Cell(55, 10, 'Nama Karyawan', 1, 0, 'C', 1);
        $this->fpdf->Cell(45, 10, 'Merk/Type', 1, 0, 'C', 1);
        $this->fpdf->Cell(25, 10, 'No Polisi', 1, 0, 'C', 1);
        $this->fpdf->Cell(32, 10, 'Akhir Pajak', 1, 0, 'C', 1);
        $this->fpdf->Cell(32, 10, 'Akhir Plat', 1, 0, 'C', 1);

        $no = 1;

        foreach ($inventorymobils as $inventorymobil) {
            $this->fpdf->Ln();
            $this->fpdf->Cell(0.1);
            $this->fpdf->SetFont('Arial', '', '11');
            $this->fpdf->Cell(8, 8, $no, 1, 0, 'C');
            $this->fpdf->Cell(55, 8, $inventorymobil->employees->nama_karyawan, 1, 0, 'L');
            $this->fpdf->Cell(45, 8, $inventorymobil->merk_mobil.'/'.$inventorymobil->type_mobil, 1, 0, 'C');
            $this->fpdf->Cell(25, 8, $inventorymobil->nomor_polisi, 1, 0, 'C');
            $this->fpdf->Cell(32, 8, \Carbon\Carbon::parse($inventorymobil->tanggal_akhir_pajak_mobil)->isoformat(' D MMMM Y'), 1, 0, 'C');
            $this->fpdf->Cell(32, 8, \Carbon\Carbon::parse($inventorymobil->tanggal_akhir_plat_mobil)->isoformat(' D MMMM Y'), 1, 0, 'C');
            $no++;
        }

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
