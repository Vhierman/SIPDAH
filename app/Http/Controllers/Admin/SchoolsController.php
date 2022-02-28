<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\Admin\SchoolsRequest;
use App\Models\Admin\Schools;
use Alert;

class SchoolsController extends Controller
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
        $items = Schools::all();
        return view('pages.admin.schools.index',[
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
        return view('pages.admin.schools.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(SchoolsRequest $request)
    {
        //
        if (auth()->user()->roles != 'ADMIN') {
            abort(403);
        }
        $data = $request->all();
        Schools::create($data);
        Alert::success('Success Input Data Sekolah','Oleh '.auth()->user()->name);
        return redirect()->route('schools.index');
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
        $item = Schools::findOrFail($id);

        return view('pages.admin.schools.edit',[
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
    public function update(SchoolsRequest $request, $id)
    {
        //
        if (auth()->user()->roles != 'ADMIN') {
            abort(403);
        }
        $data = $request->all();
        $item = Schools::findOrFail($id);
        $item->update($data);
        Alert::info('Success Edit Data Sekolah','Oleh '.auth()->user()->name);
        return redirect()->route('schools.index');
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
        $item = Schools::findOrFail($id);
        $item->delete();
        Alert::error('Menghapus Data Sekolah','Oleh '.auth()->user()->name);
        return redirect()->route('schools.index');
    }
}
