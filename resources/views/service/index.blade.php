@extends('layouts.frontend.app')
@section('title', 'Services' )
@section('style')

@endsection
@section('script')
    @if(isset($schema))
        {!! $schema !!}
    @endif
@endsection
@section('content')
    <div class="banner-area" id="banner-area"
         style="background-image:url({{asset('frontend/images/banner/banner2.jpg')}});">
        <div class="container">
            <div class="row ">
                <div class="col">
                    <div class="banner-heading">
                        <h1 class="banner-title">Services</h1>
                        <ol class="breadcrumb">
                            <li><a href="{{route('home.index')}}">{{trans('general.home')}}</a></li>
                            <li>Services</li>
                        </ol>
                    </div>
                </div>
                <!-- Col end-->
            </div>
            <!-- Row end-->
        </div>
        <!-- Container end-->
    </div>
    <section class="main-container" id="main-container">
        <div class="container">
            <div class="row">
                <div class="col-xs-12 col-sm-12 col-md-9 maintxt">
                    <div class="row">
                        @if(isset($services) && !$services->isEmpty())
                            @foreach($services as $service)
                                @php
                                    $link = isset($service->url) && !empty($service->url) ? $service->url : url('/services/'.$service->slug);
                                @endphp
                                <div class="col-xs-12 col-sm-6 col-md-6 col-lg-4">
                                    <div class="notice-inner-block">
                                        <a href="{{ $link }}" class="inner-notice-title inner-services">
                                            @if(file_exists('storage/thumbs/'.$service->image) && $service->image != '')
                                                <img src="{{ asset('storage/thumbs/'. $service->image) }}"
                                                     style="padding-top: 10px;">
                                            @else
                                                <img src="{{ asset('frontend/images/no-image.png') }}">
                                            @endif
                                            <div class="">{{ $service->title }}</div>
                                        </a>
                                    </div>
                                </div>
                            @endforeach
                        @else
                            <div class="col-xs-12">
                                No record found.
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
