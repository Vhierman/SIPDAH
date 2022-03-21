<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\OvertimesRequest;
use App\Http\Requests\Admin\CariOvertimesRequest;
use App\Http\Requests\Admin\EditOvertimesRequest;
use App\Models\Admin\Overtimes;
use App\Models\Admin\Employees;
use App\Models\Admin\Companies;
use App\Models\Admin\Areas;
use App\Models\Admin\Divisions;
use App\Models\Admin\Positions;
use Illuminate\Http\Request;
use Carbon\Carbon;
use DB;
use Alert;

class OvertimesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        if (auth()->user()->roles != 'ADMIN' && auth()->user()->roles != 'HRD' && auth()->user()->roles != 'ACCOUNTING' && auth()->user()->roles != 'LEADER') {
            abort(403);
        }
        return view('pages.admin.overtimes.index');
    }

    public function lihat_overtime()
    {
        //
        if (auth()->user()->roles != 'ADMIN' && auth()->user()->roles != 'HRD' && auth()->user()->roles != 'ACCOUNTING' && auth()->user()->roles != 'LEADER') {
            abort(403);
        }
        return view('pages.admin.overtimes.cariovertime');
    }

    public function tampil_overtime(CariOvertimesRequest $request)
    {
        //
        if (auth()->user()->roles != 'ADMIN' && auth()->user()->roles != 'HRD' && auth()->user()->roles != 'ACCOUNTING' && auth()->user()->roles != 'LEADER') {
            abort(403);
        }

        $awal           = $request->input('awal');
        $akhir          = $request->input('akhir');

        $nik            = auth()->user()->nik;
        $caridivisi     = Employees::all()->where('nik_karyawan', $nik)->first();
        $divisi         = $caridivisi->divisions_id;

        if ($divisi == 11) {
            $items = 
                DB::table('overtimes')
                ->join('employees', 'employees.nik_karyawan', '=', 'overtimes.employees_id')
                ->join('divisions', 'divisions.id', '=', 'employees.divisions_id')
                ->whereIn('divisions_id', [11])
                ->where('overtimes.deleted_at',NULL)
                ->whereBetween('tanggal_lembur', [$awal, $akhir])->get();
        } 
        elseif ($divisi == 19) {
            $items = 
                DB::table('overtimes')
                ->join('employees', 'employees.nik_karyawan', '=', 'overtimes.employees_id')
                ->join('divisions', 'divisions.id', '=', 'employees.divisions_id')
                ->whereIn('divisions_id', [19,20,21,22])
                ->where('overtimes.deleted_at',NULL)
                ->whereBetween('tanggal_lembur', [$awal, $akhir])->get();
        }
        elseif ($divisi == 2) {
            $items = 
                DB::table('overtimes')
                ->join('employees', 'employees.nik_karyawan', '=', 'overtimes.employees_id')
                ->join('divisions', 'divisions.id', '=', 'employees.divisions_id')
                ->whereIn('divisions_id', [2])
                ->where('overtimes.deleted_at',NULL)
                ->whereBetween('tanggal_lembur', [$awal, $akhir])->get();
        }
        elseif ($divisi == 7) {
            $items = 
                DB::table('overtimes')
                ->join('employees', 'employees.nik_karyawan', '=', 'overtimes.employees_id')
                ->join('divisions', 'divisions.id', '=', 'employees.divisions_id')
                ->whereIn('divisions_id', [7])
                ->where('overtimes.deleted_at',NULL)
                ->whereBetween('tanggal_lembur', [$awal, $akhir])->get();
        }
        elseif ($divisi == 8) {
            $items = 
                DB::table('overtimes')
                ->join('employees', 'employees.nik_karyawan', '=', 'overtimes.employees_id')
                ->join('divisions', 'divisions.id', '=', 'employees.divisions_id')
                ->whereIn('divisions_id', [8])
                ->where('overtimes.deleted_at',NULL)
                ->whereBetween('tanggal_lembur', [$awal, $akhir])->get();
        }
        elseif ($divisi == 9) {
            $items = 
                DB::table('overtimes')
                ->join('employees', 'employees.nik_karyawan', '=', 'overtimes.employees_id')
                ->join('divisions', 'divisions.id', '=', 'employees.divisions_id')
                ->whereIn('divisions_id', [9])
                ->where('overtimes.deleted_at',NULL)
                ->whereBetween('tanggal_lembur', [$awal, $akhir])->get();
        }
        elseif ($divisi == 10) {
            $items = 
                DB::table('overtimes')
                ->join('employees', 'employees.nik_karyawan', '=', 'overtimes.employees_id')
                ->join('divisions', 'divisions.id', '=', 'employees.divisions_id')
                ->whereIn('divisions_id', [12,13,14,15,18])
                ->where('overtimes.deleted_at',NULL)
                ->whereBetween('tanggal_lembur', [$awal, $akhir])->get();
        }
        elseif ($divisi == 4) {
            $items = 
                DB::table('overtimes')
                ->join('employees', 'employees.nik_karyawan', '=', 'overtimes.employees_id')
                ->join('divisions', 'divisions.id', '=', 'employees.divisions_id')
                ->where('overtimes.deleted_at',NULL)
                ->whereBetween('tanggal_lembur', [$awal, $akhir])->get();
        }
        elseif ($divisi == 1) {
            $items = 
                DB::table('overtimes')
                ->join('employees', 'employees.nik_karyawan', '=', 'overtimes.employees_id')
                ->join('divisions', 'divisions.id', '=', 'employees.divisions_id')
                ->where('overtimes.deleted_at',NULL)
                ->whereBetween('tanggal_lembur', [$awal, $akhir])->get();
        }
        else {
            abort(403);
        }
        
        if (!$items->isEmpty()) {
            return view('pages.admin.overtimes.tampilovertime',[
                'items' => $items
            ]);
        } else {
            Alert::error('Data Tidak Ditemukan');
            //Redirect
            return redirect()->route('overtimes.lihat_overtime');
        }
        
    }

    public function form_approve_overtime()
    {
        if (auth()->user()->roles != 'ADMIN' && auth()->user()->roles != 'HRD') {
            abort(403);
        }
        return view('pages.admin.overtimes.formapproveovertime');
    }

    public function form_cancel_approve_overtime()
    {
        if (auth()->user()->roles != 'ADMIN' && auth()->user()->roles != 'HRD') {
            abort(403);
        }
        return view('pages.admin.overtimes.formcancelapproveovertime');
    }

    public function tampil_approve_overtime(CariOvertimesRequest $request)
    {
        //
        if (auth()->user()->roles != 'ADMIN' && auth()->user()->roles != 'HRD') {
            abort(403);
        }

        $awal           = $request->input('awal');
        $akhir          = $request->input('akhir');

        $nik            = auth()->user()->nik;
        $caridivisi     = Employees::all()->where('nik_karyawan', $nik)->first();
        $divisi         = $caridivisi->divisions_id;

        if ($divisi == 11) {
            $items = 
                DB::table('overtimes')
                ->join('employees', 'employees.nik_karyawan', '=', 'overtimes.employees_id')
                ->join('divisions', 'divisions.id', '=', 'employees.divisions_id')
                ->whereIn('divisions_id', [11])
                ->where('overtimes.acc_hrd','')
                ->where('overtimes.deleted_at',NULL)
                ->whereBetween('tanggal_lembur', [$awal, $akhir])->get();
        } 
        elseif ($divisi == 19) {
            $items = 
                DB::table('overtimes')
                ->join('employees', 'employees.nik_karyawan', '=', 'overtimes.employees_id')
                ->join('divisions', 'divisions.id', '=', 'employees.divisions_id')
                ->whereIn('divisions_id', [19,20,21,22])
                ->where('overtimes.acc_hrd','')
                ->where('overtimes.deleted_at',NULL)
                ->whereBetween('tanggal_lembur', [$awal, $akhir])->get();
        }
        elseif ($divisi == 2) {
            $items = 
                DB::table('overtimes')
                ->join('employees', 'employees.nik_karyawan', '=', 'overtimes.employees_id')
                ->join('divisions', 'divisions.id', '=', 'employees.divisions_id')
                ->whereIn('divisions_id', [2])
                ->where('overtimes.acc_hrd','')
                ->where('overtimes.deleted_at',NULL)
                ->whereBetween('tanggal_lembur', [$awal, $akhir])->get();
        }
        elseif ($divisi == 7) {
            $items = 
                DB::table('overtimes')
                ->join('employees', 'employees.nik_karyawan', '=', 'overtimes.employees_id')
                ->join('divisions', 'divisions.id', '=', 'employees.divisions_id')
                ->whereIn('divisions_id', [7])
                ->where('overtimes.acc_hrd','')
                ->where('overtimes.deleted_at',NULL)
                ->whereBetween('tanggal_lembur', [$awal, $akhir])->get();
        }
        elseif ($divisi == 8) {
            $items = 
                DB::table('overtimes')
                ->join('employees', 'employees.nik_karyawan', '=', 'overtimes.employees_id')
                ->join('divisions', 'divisions.id', '=', 'employees.divisions_id')
                ->whereIn('divisions_id', [8])
                ->where('overtimes.acc_hrd','')
                ->where('overtimes.deleted_at',NULL)
                ->whereBetween('tanggal_lembur', [$awal, $akhir])->get();
        }
        elseif ($divisi == 9) {
            $items = 
                DB::table('overtimes')
                ->join('employees', 'employees.nik_karyawan', '=', 'overtimes.employees_id')
                ->join('divisions', 'divisions.id', '=', 'employees.divisions_id')
                ->whereIn('divisions_id', [9])
                ->where('overtimes.acc_hrd','')
                ->where('overtimes.deleted_at',NULL)
                ->whereBetween('tanggal_lembur', [$awal, $akhir])->get();
        }
        elseif ($divisi == 10) {
            $items = 
                DB::table('overtimes')
                ->join('employees', 'employees.nik_karyawan', '=', 'overtimes.employees_id')
                ->join('divisions', 'divisions.id', '=', 'employees.divisions_id')
                ->whereIn('divisions_id', [12,13,14,15,18])
                ->where('overtimes.acc_hrd','')
                ->where('overtimes.deleted_at',NULL)
                ->whereBetween('tanggal_lembur', [$awal, $akhir])->get();
        }
        elseif ($divisi == 4) {
            $items = 
                DB::table('overtimes')
                ->join('employees', 'employees.nik_karyawan', '=', 'overtimes.employees_id')
                ->join('divisions', 'divisions.id', '=', 'employees.divisions_id')
                ->where('overtimes.acc_hrd','')
                ->where('overtimes.deleted_at',NULL)
                ->whereBetween('tanggal_lembur', [$awal, $akhir])->get();
        }
        else {
            abort(403);
        }
        
        if (!$items->isEmpty()) {
            return view('pages.admin.overtimes.tampilapprovalovertime',[
                'items' => $items,
                'awal'  => $awal,
                'akhir' => $akhir
            ]);
        } else {
            Alert::error('Data Tidak Ditemukan');
            //Redirect
            return redirect()->route('overtimes.lihat_overtime');
        }

    }

    public function tampil_cancel_approve_overtime(CariOvertimesRequest $request)
    {
        //
        if (auth()->user()->roles != 'ADMIN' && auth()->user()->roles != 'HRD') {
            abort(403);
        }

        $awal           = $request->input('awal');
        $akhir          = $request->input('akhir');

        $nik            = auth()->user()->nik;
        $caridivisi     = Employees::all()->where('nik_karyawan', $nik)->first();
        $divisi         = $caridivisi->divisions_id;

        if ($divisi == 11) {
            $items = 
                DB::table('overtimes')
                ->join('employees', 'employees.nik_karyawan', '=', 'overtimes.employees_id')
                ->join('divisions', 'divisions.id', '=', 'employees.divisions_id')
                ->whereIn('divisions_id', [11])
                ->where('overtimes.acc_hrd','<>','')
                ->where('overtimes.deleted_at',NULL)
                ->whereBetween('tanggal_lembur', [$awal, $akhir])->get();
        } 
        elseif ($divisi == 19) {
            $items = 
                DB::table('overtimes')
                ->join('employees', 'employees.nik_karyawan', '=', 'overtimes.employees_id')
                ->join('divisions', 'divisions.id', '=', 'employees.divisions_id')
                ->whereIn('divisions_id', [19,20,21,22])
                ->where('overtimes.acc_hrd','<>','')
                ->where('overtimes.deleted_at',NULL)
                ->whereBetween('tanggal_lembur', [$awal, $akhir])->get();
        }
        elseif ($divisi == 2) {
            $items = 
                DB::table('overtimes')
                ->join('employees', 'employees.nik_karyawan', '=', 'overtimes.employees_id')
                ->join('divisions', 'divisions.id', '=', 'employees.divisions_id')
                ->whereIn('divisions_id', [2])
                ->where('overtimes.acc_hrd','<>','')
                ->where('overtimes.deleted_at',NULL)
                ->whereBetween('tanggal_lembur', [$awal, $akhir])->get();
        }
        elseif ($divisi == 7) {
            $items = 
                DB::table('overtimes')
                ->join('employees', 'employees.nik_karyawan', '=', 'overtimes.employees_id')
                ->join('divisions', 'divisions.id', '=', 'employees.divisions_id')
                ->whereIn('divisions_id', [7])
                ->where('overtimes.acc_hrd','<>','')
                ->where('overtimes.deleted_at',NULL)
                ->whereBetween('tanggal_lembur', [$awal, $akhir])->get();
        }
        elseif ($divisi == 8) {
            $items = 
                DB::table('overtimes')
                ->join('employees', 'employees.nik_karyawan', '=', 'overtimes.employees_id')
                ->join('divisions', 'divisions.id', '=', 'employees.divisions_id')
                ->whereIn('divisions_id', [8])
                ->where('overtimes.acc_hrd','<>','')
                ->where('overtimes.deleted_at',NULL)
                ->whereBetween('tanggal_lembur', [$awal, $akhir])->get();
        }
        elseif ($divisi == 9) {
            $items = 
                DB::table('overtimes')
                ->join('employees', 'employees.nik_karyawan', '=', 'overtimes.employees_id')
                ->join('divisions', 'divisions.id', '=', 'employees.divisions_id')
                ->whereIn('divisions_id', [9])
                ->where('overtimes.acc_hrd','<>','')
                ->where('overtimes.deleted_at',NULL)
                ->whereBetween('tanggal_lembur', [$awal, $akhir])->get();
        }
        elseif ($divisi == 10) {
            $items = 
                DB::table('overtimes')
                ->join('employees', 'employees.nik_karyawan', '=', 'overtimes.employees_id')
                ->join('divisions', 'divisions.id', '=', 'employees.divisions_id')
                ->whereIn('divisions_id', [12,13,14,15,18])
                ->where('overtimes.acc_hrd','<>','')
                ->where('overtimes.deleted_at',NULL)
                ->whereBetween('tanggal_lembur', [$awal, $akhir])->get();
        }
        elseif ($divisi == 4) {
            $items = 
                DB::table('overtimes')
                ->join('employees', 'employees.nik_karyawan', '=', 'overtimes.employees_id')
                ->join('divisions', 'divisions.id', '=', 'employees.divisions_id')
                ->where('overtimes.acc_hrd','<>','')
                ->where('overtimes.deleted_at',NULL)
                ->whereBetween('tanggal_lembur', [$awal, $akhir])->get();
        }
        else {
            abort(403);
        }
        
        if (!$items->isEmpty()) {
            return view('pages.admin.overtimes.tampilcancelapprovalovertime',[
                'items' => $items,
                'awal'  => $awal,
                'akhir' => $akhir
            ]);
        } else {
            Alert::error('Data Tidak Ditemukan');
            //Redirect
            return redirect()->route('overtimes.lihat_overtime');
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

        if ($divisi == 11) {
            $items = Employees::with([
                'companies',
                'areas',
                'divisions',
                'positions'
                ])->whereIn('divisions_id', [11])->get();
        }
        elseif ($divisi == 19) {
            $items = Employees::with([
                'companies',
                'areas',
                'divisions',
                'positions'
                ])->whereIn('divisions_id', [19,20,21,22])->get();
        } 
        elseif ($divisi == 2) {
            $items = Employees::with([
                'companies',
                'areas',
                'divisions',
                'positions'
                ])->whereIn('divisions_id', [2])->get();
        } 
        elseif ($divisi == 7) {
            $items = Employees::with([
                'companies',
                'areas',
                'divisions',
                'positions'
                ])->whereIn('divisions_id', [7])->get();
        } 
        elseif ($divisi == 8) {
            $items = Employees::with([
                'companies',
                'areas',
                'divisions',
                'positions'
                ])->whereIn('divisions_id', [8])->get();
        } 
        elseif ($divisi == 9) {
            $items = Employees::with([
                'companies',
                'areas',
                'divisions',
                'positions'
                ])->whereIn('divisions_id', [9])->get();
        } 
        elseif ($divisi == 10) {
            $items = Employees::with([
                'companies',
                'areas',
                'divisions',
                'positions'
                ])->whereIn('divisions_id', [12,13,14,15,18])->get();
        } 
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

        return view ('pages.admin.overtimes.create',[
            'items'     => $items
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(OvertimesRequest $request)
    {
        //
        // $nik_karyawan   = $request->input('employees_id');
        // $item           = Employees::where('nik_karyawan', $nik_karyawan)->first();
        if (auth()->user()->roles != 'ADMIN' && auth()->user()->roles != 'HRD' && auth()->user()->roles != 'LEADER') {
            abort(403);
        }
        
        $employees_id       = $request->input('employees_id');
        $tanggal_lembur     = $request->input('tanggal_lembur');
        $jenis_lembur       = $request->input('jenis_lembur');
        $keterangan_lembur  = $request->input('keterangan_lembur');
        $jam_masuk          = $request->input('jam_masuk');
        $jam_istirahat      = $request->input('jam_istirahat');
        $jam_pulang         = $request->input('jam_pulang');
        $jam_lembur         = $jam_pulang-$jam_istirahat-$jam_masuk;

        if ($jam_lembur >= "3") {
            $uang_makan_lembur  = "12500";
        } else {
            $uang_makan_lembur  = "0";
        }

        if ($jenis_lembur == "Libur") {

            $jam_pertama = 0;

                if ($jam_lembur < 8) {
                    $jam_kedua = $jam_lembur;
                    $jam_ketiga = 0;
                    $jam_keempat = 0;
                } elseif ($jam_lembur == 8) {
                    $jam_kedua = 8;
                    $jam_ketiga = 0;
                    $jam_keempat = 0;
                } elseif ($jam_lembur > 8) {

                    $jam_kedua = 8;

                    if ($jam_lembur - $jam_kedua > 1) {
                        $jam_ketiga = 1;
                        $jam_keempat = $jam_lembur - $jam_kedua - $jam_ketiga;
                    } elseif ($jam_lembur - $jam_kedua == 1) {
                        $jam_ketiga = 1;
                        $jam_keempat = 0;
                    } else {
                        $jam_ketiga = $jam_lembur - $jam_kedua;
                    }
                } 
        } 
        elseif ($jenis_lembur == "Biasa") {
            // $jam_pertama = 0;

                if ($jam_lembur < 1) {
                    $jam_pertama = $jam_lembur;
                    $jam_kedua   = 0;
                    $jam_ketiga  = 0;
                    $jam_keempat = 0;
                } elseif ($jam_lembur == 1) {
                    $jam_pertama = 1;
                    $jam_kedua   = 0;
                    $jam_ketiga  = 0;
                    $jam_keempat = 0;
                } elseif ($jam_lembur > 1) {

                    $jam_pertama = 1;

                    if ($jam_lembur < 9) {
                        $jam_kedua = $jam_lembur - $jam_pertama;
                        $jam_ketiga = 0;
                        $jam_keempat = 0;
                    } elseif ($jam_lembur == 9) {
                        $jam_kedua = 8;
                        $jam_ketiga = 0;
                        $jam_keempat = 0;
                    } elseif ($jam_lembur > 9) {

                        $jam_kedua = 8;

                        if ($jam_lembur - $jam_kedua - $jam_pertama == 1) {

                            $jam_ketiga = 1;
                            $jam_keempat = 0;
                        } elseif ($jam_lembur - $jam_kedua - $jam_pertama > 1) {

                            $jam_ketiga = 1;
                            $jam_keempat = $jam_lembur - $jam_ketiga - $jam_kedua - $jam_pertama;
                        } elseif ($jam_lembur - $jam_kedua - $jam_pertama < 1) {

                            $jam_ketiga = $jam_lembur - $jam_kedua - $jam_pertama;
                            $jam_keempat = 0;
                        }
                    }
                }
        }
        else {
            return redirect()->route('overtimes.create');
        }

        $jumlah_jam_pertama     = $jam_pertama * 1.5;
        $jumlah_jam_kedua       = $jam_kedua * 2;
        $jumlah_jam_ketiga      = $jam_ketiga * 3;
        $jumlah_jam_keempat     = $jam_keempat * 4;
        
        // dd($uangmakanlembur);

        // TimeStamp
        // $waktu_acc_hrd      = Carbon::now()->toDateTimeString();
        // TimeStamp

        foreach ($request->input('employees_id') as $key=>$name) {
            $insert =[
                'employees_id'          => $request->input('employees_id')[$key],
                'jam_masuk'             => $jam_masuk,
                'jam_istirahat'         => $jam_istirahat,
                'jam_pulang'            => $jam_pulang,
                'keterangan_lembur'     => $keterangan_lembur,
                'tanggal_lembur'        => $tanggal_lembur,
                'jenis_lembur'          => $jenis_lembur,
                'jam_lembur'            => $jam_lembur,
                'jam_pertama'           => $jam_pertama,
                'jumlah_jam_pertama'    => $jumlah_jam_pertama,
                'jam_kedua'             => $jam_kedua,
                'jumlah_jam_kedua'      => $jumlah_jam_kedua,
                'jam_ketiga'            => $jam_ketiga,
                'jumlah_jam_ketiga'     => $jumlah_jam_ketiga,
                'jam_keempat'           => $jam_keempat,
                'jumlah_jam_keempat'    => $jumlah_jam_keempat,
                'uang_makan_lembur'     => $uang_makan_lembur,
                'input_oleh'            => $request->input('input_oleh')
            ];
            Overtimes::create($insert);
        }
        
        Alert::success('Success Input Data Lembur','Oleh '.auth()->user()->name);
        //Redirect
        return redirect()->route('overtimes.create');
    }

    public function proses_approve_overtime(CariOvertimesRequest $request)
    {
        if (auth()->user()->roles != 'ADMIN' && auth()->user()->roles != 'HRD') {
            abort(403);
        }

        $awal   = $request->input('awal');
        $akhir  = $request->input('akhir');
        // TimeStamp
        $waktu_acc_hrd      = Carbon::now()->toDateTimeString();
        // TimeStamp

        $overtimes          = Overtimes::whereBetween('tanggal_lembur', [$awal, $akhir])
            
        ->update([
            'acc_hrd'       => auth()->user()->name,
            'waktu_acc_hrd' => $waktu_acc_hrd
        ]);

        Alert::success('Success Approve Data Lembur','Oleh '.auth()->user()->name);
        //Redirect
        return redirect()->route('overtimes.index');
    }

    public function proses_cancel_approve_overtime(CariOvertimesRequest $request)
    {
        if (auth()->user()->roles != 'ADMIN' && auth()->user()->roles != 'HRD') {
            abort(403);
        }

        $awal   = $request->input('awal');
        $akhir  = $request->input('akhir');
        // TimeStamp
        $waktu_acc_hrd      = Carbon::now()->toDateTimeString();
        // TimeStamp

        $overtimes          = Overtimes::whereBetween('tanggal_lembur', [$awal, $akhir])
            
        ->update([
            'acc_hrd'       => '',
            'waktu_acc_hrd' => $waktu_acc_hrd
        ]);

        Alert::success('Success Cancel Approve Data Lembur','Oleh '.auth()->user()->name);
        //Redirect
        return redirect()->route('overtimes.index');
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
    public function edit_overtime()
    {
        //
        if (auth()->user()->roles != 'ADMIN' && auth()->user()->roles != 'HRD' && auth()->user()->roles != 'LEADER') {
            abort(403);
        }
        $nik            = auth()->user()->nik;
        $caridivisi     = Employees::all()->where('nik_karyawan', $nik)->first();
        $divisi         = $caridivisi->divisions_id;

        if ($divisi == 11) {
            $items = 
                DB::table('overtimes')
                ->join('employees', 'employees.nik_karyawan', '=', 'overtimes.employees_id')
                ->join('divisions', 'divisions.id', '=', 'employees.divisions_id')
                ->where('overtimes.acc_hrd','')
                ->where('overtimes.deleted_at',NULL)
                ->whereIn('divisions_id', [11])
                ->get();
        }
        elseif ($divisi == 19) {
            $items = 
                DB::table('overtimes')
                ->join('employees', 'employees.nik_karyawan', '=', 'overtimes.employees_id')
                ->join('divisions', 'divisions.id', '=', 'employees.divisions_id')
                ->where('overtimes.acc_hrd','')
                ->where('overtimes.deleted_at',NULL)
                ->whereIn('divisions_id', [19,20,21,22])
                ->get();
        } 
        elseif ($divisi == 2) {
            $items = 
                DB::table('overtimes')
                ->join('employees', 'employees.nik_karyawan', '=', 'overtimes.employees_id')
                ->join('divisions', 'divisions.id', '=', 'employees.divisions_id')
                ->where('overtimes.acc_hrd','')
                ->where('overtimes.deleted_at',NULL)
                ->whereIn('divisions_id', [2])
                ->get();
        } 
        elseif ($divisi == 7) {
            $items = 
                DB::table('overtimes')
                ->join('employees', 'employees.nik_karyawan', '=', 'overtimes.employees_id')
                ->join('divisions', 'divisions.id', '=', 'employees.divisions_id')
                ->where('overtimes.acc_hrd','')
                ->where('overtimes.deleted_at',NULL)
                ->whereIn('divisions_id', [7])
                ->get();
        } 
        elseif ($divisi == 8) {
            $items = 
                DB::table('overtimes')
                ->join('employees', 'employees.nik_karyawan', '=', 'overtimes.employees_id')
                ->join('divisions', 'divisions.id', '=', 'employees.divisions_id')
                ->where('overtimes.acc_hrd','')
                ->where('overtimes.deleted_at',NULL)
                ->whereIn('divisions_id', [8])
                ->get();
        } 
        elseif ($divisi == 9) {
            $items = 
                DB::table('overtimes')
                ->join('employees', 'employees.nik_karyawan', '=', 'overtimes.employees_id')
                ->join('divisions', 'divisions.id', '=', 'employees.divisions_id')
                ->where('overtimes.acc_hrd','')
                ->where('overtimes.deleted_at',NULL)
                ->whereIn('divisions_id', [9])
                ->get();
        } 
        elseif ($divisi == 10) {
            $items = 
                DB::table('overtimes')
                ->join('employees', 'employees.nik_karyawan', '=', 'overtimes.employees_id')
                ->join('divisions', 'divisions.id', '=', 'employees.divisions_id')
                ->where('overtimes.acc_hrd','')
                ->where('overtimes.deleted_at',NULL)
                ->whereIn('divisions_id', [12,13,14,15,18])
                ->get();
        } 
        elseif ($divisi == 4) {
            $items = 
                DB::table('overtimes')
                ->join('employees', 'employees.nik_karyawan', '=', 'overtimes.employees_id')
                ->join('divisions', 'divisions.id', '=', 'employees.divisions_id')
                ->where('overtimes.acc_hrd','')
                ->where('overtimes.deleted_at',NULL)
                ->get();
        } 
        else {
            abort(403);
        }

        if (!$items->isEmpty()) {
            return view ('pages.admin.overtimes.editovertime',[
                'items'     => $items
            ]);
        } else {
            Alert::error('Data Overtimes Karyawan Sudah Di Approve Semua');
            //Redirect
            return redirect()->route('overtimes.index');
        }

        
    }

    public function tampiledit_overtime(EditOvertimesRequest $request)
    {
        //
        if (auth()->user()->roles != 'ADMIN' && auth()->user()->roles != 'HRD' && auth()->user()->roles != 'LEADER') {
            abort(403);
        }

        $employees_id       = $request->input('employees_id');
        $tanggal_lembur     = $request->input('tanggal_lembur');

        $items = Overtimes::with([
            'employees'
            ])
            ->where('employees_id', $employees_id)
            ->where('tanggal_lembur', $tanggal_lembur)
            ->first();
        
        if ($items == null) {
            Alert::error('Data yang anda cari tidak ada');
            return redirect()->route('overtimes.index');
        } else {
        return view('pages.admin.overtimes.tampileditovertime',[
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
    public function update(OvertimesRequest $request, $id)
    {
        //
        if (auth()->user()->roles != 'ADMIN' && auth()->user()->roles != 'HRD' && auth()->user()->roles != 'LEADER') {
            abort(403);
        }

        $employees_id       = $request->input('employees_id');
        $nama_karyawan      = $request->input('nama_karyawan');
        $tanggal_lembur     = $request->input('tanggal_lembur');
        $jenis_lembur       = $request->input('jenis_lembur');
        $keterangan_lembur  = $request->input('keterangan_lembur');
        $jam_masuk          = $request->input('jam_masuk');
        $jam_istirahat      = $request->input('jam_istirahat');
        $jam_pulang         = $request->input('jam_pulang');
        $jam_lembur         = $jam_pulang-$jam_istirahat-$jam_masuk;

        if ($jam_lembur >= "3") {
            $uang_makan_lembur  = "12500";
        } else {
            $uang_makan_lembur  = "0";
        }

        if ($jenis_lembur == "Libur") {

            $jam_pertama = 0;

                if ($jam_lembur < 8) {
                    $jam_kedua = $jam_lembur;
                    $jam_ketiga = 0;
                    $jam_keempat = 0;
                } elseif ($jam_lembur == 8) {
                    $jam_kedua = 8;
                    $jam_ketiga = 0;
                    $jam_keempat = 0;
                } elseif ($jam_lembur > 8) {

                    $jam_kedua = 8;

                    if ($jam_lembur - $jam_kedua > 1) {
                        $jam_ketiga = 1;
                        $jam_keempat = $jam_lembur - $jam_kedua - $jam_ketiga;
                    } elseif ($jam_lembur - $jam_kedua == 1) {
                        $jam_ketiga = 1;
                        $jam_keempat = 0;
                    } else {
                        $jam_ketiga = $jam_lembur - $jam_kedua;
                    }
                } 
        } 
        elseif ($jenis_lembur == "Biasa") {
            // $jam_pertama = 0;

                if ($jam_lembur < 1) {
                    $jam_pertama = $jam_lembur;
                    $jam_kedua   = 0;
                    $jam_ketiga  = 0;
                    $jam_keempat = 0;
                } elseif ($jam_lembur == 1) {
                    $jam_pertama = 1;
                    $jam_kedua   = 0;
                    $jam_ketiga  = 0;
                    $jam_keempat = 0;
                } elseif ($jam_lembur > 1) {

                    $jam_pertama = 1;

                    if ($jam_lembur < 9) {
                        $jam_kedua = $jam_lembur - $jam_pertama;
                        $jam_ketiga = 0;
                        $jam_keempat = 0;
                    } elseif ($jam_lembur == 9) {
                        $jam_kedua = 8;
                        $jam_ketiga = 0;
                        $jam_keempat = 0;
                    } elseif ($jam_lembur > 9) {

                        $jam_kedua = 8;

                        if ($jam_lembur - $jam_kedua - $jam_pertama == 1) {

                            $jam_ketiga = 1;
                            $jam_keempat = 0;
                        } elseif ($jam_lembur - $jam_kedua - $jam_pertama > 1) {

                            $jam_ketiga = 1;
                            $jam_keempat = $jam_lembur - $jam_ketiga - $jam_kedua - $jam_pertama;
                        } elseif ($jam_lembur - $jam_kedua - $jam_pertama < 1) {

                            $jam_ketiga = $jam_lembur - $jam_kedua - $jam_pertama;
                            $jam_keempat = 0;
                        }
                    }
                }
        }
        else {
            return redirect()->route('overtimes.index');
        }

        $jumlah_jam_pertama     = $jam_pertama * 1.5;
        $jumlah_jam_kedua       = $jam_kedua * 2;
        $jumlah_jam_ketiga      = $jam_ketiga * 3;
        $jumlah_jam_keempat     = $jam_keempat * 4;

        $overtimes              = Overtimes::where('id', $id)->first();
        $overtimes->update([
            'jam_masuk'             => $jam_masuk,
            'jam_istirahat'         => $jam_istirahat,
            'jam_pulang'            => $jam_pulang,
            'keterangan_lembur'     => $keterangan_lembur,
            'tanggal_lembur'        => $tanggal_lembur,
            'jenis_lembur'          => $jenis_lembur,
            'jam_lembur'            => $jam_lembur,
            'jam_pertama'           => $jam_pertama,
            'jumlah_jam_pertama'    => $jumlah_jam_pertama,
            'jam_kedua'             => $jam_kedua,
            'jumlah_jam_kedua'      => $jumlah_jam_kedua,
            'jam_ketiga'            => $jam_ketiga,
            'jumlah_jam_ketiga'     => $jumlah_jam_ketiga,
            'jam_keempat'           => $jam_keempat,
            'jumlah_jam_keempat'    => $jumlah_jam_keempat,
            'uang_makan_lembur'     => $uang_makan_lembur,
            'edit_oleh'             => $request->input('edit_oleh')
        ]);

        Alert::info('Success Edit Data Overtimes','Oleh '.auth()->user()->name);
        return redirect()->route('overtimes.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */

    public function form_hapus_overtime()
    {
        if (auth()->user()->roles != 'ADMIN' && auth()->user()->roles != 'HRD' && auth()->user()->roles != 'LEADER') {
            abort(403);
        }

        $nik            = auth()->user()->nik;
        $caridivisi     = Employees::all()->where('nik_karyawan', $nik)->first();
        $divisi         = $caridivisi->divisions_id;

        if ($divisi == 11) {
            $items = 
                DB::table('overtimes')
                ->join('employees', 'employees.nik_karyawan', '=', 'overtimes.employees_id')
                ->join('divisions', 'divisions.id', '=', 'employees.divisions_id')
                ->where('overtimes.acc_hrd','')
                ->where('overtimes.deleted_at',NULL)
                ->whereIn('divisions_id', [11])
                ->get();
        }
        elseif ($divisi == 19) {
            $items = 
                DB::table('overtimes')
                ->join('employees', 'employees.nik_karyawan', '=', 'overtimes.employees_id')
                ->join('divisions', 'divisions.id', '=', 'employees.divisions_id')
                ->where('overtimes.acc_hrd','')
                ->where('overtimes.deleted_at',NULL)
                ->whereIn('divisions_id', [19,20,21,22])
                ->get();
        } 
        elseif ($divisi == 2) {
            $items = 
                DB::table('overtimes')
                ->join('employees', 'employees.nik_karyawan', '=', 'overtimes.employees_id')
                ->join('divisions', 'divisions.id', '=', 'employees.divisions_id')
                ->where('overtimes.acc_hrd','')
                ->where('overtimes.deleted_at',NULL)
                ->whereIn('divisions_id', [2])
                ->get();
        } 
        elseif ($divisi == 7) {
            $items = 
                DB::table('overtimes')
                ->join('employees', 'employees.nik_karyawan', '=', 'overtimes.employees_id')
                ->join('divisions', 'divisions.id', '=', 'employees.divisions_id')
                ->where('overtimes.acc_hrd','')
                ->where('overtimes.deleted_at',NULL)
                ->whereIn('divisions_id', [7])
                ->get();
        } 
        elseif ($divisi == 8) {
            $items = 
                DB::table('overtimes')
                ->join('employees', 'employees.nik_karyawan', '=', 'overtimes.employees_id')
                ->join('divisions', 'divisions.id', '=', 'employees.divisions_id')
                ->where('overtimes.acc_hrd','')
                ->where('overtimes.deleted_at',NULL)
                ->whereIn('divisions_id', [8])
                ->get();
        } 
        elseif ($divisi == 9) {
            $items = 
                DB::table('overtimes')
                ->join('employees', 'employees.nik_karyawan', '=', 'overtimes.employees_id')
                ->join('divisions', 'divisions.id', '=', 'employees.divisions_id')
                ->where('overtimes.acc_hrd','')
                ->where('overtimes.deleted_at',NULL)
                ->whereIn('divisions_id', [9])
                ->get();
        } 
        elseif ($divisi == 10) {
            $items = 
                DB::table('overtimes')
                ->join('employees', 'employees.nik_karyawan', '=', 'overtimes.employees_id')
                ->join('divisions', 'divisions.id', '=', 'employees.divisions_id')
                ->where('overtimes.acc_hrd','')
                ->where('overtimes.deleted_at',NULL)
                ->whereIn('divisions_id', [12,13,14,15,18])
                ->get();
        } 
        elseif ($divisi == 4) {
            $items = 
                DB::table('overtimes')
                ->join('employees', 'employees.nik_karyawan', '=', 'overtimes.employees_id')
                ->join('divisions', 'divisions.id', '=', 'employees.divisions_id')
                ->where('overtimes.acc_hrd','')
                ->where('overtimes.deleted_at',NULL)
                ->get();
        } 
        else {
            abort(403);
        }
        if (!$items->isEmpty()) {
            return view ('pages.admin.overtimes.formhapusovertime',[
                'items'     => $items
            ]);
        } else {
            Alert::error('Data Tidak Ditemukan, Data Sudah Di Approve');
            //Redirect
            return redirect()->route('overtimes.index');
        }
        
    }

    public function tampilhapus_overtime(EditOvertimesRequest $request)
    {
        if (auth()->user()->roles != 'ADMIN' && auth()->user()->roles != 'HRD' && auth()->user()->roles != 'LEADER') {
            abort(403);
        }

        $employees_id       = $request->input('employees_id');
        $tanggal_lembur     = $request->input('tanggal_lembur');

        $items = Overtimes::with([
            'employees'
            ])
            ->where('employees_id', $employees_id)
            ->where('tanggal_lembur', $tanggal_lembur)
            ->first();
            if ($items == null) {
                Alert::error('Data yang anda cari tidak ada');
                return redirect()->route('overtimes.index');
            } else {
            return view('pages.admin.overtimes.tampilhapusovertime',[
                'items' => $items
            ]);
        }
    }

    public function destroy(Request $request,$id)
    {
        //
        if (auth()->user()->roles != 'ADMIN' && auth()->user()->roles != 'HRD' && auth()->user()->roles != 'LEADER') {
            abort(403);
        }

        $hapus_oleh     = $request->input('hapus_oleh');
        $overtimes      = Overtimes::where('id', $id)->first();
        $overtimes->update([
            'hapus_oleh'    => $request->input('hapus_oleh')
        ]);

        $item = Overtimes::findOrFail($id);
        $item->delete();
        Alert::error('Menghapus Data Overtimes','Oleh '.auth()->user()->name);
        return redirect()->route('overtimes.index');


    }
}
