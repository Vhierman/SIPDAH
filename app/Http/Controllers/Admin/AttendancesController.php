<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\AttendancesRequest;
use App\Http\Requests\Admin\AttendancesEditRequest;
use App\Http\Requests\Admin\AttendancesLihatRequest;
use App\Models\Admin\Employees;
use App\Models\Admin\Attendances;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Alert;
use DB;

class AttendancesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        if (auth()->user()->roles != 'ADMIN' && auth()->user()->roles != 'MANAGER HRD' && auth()->user()->roles != 'HRD' && auth()->user()->roles != 'LEADER') {
            abort(403);
        }
        return view('pages.admin.absensi.index');
    }

    public function lihat_absensi()
    {
        if (auth()->user()->roles != 'ADMIN' && auth()->user()->roles != 'MANAGER HRD' && auth()->user()->roles != 'HRD' &&  auth()->user()->roles != 'LEADER') {
            abort(403);
        }
        return view('pages.admin.absensi.cariabsensi');
    }
    
    public function tampil_absensi(AttendancesLihatRequest $request)
    {
        if (auth()->user()->roles != 'ADMIN' && auth()->user()->roles != 'MANAGER HRD' && auth()->user()->roles != 'HRD' && auth()->user()->roles != 'LEADER') {
            abort(403);
        }

        $awal           = $request->input('awal');
        $akhir          = $request->input('akhir');

        $nik            = auth()->user()->nik;
        $caridivisi     = Employees::all()->where('nik_karyawan', $nik)->first();
        $divisi         = $caridivisi->divisions_id;

        //PDC Daihatsu
        if ($divisi == 19) {
            $items = 
                DB::table('attendances')
                ->join('employees', 'employees.nik_karyawan', '=', 'attendances.employees_id')
                ->join('divisions', 'divisions.id', '=', 'employees.divisions_id')
                ->whereIn('divisions_id', [19,20,21,22])
                ->where('attendances.deleted_at',NULL)
                ->whereBetween('tanggal_absen', [$awal, $akhir])
                ->get();
        } 
        //Produksi
        elseif ($divisi == 11) {
            $items = 
                DB::table('attendances')
                ->join('employees', 'employees.nik_karyawan', '=', 'attendances.employees_id')
                ->join('divisions', 'divisions.id', '=', 'employees.divisions_id')
                ->whereIn('divisions_id', [11])
                ->where('attendances.deleted_at',NULL)
                ->whereBetween('tanggal_absen', [$awal, $akhir])
                ->get();
        } 
        //HRD-GA
        elseif ($divisi == 4) {
            $items = 
            DB::table('attendances')
            ->join('employees', 'employees.nik_karyawan', '=', 'attendances.employees_id')
            ->join('divisions', 'divisions.id', '=', 'employees.divisions_id')
            ->where('attendances.deleted_at',NULL)
            ->whereBetween('tanggal_absen', [$awal, $akhir])
            ->get();
        }
        else {
            abort(403);
        }

        if (!$items->isEmpty()) {
            return view('pages.admin.absensi.tampilabsensi',[
                'items' => $items
            ]);
        } else {
            Alert::error('Data Tidak Ditemukan');
            return redirect()->route('absensi.lihat_absensi');
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
        if (auth()->user()->roles != 'ADMIN' && auth()->user()->roles != 'HRD' && auth()->user()->roles != 'LEADER') {
            abort(403);
        }

        $nik            = auth()->user()->nik;
        $caridivisi     = Employees::all()->where('nik_karyawan', $nik)->first();
        $divisi         = $caridivisi->divisions_id;

        //PDC
        if ($divisi == 19) {
            $items = Employees::with([
                'companies',
                'areas',
                'divisions',
                'positions'
                ])->whereIn('divisions_id', [19,20,21,22])->get();
        }
        //Produksi
        elseif ($divisi == 11) {
            $items = Employees::with([
                'companies',
                'areas',
                'divisions',
                'positions'
                ])->whereIn('divisions_id', [11])->get();
        }
        //HRD-GA
        elseif ($divisi == 4) {
            $items = Employees::with([
                'companies',
                'areas',
                'divisions',
                'positions'
                ])->get();
        }
        else {
            abort(403);
        }

        return view ('pages.admin.absensi.create',[
            'items'     => $items
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(AttendancesRequest $request)
    {
        //
        if (auth()->user()->roles != 'ADMIN' && auth()->user()->roles != 'HRD' && auth()->user()->roles != 'LEADER') {
            abort(403);
        }

        Attendances::create([
            'employees_id'              => $request->input('employees_id'),
            'tanggal_absen'             => $request->input('tanggal_absen'),
            'keterangan_absen'          => $request->input('keterangan_absen'),
            'lama_absen'                => 1,
            'keterangan_cuti_khusus'    => $request->input('keterangan_cuti_khusus'),
            'input_oleh'                => $request->input('input_oleh'),
        ]);

        Alert::success('Success Input Data Absensi','Oleh '.auth()->user()->name);
        return redirect()->route('absensi.create');
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
        if (auth()->user()->roles != 'ADMIN' && auth()->user()->roles != 'HRD' && auth()->user()->roles != 'LEADER') {
            abort(403);
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */

    public function form_edit()
    {
        //
        if (auth()->user()->roles != 'ADMIN' && auth()->user()->roles != 'HRD' && auth()->user()->roles != 'LEADER') {
            abort(403);
        }

        $nik            = auth()->user()->nik;
        $caridivisi     = Employees::all()->where('nik_karyawan', $nik)->first();
        $divisi         = $caridivisi->divisions_id;

        //PDC
        if ($divisi == 19) {
            $items = Employees::with([
                'companies',
                'areas',
                'divisions',
                'positions'
                ])->whereIn('divisions_id', [19,20,21,22])->get();
        }
        //Produksi
        elseif ($divisi == 11) {
            $items = Employees::with([
                'companies',
                'areas',
                'divisions',
                'positions'
                ])->whereIn('divisions_id', [11])->get();
        }
        //HRD-GA
        elseif ($divisi == 4) {
            $items = Employees::with([
                'companies',
                'areas',
                'divisions',
                'positions'
                ])->get();
        }
        else {
            abort(403);
        }

        return view ('pages.admin.absensi.editabsensi',[
                'items'     => $items
        ]);
        
    }

    public function tampil_edit(AttendancesEditRequest $request)
    {
        //
        if (auth()->user()->roles != 'ADMIN' && auth()->user()->roles != 'HRD' && auth()->user()->roles != 'LEADER') {
            abort(403);
        }

        $employees_id       = $request->input('employees_id');
        $tanggal_absen      = $request->input('tanggal_absen');

        $items = Attendances::with([
            'employees'
            ])
            ->where('employees_id', $employees_id)
            ->where('tanggal_absen', $tanggal_absen)
            ->first();

        if ($items == null) {
            Alert::error('Data yang anda cari tidak ada');
            return redirect()->route('absensi.index');
        } else {
        return view('pages.admin.absensi.tampileditabsensi',[
            'items' => $items
        ]);
        }
    }

    public function edit($id)
    {
        //
        if (auth()->user()->roles != 'ADMIN' && auth()->user()->roles != 'HRD' && auth()->user()->roles != 'LEADER') {
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
    public function update(AttendancesRequest $request, $id)
    {
        //
        if (auth()->user()->roles != 'ADMIN' && auth()->user()->roles != 'HRD' && auth()->user()->roles != 'LEADER') {
            abort(403);
        }

        $employees_id           = $request->input('employees_id');
        $tanggal_absen          = $request->input('tanggal_absen');
        $keterangan_absen       = $request->input('keterangan_absen');
        $keterangan_cuti_khusus = $request->input('keterangan_cuti_khusus');

        $attendance             = Attendances::where('id', $id)->first();
        $attendance->update([
            'tanggal_absen'             => $tanggal_absen,
            'keterangan_absen'          => $keterangan_absen,
            'keterangan_cuti_khusus'    => $keterangan_cuti_khusus,
            'edit_oleh'                 => $request->input('edit_oleh')
        ]);
        Alert::info('Success Edit Data Absensi','Oleh '.auth()->user()->name);
        return redirect()->route('absensi.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function form_hapus()
    {
        if (auth()->user()->roles != 'ADMIN' && auth()->user()->roles != 'HRD' && auth()->user()->roles != 'LEADER') {
            abort(403);
        }

        $nik            = auth()->user()->nik;
        $caridivisi     = Employees::all()->where('nik_karyawan', $nik)->first();
        $divisi         = $caridivisi->divisions_id;

        //PDC
        if ($divisi == 19) {
            $items = Employees::with([
                'companies',
                'areas',
                'divisions',
                'positions'
                ])->whereIn('divisions_id', [19,20,21,22])->get();
        }
        //Produksi
        elseif ($divisi == 11) {
            $items = Employees::with([
                'companies',
                'areas',
                'divisions',
                'positions'
                ])->whereIn('divisions_id', [11])->get();
        }
        //HRD-GA
        elseif ($divisi == 4) {
            $items = Employees::with([
                'companies',
                'areas',
                'divisions',
                'positions'
                ])->get();
        }
        else {
            abort(403);
        }

        return view ('pages.admin.absensi.hapusabsensi',[
                'items'     => $items
        ]);
    }

    public function tampil_hapus(AttendancesEditRequest $request)
    {
        if (auth()->user()->roles != 'ADMIN' && auth()->user()->roles != 'HRD' && auth()->user()->roles != 'LEADER') {
            abort(403);
        }

        $employees_id       = $request->input('employees_id');
        $tanggal_absen      = $request->input('tanggal_absen');

        $items = Attendances::with([
            'employees'
            ])
            ->where('employees_id', $employees_id)
            ->where('tanggal_absen', $tanggal_absen)
            ->first();

        if ($items == null) {
            Alert::error('Data yang anda cari tidak ada');
            return redirect()->route('absensi.index');
        } else {
        return view('pages.admin.absensi.tampilhapusabsensi',[
            'items' => $items
        ]);
        }
    }
    
    public function destroy(Request $request, $id)
    {
        //
        if (auth()->user()->roles != 'ADMIN' && auth()->user()->roles != 'HRD' && auth()->user()->roles != 'LEADER') {
            abort(403);
        }

        $hapus_oleh     = $request->input('hapus_oleh');

        $attendance     = Attendances::where('id', $id)->first();
        $attendance->update([
            'hapus_oleh'    => $request->input('hapus_oleh')
        ]);

        $item = Attendances::findOrFail($id);
        $item->delete();
        
        Alert::error('Menghapus Data Absensi','Oleh '.auth()->user()->name);
        return redirect()->route('absensi.index');
    }
}
