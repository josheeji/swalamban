@extends('layouts.frontend.app')
@section('title', $offer->title)
@section('meta_keys', $offer->title)
@section('meta_description', $offer->title)
@section('style')

@endsection
@section('script')

@endsection
@section('content')
    @php
    $banner = isset($offer->banner) && !empty($offer->banner) && file_exists('storage/' . $offer->banner) ? asset('storage/' . $offer->banner) : asset('frontend/images/banner/banner2.jpg');
    @endphp
    <!-- header area start-->
    <section class="content-pd breadcrumb-wrap">
        <div class="container">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('home.index') }}">{{ trans('general.home') }}</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('offer.index') }}">{{ trans('general.offers') }}</a>
                    </li>
                    <li class="breadcrumb-item active" aria-current="page">{!! $offer->title !!}</li>
                </ol>
            </nav>
            <h1>{!! $offer->title !!} </h1>
        </div>
    </section>
    <!-- Banner area end-->
    <section class="main-container" id="main-container">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <div class="post-content post-single">
                        {!! $offer->description !!}
                        <!-- entry content end-->
                    </div>
                </div>
            </div>
            <!-- Main row end-->
        </div>
        <!-- Container end-->
    </section>
@endsection
