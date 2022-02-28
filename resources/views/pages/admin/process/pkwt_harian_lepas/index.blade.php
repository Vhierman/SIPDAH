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
                    <li class="breadcrumb-item active">PKWT Harian Lepas</li>
                </ol>

                <div class="card mb-4">
                    <div class="card-header">
                        <i class="fas fa-table mr-1"></i>
                        PKWT Harian Lepas
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
                            <form action="{{ route('process.hasil_cetak_pkwt_magang') }}" target="_blank" method="post"
                                enctype="multipart/form-data">
                                @csrf
                                <div class="form-group">

                                    <div class="form-group mt-2">
                                        <label for="title" class="form-label">NIK KTP</label>
                                        <input type="text" class="form-control" name="nik_magang"
                                            placeholder="Masukan NIK KTP" onkeyup="angka(this);" maxlength="16"
                                            value="{{ old('nik_magang') }}">
                                    </div>

                                    <div class="form-group mt-2">
                                        <label for="title" class="form-label">Nama Lengkap</label>
                                        <input type="text" class="form-control" name="nama_magang"
                                            placeholder="Masukan Nama Sesuai KTP" onkeyup="huruf(this);" maxlength="50"
                                            value="{{ old('nama_magang') }}">
                                    </div>

                                    <div class="form-group mt-2">
                                        <label for="title" class="form-label">Jabatan</label>
                                        <input type="text" class="form-control" name="jabatan_magang"
                                            placeholder="Masukan Jabatan" maxlength="30"
                                            value="{{ old('jabatan_magang') }}">
                                    </div>

                                    <div class="form-group mt-2">
                                        <label for="title" class="form-label">Penempatan</label>
                                        <input type="text" class="form-control" name="penempatan_magang"
                                            placeholder="Masukan Penempatan" maxlength="30"
                                            value="{{ old('penempatan_magang') }}">
                                    </div>

                                    <div class="form-group mt-2">
                                        <label for="title" class="form-label">Tempat Lahir</label>
                                        <input type="text" class="form-control" name="tempat_lahir_magang"
                                            placeholder="Masukan Tempat Lahir" maxlength="30"
                                            value="{{ old('tempat_lahir_magang') }}">
                                    </div>

                                    <div class="form-group mt-2">
                                        <label for="title" class="form-label">Tanggal Lahir</label>
                                        <input type="date" class="form-control" name="tanggal_lahir_magang"
                                            placeholder="DD-MM-YYYY" value="{{ old('tanggal_lahir_magang') }}">
                                    </div>

                                    <div class="form-group  mt-2">
                                        <label for="pendidikan_terakhir_magang">Pendidikan Terakhir</label>
                                        <select name="pendidikan_terakhir_magang" class="form-select">
                                            <option value="">Pilih Pendidikan Terakhir</option>
                                            <option value="SD" @if (old('pendidikan_terakhir_magang') == 'SD') {{ 'selected' }} @endif>
                                                SD
                                            </option>
                                            <option value="SMP"
                                                @if (old('pendidikan_terakhir_magang') == 'SMP') {{ 'selected' }} @endif>SMP
                                            </option>
                                            <option value="SMA/SMK"
                                                @if (old('pendidikan_terakhir_magang') == 'SMA/SMK') {{ 'selected' }} @endif>SMA/SMK
                                            </option>
                                            <option value="D1"
                                                @if (old('pendidikan_terakhir_magang') == 'D1') {{ 'selected' }} @endif>D1
                                            </option>
                                            <option value="D2"
                                                @if (old('pendidikan_terakhir_magang') == 'D2') {{ 'selected' }} @endif>D2
                                            </option>
                                            <option value="D3"
                                                @if (old('pendidikan_terakhir_magang') == 'D3') {{ 'selected' }} @endif>D3
                                            </option>
                                            <option value="S1"
                                                @if (old('pendidikan_terakhir_magang') == 'S1') {{ 'selected' }} @endif>S1
                                            </option>
                                            <option value="S2"
                                                @if (old('pendidikan_terakhir_magang') == 'S2') {{ 'selected' }} @endif>S2
                                            </option>
                                            <option value="S3"
                                                @if (old('pendidikan_terakhir_magang') == 'S3') {{ 'selected' }} @endif>S3
                                            </option>
                                        </select>
                                    </div>

                                    <div class="form-group  mt-2">
                                        <label for="jenis_kelamin_magang">Jenis Kelamin</label>
                                        <select name="jenis_kelamin_magang" class="form-select">
                                            <option value="">Pilih Jenis Kelamin</option>
                                            <option value="Pria"
                                                @if (old('jenis_kelamin') == 'Pria') {{ 'selected' }} @endif>Pria
                                            </option>
                                            <option value="Wanita"
                                                @if (old('jenis_kelamin') == 'Wanita') {{ 'selected' }} @endif>Wanita
                                            </option>
                                        </select>
                                    </div>

                                    <div class="form-group  mt-2">
                                        <label for="agama_magang">Agama</label>
                                        <select name="agama_magang" class="form-select">
                                            <option value="">Pilih Agama</option>
                                            <option value="Islam"
                                                @if (old('agama_magang') == 'Islam') {{ 'selected' }} @endif>Islam
                                            </option>
                                            <option value="Kristen Protestan"
                                                @if (old('agama_magang') == 'Kristen Protestan') {{ 'selected' }} @endif>Kristen
                                                Protestan
                                            </option>
                                            <option value="Kristen Katholik"
                                                @if (old('agama_magang') == 'Kristen Katholik') {{ 'selected' }} @endif>Kristen
                                                Katholik
                                            </option>
                                            <option value="Hindu"
                                                @if (old('agama_magang') == 'Hindu') {{ 'selected' }} @endif>Hindu
                                            </option>
                                            <option value="Budha"
                                                @if (old('agama_magang') == 'Budha') {{ 'selected' }} @endif>Budha
                                            </option>
                                            <option value="Konghucu"
                                                @if (old('agama_magang') == 'Konghucu') {{ 'selected' }} @endif>Konghucu
                                            </option>

                                        </select>
                                    </div>

                                    <div class="form-group mt-2">
                                        <label for="title" class="form-label">Alamat</label>
                                        <input type="text" class="form-control" name="alamat_magang"
                                            placeholder="Masukan Alamat" value="{{ old('alamat_magang') }}">
                                    </div>
                                    <div class="form-group mt-2">
                                        <label for="title" class="form-label">RT</label>
                                        <input type="text" class="form-control" name="rt_magang" placeholder="Masukan RT"
                                            onkeyup="angka(this);" maxlength="3" value="{{ old('rt_magang') }}">
                                    </div>
                                    <div class="form-group mt-2">
                                        <label for="title" class="form-label">RW</label>
                                        <input type="text" class="form-control" name="rw_magang" placeholder="Masukan RW"
                                            onkeyup="angka(this);" maxlength="3" value="{{ old('rw_magang') }}">
                                    </div>
                                    <div class="form-group mt-2">
                                        <label for="title" class="form-label">Kelurahan</label>
                                        <input type="text" class="form-control" name="kelurahan_magang"
                                            placeholder="Masukan Kelurahan" value="{{ old('kelurahan_magang') }}">
                                    </div>
                                    <div class="form-group mt-2">
                                        <label for="title" class="form-label">Kecamatan</label>
                                        <input type="text" class="form-control" name="kecamatan_magang"
                                            placeholder="Masukan Kecamatan" value="{{ old('kecamatan_magang') }}">
                                    </div>
                                    <div class="form-group mt-2">
                                        <label for="title" class="form-label">Kabupaten/Kota</label>
                                        <input type="text" class="form-control" name="kota_magang"
                                            placeholder="Masukan Kabupaten/Kota" value="{{ old('kota_magang') }}">
                                    </div>
                                    <div class="form-group mt-2">
                                        <label for="title" class="form-label">Provinsi</label>
                                        <input type="text" class="form-control" name="provinsi_magang"
                                            placeholder="Masukan Provinsi" value="{{ old('provinsi_magang') }}">
                                    </div>

                                    <div class="form-group mt-2">
                                        <label for="title" class="form-label">Cetak Surat Magang</label>
                                        <input type="date" class="form-control" name="cetak_surat_magang"
                                            placeholder="DD-MM-YYYY" value="{{ old('cetak_surat_magang') }}">
                                    </div>
                                    <div class="form-group mt-2">
                                        <label for="title" class="form-label">Akhir Magang</label>
                                        <input type="date" class="form-control" name="akhir_magang"
                                            placeholder="DD-MM-YYYY" value="{{ old('akhir_magang') }}">
                                    </div>

                                    <div class="d-grid gap-2 mt-3">
                                        <button type="submit" class="btn btn-primary btn-block">
                                            Cetak
                                        </button>
                                        <a href="{{ route('process.process_magang') }}" class="btn btn-danger btn-block">
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
