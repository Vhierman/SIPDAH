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
                    <li class="breadcrumb-item active">Edit History Jabatan</li>
                </ol>

                <div class="card mb-4">
                    <div class="card-header">
                        <i class="fas fa-table mr-1"></i>
                        Edit Data History Jabatan {{ $item->nama_karyawan }}
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
                            <form action="{{ route('history_positions.update', $historypositions->id) }}" method="post"
                                enctype="multipart/form-data">
                                @method('PUT')
                                @csrf
                                <div class="form-group">
                                    <input type="hidden" class="form-control" name="edit_oleh" placeholder="Name"
                                        value="{{ Auth::user()->name }}">

                                    <div class="form-group mt-2">
                                        <label for="title" class="form-label">NIK Karyawan</label>
                                        <input type="text" class="form-control" name="employees_id"
                                            placeholder="Masukan NIK Karyawan" value="{{ $item->nik_karyawan }}" readonly>
                                    </div>

                                    <div class="form-group mt-2">
                                        <label for="title" class="form-label">Nama Karyawan</label>
                                        <input type="text" class="form-control" name="nama_karyawan"
                                            placeholder="Masukan Nama Karyawan" value="{{ $item->nama_karyawan }}"
                                            readonly>
                                    </div>

                                    <div class="form-group mt-2">
                                        <label for="companies_id_history">Perusahaan</label>
                                        <select name="companies_id_history" class="form-select">
                                            <option value="{{ $historypositions->companies_id_history }}">Pilih Perusahaan
                                            </option>
                                            @foreach ($companies as $company)
                                                <option value="{{ $company->id }}"
                                                    @if ($historypositions->companies_id_history == $company->id) {{ 'selected="selected"' }} @endif>
                                                    {{ $company->nama_perusahaan }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <div class="form-group mt-2">
                                        <label for="areas_id_history">Area</label>
                                        <select name="areas_id_history" class="form-select">
                                            <option value="{{ $historypositions->areas_id_history }}">Pilih Area</option>
                                            @foreach ($areas as $area)
                                                <option value="{{ $area->id }}"
                                                    @if ($historypositions->areas_id_history == $area->id) {{ 'selected="selected"' }} @endif>
                                                    {{ $area->area }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <div class="form-group mt-2">
                                        <label for="divisions_id_history">Penempatan</label>
                                        <select name="divisions_id_history" class="form-select">
                                            <option value="{{ $historypositions->divisions_id_history }}">Pilih
                                                Penempatan
                                            </option>
                                            @foreach ($divisions as $division)
                                                <option value="{{ $division->id }}"
                                                    @if ($historypositions->divisions_id_history == $division->id) {{ 'selected="selected"' }} @endif>
                                                    {{ $division->penempatan }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <div class="form-group mt-2">
                                        <label for="positions_id_history">Jabatan</label>
                                        <select name="positions_id_history" class="form-select">
                                            <option value="{{ $historypositions->positions_id_history }}">Pilih Area
                                            </option>
                                            @foreach ($positions as $position)
                                                <option value="{{ $position->id }}"
                                                    @if ($historypositions->positions_id_history == $position->id) {{ 'selected="selected"' }} @endif>
                                                    {{ $position->jabatan }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <div class="form-group mt-2">
                                        <label for="title" class="form-label">Tanggal Mutasi</label>
                                        <input type="date" class="form-control" name="tanggal_mutasi"
                                            placeholder="Masukan Tanggal Mutasi"
                                            value="{{ $historypositions->tanggal_mutasi }}">
                                    </div>

                                    <div class="form-group mt-2">
                                        <label for="title" class="form-label">Surat Mutasi <font color="red">*Hanya File
                                                JPEG,JPG,PNG
                                                (Maksimal 500kb)</font>
                                        </label>
                                        <label for="title" class="form-label">
                                            <font color="red">*Jika File Tidak Diubah Maka Kosongkan Saja</font>
                                        </label>
                                        <input type="file" class="form-control" name="file_surat_mutasi"
                                            value="{{ $historypositions->file_surat_mutasi }}">
                                        <input type="hidden" class="form-control" name="filesuratmutasi"
                                            value="{{ $historypositions->file_surat_mutasi }}">
                                    </div>

                                    <div class="d-grid gap-2 mt-3">
                                        <button type="submit" class="btn btn-primary btn-block">
                                            Update
                                        </button>
                                        <a href="{{ route('employees.show', $item->id) }}"
                                            class="btn btn-danger btn-block">
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
