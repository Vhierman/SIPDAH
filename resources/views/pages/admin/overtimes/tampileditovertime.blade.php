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
                        Edit Overtimes
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
                            <form action="{{ route('overtimes.update', $items->id) }}" method="post"
                                enctype="multipart/form-data">
                                @method('PUT')
                                @csrf
                                <div class="form-group">
                                    <input type="hidden" readonly class="form-control" name="edit_oleh" placeholder="Name"
                                        value="{{ Auth::user()->name }}">

                                    <div class="form-group mt-2">
                                        <label for="title" class="form-label">NIK Karyawan</label>
                                        <input type="text" class="form-control" name="employees_id" readonly
                                            value="{{ $items->employees->nik_karyawan }}">
                                    </div>
                                    <div class="form-group mt-2">
                                        <label for="title" class="form-label">Nama Karyawan</label>
                                        <input type="text" class="form-control" name="nama_karyawan" readonly
                                            value="{{ $items->employees->nama_karyawan }}">
                                    </div>
                                    <div class="form-group mt-2">
                                        <label for="title" class="form-label">Tanggal Lembur</label>
                                        <input type="date" class="form-control" name="tanggal_lembur" readonly
                                            value="{{ $items->tanggal_lembur }}">
                                    </div>
                                    <div class="form-group  mt-2">
                                        <label for="jenis_lembur">Jenis Lembur</label>
                                        <select name="jenis_lembur" class="form-select">
                                            <option value="">Pilih Jenis Lembur</option>
                                            <option value="Biasa"
                                                @if ($items->jenis_lembur == 'Biasa') {{ 'selected="selected"' }} @endif>
                                                Biasa</option>
                                            <option value="Libur"
                                                @if ($items->jenis_lembur == 'Libur') {{ 'selected="selected"' }} @endif>
                                                Libur</option>
                                        </select>
                                    </div>
                                    <div class="form-group  mt-2">
                                        <label for="uang_makan_lembur">Uang Makan Lembur</label>
                                        <select name="uang_makan_lembur" class="form-select">
                                            <option value="">Dapat Uang Makan Lembur ?</option>
                                            <option value="12500"
                                                @if (old('uang_makan_lembur') == '12500') {{ 'selected' }} @endif>Dapat
                                            </option>
                                            <option value="0" @if (old('uang_makan_lembur') == '0') {{ 'selected' }} @endif>
                                                Tidak
                                            </option>
                                        </select>
                                    </div>
                                    <div class="form-group mt-2">
                                        <label for="title" class="form-label">Keterangan Lembur</label>
                                        <input type="text" class="form-control" name="keterangan_lembur"
                                            placeholder="Masukan Keterangan Lembur"
                                            value="{{ $items->keterangan_lembur }}">
                                    </div>
                                    <div class="form-group mt-2">
                                        <label for="title" class="form-label">Jam Masuk</label>
                                        <input type="number" class="form-control" name="jam_masuk"
                                            placeholder="Masukan Jam Masuk" maxlength="2"
                                            value="{{ $items->jam_masuk }}">
                                    </div>
                                    <div class="form-group  mt-2">
                                        <label for="jam_istirahat">Jam Istirahat</label>
                                        <select name="jam_istirahat" class="form-select">
                                            <option value="">Pilih Jam Istirahat</option>
                                            <option value="0"
                                                @if ($items->jam_istirahat == '0') {{ 'selected="selected"' }} @endif>
                                                0 ( Tidak Ada Istirahat )</option>
                                            <option value="0.5"
                                                @if ($items->jam_istirahat == '0.5') {{ 'selected="selected"' }} @endif>
                                                0.5 ( Setengah
                                                Jam )</option>
                                            <option value="1"
                                                @if ($items->jam_istirahat == '1') {{ 'selected="selected"' }} @endif>
                                                1 Jam</option>
                                            <option value="1.5"
                                                @if ($items->jam_istirahat == '1.5') {{ 'selected="selected"' }} @endif>
                                                1.5 ( 1
                                                Setengah
                                                Jam )</option>
                                            <option value="2"
                                                @if ($items->jam_istirahat == '2') {{ 'selected="selected"' }} @endif>
                                                2 Jam</option>
                                        </select>
                                    </div>
                                    <div class="form-group mt-2">
                                        <label for="title" class="form-label">Jam Pulang</label>
                                        <input type="number" class="form-control" name="jam_pulang"
                                            placeholder="Masukan Jam Pulang" maxlength="2"
                                            value="{{ $items->jam_pulang }}">
                                    </div>

                                    <div class="d-grid gap-2 mt-3">
                                        <button type="submit" class="btn btn-primary btn-block">
                                            Update Data
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
