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
                    <li class="breadcrumb-item active">Edit Inventaris Laptop</li>
                </ol>

                <div class="card mb-4">
                    <div class="card-header">
                        <i class="fas fa-table mr-1"></i>
                        Edit Data Inventaris Laptop
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
                            <form action="{{ route('inventory_laptops.update', $iteminventory->id) }}" method="post"
                                enctype="multipart/form-data">
                                @method('PUT')
                                @csrf
                                <div class="form-group">
                                    <input type="hidden" class="form-control" name="edit_oleh" placeholder="Name"
                                        value="{{ Auth::user()->name }}">

                                    <div class="form-group mt-2">
                                        <label for="title" class="form-label">Nama Karyawan</label>
                                        <select class="selectpicker" name="employees_id" data-width="100%"
                                            data-live-search="true" required>
                                            <option value="{{ $iteminventory->employees_id }}">Pilih Nama Karyawan
                                            </option>
                                            @foreach ($items as $item)
                                                <option value="{{ $item->nik_karyawan }}"
                                                    @if ($iteminventory->employees_id == $item->nik_karyawan) {{ 'selected="selected"' }} @endif>
                                                    {{ $item->nama_karyawan . ' / ' . $item->divisions->penempatan }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <div class="form-group mt-2">
                                        <label for="title" class="form-label">Merk Laptop</label>
                                        <input type="text" class="form-control" name="merk_laptop"
                                            placeholder="Merk Laptop" maxlength="20"
                                            value="{{ $iteminventory->merk_laptop }}">
                                    </div>

                                    <div class="form-group mt-2">
                                        <label for="title" class="form-label">Type Laptop</label>
                                        <input type="text" class="form-control" name="type_laptop"
                                            placeholder="Type Laptop" maxlength="20"
                                            value="{{ $iteminventory->type_laptop }}">
                                    </div>

                                    <div class="form-group mt-2">
                                        <label for="title" class="form-label">Processor</label>
                                        <input type="text" class="form-control" name="processor" placeholder="Processor"
                                            maxlength="20" value="{{ $iteminventory->processor }}">
                                    </div>

                                    <div class="form-group mt-2">
                                        <label for="title" class="form-label">RAM</label>
                                        <input type="text" class="form-control" name="ram" placeholder="RAM"
                                            maxlength="20" value="{{ $iteminventory->ram }}">
                                    </div>

                                    <div class="form-group mt-2">
                                        <label for="title" class="form-label">Kapasitas Penyimpanan</label>
                                        <input type="text" class="form-control" name="hardisk"
                                            placeholder="Kapasitas Hardisk / SSD" maxlength="20"
                                            value="{{ $iteminventory->hardisk }}">
                                    </div>

                                    <div class="form-group mt-2">
                                        <label for="title" class="form-label">VGA</label>
                                        <input type="text" class="form-control" name="vga" placeholder="VGA"
                                            maxlength="20" value="{{ $iteminventory->vga }}">
                                    </div>

                                    <div class="form-group mt-2">
                                        <label for="title" class="form-label">Sistem Operasi</label>
                                        <input type="text" class="form-control" name="sistem_operasi"
                                            placeholder="Sistem Operasi" maxlength="20"
                                            value="{{ $iteminventory->sistem_operasi }}">
                                    </div>

                                    <div class="form-group mt-2">
                                        <label for="title" class="form-label">Tanggal Penyerahan Laptop</label>
                                        <input type="date" class="form-control" name="tanggal_penyerahan_laptop"
                                            placeholder="DD-MM-YYYY"
                                            value="{{ $iteminventory->tanggal_penyerahan_laptop }}">
                                    </div>

                                    <div class="form-group mt-2">
                                        <label for="title" class="form-label">Foto Laptop <font color="red">*Hanya File
                                                JPEG,JPG,PNG
                                                (Maksimal 500kb)</font>
                                        </label>
                                        <input type="file" class="form-control" name="foto_laptop"
                                            value="{{ $iteminventory->foto_laptop }}">
                                    </div>

                                    <div class="d-grid gap-2 mt-3">
                                        <button type="submit" class="btn btn-primary btn-block">
                                            Update
                                        </button>
                                        <a href="{{ route('inventory_laptops.index') }}"
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
