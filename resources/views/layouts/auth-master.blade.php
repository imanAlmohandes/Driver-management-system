<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>@yield('title') | {{ config('app.name') }}</title>

    <!-- Custom fonts & styles for this template-->
    <link href="{{ asset('assets/vendor/fontawesome-free/css/all.min.css') }}" rel="stylesheet" type="text/css">
    <link
        href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i"
        rel="stylesheet">
    <link href="{{ asset('assets/css/sb-admin-2.min.css') }}" rel="stylesheet">
    <style>
        .btn-twitter {
            color: #fff;
            background-color: #1DA1F2;
            border-color: #1DA1F2;
        }

        .btn-twitter:hover {
            background-color: #0c85d0;
            border-color: #0c85d0;
        }
    </style>
    @if (app()->currentLocale() == 'ar')
        <style>
            .p-2,
            h1.h4 {
                padding: .5rem !important;
                text-align: center;
                direction: rtl;
            }

            div.form-group {
                text-align: right !important;
                direction: rtl !important;
            }

        </style>
    @endif

</head>

<body class="bg-gradient-primary">
    <div class="container">
        <!-- Outer Row -->
        <div class="row justify-content-center">
            <div class="col-xl-10 col-lg-12 col-md-9">
                <div class="card o-hidden border-0 shadow-lg my-5">
                    <div class="card-body p-0">
                        <!-- Nested Row within Card Body -->
                        <div class="row">
                            <div class="col-lg-6 d-none d-lg-block @yield('image-class', 'bg-login-image')"></div>
                            <div class="col-lg-6">
                                <div class="p-5">
                                    {{-- The content of each specific page will be injected here --}}
                                    @yield('content')
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap & Core JavaScript-->
    <script src="{{ asset('assets/vendor/jquery/jquery.min.js') }}"></script>
    <script src="{{ asset('assets/vendor/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
    <script src="{{ asset('assets/vendor/jquery-easing/jquery.easing.min.js') }}"></script>
    <script src="{{ asset('assets/js/sb-admin-2.min.js') }}"></script>
</body>

</html>
