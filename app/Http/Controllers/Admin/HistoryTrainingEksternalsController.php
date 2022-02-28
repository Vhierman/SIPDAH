<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\HistoryTrainingEksternalsRequest;
use App\Models\Admin\Employees;
use App\Models\Admin\HistoryTrainingEksternals;
use Illuminate\Http\Request;
use Alert;

class HistoryTrainingEksternalsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function tambahhistorytrainingeksternal($nik_karyawan)
    {
        //
        if (auth()->user()->roles != 'ADMIN' && auth()->user()->roles != 'HRD') {
            abort(403);
        }
        $item       = Employees::where('nik_karyawan', $nik_karyawan)->first();

        $historytrainingeksternals = HistoryTrainingEksternals::with([
            'employees'
            ])->where('employees_id', $nik_karyawan)->first();

        return view ('pages.admin.history-training-eksternals.create',[
            'item'                      => $item,
            'historytrainingeksternals' => $historytrainingeksternals
        ]);

    }

    public function index()
    {
        //
        if (auth()->user()->roles != 'ADMIN' && auth()->user()->roles != 'HRD' && auth()->user()->roles != 'LEADER' && auth()->user()->roles != 'MANAGER') {
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
    public function store(HistoryTrainingEksternalsRequest $request)
    {
        //
        if (auth()->user()->roles != 'ADMIN' && auth()->user()->roles != 'HRD') {
            abort(403);
        }
        $tanggalawal    = $request->input('tanggal_awal_training_eksternal');
        $tanggalakhir   = $request->input('tanggal_akhir_training_eksternal');
        $hariawal       = \Carbon\Carbon::parse($tanggalawal)->isoformat('dddd');
        $hariakhir      = \Carbon\Carbon::parse($tanggalakhir)->isoformat('dddd');

        //Insert History Training Eksternal
        HistoryTrainingEksternals::create([
            'employees_id'                                  => $request->input('nik_karyawan'),
            'institusi_penyelenggara_training_eksternal'    => $request->input('institusi_penyelenggara_training_eksternal'),
            'perihal_training_eksternal'                    => $request->input('perihal_training_eksternal'),
            'hari_awal_training_eksternal'                  => $hariawal,
            'hari_akhir_training_eksternal'                 => $hariakhir,
            'tanggal_awal_training_eksternal'               => $request->input('tanggal_awal_training_eksternal'),
            'tanggal_akhir_training_eksternal'              => $request->input('tanggal_akhir_training_eksternal'),
            'jam_training_eksternal'                        => $request->input('jam_training_eksternal'),
            'lokasi_training_eksternal'                     => $request->input('lokasi_training_eksternal'),
            'alamat_training_eksternal'                     => $request->input('alamat_training_eksternal'),
            'input_oleh'                                    => $request->input('input_oleh')
        ]);
        $employees                          = Employees::where('nik_karyawan', $request->input('nik_karyawan'))->first();
        //Insert History Training Eksternal
        Alert::success('Success Input Data Training Eksternal','Oleh '.auth()->user()->name);
        return redirect()->route('employees.show',$employees->id);
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
        $itemhistory        = HistoryTrainingEksternals::findOrFail($id);
        $nikkaryawan        = $itemhistory->employees_id;
        $item               = Employees::where('nik_karyawan', $nikkaryawan)->first();

        $historytrainingeksternals   = HistoryTrainingEksternals::where('id', $id)->first();

        return view ('pages.admin.history-training-eksternals.edit',[
            'item'                      => $item,
            'historytrainingeksternals' => $historytrainingeksternals
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(HistoryTrainingEksternalsRequest $request, $id)
    {
        //
        if (auth()->user()->roles != 'ADMIN' && auth()->user()->roles != 'HRD') {
            abort(403);
        }
        $itemhistory    = HistoryTrainingEksternals::findOrFail($id);
        $nikkaryawan    = $itemhistory->employees_id;
        $itemkaryawan   = Employees::where('nik_karyawan', $nikkaryawan)->first();

        $tanggalawal    = $request->input('tanggal_awal_training_eksternal');
        $tanggalakhir   = $request->input('tanggal_akhir_training_eksternal');
        $hariawal       = \Carbon\Carbon::parse($tanggalawal)->isoformat('dddd');
        $hariakhir      = \Carbon\Carbon::parse($tanggalakhir)->isoformat('dddd');

        $data           = $request->all();
        $item           = HistoryTrainingEksternals::findOrFail($id);

        $item->update([
            'institusi_penyelenggara_training_eksternal'    => $request->input('institusi_penyelenggara_training_eksternal'),
            'perihal_training_eksternal'                    => $request->input('perihal_training_eksternal'),
            'hari_awal_training_eksternal'                  => $hariawal,
            'hari_akhir_training_eksternal'                 => $hariakhir,
            'tanggal_awal_training_eksternal'               => $request->input('tanggal_awal_training_eksternal'),
            'tanggal_akhir_training_eksternal'              => $request->input('tanggal_akhir_training_eksternal'),
            'jam_training_eksternal'                        => $request->input('jam_training_eksternal'),
            'lokasi_training_eksternal'                     => $request->input('lokasi_training_eksternal'),
            'alamat_training_eksternal'                     => $request->input('alamat_training_eksternal'),
            'edit_oleh'                                     => $request->input('edit_oleh')
        ]);
        Alert::info('Success Edit Data Training Eksternal','Oleh '.auth()->user()->name);
        return redirect()->route('employees.show',$itemkaryawan->id);
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
        $item = HistoryTrainingEksternals::findOrFail($id);

        $nikkaryawan    = $item->employees_id;
        $itemkaryawan   = Employees::where('nik_karyawan', $nikkaryawan)->first();

        $item->delete();
        Alert::error('Menghapus Data Training Eksternal','Oleh '.auth()->user()->name);
        return redirect()->route('employees.show',$itemkaryawan->id);
    }
}
