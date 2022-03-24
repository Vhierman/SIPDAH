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
                            <form action="{{ route('overtimes.tampilhapus_overtime') }}" method="post"
                                enctype="multipart/form-data">
                                @csrf
                                <div class="form-group">

                                    <label for="title" class="form-label">Nama Karyawan</label>
                                    <select class="selectpicker" name="employees_id" data-width="100%"
                                        data-live-search="true" required>
                                        <option value="">Pilih Nama Karyawan</option>
                                        @foreach ($items as $item)
                                            <option value="{{ $item->nik_karyawan }}">
                                                {{ $item->nama_karyawan . ' / ' . $item->penempatan . ' / ' . $item->jabatan }}
                                            </option>
                                        @endforeach
                                    </select>

                                    <div class="form-group mt-2">
                                        <label for="title" class="form-label">Tanggal Lembur</label>
                                        <input type="date" class="form-control" name="tanggal_lembur"
                                            placeholder="DD-MM-YYYY" value="{{ old('tanggal_lembur') }}">
                                    </div>

                                    <div class="d-grid gap-2 mt-3">
                                        <button type="submit" class="btn btn-primary btn-block">
                                            Cari Data
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
