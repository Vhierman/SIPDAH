<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <meta name="description" content="Achmad Firmansyah" />
    <meta name="author" content="Achmad Firmansyah" />
    <link rel="icon" href={{ url('backend/assets/logo/network.png') }}>
    <title>HRD-GA</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Montserrat:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&family=Roboto:ital,wght@0,100;0,300;0,400;0,500;0,700;0,900;1,100;1,300;1,400;1,500;1,700;1,900&display=swap"
        rel="stylesheet">

    <link rel="stylesheet" href="{{ url('frontend/frontend/libraries/bootstrap/css/bootstrap.css') }}">
    <link rel="stylesheet" href="{{ url('frontend/frontend/libraries/scrollanimation/aos.css') }}">
    <link rel="stylesheet" href="{{ url('frontend/frontend/styles/main.css') }}">
</head>

<body>
    <!-- navbar -->
    <div class="container">
        <nav class="row navbar fixed-top navbar-expand-lg navbar-dark bg-dark">
            <a href="#" class="navbar-brand">
                <img src="{{ url('frontend/frontend/images/navbar/logo-bulat.png') }}"
                    alt="Logo Prima Komponen Indonesia">
            </a>
            <button class="navbar-toggler navbar-toggler-right" type="button" data-toggle="collapse"
                data-target="#navb">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navb">
                <ul class="navbar-nav ml-auto mr-3">
                    <li class="nav-item mx-md-2"><a href="{{ url('') }}" class="nav-link active">
                            Home
                        </a></li>
                    <li class="nav-item mx-md-2"><a href="{{ url('') }}" class="nav-link">
                            Facilities
                        </a></li>
                    <li class="nav-item mx-md-2"><a href="{{ url('') }}" class="nav-link">
                            Product
                        </a></li>
                    <li class="nav-item mx-md-2"><a href="{{ url('') }}" class="nav-link">
                            Contact Us
                        </a></li>
                </ul>

                @guest
                    <!-- Mobile Button-->
                    <form class="form-inline d-sm-block d-md-none">
                        <button class="btn btn-login my-2 my-sm-0" type="button"
                            onclick="event.preventDefault(); location.href='{{ url('login') }}'">
                            Masuk
                        </button>
                    </form>
                    <!-- End Mobile Button-->
                    <!-- Desktop Button -->
                    <form class="form-inline my-2 my-lg-0 d-none d-md-block">
                        <button class="btn btn-login btn-navbar-right mr-4 my-2 my-sm-0 px-4" type="button"
                            onclick="event.preventDefault(); location.href='{{ url('login') }}'">
                            Masuk
                        </button>
                    </form>
                    <!-- End Desktop Button -->
                @endguest

                @auth
                    <!-- Mobile Button-->
                    <form class="form-inline d-sm-block d-md-none" action="{{ url('logout') }}" method="post">
                        @csrf
                        <button class="btn btn-login my-2 my-sm-0" type="submit">
                            Keluar
                        </button>
                    </form>
                    <!-- End Mobile Button-->
                    <!-- Desktop Button -->
                    <form class="form-inline my-2 my-lg-0 d-none d-md-block" action="{{ url('logout') }}" method="post">
                        @csrf
                        <button class="btn btn-login btn-navbar-right mr-4 my-2 my-sm-0 px-4" type="submit">
                            Keluar
                        </button>
                    </form>
                    <!-- End Desktop Button -->
                @endauth

            </div>
        </nav>
    </div>
    <!-- End navbar -->

    @yield('content')

    <!-- footer -->
    <footer class="section-footer border-top">
        <div class="container pt-5 pb-5">
            <div class="row justify-content-center">
                <div class="col-12">
                    <div class="row">
                        <div class="col-12 col-lg-3" data-aos="fade-up" data-aos-duration="3000">
                            <img src="{{ url('frontend/frontend/images/footer/logobulat.png') }}" alt="">
                        </div>
                        <div class="col-12 col-lg-3" data-aos="fade-up" data-aos-duration="3000">
                            <h5>HEAD OFFICE</h5>
                            <ul class="list-unstyled mt-4">
                                <li><i class="fab fa-periscope"></i>&nbsp;&nbsp;Kawasan Industri Taman Tekno
                                <li>
                                <li>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; Blok F2,No.10-11,F 1J,F1 A2</li>
                                <li>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; Setu,Setu,Tangsel,Banten.</li>
                            </ul>
                            <ul class="list-unstyled">
                                <li><i class="fas fa-phone-alt"></i>&nbsp; Telp.(021) 75880223 - 25</li>
                                <li>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; Fax.(021) 75880220 - 21</a></li>
                            </ul>
                            <ul class="list-unstyled">
                                <li><i class="fas fa-envelope-open-text"></i>&nbsp; marketing@primakomindonesia.com</li>
                                <li>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; hrd-ga@primakomindonesia.com</a></li>
                            </ul>
                        </div>
                        <div class="col-12 col-lg-3" data-aos="fade-up" data-aos-duration="3000">
                            <h5>ACCOUNTING OFFICE</h5>
                            <ul class="list-unstyled mt-4">
                                <li><i class="fab fa-periscope"></i>&nbsp;&nbsp;Komp.Greenville Blok AW,No.66</li>
                                <li>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; Duri Kepa,Kebon Jeruk,</li>
                                <li>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; Jakarta Barat,DKI Jakarta</li>
                            </ul>
                            <ul class="list-unstyled">
                                <li><i class="fas fa-phone-alt"></i>&nbsp; Telp.(021) 56943136 - 38</li>
                                <li>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; Fax.(021) 56943135</li>
                            </ul>
                            <ul class="list-unstyled">
                                <li><a><i class="fas fa-envelope-open-text"></i></a>&nbsp;
                                    accounting@primakomindonesia.com
                                </li>
                            </ul>
                        </div>
                        <div class="col-12 col-lg-3" data-aos="fade-up" data-aos-duration="3000">
                            <h5>CERTIFICATION</h5>
                            <ul class="list-unstyled mt-2 row justify-content-center">
                                <li>
                                    <img class="certification mt-2"
                                        src="{{ url('frontend/frontend/images/footer/9001.png') }}" alt="">
                                    <img class="certification mt-2"
                                        src="{{ url('frontend/frontend/images/footer/14001.png') }}"
                                        alt="">
                                    <img class="certification mt-2"
                                        src="{{ url('frontend/frontend/images/footer/integrasi.png') }}"
                                        alt="">
                                    <img class="certification mt-2"
                                        src="{{ url('frontend/frontend/images/footer/sni.png') }}" alt="">
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="container-fluid">
            <div class="row border-top justify-content-center align-items-center pt-3">
                <div class="col auto text-gray-500 font-weight-light">2022 Copyright
                    <!-- Bootstrap Icon -->
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor"
                        class="bi bi-award-fill" viewBox="0 0 16 16">
                        <path
                            d="m8 0 1.669.864 1.858.282.842 1.68 1.337 1.32L13.4 6l.306 1.854-1.337 1.32-.842 1.68-1.858.282L8 12l-1.669-.864-1.858-.282-.842-1.68-1.337-1.32L2.6 6l-.306-1.854 1.337-1.32.842-1.68L6.331.864 8 0z" />
                        <path d="M4 11.794V16l4-1 4 1v-4.206l-2.018.306L8 13.126 6.018 12.1 4 11.794z" />
                    </svg>
                    <!-- End Bootstrap Icon -->
                    Achmad Firmansyah.All Right Reserved.
                    <!-- Bootstrap Icon -->
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor"
                        class="bi bi-geo-alt-fill" viewBox="0 0 16 16">
                        <path
                            d="M8 16s6-5.686 6-10A6 6 0 0 0 2 6c0 4.314 6 10 6 10zm0-7a3 3 0 1 1 0-6 3 3 0 0 1 0 6z" />
                    </svg>
                    <!-- End Bootstrap Icon -->
                    Tangerang Selatan.
                </div>
            </div>
        </div>
    </footer>
    <!-- End footer -->

    <!-- ScrollOnTop -->
    <button id="topBtn"><i class="fas fa-arrow-up"></i></button>
    <!-- End ScrollOnTop -->

    <script src="{{ url('frontend/frontend/libraries/jquery/jquery-3.6.0.min.js') }}"></script>
    <script src="{{ url('frontend/frontend/libraries/bootstrap/js/bootstrap.js') }}"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.1/js/all.min.js" crossorigin="anonymous"></script>
    <script src="{{ url('frontend/frontend/libraries/scrollontop/scrollontop.js') }}"></script>
    <script src="{{ url('frontend/frontend/libraries/scrollanimation/aos.js') }}"></script>
    <script>
        AOS.init();
    </script>
</body>

</html>
