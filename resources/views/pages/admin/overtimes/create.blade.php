@extends('layouts.admin')

@section('content')
    {{-- Content Dan Footer --}}
    <div id="layoutSidenav_content">
        {{-- Content --}}
        <main>
            <div class="container-fluid mt-4">
                <ol class="breadcrumb mb-4">
                    <li class="breadcrumb-item">Dashboard</li>
                    <li class="breadcrumb-item">Inventaris</li>
                    <li class="breadcrumb-item active">Tambah Data Lembur</li>
                </ol>

                <div class="card mb-4">
                    <div class="card-header">
                        <i class="fas fa-table mr-1"></i>
                        Tambah Data Lembur
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
                            <form action="{{ route('overtimes.store') }}" method="post" enctype="multipart/form-data">
                                @csrf
                                <div class="form-group">

                                    <div class="form-group mt-2">

                                        <input type="hidden" class="form-control" name="input_oleh" placeholder="Name"
                                            value="{{ Auth::user()->name }}">

                                        <label for="title" class="form-label">Nama Karyawan</label>
                                        <select class="selectpicker" name="employees_id[]" data-width="100%"
                                            data-live-search="true" multiple required>
                                            @foreach ($items as $item)
                                                <option value="{{ $item->nik_karyawan }}">
                                                    {{ $item->nama_karyawan . ' / ' . $item->divisions->penempatan }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <div class="form-group mt-2">
                                        <label for="title" class="form-label">Tanggal Lembur</label>
                                        <input type="date" class="form-control" name="tanggal_lembur"
                                            placeholder="dd/mm/yyyy" value="{{ old('tanggal_lembur') }}">
                                    </div>
                                    <div class="form-group  mt-2">
                                        <label for="jenis_lembur">Jenis Lembur</label>
                                        <select name="jenis_lembur" class="form-select">
                                            <option value="">Pilih Jenis Lembur</option>
                                            <option value="Biasa"
                                                @if (old('jenis_lembur') == 'Biasa') {{ 'selected' }} @endif>Biasa
                                            </option>
                                            <option value="Libur"
                                                @if (old('jenis_lembur') == 'Libur') {{ 'selected' }} @endif>Libur
                                            </option>
                                        </select>
                                    </div>
                                    <div class="form-group mt-2">
                                        <label for="title" class="form-label">Keterangan Lembur</label>
                                        <input type="text" class="form-control" name="keterangan_lembur"
                                            placeholder="Masukan Keterangan Lembur"
                                            value="{{ old('keterangan_lembur') }}">
                                    </div>
                                    <div class="form-group mt-2">
                                        <label for="title" class="form-label">Jam Masuk</label>
                                        <input type="number" class="form-control" name="jam_masuk"
                                            placeholder="Masukan Jam Masuk" maxlength="2" value="{{ old('jam_masuk') }}">
                                    </div>
                                    <div class="form-group  mt-2">
                                        <label for="jam_istirahat">Jam Istirahat</label>
                                        <select name="jam_istirahat" class="form-select">
                                            <option value="">Pilih Jam Istirahat</option>
                                            <option value="0" @if (old('jam_istirahat') == '0') {{ 'selected' }} @endif>
                                                0 ( Tidak Ada Istirahat )
                                            </option>
                                            <option value="0.5"
                                                @if (old('jam_istirahat') == '0.5') {{ 'selected' }} @endif>0.5 ( Setengah
                                                Jam )
                                            </option>
                                            <option value="1" @if (old('jam_istirahat') == '1') {{ 'selected' }} @endif>
                                                1 Jam
                                            </option>
                                            <option value="1.5"
                                                @if (old('jam_istirahat') == '1.5') {{ 'selected' }} @endif>1.5 ( 1
                                                Setengah
                                                Jam )
                                            </option>
                                            <option value="2" @if (old('jam_istirahat') == '2') {{ 'selected' }} @endif>
                                                2 Jam
                                            </option>
                                        </select>
                                    </div>
                                    <div class="form-group mt-2">
                                        <label for="title" class="form-label">Jam Pulang</label>
                                        <input type="number" class="form-control" name="jam_pulang"
                                            placeholder="Masukan Jam Pulang" maxlength="2"
                                            value="{{ old('jam_pulang') }}">
                                    </div>

                                    <div class="d-grid gap-2 mt-3">
                                        <button type="submit" class="btn btn-primary btn-block">
                                            Simpan
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
