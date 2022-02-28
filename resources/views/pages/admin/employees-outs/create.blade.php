@extends('layouts.admin')

@section('content')
    {{-- Content Dan Footer --}}
    <div id="layoutSidenav_content">
        {{-- Content --}}
        <main>
            <div class="container-fluid mt-4">
                <ol class="breadcrumb mb-4">
                    <li class="breadcrumb-item">Dashboard</li>
                    <li class="breadcrumb-item">Karyawan</li>
                    <li class="breadcrumb-item active">Tambah Karyawan Keluar</li>
                </ol>

                <div class="card mb-4">
                    <div class="card-header">
                        <i class="fas fa-table mr-1"></i>
                        Tambah Data Karyawan Keluar
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
                            <form action="{{ route('employees_outs.store') }}" method="post"
                                enctype="multipart/form-data">
                                @csrf
                                <div class="form-group">
                                    <input type="hidden" class="form-control" name="input_oleh" placeholder="Name"
                                        value="{{ Auth::user()->name }}">

                                    <div class="form-group mt-2">
                                        <label for="title" class="form-label">Nama Karyawan</label>
                                        <select class="selectpicker" name="employees_id" data-width="100%"
                                            data-live-search="true" required>
                                            <option value="">Pilih Nama Karyawan</option>
                                            @foreach ($items as $item)
                                                <option value="{{ $item->nik_karyawan }}">
                                                    {{ $item->nama_karyawan . ' / ' . $item->divisions->penempatan }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <div class="form-group  mt-2">
                                        <label for="keterangan_keluar">Keterangan Keluar</label>
                                        <select name="keterangan_keluar" class="form-select">
                                            <option value="">Pilih Keterangan Keluar</option>
                                            <option value="Pengunduran Diri"
                                                @if (old('keterangan_keluar') == 'Pengunduran Diri') {{ 'selected' }} @endif>Pengunduran
                                                Diri</option>
                                            <option value="Berakhir Kontrak Kerja"
                                                @if (old('keterangan_keluar') == 'Berakhir Kontrak Kerja') {{ 'selected' }} @endif>Berakhir
                                                Kontrak Kerja</option>
                                            <option value="Pemutusan Hubungan Kerja"
                                                @if (old('keterangan_keluar') == 'Pemutusan Hubungan Kerja') {{ 'selected' }} @endif>Pemutusan
                                                Hubungan Kerja</option>
                                            <option value="Meninggal Dunia"
                                                @if (old('keterangan_keluar') == 'Meninggal Dunia') {{ 'selected' }} @endif>Meninggal Dunia
                                            </option>
                                        </select>
                                    </div>

                                    <div class="form-group mt-2">
                                        <label for="title" class="form-label">Tanggal Keluar</label>
                                        <input type="date" class="form-control" name="tanggal_keluar_karyawan_keluar"
                                            placeholder="DD-MM-YYYY" value="{{ old('tanggal_keluar_karyawan_keluar') }}">
                                    </div>

                                    <div class="d-grid gap-2 mt-3">
                                        <button type="submit" class="btn btn-primary btn-block">
                                            Simpan
                                        </button>
                                        <a href="{{ route('employees_outs.index') }}" class="btn btn-danger btn-block">
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
