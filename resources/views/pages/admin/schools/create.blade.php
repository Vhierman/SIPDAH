@extends('layouts.admin')

@section('content')
    {{-- Content Dan Footer --}}
    <div id="layoutSidenav_content">
        {{-- Content --}}
        <main>
            <div class="container-fluid mt-4">
                <ol class="breadcrumb mb-4">
                    <li class="breadcrumb-item">Dashboard</li>
                    <li class="breadcrumb-item">Master</li>
                    <li class="breadcrumb-item active">Tambah Sekolah</li>
                </ol>

                <div class="card mb-4">
                    <div class="card-header">
                        <i class="fas fa-table mr-1"></i>
                        Tambah Data Sekolah
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
                            <form action="{{ route('schools.store') }}" method="post" enctype="multipart/form-data">
                                @csrf
                                <div class="form-group">

                                    <input type="hidden" class="form-control" name="input_oleh" placeholder="Name"
                                        value="{{ Auth::user()->name }}">

                                    <div class="form-group mt-2">
                                        <label for="title" class="form-label">Nama Sekolah</label>
                                        <input type="text" class="form-control" name="nama_sekolah"
                                            placeholder="Masukan Nama Sekolah" value="{{ old('nama_sekolah') }}">
                                    </div>
                                    <div class="form-group mt-2">
                                        <label for="title" class="form-label">No Telepon Sekolah</label>
                                        <input type="text" class="form-control" name="no_telepon_sekolah"
                                            placeholder="Masukan Nomor Telepon Sekolah"
                                            value="{{ old('no_telepon_sekolah') }}">
                                    </div>
                                    <div class="form-group mt-2">
                                        <label for="title" class="form-label">Email Sekolah</label>
                                        <input type="text" class="form-control" name="email_sekolah"
                                            placeholder="Masukan Email Sekolah" value="{{ old('email_sekolah') }}">
                                    </div>
                                    <div class="form-group mt-2">
                                        <label for="title" class="form-label">Nama Guru Pembimbing</label>
                                        <input type="text" onkeyup="huruf(this);" class="form-control"
                                            name="nama_guru_pembimbing" placeholder="Masukan Nama Guru Pembimbing"
                                            value="{{ old('nama_guru_pembimbing') }}">
                                    </div>
                                    <div class="form-group mt-2">
                                        <label for="title" class="form-label">No Handphone Guru Pembimbing</label>
                                        <input type="text" onkeyup="angka(this);" class="form-control"
                                            name="no_handphone_guru_pembimbing"
                                            placeholder="Masukan Nomor Handphone Guru Pembimbing"
                                            value="{{ old('no_handphone_guru_pembimbing') }}">
                                    </div>
                                    <div class="form-group mt-2">
                                        <label for="title" class="form-label">Alamat Sekolah</label>
                                        <input type="text" class="form-control" name="alamat_sekolah"
                                            placeholder="Masukan Alamat" value="{{ old('alamat_sekolah') }}">
                                    </div>
                                    <div class="form-group mt-2">
                                        <label for="title" class="form-label">RT</label>
                                        <input type="text" onkeyup="angka(this);" class="form-control" maxlength="3"
                                            name="rt_sekolah" placeholder="Masukan RT" value="{{ old('rt_sekolah') }}">
                                    </div>
                                    <div class="form-group mt-2">
                                        <label for="title" class="form-label">RW</label>
                                        <input type="text" onkeyup="angka(this);" class="form-control" maxlength="3"
                                            name="rw_sekolah" placeholder="Masukan RW" value="{{ old('rw_sekolah') }}">
                                    </div>
                                    <div class="form-group mt-2">
                                        <label for="title" class="form-label">Kelurahan</label>
                                        <input type="text" class="form-control" name="kelurahan_sekolah"
                                            placeholder="Masukan Kelurahan" value="{{ old('kelurahan_sekolah') }}">
                                    </div>
                                    <div class="form-group mt-2">
                                        <label for="title" class="form-label">Kecamatan</label>
                                        <input type="text" class="form-control" name="kecamatan_sekolah"
                                            placeholder="Masukan Kecamatan" value="{{ old('kecamatan_sekolah') }}">
                                    </div>
                                    <div class="form-group mt-2">
                                        <label for="title" class="form-label">Kota</label>
                                        <input type="text" class="form-control" name="kota_sekolah"
                                            placeholder="Masukan Kota" value="{{ old('kota_sekolah') }}">
                                    </div>
                                    <div class="form-group mt-2">
                                        <label for="title" class="form-label">Provinsi</label>
                                        <input type="text" class="form-control" name="provinsi_sekolah"
                                            placeholder="Masukan Provinsi" value="{{ old('provinsi_sekolah') }}">
                                    </div>
                                    <div class="form-group mt-2">
                                        <label for="title" class="form-label">Kode POS</label>
                                        <input type="text" onkeyup="angka(this);" class="form-control"
                                            name="kode_pos_sekolah" placeholder="Masukan Kode POS" maxlength="5"
                                            value="{{ old('kode_pos_sekolah') }}">
                                    </div>


                                    <div class="d-grid gap-2 mt-3">
                                        <button type="submit" class="btn btn-primary btn-block">
                                            Simpan
                                        </button>
                                        <a href="{{ route('schools.index') }}" class="btn btn-danger btn-block">
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
