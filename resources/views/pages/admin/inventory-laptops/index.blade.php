@extends('layouts.admin')

@section('content')
    <script type="text/javascript">
        function viewdata($nama_karyawan, $merk_laptop, $type_laptop, $foto_laptop, $processor, $ram, $hardisk, $vga,
            $sistem_operasi, $tanggal_penyerahan_laptop) {
            $("#nama_karyawan").val($nama_karyawan);
            $("#merk_laptop").val($merk_laptop);
            $("#type_laptop").val($type_laptop);
            $("#processor").val($processor);
            $("#ram").val($ram);
            $("#hardisk").val($hardisk);
            $("#vga").val($vga);
            $("#sistem_operasi").val($sistem_operasi);
            $("#tanggal_penyerahan_laptop").val($tanggal_penyerahan_laptop);
            $('#foto_laptop').attr("src", "{{ Storage::url('') }}" + $foto_laptop);
        }
    </script>
    {{-- Content Dan Footer --}}
    <div id="layoutSidenav_content">
        {{-- Content --}}
        <main>
            <div class="container-fluid mt-4">
                <ol class="breadcrumb mb-4">
                    <li class="breadcrumb-item">Dashboard</li>
                    <li class="breadcrumb-item">Inventaris</li>
                    <li class="breadcrumb-item active">Laptop</li>
                </ol>

                @if (Auth::user()->roles == 'ADMIN' || Auth::user()->roles == 'HRD')
                    <a href="{{ route('inventory_laptops.create') }}" class="btn btn-primary shadow-sm mb-3">
                        <i class="fas fa-plus fa-sm text-white-50"></i> Tambah Inventaris Laptop
                    </a>
                @endif

                <div class="card mb-4">
                    <div class="card-header">
                        <i class="fas fa-table mr-1"></i>
                        Data Inventaris Laptop
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-bordered" id="datatablesSimple" width="100%" cellspacing="0">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>NIK</th>
                                        <th>Nama</th>
                                        <th>Merk/Type</th>
                                        <th>Tanggal Penyerahan</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @php
                                        $no = 1;
                                    @endphp
                                    @foreach ($items as $item)
                                        <tr>
                                            <td>{{ $no++ }}</td>
                                            <td>{{ $item->employees->nik_karyawan }}</td>
                                            <td>{{ $item->employees->nama_karyawan }}</td>
                                            <td>{{ $item->merk_laptop }}.'/'.{{ $item->type_laptop }}</td>
                                            <td>{{ \Carbon\Carbon::parse($item->tanggal_penyerahan_laptop)->isoformat('D MMMM Y') }}
                                            </td>
                                            <td>
                                                <a type="button" class="btn btn-primary" data-bs-toggle="modal"
                                                    data-bs-target="#ViewInventoryLaptop"
                                                    onclick="viewdata('{{ $item->employees->nama_karyawan }}','{{ $item->merk_laptop }}','{{ $item->type_laptop }}','{{ $item->foto_laptop }}','{{ $item->processor }}','{{ $item->ram }}','{{ $item->hardisk }}','{{ $item->vga }}','{{ $item->sistem_operasi }}','{{ \Carbon\Carbon::parse($item->tanggal_penyerahan_laptop)->isoformat('D MMMM Y') }}')">
                                                    <i class="fa fa-eye"></i>
                                                </a>
                                                @if (Auth::user()->roles == 'ADMIN' || Auth::user()->roles == 'HRD')
                                                    <a href="{{ route('inventory_laptops.edit', $item->id) }}"
                                                        class="btn btn-success">
                                                        <i class="fa fa-pencil-alt"></i>
                                                    </a>
                                                    <form action="{{ route('inventory_laptops.destroy', $item->id) }}"
                                                        method="POST" class="d-inline">
                                                        @csrf
                                                        @method('delete')
                                                        <button class="btn btn-danger ">
                                                            <i class="fa fa-trash"></i>
                                                        </button>
                                                    </form>
                                                    <a href="{{ route('inventory_laptops.show', $item->id) }}"
                                                        class="btn btn-info" target="_blank">
                                                        <i class="fa fa-download"></i>
                                                    </a>
                                                @endif
                                            </td>
                                        </tr>
                                    @endforeach

                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </main>
        {{-- End Content --}}

        {{-- Modal --}}
        <div class="modal fade" id="ViewInventoryLaptop" tabindex="-1" aria-labelledby="exampleModalLabel"
            aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">Inventaris Laptop </h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>

                    <div class="modal-body">
                        <div class="text-center">
                            <img id="foto_laptop" class="img-fluid" width="40%">
                        </div>

                        <div class="mb-3 row">
                            <label for="staticEmail" class="col-sm-4 col-form-label"><b>Nama</b></label>
                            <div class="col-sm-8">
                                <input type="text" readonly class="form-control-plaintext" id="nama_karyawan"
                                    value="email@example.com">
                            </div>
                        </div>
                        <div class="mb-3 row">
                            <label for="staticEmail" class="col-sm-4 col-form-label"><b>Merk</b></label>
                            <div class="col-sm-8">
                                <input type="text" readonly class="form-control-plaintext" id="merk_laptop"
                                    value="email@example.com">
                            </div>
                        </div>
                        <div class="mb-3 row">
                            <label for="staticEmail" class="col-sm-4 col-form-label"><b>Type</b></label>
                            <div class="col-sm-8">
                                <input type="text" readonly class="form-control-plaintext" id="type_laptop"
                                    value="email@example.com">
                            </div>
                        </div>
                        <div class="mb-3 row">
                            <label for="staticEmail" class="col-sm-4 col-form-label"><b>Processor</b></label>
                            <div class="col-sm-8">
                                <input type="text" readonly class="form-control-plaintext" id="processor"
                                    value="email@example.com">
                            </div>
                        </div>
                        <div class="mb-3 row">
                            <label for="staticEmail" class="col-sm-4 col-form-label"><b>RAM</b></label>
                            <div class="col-sm-8">
                                <input type="text" readonly class="form-control-plaintext" id="ram"
                                    value="email@example.com">
                            </div>
                        </div>
                        <div class="mb-3 row">
                            <label for="staticEmail" class="col-sm-4 col-form-label"><b>Storage</b></label>
                            <div class="col-sm-8">
                                <input type="text" readonly class="form-control-plaintext" id="hardisk"
                                    value="email@example.com">
                            </div>
                        </div>
                        <div class="mb-3 row">
                            <label for="staticEmail" class="col-sm-4 col-form-label"><b>VGA</b></label>
                            <div class="col-sm-8">
                                <input type="text" readonly class="form-control-plaintext" id="vga"
                                    value="email@example.com">
                            </div>
                        </div>
                        <div class="mb-3 row">
                            <label for="staticEmail" class="col-sm-4 col-form-label"><b>Sistem Operasi</b></label>
                            <div class="col-sm-8">
                                <input type="text" readonly class="form-control-plaintext" id="sistem_operasi"
                                    value="email@example.com">
                            </div>
                        </div>
                        <div class="mb-3 row">
                            <label for="staticEmail" class="col-sm-4 col-form-label"><b>Tgl Penyerahan</b></label>
                            <div class="col-sm-8">
                                <input type="text" readonly class="form-control-plaintext" id="tanggal_penyerahan_laptop"
                                    value="email@example.com">
                            </div>
                        </div>
                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    </div>
                </div>
            </div>
        </div>
        {{-- End Modal --}}


    </div>
    {{-- End Content Dan Footer --}}
@endsection
