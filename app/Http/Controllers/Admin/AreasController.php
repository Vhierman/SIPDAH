<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\Admin\AreasRequest;
use App\Models\Admin\Areas;
use Alert;

class AreasController extends Controller
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

        $items = Areas::all();
        return view('pages.admin.areas.index',[
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
        
        return view('pages.admin.areas.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(AreasRequest $request)
    {
        //
        if (auth()->user()->roles != 'ADMIN') {
            abort(403);
        }
        $data = $request->all();
        Areas::create($data);
        Alert::success('Success Input Data Area','Oleh '.auth()->user()->name);
        return redirect()->route('areas.index');
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
        $item = Areas::findOrFail($id);

        return view('pages.admin.areas.edit',[
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
    public function update(AreasRequest $request, $id)
    {
        //
        if (auth()->user()->roles != 'ADMIN') {
            abort(403);
        }
        $data = $request->all();
        $item = Areas::findOrFail($id);
        $item->update($data);
        Alert::info('Success Edit Data Area','Oleh '.auth()->user()->name);
        return redirect()->route('areas.index');
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
        $item = Areas::findOrFail($id);
        $item->delete();
        Alert::error('Menghapus Data Area','Oleh '.auth()->user()->name);
        return redirect()->route('areas.index');
    }
}
