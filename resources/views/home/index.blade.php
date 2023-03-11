@extends('layouts.frontend.app')
@section('title', 'Home Page')

@section('content')
    <!-- BANNER -->
    <section id="carouselExampleIndicators" class="carousel slide" data-bs-ride="true">
        <div class="carousel-indicators">
            @foreach ($banners as $key => $banner)
                <button type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide-to="{{ $key }}"
                    class="{{ $loop->first ? 'active' : '' }}" aria-label="{{ $banner->title }}"></button>
            @endforeach
        </div>
        <div class="carousel-inner">
            @foreach ($banners as $key => $banner)
                @if (file_exists('storage/' . $banner->image) && $banner->image != '')
                    <div class="carousel-item {{ $loop->first ? 'active' : '' }}">
                        <img src="{{ asset('storage/' . $banner->image) }}" class="d-block w-100" alt="{{ $banner->title }}"
                            title="slider1">
                    </div>
                @endif
            @endforeach

        </div>
        <button class="carousel-control-prev" type="button" data-bs-target="#carouselExampleIndicators"
            data-bs-slide="prev">
            <span class="carousel-control-prev-icon" aria-hidden="true"></span>
            <span class="visually-hidden">Previous </span>
        </button>
        <button class="carousel-control-next" type="button" data-bs-target="#carouselExampleIndicators"
            data-bs-slide="next">
            <span class="carousel-control-next-icon" aria-hidden="true"></span>
            <span class="visually-hidden">Next </span>
        </button>
    </section>
    <!-- BANNER--END -->

    <!-- ABOUT -->
    <section id="about" class="section-padding">
        <div class="container">
            <div class="row">
                <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 ">
                    @include('layouts.frontend.inc.socialmedia')
                    @if (isset($blocks) && isset($blocks['block-1']['value']))
                        <div class="Swabalamban-text">
                            {{-- <h2 class="paragraph-title">About Swabalamban </h2> --}}
                            {!! $blocks['block-1']['value'] !!}
                            {{-- <a href="#!" class="btn">Read More <i class="fal fa-plus"></i>
                            </a> --}}
                        </div>
                    @endif
                    @include('layouts.frontend.inc.comments')
                </div>
                {{-- <div class="col-xs-12 col-sm-6 col-md-6 col-lg-6 ">
                    <div class="Swabalamban-img">
                        <img src="{{ asset('swabalamban/images/aboutimages.png') }}" class="d-block w-100" alt="video"
                            title="video">
                        <div class="video-section">
                            <a href="#!" class="video-btn">
                                <i class="fas fa-play"></i>
                            </a>
                        </div>
                    </div>
                </div> --}}

            </div>
        </div>
    </section>
    <!-- ABOUT--END -->

    <!-- PRODUCT AND SERIVES -->
    @if ($products->count())
        <section id="productandserives" class="section-padding">
            <div class="container">
                <div class="product-text">
                    <h2 class="paragraph-title">{{ trans('general.product-services') }}</h2>
                    <div>
                        To uplift socio-economic condition of rural and urban disadvantaged poor by providing easy access to
                        microfinance services.
                    </div>
                </div>
                <div class="row">
                    @foreach ($products->take(6) as $product)
                        <div class="col-xs-12 col-sm-4 col-md-4 col-lg-2 col-xl-2">
                            <a href="{{ route('product.show', ['slug' => $product->slug]) }}"
                                class="service-block">

                                @if (file_exists('storage/' . $product->image) && $product->image != '')
                                    <img src="{{ asset('storage/' . $product->image) }}" class="d-block w-100"
                                        alt="{{ $product->title }}" title="{{ $product->title }}">
                                @endif
                                <div>{{ $product->title }} </div>
                            </a>
                        </div>
                    @endforeach
                </div>
            </div>
            </div>
            </div>
        </section>
    @endif

    <!-- PRODUCT AND SERIVES---END---->

    <!-- NEWS AND EVENTS---->
    @if ($news->count())
        <section id="news&events" class="section-padding">
            <div class="container">
                <div class="newsevents-text">
                    <h2 class="paragraph-title">{{ trans('general.news-and-events') }}</h2>
                    <div>
                        Take A Look At Our News Articles And Events 
                    </div>
                </div>
                <div class="testimonial">
                    <div class="owl-carousel owl-theme news-carousel">
                        @foreach ($news as $item)
                            <div class="item">
                                <div class="news-update">
                                    <div class="publish-date">
                                        <span>on {{ date('M d, Y', strtotime($item->published_date)) }} </span>
                                        <a href="{{ route('news.show', [$item->category->slug, $item->slug]) }}"
                                            class="news-category">{{ $item->category->title }} </a>
                                    </div>
                                    <div class="news-title notice">
                                        <a href="{{ route('news.show', [$item->category->slug, $item->slug]) }} "
                                            class="">{{ $item->title }}</a>
                                        @if (now()->subDays(7)->format('y-m-d') <= $item->published_date->format('y-m-d'))
                                            <span>New
                                            </span>
                                        @endif
                                    </div>
                                    <p>

                                        {!! str_limit($item->excerpt, 100) ?? str_limit($item->description, 100) !!}

                                    </p>
                                    <div class="read-more">
                                        <a href="{{ route('news.show', [$item->category->slug, $item->slug]) }} "
                                            class=""> {{ trans('general.read-more') }} <i class="fal fa-plus"></i>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        @endforeach

                    </div>
                </div>
        </section>
    @endif
    <!-- NEWS AND EVENTS- END--->

    <!-- SUCCESS-STORY -->
    <section id="success-story" class="section-padding">
        <div class="container">
            <div class="story-text">
                <div>View Our Success Story </div>
                <h2 class="paragraph-title">
                    We’re All About Helping You Reach Loan Help
                </h2>
                <a href="{{ url('/stories') }}" class="btn">{{ trans('general.read-more') }}<i class="fal fa-plus"></i>
                </a>
            </div>
        </div>
    </section>
    <!-- SUCCESS-STORY--END-->

    <!-- LATEST UPDATE -->
    <section id="latest-update" class="section-padding">
        <div class="container">
            <div class="row">
                @if ($notices->count())
                    <div class="col-xs-12 col-sm-12 col-md-6 col-lg-4 ">
                        <div class="latest-notice">
                            <div class="cns-title">
                                <h3 class="">{{ trans('general.latest-notice') }}</h3>
                            </div>
                            @foreach ($notices->take(3) as $key => $notice)
                                <div class="notice-{{ $key + 1 }}">

                                    <div class="notice-date">
                                        @isset($notice->end_date)
                                            <span>Expires On {{ date('M d, Y', strtotime($notice->end_date)) }} </span>
                                        @endisset
                                        {{-- <a href="{{ asset('storage/' . $notice->link) }}" class="time-update"
                                            target="_blank"> --}}
                                            {{ $notice->start_date->diffForHumans() }}
                                         {{-- </a> --}}
                                    </div>
                                    <div class="notice">
                                        <a href="{{ asset('storage/' . $notice->link) }}" class=""
                                            target="_blank">{{ $notice->title }}</a>
                                        @if (now()->subDays(7)->format('y-m-d') <= $notice->start_date->format('y-m-d'))
                                            <span>New
                                            </span>
                                        @endif
                                    </div>
                                    <div class="dotted"></div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif
                @if ($galleries->count())
                    <div class="col-xs-12 col-sm-12 col-md-6 col-lg-4 ">
                        <div class="latest-gallery">
                            <div class="cns-title">
                                <h3 class="">{{ trans('general.latest-gallery') }}</h3>
                            </div>
                            <div class="row">
                                @foreach ($galleries as $gallery)
                                    <div class="col-xs-12 col-sm-4 col-md-4 col-lg-4 col-xl-4">
                                        <a href="{{ route('gallery.show', $gallery->slug) }}" class="home-gallery">
                                            <img src="{{ asset('storage/' . $gallery->image) }}" class="img-fluid"
                                                alt="{{ $gallery->title }}" title="{{ $gallery->title }}">
                                        </a>
                                    </div>
                                @endforeach

                            </div>
                            <div class="view-txt">
                                <a href="{{ route('gallery.index') }}" class=""> {{ trans('general.view-all') }}
                                    <i class="fal fa-plus"></i></a>
                                <div class="ending-line"></div>
                            </div>
                        </div>
                    </div>
                @endif
                @if ($csr->count())
                    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-4 ">

                        <div class="latest-CSR">
                            <div class="cns-title">
                                <h3 class="">Swabalamban CSR </h3>
                            </div>
                            {{-- @dd(file_exists('storage/' . $csr[0]->image)) --}}
                            @if (file_exists('storage/' . $csr[0]->image) && !empty($csr[0]->image))
                                <div class="CRS-img">
                                    <a href="{{ route('news.show', [$csr[0]->category->slug, $csr[0]->slug]) }}">
                                        <img src="{{ asset('storage/' . $csr[0]->image) }}" class="img-fluid"
                                            alt="{{ $csr[0]->title }}" title="{{ $csr[0]->title }}">
                                    </a>
                                    <div class="date-box">
                                        <span>{{ date('M d, Y', strtotime($csr[0]->published_date)) }}</span>
                                    </div>
                                </div>
                            @endif

                            <div class="notice">
                                <a href="{{ route('news.show', [$csr[0]->category->slug, $csr[0]->slug]) }}">
                                    {{ $csr[0]->title }}
                                </a>
                                @if (now()->subDays(7)->format('y-m-d') <= $csr[0]->published_date->format('y-m-d'))
                                            <span>New
                                            </span>
                                        @endif
                            </div>

                            <p>{!! @$csr[0]->excerpt ?? str_limit(@$csr[0]->description, 120) !!}
                            </p>
                            <div class="">
                                <a href="{{ route('news.show', [$csr[0]->category->slug, $csr[0]->slug]) }}"
                                    class=""> {{ trans('general.read-more') }} <i class="fal fa-plus"></i>

                                </a>
                            </div>
                        </div>
                    </div>
                @endif

            </div>
        </div>
    </section>

    <!-- LATEST UPDATE END---->

    <!-- PIE-CHART -->
    <section id="pie-chart" class="section-padding">
        <div class="container">
            <div class="row">
                <div class="col-xs-12 col-sm-12 col-md-12 col-lg-6">
                    {!! $blocks['block-2']['value'] !!}
                </div>
                <div class="col-xs-12 col-sm-12 col-md-12 col-lg-6 ">
                    {!! $blocks['block-3']['value'] !!}
                </div>


                <!-- <div class="col-xs-12 col-sm-12 col-md-12 col-lg-5">

                        <h2 class="cns-title">SWBBL at a Glance </h2>
                        <div class="text-left">Bhadra End 2079 (16 September 2022) </div>
                        <div id="data-chart" style="height: 100%"></div>
                    </div>
                    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-7 ">
                        <div class="branches">
                            <h2 class="cns-title">Our Branches </h2>
                            <img src="{{ asset('swabalamban/images/nepalmap.png') }}" class="img-fluid" alt="map"
                                title="map">
                        </div>
                    </div> -->
            </div>
        </div>
    </section>
    <!-- PIE-CHART--END -->

    <!-- ONLINE-SEWA -->
    @if ($partners->count())
        <section id="online-seva" class="section-padding">
            <div class="container">
                <h2 class="cns-title text-center">Remit</h2>
                <div class="owl-carousel owl-theme onlineseva-carousel">
                    @foreach ($partners as $item)
                        <div class="item">
                            <a href="{{ $item->url }}" class="onlineservices-img">
                                <img src="{{ asset('storage/' . $item->image) }}" class=""
                                    alt="{{ $item->title }}" title="{{ $item->title }}">
                            </a>
                        </div>
                    @endforeach
                </div>
            </div>
        </section>
    @endif
    <!-- ONLINE-SEWA-END--->
@endsection
