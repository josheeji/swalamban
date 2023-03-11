<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name') }} | @yield('title')</title>
    <!-- Favicon -->
    <link href="{{ asset('swabalamban/images/favicon.png') }}" type="image/png" rel="icon" />
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Poppins:300,400,500,600,700" />
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Mukta:wght@200&display=swap');

        .editor,
        .note-editable {
            font-family: 'Mukta', sans-serif;
        }
    </style>
    <!--begin::Global Theme Styles(used by all pages)-->
    <link href="{{ asset('backend/css/plugins.bundle.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('backend/plugins/prismjs/prismjs.bundle.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('backend/css/style.bundle.css') }}" rel="stylesheet" type="text/css" />
    <!--end::Global Theme Styles-->
    <!--begin::Layout Themes(used by all pages)-->
    <link href="{{ asset('backend/css/header/base/light.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('backend/css/header/menu/light.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('backend/css/brand/light.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('backend/css/aside/light.css') }}" rel="stylesheet" type="text/css" />
    {{-- datatable --}}
    <link href="{{ asset('backend/css/dataTables.bootstrap4.min.css') }}" rel="stylesheet" />

    <!--end::Layout Themes-->
    <!--begin::Page Custom Styles(used by this page)-->
    <link href="{{ asset('backend/css/custom.min.css') }}" rel="stylesheet" type="text/css" />
    @yield('styles')
    <!--end::Page Custom Styles-->
</head>

<body id="kt_body" class="header-fixed header-mobile-fixed subheader-enabled subheader-fixed aside-enabled aside-fixed aside-minimize-hoverable page-loading">
    <!--begin::Main-->
    <!--begin::Header Mobile-->
    <div id="kt_header_mobile" class="header-mobile align-items-center  header-mobile-fixed ">
        <!--begin::Logo-->
        <a href="{{route('admin.dashboard')}}">
            <img alt="Logo" src="{{asset('swabalamban/images/logo.svg')}}">
        </a>
        <!--end::Logo-->
        <!--begin::Toolbar-->
        <div class="d-flex align-items-center">
            <!--begin::Aside Mobile Toggle-->
            <button class="btn p-0 burger-icon burger-icon-left" id="kt_aside_mobile_toggle">
                <span></span>
            </button>
            <!--end::Aside Mobile Toggle-->
            <!--begin::Header Menu Mobile Toggle-->
            <!--end::Header Menu Mobile Toggle-->
            <!--begin::Topbar Mobile Toggle-->
            <button class="btn btn-hover-text-primary p-0 ml-2" id="kt_header_mobile_topbar_toggle">
                <span class="svg-icon svg-icon-xl">
                    <!--begin::Svg Icon | path:assets/media/svg/icons/General/User.svg-->
                    <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1">
                        <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                            <polygon points="0 0 24 0 24 24 0 24" />
                            <path d="M12,11 C9.790861,11 8,9.209139 8,7 C8,4.790861 9.790861,3 12,3 C14.209139,3 16,4.790861 16,7 C16,9.209139 14.209139,11 12,11 Z" fill="#000000" fill-rule="nonzero" opacity="0.3" />
                            <path d="M3.00065168,20.1992055 C3.38825852,15.4265159 7.26191235,13 11.9833413,13 C16.7712164,13 20.7048837,15.2931929 20.9979143,20.2 C21.0095879,20.3954741 20.9979143,21 20.2466999,21 C16.541124,21 11.0347247,21 3.72750223,21 C3.47671215,21 2.97953825,20.45918 3.00065168,20.1992055 Z" fill="#000000" fill-rule="nonzero" />
                        </g>
                    </svg>
                    <!--end::Svg Icon-->
                </span> </button>
            <!--end::Topbar Mobile Toggle-->
        </div>
        <!--end::Toolbar-->
    </div>
    <div class="d-flex flex-column flex-root">
        <!--begin::Page-->
        <div class="d-flex flex-row flex-column-fluid page">
            @include('layouts.backend.inc.sidebar')
            <div class="d-flex flex-column flex-row-fluid wrapper" id="kt_wrapper">
                @include('layouts.backend.inc.header')
                <div class="content d-flex flex-column flex-column-fluid" id="kt_content">
                    {{-- @yield('page-header') --}}
                    <div class="container-fluid">
                        @include('layouts.backend.inc.alert')
                    </div>
                    @yield('content')
                </div>
                @include('layouts.backend.inc.footer')
            </div>
        </div>
    </div>
    <script src="{{ asset('backend/js/plugins.bundle.js') }}"></script>
    <script src="{{ asset('backend/plugins/prismjs/prismjs.bundle.js') }}"></script>
    <script src="{{ asset('backend/js/scripts.bundle.js') }}"></script>
    <script src="{{ asset('backend/js/jquery-ui.js') }}"></script>
    <!-- <script src="{{ asset('backend/plugins/ckeditor/ckeditor.js') }}"></script>
    <script src="{{ asset('backend/plugins/ckeditor/jquery.js') }}"></script> -->

    {{-- datatables --}}
    <script src="{{ asset('backend/js/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('backend/js/dataTables.bootstrap4.min.js') }}"></script>

    <script>
        $(document).ready(function() {
            $('#check-bank-guarantee').DataTable();
        });
    </script>

    <script>
        var baseUrl = '{!! url("") !!}';
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

        $('#change_password').on("click", function() {
            jQuery('.alert-success').hide();
            $("#myModal").modal();
        });

        $('#Password').on('submit', function(e) {
            e.preventDefault();
            var data = $(this).serialize();
            var url = '{{route("admin.reset_password")}}';
            $.ajax({
                type: "post",
                url: url,
                data: data,
                dataType: "json",
                success: function(response) {
                    if (response.status == 'ok') {
                        jQuery('.alert-success').html('');
                        jQuery('.alert-danger').hide();
                        jQuery('.alert-success').show();
                        jQuery('.alert-success').append('<p>' + response.message + '</p>');

                        setTimeout(function() {
                            $('#myModal').modal('hide');
                            $('#Password')[0].reset();
                        }, 10000);
                        $('#Password').reset();
                    } else if (response.error == 'false') {
                        jQuery('.alert-danger').html('');
                        jQuery('.alert-danger').show();
                        jQuery('.alert-danger').append('<p>' + response.message + '</p>');
                        setTimeout(function() {
                            $('#myModal').modal('hide');
                            $('#Password')[0].reset();
                        }, 10000);
                        $('#Password').reset();
                    } else {
                        jQuery('.alert-danger').html('');
                        jQuery.each(response.errors, function(key, value) {
                            jQuery('.alert-danger').show();
                            jQuery('.alert-danger').append('<p>' + value + '</p>');
                        });
                    }
                },
                error: function(xhr) {}
            });
        });

        // Define function to open filemanager window
        var lfm = function(options, cb) {
            var route_prefix = (options && options.prefix) ? options.prefix : '/laravel-filemanager';
            window.open('{{ route("home.index")}}/admin' + route_prefix + '?type=' + options.type || 'file', 'FileManager', 'width=900,height=600');
            window.SetUrl = cb;
        };

        // Define LFM summernote button
        var LFMButton = function(context) {
            var ui = $.summernote.ui;
            var button = ui.button({
                contents: '<i class="note-icon-picture"></i> ',
                tooltip: 'Insert image with filemanager',
                click: function() {

                    lfm({
                        type: 'image',
                        prefix: '/laravel-filemanager'
                    }, function(lfmItems, path) {
                        lfmItems.forEach(function(lfmItem) {
                            context.invoke('insertImage', lfmItem.url);
                        });
                    });

                }
            });
            return button.render();
        };

        var KTSummernoteDemo = function() {
            // Private functions
            var demos = function() {
                $('.editor').summernote({
                    height: 400,
                    toolbar: [
                        ['style', ['style']],
                        ['font', ['bold', 'underline', 'clear']],
                        ['color', ['color']],
                        ['para', ['ul', 'ol', 'paragraph']],
                        ['table', ['table']],
                        ['insert', ['link', 'lfm', 'video']],
                        ['view', ['fullscreen', 'codeview', 'help']],
                    ],
                    buttons: {
                        lfm: LFMButton
                    }
                });
            }

            return {
                // public functions
                init: function() {
                    demos();
                }
            };
        }();

        // Initialization
        jQuery(document).ready(function() {
            KTSummernoteDemo.init();
        });
    </script>
    @yield('scripts')
</body>

</html>
