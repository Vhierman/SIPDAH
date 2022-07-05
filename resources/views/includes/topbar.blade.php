{{-- Topbar --}}
<nav class="sb-topnav navbar navbar-expand navbar-dark bg-dark">
    <!-- Navbar Brand-->
    <a class="navbar-brand ps-3" href="{{ route('dashboard') }}"><i class="fas fa-seedling"></i> HRD-GA <i
            class="fas fa-seedling"></i></a>
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
            <a class="nav-link dropdown-toggle" id="navbarDropdown" href="#" role="button"
                data-bs-toggle="dropdown" aria-expanded="false"><i class="fas fa-cog"></i></a>
            <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdown">
                <li><a class="dropdown-item" href="#"><i class="fas fa-street-view"></i>
                        {{ Auth::user()->name }}</a>
                </li>
                <li><a class="dropdown-item" href="{{ route('privacypolicy') }}"><i class="fas fa-tasks"></i>
                        Privacy Policy</a></li>
                <li>
                <li><a class="dropdown-item" href="{{ route('dashboard.ubah_password') }}"><i class="fas fa-lock"></i>
                        Change Password</a></li>
                <li>
                    <hr class="dropdown-divider" />
                </li>
                <form class="form-inline ml-3" action="{{ url('logout') }}" method="post">
                    @csrf
                    <li class="text-center">
                        <button class="btn btn-light btn-block" type="submit">
                            <i class="fas fa-power-off"></i> Keluar
                        </button>
                    </li>
                </form>
            </ul>
        </li>
    </ul>
</nav>
{{-- End Topbar --}}
