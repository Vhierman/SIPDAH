@extends('layouts.admin')

@section('content')
    {{-- Content Dan Footer --}}
    <div id="layoutSidenav_content">
        {{-- Content --}}
        <main>
            <div class="container-fluid mt-4">

                <div class="card mb-4">
                    <div class="card-header">
                        <i class="fas fa-table mr-1"></i>
                        Data Lembur
                    </div>

                    <div class="card shadow">

                        @if (Auth::user()->roles == 'ADMIN' || Auth::user()->roles == 'HRD')
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-3 d-grid gap-2 mt-2">
                                        <a href="{{ route('overtimes.lihat_overtime') }}" class="btn btn-primary btn-lg">
                                            <i class="fas fa-search"></i>
                                            Lihat Data
                                        </a>
                                    </div>
                                    <div class="col-md-3 d-grid gap-2 mt-2">
                                        <a href="{{ route('overtimes.create') }}" class="btn btn-success btn-lg">
                                            <i class="fas fa-plus"></i>
                                            Tambah Data
                                        </a>
                                    </div>
                                    <div class="col-md-3 d-grid gap-2 mt-2">
                                        <a href="{{ route('overtimes.edit_overtime') }}" class="btn btn-warning btn-lg">
                                            <i class="fas fa-sync-alt"></i>
                                            Edit Data
                                        </a>
                                    </div>
                                    <div class="col-md-3 d-grid gap-2 mt-2">
                                        <a href="{{ route('overtimes.form_hapus_overtime') }}"
                                            class="btn btn-danger btn-lg">
                                            <i class="fas fa-trash-alt"></i>
                                            Hapus Data
                                        </a>
                                    </div>
                                    <div class="col-md-3 d-grid gap-2 mt-2">
                                        <a href="{{ route('overtimes.form_approve_overtime') }}"
                                            class="btn btn-primary btn-lg">
                                            <i class="fas fa-check"></i>
                                            Rekap Data
                                        </a>
                                    </div>
                                    <div class="col-md-3 d-grid gap-2 mt-2">
                                        <a href="{{ route('overtimes.form_cetak_slip_overtime') }}"
                                            class="btn btn-success btn-lg">
                                            <i class="fas fa-print"></i>
                                            Cetak Slip
                                        </a>
                                    </div>
                                    <div class="col-md-3 d-grid gap-2 mt-2">
                                        <a href="{{ route('overtimes.index') }}" class="btn btn-warning btn-lg">
                                            <i class="fas fa-print"></i>
                                            Cetak Rekap
                                        </a>
                                    </div>
                                    <div class="col-md-3 d-grid gap-2 mt-2">
                                        <a href="{{ route('overtimes.form_cancel_approve_overtime') }}"
                                            class="btn btn-danger btn-lg">
                                            <i class="fas fa-undo"></i>
                                            Cancel Rekap
                                        </a>
                                    </div>
                                </div>
                            </div>
                        @elseif(Auth::user()->roles == 'ACCOUNTING')
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-3 d-grid gap-2 mt-2">
                                        <a href="{{ route('overtimes.lihat_overtime') }}" class="btn btn-primary btn-lg">
                                            <i class="fas fa-search"></i>
                                            Lihat Data
                                        </a>
                                    </div>
                                    <div class="col-md-3 d-grid gap-2 mt-2">
                                        <a href="{{ route('overtimes.form_cetak_slip_overtime') }}"
                                            class="btn btn-success btn-lg">
                                            <i class="fas fa-print"></i>
                                            Cetak Slip
                                        </a>
                                    </div>
                                    <div class="col-md-3 d-grid gap-2 mt-2">
                                        <a href="{{ route('overtimes.index') }}" class="btn btn-warning btn-lg">
                                            <i class="fas fa-print"></i>
                                            Cetak Rekap
                                        </a>
                                    </div>
                                </div>
                            </div>
                        @elseif(Auth::user()->roles == 'LEADER')
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-3 d-grid gap-2 mt-2">
                                        <a href="{{ route('overtimes.lihat_overtime') }}" class="btn btn-primary btn-lg">
                                            <i class="fas fa-search"></i>
                                            Lihat Data
                                        </a>
                                    </div>
                                    <div class="col-md-3 d-grid gap-2 mt-2">
                                        <a href="{{ route('overtimes.create') }}" class="btn btn-success btn-lg">
                                            <i class="fas fa-plus"></i>
                                            Tambah Data
                                        </a>
                                    </div>
                                    <div class="col-md-3 d-grid gap-2 mt-2">
                                        <a href="{{ route('overtimes.edit_overtime') }}" class="btn btn-warning btn-lg">
                                            <i class="fas fa-sync-alt"></i>
                                            Edit Data
                                        </a>
                                    </div>
                                    <div class="col-md-3 d-grid gap-2 mt-2">
                                        <a href="{{ route('overtimes.form_hapus_overtime') }}"
                                            class="btn btn-danger btn-lg">
                                            <i class="fas fa-trash-alt"></i>
                                            Hapus Data
                                        </a>
                                    </div>
                                </div>
                            </div>
                        @else
                        @endif

                    </div>
                </div>
            </div>
        </main>
        {{-- End Content --}}
    </div>
    {{-- End Content Dan Footer --}}
@endsection
