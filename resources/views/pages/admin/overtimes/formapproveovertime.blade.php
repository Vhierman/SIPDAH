@extends('layouts.admin')

@section('content')
    {{-- Content Dan Footer --}}
    <div id="layoutSidenav_content">
        {{-- Content --}}
        <main>

            <div class="container-fluid mt-4">
                <ol class="breadcrumb mb-4">
                    <li class="breadcrumb-item">Dashboard</li>
                    <li class="breadcrumb-item">Prosess</li>
                    <li class="breadcrumb-item active">Overtimes</li>
                </ol>

                <div class="card mb-4">
                    <div class="card-header">
                        <i class="fas fa-table mr-1"></i>
                        Data Overtimes
                    </div>

                    @if ($errors->any())
                        <div class="alert alert-danger">
                            <ul>
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <div class="card shadow">
                        <div class="card-body">
                            <form action="{{ route('overtimes.tampil_approve_overtime') }}" method="post"
                                enctype="multipart/form-data">
                                @csrf
                                <div class="form-group">

                                    <div class="form-group mt-2">
                                        <label for="title" class="form-label">Dari Tanggal</label>
                                        <input type="date" class="form-control" name="awal" placeholder="DD-MM-YYYY"
                                            value="{{ old('awal') }}">
                                    </div>
                                    <div class="form-group mt-2">
                                        <label for="title" class="form-label">Sampai Tanggal</label>
                                        <input type="date" class="form-control" name="akhir" placeholder="DD-MM-YYYY"
                                            value="{{ old('akhir') }}">
                                    </div>
                                    <div class="d-grid gap-2 mt-3">
                                        <button type="submit" class="btn btn-primary btn-block">
                                            Lihat Data
                                        </button>
                                        <a href="{{ route('overtimes.index') }}" class="btn btn-danger btn-block">
                                            Cancel
                                        </a>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </main>
        {{-- End Content --}}
    </div>
    {{-- End Content Dan Footer --}}
@endsection
