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
        if (auth()->user()->roles != 'ADMIN') {
            abort(403);
        }

        $items = Employees::with([
            'divisions'
            ])->get();

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
        

        $item = Employees::findOrFail($id);
        $nikkaryawan = $item->employees_id;
        
        $itemkaryawans = Employees::with([
            'divisions'
        ])->where('nik_karyawan',$nikkaryawan)->first();

        return view('pages.admin.temporary.edit',[
        'item'  => $item,
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

        $item   = Employees::findOrFail($id);
        $item->update([
            'email_karyawan'    => $request->input('email_karyawan'),
            'nomor_handphone'   => $request->input('nomor_handphone'),
            'edit_oleh'         => $request->input('edit_oleh')
        ]);
        Alert::info('Success Edit Email Dan No HP','Oleh '.auth()->user()->name);
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
