<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\Admin\WorkingHoursRequest;
use App\Models\Admin\WorkingHours;
use Alert;

class WorkingHoursController extends Controller
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
        $items = WorkingHours::all();
        return view('pages.admin.working-hours.index',[
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
        return view('pages.admin.working-hours.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(WorkingHoursRequest $request)
    {
        //
        if (auth()->user()->roles != 'ADMIN') {
            abort(403);
        }
        $data = $request->all();
        WorkingHours::create($data);
        Alert::success('Success Input Data Jam Kerja','Oleh '.auth()->user()->name);
        return redirect()->route('working-hours.index');
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
        $item = WorkingHours::findOrFail($id);

        return view('pages.admin.working-hours.edit',[
        'item' => $item
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(WorkingHoursRequest $request, $id)
    {
        //
        if (auth()->user()->roles != 'ADMIN') {
            abort(403);
        }
        $data = $request->all();
        $item = WorkingHours::findOrFail($id);
        $item->update($data);
        Alert::info('Success Edit Data Jam Kerja','Oleh '.auth()->user()->name);
        return redirect()->route('working-hours.index');
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
        $item = WorkingHours::findOrFail($id);
        $item->delete();
        Alert::error('Menghapus Data Jam Kerja','Oleh '.auth()->user()->name);
        return redirect()->route('working-hours.index');
    }
}
