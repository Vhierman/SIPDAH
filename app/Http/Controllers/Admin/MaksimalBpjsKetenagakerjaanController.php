<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\Admin\MaksimalBpjsKetenagakerjaanRequest;
use App\Models\Admin\MaksimalBpjsketenagakerjaans;
use Alert;

class MaksimalBpjsKetenagakerjaanController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        if (auth()->user()->roles != 'ADMIN' && auth()->user()->roles != 'MANAGER' && auth()->user()->roles != 'HRD') {
            abort(403);
        }

        $items = MaksimalBpjsketenagakerjaans::all();
        return view('pages.admin.maksimal-bpjstk.index',[
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
        if (auth()->user()->roles != 'ADMIN' && auth()->user()->roles != 'MANAGER' && auth()->user()->roles != 'HRD') {
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
        if (auth()->user()->roles != 'ADMIN' && auth()->user()->roles != 'MANAGER' && auth()->user()->roles != 'HRD') {
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
        if (auth()->user()->roles != 'ADMIN' && auth()->user()->roles != 'MANAGER' && auth()->user()->roles != 'HRD') {
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
        if (auth()->user()->roles != 'ADMIN' && auth()->user()->roles != 'MANAGER' && auth()->user()->roles != 'HRD') {
            abort(403);
        }

        $item = MaksimalBpjsketenagakerjaans::findOrFail($id);

        return view('pages.admin.maksimal-bpjstk.edit',[
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
    public function update(MaksimalBpjsKetenagakerjaanRequest $request, $id)
    {
        //
        if (auth()->user()->roles != 'ADMIN' && auth()->user()->roles != 'MANAGER' && auth()->user()->roles != 'HRD') {
            abort(403);
        }
        $data = $request->all();
        $item = MaksimalBpjsketenagakerjaans::findOrFail($id);
        $item->update($data);
        Alert::info('Success Edit Data Maksimal Upah BPJSTK','Oleh '.auth()->user()->name);
        return redirect()->route('maksimalbpjsketenagakerjaan.index');
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
        if (auth()->user()->roles != 'ADMIN' && auth()->user()->roles != 'MANAGER' && auth()->user()->roles != 'HRD') {
            abort(403);
        }
    }
}
