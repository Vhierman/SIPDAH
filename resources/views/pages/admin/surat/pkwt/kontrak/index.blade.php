@extends('layouts.admin')

@section('content')
    {{-- Content Dan Footer --}}
    <div id="layoutSidenav_content">
        {{-- Content --}}
        <main>
            <div class="container-fluid mt-4">
                <ol class="breadcrumb mb-4">
                    <li class="breadcrumb-item">Dashboard</li>
                    <li class="breadcrumb-item">Surat</li>
                    <li class="breadcrumb-item active">PKWT Kontrak</li>
                </ol>

                <div class="card mb-4">
                    <div class="card-header">
                        <i class="fas fa-table mr-1"></i>
                        PKWT Kontrak
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
                            <form action="{{ route('cetak.tampil_pkwt_kontrak') }}" method="post"
                                enctype="multipart/form-data" target="_blank">
                                @csrf
                                <div class="form-group">

                                    <label for="title" class="form-label">Awal Kontrak</label>
                                    <input type="date" class="form-control" name="awal_kontrak" placeholder="DD-MM-YYYY"
                                        value="{{ old('awal_kontrak') }}">

                                    <label for="title" class="form-label">Akhir Kontrak</label>
                                    <input type="date" class="form-control" name="akhir_kontrak" placeholder="DD-MM-YYYY"
                                        value="{{ old('akhir_kontrak') }}">


                                    <div class="d-grid gap-2 mt-3">
                                        <button type="submit" class="btn btn-primary btn-block">
                                            Cetak
                                        </button>
                                        <a href="{{ route('cetak.pkwt_kontrak') }}" class="btn btn-danger btn-block">
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
