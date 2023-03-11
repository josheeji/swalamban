@php
    $currentURL = URL::current();
    $segmentHome = Request::segment(1);
@endphp
<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="author" content="">
    <meta name="generator" content="">
    <meta name="docsearch:language" content="en">
    <meta name="docsearch:version" content="4.5">

    <title>@yield('title') | {{ SettingHelper::setting('site_title') }}</title>
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    {!! SEOMeta::generate() !!}
    {!! OpenGraph::generate() !!}
    {!! Twitter::generate() !!}

    <link rel="stylesheet" href="{{ asset('swabalamban/css/bootstrap.min.css') }}">

    <link rel="stylesheet" href="{{ asset('swabalamban/css/owl.carousel.min.css') }}">
    <link rel="stylesheet" href="{{ asset('swabalamban/css/owl.theme.default.css') }}">
    <link rel="stylesheet" href="https://pro.fontawesome.com/releases/v5.10.0/css/all.css">
    <link rel="stylesheet"
        href="https://cdn.jsdelivr.net/npm/@fortawesome/fontawesome-free@5.15.4/css/fontawesome.min.css">
    <link rel="stylesheet" href="{{ asset('swabalamban/css/reset.css') }}">
    <link rel="stylesheet" href="{{ asset('swabalamban/css/navik-all.min.css') }}">
    <!-- <link rel="stylesheet" href="{{ asset('swabalamban/css/navik-horizontal-default-menu.min.css') }}"> -->

    <link rel="stylesheet" href="{{ asset('swabalamban/css/style.css?') }}{{ rand() }}">
    <link rel="stylesheet" href="{{ asset('swabalamban/css/magnific-popup.css') }}">
    <link rel="stylesheet" href="{{ asset('swabalamban/css/responsive.css?') }}{{ rand() }}">
    <link rel="stylesheet" href="{{ asset('swabalamban/css/lightgallery-bundle.min.css?') }}">

    <!-- Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600&display=swap" rel="stylesheet">
    <!-- Favicon -->
    <link href="{{ asset('swabalamban/images/favicon.png') }}" type="image/svg" rel="icon">
    {!! Helper::customCss() !!}
    <style>
        img {
            max-width: 100%;
        }
    </style>
    @yield('style')

</head>

<body>

    @include('layouts.frontend.inc.header')
    @yield('content')
    @include('layouts.frontend.inc.footer')

    <script src="{{ asset('swabalamban/js/jquery-3.6.1.min.js') }}"></script>
    <script src="{{ asset('swabalamban/js/bootstrap.min.js') }}"></script>
    <script src="{{ asset('swabalamban/js/jquery.magnific-popup.min.js') }}"></script>
    <script src="{{ asset('swabalamban/js/jquery.magnific-popup.js') }}"></script>

    <script src="{{ asset('swabalamban/js/owl.carousel.min.js') }}"></script>
    <!-- nav js-->
    <script src="{{ asset('swabalamban/js/navik.menu.js') }}"></script>
    <!-- nav js-->
    <script type="text/javascript" src="{{ asset('swabalamban/js/echarts.min.js') }}"></script>
    <script src="{{ asset('swabalamban/js/lg-fullscreen.min.js') }}"></script>
    <script src="{{ asset('swabalamban/js/lg-medium-zoom.min.js') }}"></script>
    <script src="{{ asset('swabalamban/js/lg-thumbnail.min.js') }}"></script>
    <script src="{{ asset('swabalamban/js/lg-zoom.min.js') }}"></script>
    <script src="{{ asset('swabalamban/js/lightgallery.min.js') }}"></script>
    <script src="{{ asset('swabalamban/js/custom.js') }}"></script>
    <script src="{{ asset('swabalamban/js/custom-script.js') }}"></script>


    @yield('script')
    <script>
        $(".search-btn").click(function() {
            $(".searchwrapper").addClass("active");
            $(this).css("display", "none");
            $(".search-data").fadeIn(500);
            $(".close-btn").fadeIn(500);
            $(".search-data .line").addClass("active");
            setTimeout(function() {
                $("input").focus();
                $(".search-data label").fadeIn(500);
                $(".search-data span").fadeIn(500);
            }, 800);
        });
        $(".close-btn").click(function() {
            $(".searchwrapper").removeClass("active");
            $(".search-btn").fadeIn(800);
            $(".search-data").fadeOut(500);
            $(".close-btn").fadeOut(500);
            $(".search-data .line").removeClass("active");
            $("input").val("");
            $(".search-data label").fadeOut(500);
            $(".search-data span").fadeOut(500);
        });
        setTimeout(() => {
            $('.flash_message').hide();
        }, 5000);
        $(".search-button").click(function() {
            var keyword = $('#search-keyword').val();
            var uri = "{{ url('search?keyword=:keyword') }}";
            uri = uri.replace(":keyword", keyword);
            window.location.href = uri;
        });

        $('#languageLink').on('change', function() {
            var uri = "{{ url('locale/:locale') }}";
            uri = uri.replace(":locale", this.value);
            window.location.href = uri;
        });

        $('.subscribe').click(function() {
            var email = $('#subscription-email').val();
            // if (!email) {
            //     $("#subscription-email").val("");
            //     $('#message-subscription').html("");
            //     $('#message-subscription').removeClass("d-none");
            //     $('#message-subscription').addClass("alert alert-danger");
            //     $('#message-subscription').append(
            //        'Email is required');
            //     setTimeout(() => {
            //         $('#message-subscription').addClass("d-none");
            //     }, 3000);
            //     return false;
            // }
            $.ajax({
                type: 'post',
                url: '{{ route('subscription.store') }}',
                data: {
                    _token: $("meta[name='csrf-token']").attr('content'),
                    email: email,
                },
                dataType: "json",

                success: function(response) {
                    $("#subscription-email").val("");
                    $("#subscription-email").val("");
                    $('#message-subscription').html("");
                    $('#message-subscription').removeClass("d-none");
                    $('#message-subscription').addClass("alert alert-success");
                    $('#message-subscription').append(
                        response.message);
                    setTimeout(() => {
                        $('#message-subscription').addClass("d-none");
                    }, 3000);
                },
                error: function(request, status, error) {
                    let json = jQuery.parseJSON(request.responseText);
                    $("#subscription-email").val("");
                    $('#message-subscription').html("");
                    $('#message-subscription').removeClass("d-none");
                    $('#message-subscription').addClass("alert alert-danger");
                    $('#message-subscription').append(
                        json.message);
                    setTimeout(() => {
                        $('#message-subscription').addClass("d-none");
                    }, 3000);

                },

            })
        })
    </script>


<script type="text/javascript">
    lightGallery(document.getElementById('lightgallery'), {
        plugins: [lgZoom, lgThumbnail],
        speed: 500,
        animateThumb: false,
    zoomFromOrigin: false,
    allowMediaOverlap: true,
    toggleThumb: true,
});

  
</script>
</body>

</html>
