<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\Admin\StudentsRequest;
use App\Models\Admin\Students;
use App\Models\Admin\Schools;
use App\Models\Admin\Divisions;
use Alert;

class StudentsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        if (auth()->user()->roles != 'ADMIN' && auth()->user()->roles != 'HRD' && auth()->user()->roles != 'LEADER' && auth()->user()->roles != 'MANAGER') {
            abort(403);
        }
        $items = Students::with([
            'schools',
            'divisions'
            ])->get();

        return view('pages.admin.students.index',[
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
        if (auth()->user()->roles != 'ADMIN' && auth()->user()->roles != 'HRD') {
            abort(403);
        }
        $schools        = Schools::all();
        $divisions      = Divisions::all();
        
        return view ('pages.admin.students.create',[
            'divisions'     => $divisions,
            'schools'       => $schools
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StudentsRequest $request)
    {
        //
        if (auth()->user()->roles != 'ADMIN' && auth()->user()->roles != 'HRD') {
            abort(403);
        }
        $data = $request->all();
        Students::create($data);
        Alert::success('Success Input Data Siswa','Oleh '.auth()->user()->name);
        return redirect()->route('students.index');
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
        $item           = Students::findOrFail($id);
        $schools        = Schools::all();
        $divisions      = Divisions::all();

        return view ('pages.admin.students.edit',[
            'item'          => $item,
            'schools'       => $schools,
            'divisions'     => $divisions
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(StudentsRequest $request, $id)
    {
        //
        if (auth()->user()->roles != 'ADMIN' && auth()->user()->roles != 'HRD') {
            abort(403);
        }
        $data       = $request->all();
        $item       = Students::findOrFail($id);
        $item->update($data);
        Alert::info('Success Edit Data Siswa','Oleh '.auth()->user()->name);
        return redirect()->route('students.index');
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
        $item = Students::findOrFail($id);
        $item->delete();
        Alert::error('Menghapus Data Siswa','Oleh '.auth()->user()->name);
        return redirect()->route('students.index');
    }
}
