<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\OvertimesRequest;
use App\Models\Admin\Overtimes;
use App\Models\Admin\Employees;
use App\Models\Admin\Companies;
use App\Models\Admin\Areas;
use App\Models\Admin\Divisions;
use App\Models\Admin\Positions;
use Illuminate\Http\Request;
use Carbon\Carbon;
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
        return view('pages.admin.overtimes.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
        $items = Employees::with([
            'companies',
            'areas',
            'divisions',
            'positions'
            ])->get();

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

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
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
    }
}
