@extends('layouts.frontend.app')
@section('title', $team->full_name )
@section('style')
@endsection
@section('script')
@endsection
@section('content')
    <div class="banner-area" id="banner-area"
         style="background-image:url({{asset('frontend/images/banner/banner2.jpg')}});">
        <div class="container">
            <div class="row ">
                <div class="col">
                    <div class="banner-heading">
                        <h1 class="banner-title">{{ trans('general.team') }}</h1>
                        <ol class="breadcrumb">
                            <li><a href="{{route('home.index')}}">{{trans('general.home')}}</a></li>
                            <li><a href="{{route('home.index')}}">{{ trans('general.team') }}</a></li>
                            <li>{{ $team->full_name }}</li>
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
                <div class="col-md-3">
                    <div class="mgmt-img">
                        @if(file_exists('storage/thumbs/'.$team->photo) && $team->photo != '')
                            <img class="" src="{{ asset('storage/thumbs/'. $team->photo) }}">
                        @else
                            <img src="{{ asset('frontend/images/no-img.jpg') }}" class=""/>
                        @endif
                    </div>
                </div>
                <div class="col-md-9">
                    <h2 style="margin-top:0;">{{ $team->full_name }}</h2>
                    <p>{{ $team->designation }}</p>
                    <div>{!! $team->description !!}</div>
                </div>
            </div>
        </div>
    </section>
@endsection
