{{-- Topbar --}}
<nav class="sb-topnav navbar navbar-expand navbar-dark bg-dark">
    <!-- Navbar Brand-->
    <a class="navbar-brand ps-3" href="{{ route('dashboard') }}"><i class="fas fa-seedling"></i> - HRD-GA</a>
    <!-- Sidebar Toggle-->
    <button class="btn btn-link btn-sm order-1 order-lg-0 me-4 me-lg-0" id="sidebarToggle" href="#!"><i
            class="fas fa-bars"></i></button>
    <!-- Navbar Search-->
    <form class="d-none d-md-inline-block form-inline ms-auto me-0 me-md-3 my-2 my-md-0">
        Hello : {{ Auth::user()->name }}
    </form>
    <!-- Navbar-->
    <ul class="navbar-nav ms-auto ms-md-0 me-3 me-lg-4">
        <li class="nav-item dropdown">
            <a class="nav-link dropdown-toggle" id="navbarDropdown" href="#" role="button" data-bs-toggle="dropdown"
                aria-expanded="false"><i class="fas fa-cog"></i></a>
            <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdown">
                <li><a class="dropdown-item" href="#">{{ Auth::user()->name }}</a></li>
                {{-- <li><a class="dropdown-item" href="#!">Activity Log</a></li> --}}
                <li>
                    <hr class="dropdown-divider" />
                </li>
                <form class="form-inline ml-3" action="{{ url('logout') }}" method="post">
                    @csrf
                    <li class="ml-3">
                        <button class="btn btn-light btn-block" type="submit">
                            Keluar
                        </button>
                    </li>
                </form>
            </ul>
        </li>
    </ul>
</nav>
{{-- End Topbar --}}
