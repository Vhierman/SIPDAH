<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\HistoryTrainingInternalsRequest;
use App\Models\Admin\Employees;
use App\Models\Admin\HistoryTrainingInternals;
use Illuminate\Http\Request;
use DB;
use Alert;

class HistoryTrainingInternalsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function tambahhistorytraininginternal($nik_karyawan)
    {
        //
        if (auth()->user()->roles != 'ADMIN' && auth()->user()->roles != 'HRD') {
            abort(403);
        }
        $item       = Employees::where('nik_karyawan', $nik_karyawan)->first();

        $historytraininginternals = HistoryTrainingInternals::with([
            'employees'
            ])->where('employees_id', $nik_karyawan)->first();

        return view ('pages.admin.history-training-internals.create',[
            'item'                      => $item,
            'historytraininginternals'  => $historytraininginternals
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
    public function store(HistoryTrainingInternalsRequest $request)
    {
        //
        if (auth()->user()->roles != 'ADMIN' && auth()->user()->roles != 'HRD') {
            abort(403);
        }
        $tanggal    = $request->input('tanggal_training_internal');
        $hari       = \Carbon\Carbon::parse($tanggal)->isoformat('dddd');

        //Insert History Training Internal
        HistoryTrainingInternals::create([
            'employees_id'                  => $request->input('nik_karyawan'),
            'hari_training_internal'        => $hari,
            'tanggal_training_internal'     => $request->input('tanggal_training_internal'),
            'jam_training_internal'         => $request->input('jam_training_internal'),
            'lokasi_training_internal'      => $request->input('lokasi_training_internal'),
            'materi_training_internal'      => $request->input('materi_training_internal'),
            'trainer_training_internal'     => $request->input('trainer_training_internal'),
            'input_oleh'                    => $request->input('input_oleh')
        ]);
        $employees                          = Employees::where('nik_karyawan', $request->input('nik_karyawan'))->first();
        //Insert History Training Internal
        Alert::success('Success Input Data Training Internal','Oleh '.auth()->user()->name);
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
        $itemhistory        = HistoryTrainingInternals::findOrFail($id);
        $nikkaryawan        = $itemhistory->employees_id;
        $item               = Employees::where('nik_karyawan', $nikkaryawan)->first();

        $historytraininginternals   = HistoryTrainingInternals::where('id', $id)->first();

        return view ('pages.admin.history-training-internals.edit',[
            'item'                          => $item,
            'historytraininginternals'      => $historytraininginternals
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(HistoryTrainingInternalsRequest $request, $id)
    {
        //
        if (auth()->user()->roles != 'ADMIN' && auth()->user()->roles != 'HRD') {
            abort(403);
        }
        $itemhistory    = HistoryTrainingInternals::findOrFail($id);
        $nikkaryawan    = $itemhistory->employees_id;
        $itemkaryawan   = Employees::where('nik_karyawan', $nikkaryawan)->first();

        $tanggal        = $request->input('tanggal_training_internal');
        $hari           = \Carbon\Carbon::parse($tanggal)->isoformat('dddd');

        $data           = $request->all();
        $item           = HistoryTrainingInternals::findOrFail($id);

        $item->update([
            'employees_id'              => $request->input('nik_karyawan'),
            'hari_training_internal'    => $hari,
            'tanggal_training_internal' => $request->input('tanggal_training_internal'),
            'jam_training_internal'     => $request->input('jam_training_internal'),
            'lokasi_training_internal'  => $request->input('lokasi_training_internal'),
            'materi_training_internal'  => $request->input('materi_training_internal'),
            'trainer_training_internal' => $request->input('trainer_training_internal'),
            'edit_oleh'                 => $request->input('edit_oleh')
        ]);
        Alert::info('Success Edit Data Training Internal','Oleh '.auth()->user()->name);
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
        $item = HistoryTrainingInternals::findOrFail($id);

        $nikkaryawan    = $item->employees_id;
        $itemkaryawan   = Employees::where('nik_karyawan', $nikkaryawan)->first();

        $item->delete();
        Alert::error('Menghapus Data Training Internal','Oleh '.auth()->user()->name);
        return redirect()->route('employees.show',$itemkaryawan->id);
    }
}
