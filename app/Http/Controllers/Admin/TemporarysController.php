<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\Admin\TemporarysRequest;
use App\Models\Admin\Employees;
use App\Models\Admin\HistorySalaries;
use App\Models\Admin\Divisions;
use App\Models\Admin\Positions;
use Alert;
use DB;

class TemporarysController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        if (auth()->user()->roles != 'ADMIN' && auth()->user()->roles != 'HRD') {
            abort(403);
        }

        $items = Employees::with([
            'divisions'
            ])->get();
        
            // $salaries       = HistorySalaries::where('employees_id', $nikkaryawan)->first();

        return view('pages.admin.temporary.index',[
            'items' => $items
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
        if (auth()->user()->roles != 'ADMIN') {
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
        if (auth()->user()->roles != 'ADMIN') {
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
        if (auth()->user()->roles != 'ADMIN') {
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
        if (auth()->user()->roles != 'ADMIN') {
            abort(403);
        }
        

        $item = HistorySalaries::findOrFail($id);

        $nikkaryawan = $item->employees_id;
        
        $itemkaryawans = HistorySalaries::with([
            'employees'
        ])->where('employees_id',$nikkaryawan)->first();

        return view('pages.admin.temporary.edit',[
        'item'          => $item,
        'itemkaryawans' => $itemkaryawans
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(TemporarysRequest $request, $id)
    {
        //
        if (auth()->user()->roles != 'ADMIN') {
            abort(403);
        }

        $item           = HistorySalaries::findOrFail($id);
        $nikkaryawan    = $item->nik_karyawan;
        
        $items = HistorySalaries::with([
            'employees'
        ])->where('employees_id',$nikkaryawan)->first();

        $item->update([
            'upah_lembur_perjam'    => $request->input('upah_lembur_perjam'),
            'edit_oleh'             => $request->input('edit_oleh')
        ]);
        Alert::info('Success Edit Upah Lembur Perjam','Oleh '.auth()->user()->name);
        return redirect()->route('temporarys.index');
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
        if (auth()->user()->roles != 'ADMIN') {
            abort(403);
        }
    }
}
