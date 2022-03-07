{{-- Side Bar Menu --}}
<div id="layoutSidenav_nav">
    <nav class="sb-sidenav accordion sb-sidenav-dark" id="sidenavAccordion">
        <div class="sb-sidenav-menu">
            <div class="nav">
                <a class="nav-link" href="{{ route('dashboard') }}">
                    <div class="sb-nav-link-icon"><i class="fas fa-tachometer-alt"></i></div>
                    Dashboard
                </a>

                {{-- Website --}}
                @if (Auth::user()->roles == 'ADMIN')
                    <a class="nav-link collapsed" data-bs-toggle="collapse" data-bs-target="#collapseWebsite"
                        aria-expanded="false" aria-controls="collapseWebsite">
                        <div class="sb-nav-link-icon"><i class="fas fa-globe"></i></div>
                        Website
                        <div class="sb-sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
                    </a>
                    <div class="collapse" id="collapseWebsite" aria-labelledby="headingOne"
                        data-bs-parent="#sidenavAccordion">
                        <nav class="sb-sidenav-menu-nested nav">
                            <a class="nav-link" href="{{ route('comingsoon.index') }}">Company Overview</a>
                            <a class="nav-link" href="{{ route('comingsoon.index') }}">Visi Misi</a>
                            <a class="nav-link" href="{{ route('comingsoon.index') }}">GROWTH</a>
                        </nav>
                    </div>
                @endif
                {{-- End Website --}}

                {{-- Halaman Karyawan --}}
                @if (Auth::user()->roles == 'KARYAWAN')
                    <a class="nav-link collapsed" data-bs-toggle="collapse" data-bs-target="#collapseStudents"
                        aria-expanded="false" aria-controls="collapseStudents">
                        <div class="sb-nav-link-icon"><i class="fas fa-user-graduate"></i></div>
                        Halaman Karyawan
                        <div class="sb-sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
                    </a>
                    <div class="collapse" id="collapseStudents" aria-labelledby="headingOne"
                        data-bs-parent="#sidenavAccordion">
                        <nav class="sb-sidenav-menu-nested nav">
                            <a class="nav-link" href="{{ route('comingsoon.index') }}">Absensi</a>
                        </nav>
                    </div>
                    <div class="collapse" id="collapseStudents" aria-labelledby="headingOne"
                        data-bs-parent="#sidenavAccordion">
                        <nav class="sb-sidenav-menu-nested nav">
                            <a class="nav-link" href="{{ route('comingsoon.index') }}">Lemburan</a>
                        </nav>
                    </div>
                @endif
                {{-- Halaman Karyawan --}}

                {{-- Master --}}
                @if (Auth::user()->roles == 'ADMIN')
                    <a class="nav-link collapsed" data-bs-toggle="collapse" data-bs-target="#collapseMaster"
                        aria-expanded="false" aria-controls="collapseMaster">
                        <div class="sb-nav-link-icon"><i class="fas fa-database"></i></div>
                        Master
                        <div class="sb-sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
                    </a>
                    <div class="collapse" id="collapseMaster" aria-labelledby="headingOne"
                        data-bs-parent="#sidenavAccordion">
                        <nav class="sb-sidenav-menu-nested nav">
                            <a class="nav-link" href="{{ route('users.index') }}">User</a>
                            <a class="nav-link" href="{{ route('companies.index') }}">Perusahaan</a>
                            <a class="nav-link" href="{{ route('areas.index') }}">Area</a>
                            <a class="nav-link" href="{{ route('divisions.index') }}">Penempatan</a>
                            <a class="nav-link" href="{{ route('positions.index') }}">Jabatan</a>
                            <a class="nav-link" href="{{ route('working-hours.index') }}">Jam Kerja</a>
                            <a class="nav-link" href="{{ route('schools.index') }}">Sekolah</a>
                            {{-- Temporary Update --}}
                            <a class="nav-link" href="{{ route('temporarys.index') }}">Temporary Nomor Handphone</a>
                            {{-- Temporary Update --}}
                        </nav>
                    </div>
                @endif
                {{-- End Master --}}

                {{-- Employee --}}
                @if (Auth::user()->roles == 'ADMIN' || Auth::user()->roles == 'HRD' || Auth::user()->roles == 'LEADER' || Auth::user()->roles == 'MANAGER')
                    <a class="nav-link collapsed" data-bs-toggle="collapse" data-bs-target="#collapseEmployee"
                        aria-expanded="false" aria-controls="collapseEmployee">
                        <div class="sb-nav-link-icon"><i class="fas fa-snowboarding"></i></div>
                        Karyawan
                        <div class="sb-sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
                    </a>
                    <div class="collapse" id="collapseEmployee" aria-labelledby="headingOne"
                        data-bs-parent="#sidenavAccordion">
                        <nav class="sb-sidenav-menu-nested nav">
                            <a class="nav-link" href="{{ route('employees.index') }}">Data Karyawan Aktif</a>
                        </nav>
                    </div>
                    <div class="collapse" id="collapseEmployee" aria-labelledby="headingOne"
                        data-bs-parent="#sidenavAccordion">
                        <nav class="sb-sidenav-menu-nested nav">
                            <a class="nav-link" href="{{ route('employees_outs.index') }}">Data Karyawan
                                Keluar</a>
                        </nav>
                    </div>
                @endif
                {{-- End Employee --}}

                {{-- Siswa Prakerin --}}
                @if (Auth::user()->roles == 'ADMIN' || Auth::user()->roles == 'HRD' || Auth::user()->roles == 'LEADER' || Auth::user()->roles == 'MANAGER')
                    <a class="nav-link collapsed" data-bs-toggle="collapse" data-bs-target="#collapseStudents"
                        aria-expanded="false" aria-controls="collapseStudents">
                        <div class="sb-nav-link-icon"><i class="fas fa-user-graduate"></i></div>
                        Siswa
                        <div class="sb-sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
                    </a>
                    <div class="collapse" id="collapseStudents" aria-labelledby="headingOne"
                        data-bs-parent="#sidenavAccordion">
                        <nav class="sb-sidenav-menu-nested nav">
                            <a class="nav-link" href="{{ route('students.index') }}">Data Siswa Prakerin</a>
                        </nav>
                    </div>
                @endif
                {{-- End Siswa Prakerin --}}

                {{-- Inventory --}}
                @if (Auth::user()->roles == 'ADMIN' || Auth::user()->roles == 'HRD')
                    <a class="nav-link collapsed" data-bs-toggle="collapse" data-bs-target="#collapseHistory"
                        aria-expanded="false" aria-controls="collapseHistory">
                        <div class="sb-nav-link-icon"><i class="fas fa-tags"></i></div>
                        Inventaris
                        <div class="sb-sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
                    </a>
                    <div class="collapse" id="collapseHistory" aria-labelledby="headingOne"
                        data-bs-parent="#sidenavAccordion">
                        <nav class="sb-sidenav-menu-nested nav">
                            <a class="nav-link" href="{{ route('inventory_laptops.index') }}">Laptop</a>
                        </nav>
                        <nav class="sb-sidenav-menu-nested nav">
                            <a class="nav-link" href="{{ route('inventory_motorcycles.index') }}">Motor</a>
                        </nav>
                        <nav class="sb-sidenav-menu-nested nav">
                            <a class="nav-link" href="{{ route('inventory_cars.index') }}">Mobil</a>
                        </nav>
                    </div>
                @endif
                {{-- End Inventory --}}

                {{-- Surat --}}
                @if (Auth::user()->roles == 'ADMIN' || Auth::user()->roles == 'HRD')
                    <a class="nav-link collapsed" data-bs-toggle="collapse" data-bs-target="#collapseSurat"
                        aria-expanded="false" aria-controls="collapseSurat">
                        <div class="sb-nav-link-icon"><i class="fas fa-envelope-open-text"></i></i></div>
                        Surat
                        <div class="sb-sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
                    </a>
                    <div class="collapse" id="collapseSurat" aria-labelledby="headingOne"
                        data-bs-parent="#sidenavAccordion">
                        <nav class="sb-sidenav-menu-nested nav">
                            <a class="nav-link" href="{{ route('cetak.penilaian_karyawan') }}">Penilaian
                                Karyawan</a>
                        </nav>
                    </div>
                    <div class="collapse" id="collapseSurat" aria-labelledby="headingOne"
                        data-bs-parent="#sidenavAccordion">
                        <nav class="sb-sidenav-menu-nested nav">
                            <a class="nav-link" href="{{ route('cetak.pkwt_kontrak') }}">PKWT Kontrak</a>
                        </nav>
                    </div>
                    <div class="collapse" id="collapseSurat" aria-labelledby="headingOne"
                        data-bs-parent="#sidenavAccordion">
                        <nav class="sb-sidenav-menu-nested nav">
                            <a class="nav-link" href="{{ route('cetak.pkwt_harian') }}">PKWT Harian</a>
                        </nav>
                    </div>
                    <div class="collapse" id="collapseSurat" aria-labelledby="headingOne"
                        data-bs-parent="#sidenavAccordion">
                        <nav class="sb-sidenav-menu-nested nav">
                            <a class="nav-link" href="{{ route('comingsoon.index') }}">Slip Lemburan</a>
                        </nav>
                    </div>
                    <div class="collapse" id="collapseSurat" aria-labelledby="headingOne"
                        data-bs-parent="#sidenavAccordion">
                        <nav class="sb-sidenav-menu-nested nav">
                            <a class="nav-link" href="{{ route('comingsoon.index') }}">Rekap Lemburan</a>
                        </nav>
                    </div>
                @endif
                {{-- End Surat --}}

                {{-- Proses --}}
                @if (Auth::user()->roles == 'ADMIN' || Auth::user()->roles == 'HRD')
                    <a class="nav-link collapsed" data-bs-toggle="collapse" data-bs-target="#collapseProses"
                        aria-expanded="false" aria-controls="collapseProses">
                        <div class="sb-nav-link-icon"><i class="fas fa-paperclip"></i></div>
                        Proses
                        <div class="sb-sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
                    </a>
                    <div class="collapse" id="collapseProses" aria-labelledby="headingOne"
                        data-bs-parent="#sidenavAccordion">
                        <nav class="sb-sidenav-menu-nested nav">
                            <a class="nav-link" href="{{ route('process.process_pkwt_kontrak') }}">PKWT
                                Kontrak</a>
                        </nav>
                    </div>
                    <div class="collapse" id="collapseProses" aria-labelledby="headingOne"
                        data-bs-parent="#sidenavAccordion">
                        <nav class="sb-sidenav-menu-nested nav">
                            <a class="nav-link" href="{{ route('process.process_pkwt_harian') }}">PKWT
                                Harian</a>
                        </nav>
                    </div>
                    <div class="collapse" id="collapseProses" aria-labelledby="headingOne"
                        data-bs-parent="#sidenavAccordion">
                        <nav class="sb-sidenav-menu-nested nav">
                            <a class="nav-link" href="{{ route('process.process_magang') }}">Cetak PKWT
                                Harian Lepas</a>
                        </nav>
                    </div>
                    <div class="collapse" id="collapseProses" aria-labelledby="headingOne"
                        data-bs-parent="#sidenavAccordion">
                        <nav class="sb-sidenav-menu-nested nav">
                            <a class="nav-link" href="{{ route('comingsoon.index') }}">Overtimes</a>
                        </nav>
                    </div>
                @endif
                {{-- End Proses --}}

                {{-- Laporan --}}
                @if (Auth::user()->roles == 'ADMIN' || Auth::user()->roles == 'HRD')
                    <a class="nav-link collapsed" data-bs-toggle="collapse" data-bs-target="#collapseLaporan"
                        aria-expanded="false" aria-controls="collapseLaporan">
                        <div class="sb-nav-link-icon"><i class="fas fa-pencil-alt"></i></div>
                        Laporan
                        <div class="sb-sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
                    </a>
                    <div class="collapse" id="collapseLaporan" aria-labelledby="headingOne"
                        data-bs-parent="#sidenavAccordion">
                        <nav class="sb-sidenav-menu-nested nav">
                            <a class="nav-link" href="{{ route('reports.karyawan_masuk') }}">Karyawan
                                Masuk</a>
                        </nav>
                    </div>
                    <div class="collapse" id="collapseLaporan" aria-labelledby="headingOne"
                        data-bs-parent="#sidenavAccordion">
                        <nav class="sb-sidenav-menu-nested nav">
                            <a class="nav-link" href="{{ route('reports.karyawan_keluar') }}">Karyawan
                                Keluar</a>
                        </nav>
                    </div>
                    <div class="collapse" id="collapseLaporan" aria-labelledby="headingOne"
                        data-bs-parent="#sidenavAccordion">
                        <nav class="sb-sidenav-menu-nested nav">
                            <a class="nav-link" href="{{ route('reports.karyawan_kontrak') }}"
                                target="_blank">Karyawan
                                Kontrak</a>
                        </nav>
                    </div>
                    <div class="collapse" id="collapseLaporan" aria-labelledby="headingOne"
                        data-bs-parent="#sidenavAccordion">
                        <nav class="sb-sidenav-menu-nested nav">
                            <a class="nav-link" href="{{ route('reports.karyawan_tetap') }}"
                                target="_blank">Karyawan
                                Tetap</a>
                        </nav>
                    </div>
                    <div class="collapse" id="collapseLaporan" aria-labelledby="headingOne"
                        data-bs-parent="#sidenavAccordion">
                        <nav class="sb-sidenav-menu-nested nav">
                            <a class="nav-link" href="{{ route('reports.karyawan_harian') }}"
                                target="_blank">Karyawan
                                Harian</a>
                        </nav>
                    </div>
                    <div class="collapse" id="collapseLaporan" aria-labelledby="headingOne"
                        data-bs-parent="#sidenavAccordion">
                        <nav class="sb-sidenav-menu-nested nav">
                            <a class="nav-link" href="{{ route('reports.karyawan_outsourcing') }}"
                                target="_blank">Karyawan
                                Outsourcing</a>
                        </nav>
                    </div>
                    <div class="collapse" id="collapseLaporan" aria-labelledby="headingOne"
                        data-bs-parent="#sidenavAccordion">
                        <nav class="sb-sidenav-menu-nested nav">
                            <a class="nav-link" href="{{ route('reports.inventaris_laptop') }}"
                                target="_blank">Inventaris Laptop</a>
                        </nav>
                    </div>
                    <div class="collapse" id="collapseLaporan" aria-labelledby="headingOne"
                        data-bs-parent="#sidenavAccordion">
                        <nav class="sb-sidenav-menu-nested nav">
                            <a class="nav-link" href="{{ route('reports.inventaris_motor') }}"
                                target="_blank">Inventaris Motor</a>
                        </nav>
                    </div>
                    <div class="collapse" id="collapseLaporan" aria-labelledby="headingOne"
                        data-bs-parent="#sidenavAccordion">
                        <nav class="sb-sidenav-menu-nested nav">
                            <a class="nav-link" href="{{ route('reports.inventaris_mobil') }}"
                                target="_blank">Inventaris Mobil</a>
                        </nav>
                    </div>
                @endif
                {{-- End Laporan --}}

            </div>
        </div>
        <div class="sb-sidenav-footer">
            <div class="small">Copyright:</div>
            <i class="fas fa-code"></i></i> OLAY
            <i class="fas fa-code"></i>
        </div>
    </nav>
</div>
{{-- End Side Bar Menu --}}
