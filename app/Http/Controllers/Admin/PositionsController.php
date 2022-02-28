<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\Admin\PositionsRequest;
use App\Models\Admin\Positions;
use Alert;

class PositionsController extends Controller
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
        $items = Positions::all();
        return view('pages.admin.positions.index',[
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
        return view('pages.admin.positions.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(PositionsRequest $request)
    {
        //
        if (auth()->user()->roles != 'ADMIN') {
            abort(403);
        }
        $data = $request->all();
        Positions::create($data);
        Alert::success('Success Input Data Jabatan','Oleh '.auth()->user()->name);
        return redirect()->route('positions.index');
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
        $item = Positions::findOrFail($id);

        return view('pages.admin.positions.edit',[
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
    public function update(PositionsRequest $request, $id)
    {
        //
        if (auth()->user()->roles != 'ADMIN') {
            abort(403);
        }
        $data = $request->all();
        $item = Positions::findOrFail($id);
        $item->update($data);
        Alert::info('Success Edit Data Jabatan','Oleh '.auth()->user()->name);
        return redirect()->route('positions.index');
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
        $item = Positions::findOrFail($id);
        $item->delete();
        Alert::error('Menghapus Data Jabatan','Oleh '.auth()->user()->name);
        return redirect()->route('positions.index');
    }
}
