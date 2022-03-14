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

                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-2 d-grid gap-2 mt-2">
                                    <a href="#" class="btn btn-primary btn-lg">
                                        Lihat Data
                                    </a>
                                </div>
                                <div class="col-md-2 d-grid gap-2 mt-2">
                                    <a href="#" class="btn btn-primary btn-lg">Large button</a>
                                </div>
                                <div class="col-md-2 d-grid gap-2 mt-2">
                                    <a href="#" class="btn btn-primary btn-lg">Large button</a>
                                </div>
                                <div class="col-md-2 d-grid gap-2 mt-2">
                                    <a href="#" class="btn btn-primary btn-lg">Large button</a>
                                </div>
                                <div class="col-md-2 d-grid gap-2 mt-2">
                                    <a href="#" class="btn btn-primary btn-lg">Large button</a>
                                </div>
                                <div class="col-md-2 d-grid gap-2 mt-2">
                                    <a href="#" class="btn btn-primary btn-lg">Large button</a>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </main>
        {{-- End Content --}}
    </div>
    {{-- End Content Dan Footer --}}
@endsection
