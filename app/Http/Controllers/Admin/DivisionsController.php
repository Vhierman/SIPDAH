<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\Admin\DivisionsRequest;
use App\Models\Admin\Divisions;
use Alert;

class DivisionsController extends Controller
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
        $items = Divisions::all();
        return view('pages.admin.divisions.index',[
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
        return view('pages.admin.divisions.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(DivisionsRequest $request)
    {
        //
        if (auth()->user()->roles != 'ADMIN') {
            abort(403);
        }
        $data = $request->all();
        Divisions::create($data);
        Alert::success('Success Input Data Penempatan','Oleh '.auth()->user()->name);
        return redirect()->route('divisions.index');
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
        $item = Divisions::findOrFail($id);

        return view('pages.admin.divisions.edit',[
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
    public function update(DivisionsRequest $request, $id)
    {
        //
        if (auth()->user()->roles != 'ADMIN') {
            abort(403);
        }
        $data = $request->all();
        $item = Divisions::findOrFail($id);
        $item->update($data);
        Alert::info('Success Edit Data Penempatan','Oleh '.auth()->user()->name);
        return redirect()->route('divisions.index');
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
        $item = Divisions::findOrFail($id);
        $item->delete();
        Alert::error('Menghapus Data Penempatan','Oleh '.auth()->user()->name);
        return redirect()->route('divisions.index');
    }
}
