<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ isset($title) ? $title : config('app.name') }} | Login</title>

    <link href="{{ asset('backend/images/favicons/favicon.png') }}" type="image/png" rel="icon" sizes="32x32" />
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Poppins:300,400,500,600,700" />
    <link href="{{ asset('backend/css/style.bundle.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('backend/css/header/base/light.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('backend/css/header/menu/light.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('backend/css/custom.min.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('backend/css/login-6.css') }}" rel="stylesheet" type="text/css" />
    @yield('styles')
</head>

<body id="kt_body" style="background-color:#80B2F1" class="header-fixed header-mobile-fixed subheader-enabled subheader-fixed aside-enabled aside-fixed aside-minimize-hoverable page-loading">
    <!--begin::Main-->
    <div class="d-flex flex-column flex-root">
        <!--begin::Login-->
        <div class="login login-6 login-signin-on login-signin-on d-flex flex-column-fluid" id="kt_login">
            <div class="d-flex flex-column flex-lg-row flex-row-fluid text-center" style="background-image: url({{ asset('backend/images/login.svg') }}); background-repeat: no-repeat; background-position: left 15vh; background-size: 45vw;">
                <!--begin:Aside-->
                <div class="d-flex w-100 flex-center p-15">
                    <div class="login-wrapper">
                        <!--begin:Aside Content-->
                        <div class="text-dark-75">
                            <h3 class="mb-8 mt-22 font-weight-bold"></h3>
                            <p class="mb-15 text-muted font-weight-bold">
                            </p>
                        </div>
                        <!--end:Aside Content-->
                    </div>
                </div>
                <!--end:Aside-->

                <!--begin:Content-->
                <div class="d-flex w-100 flex-center p-15 position-relative overflow-hidden" style="background-color: #ffffff">
                    <div class="login-wrapper">
                        <!--begin:Sign In Form-->
                        <div class="login-signin">
                            <div class="text-center mb-10 mb-lg-20">
                                <img src="{{asset('swabalamban/images/logo.svg')}}" width="100">

{{--                                <h2 class="font-weight-bold">Welcome to ICAN! 👋</h2>--}}
{{--                                <p class="text-muted font-weight-bold">Please sign-in to your account and start the--}}
{{--                                    adventure</p>--}}
                            </div>

                            @include('layouts.backend.inc.alert')
                            <form class="form text-left" id="kt_login_signin_form" method="post" action="">
                                @csrf
                                <div class="form-group">
                                    <label class="font-size-h6 font-weight-bolder text-dark">Email</label>
                                    <input class="form-control form-control-solid h-auto py-7 px-6 rounded-lg" type="text" name="email" autocomplete="off" />
                                </div>

                                <div class="form-group">
                                    <label class="font-size-h6 font-weight-bolder text-dark">Password</label>
                                    <input class="form-control form-control-solid h-auto py-7 px-6 rounded-lg" type="password" name="password" autocomplete="off" />
                                </div>
                                <div class="text-center mt-15">
                                    <button id="" class="btn btn-primary btn-pill shadow-sm py-4 px-9 font-weight-bold" type="submit">Sign In</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
                <!--end:Content-->
            </div>
        </div>
    </div>
    <!--end::Main-->

    <script src="{{ asset('backend/js/plugins.bundle.js') }}"></script>
    <script src="{{ asset('backend/plugins/prismjs/prismjs.bundle.js') }}"></script>
    <script src="{{ asset('backend/js/scripts.bundle.js') }}"></script>
    <script>
        var KTAppSettings = {
            "breakpoints": {
                "sm": 576,
                "md": 768,
                "lg": 992,
                "xl": 1200,
                "xxl": 1400
            },
            "colors": {
                "theme": {
                    "base": {
                        "white": "#ffffff",
                        "primary": "#3699FF",
                        "secondary": "#E5EAEE",
                        "success": "#1BC5BD",
                        "info": "#8950FC",
                        "warning": "#FFA800",
                        "danger": "#F64E60",
                        "light": "#E4E6EF",
                        "dark": "#181C32"
                    },
                    "light": {
                        "white": "#ffffff",
                        "primary": "#E1F0FF",
                        "secondary": "#EBEDF3",
                        "success": "#C9F7F5",
                        "info": "#EEE5FF",
                        "warning": "#FFF4DE",
                        "danger": "#FFE2E5",
                        "light": "#F3F6F9",
                        "dark": "#D6D6E0"
                    },
                    "inverse": {
                        "white": "#ffffff",
                        "primary": "#ffffff",
                        "secondary": "#3F4254",
                        "success": "#ffffff",
                        "info": "#ffffff",
                        "warning": "#ffffff",
                        "danger": "#ffffff",
                        "light": "#464E5F",
                        "dark": "#ffffff"
                    }
                },
                "gray": {
                    "gray-100": "#F3F6F9",
                    "gray-200": "#EBEDF3",
                    "gray-300": "#E4E6EF",
                    "gray-400": "#D1D3E0",
                    "gray-500": "#B5B5C3",
                    "gray-600": "#7E8299",
                    "gray-700": "#5E6278",
                    "gray-800": "#3F4254",
                    "gray-900": "#181C32"
                }
            },
            "font-family": "Poppins"
        };
    </script>
    @yield('scripts')
</body>

</html>
