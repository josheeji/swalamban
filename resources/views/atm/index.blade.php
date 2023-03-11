@extends('layouts.frontend.app')
@section('content')
@section('title', 'ATM Location')
@section('style')

@endsection
@section('script')
@endsection
@section('page-banner')
<section class="bannertop">
    <div class="container">
        <div class="bannerimg parallax">
            <h1>{{ trans('atm.atm') }}</h1>
            <div class="banner-txt"></div>
            <ul class="header-bottom-navi">
                <li><a href="{{ route('home.index') }}">{{ trans('general.home') }}</a><i class="fas fa-chevron-right"></i></li>
                <li><a href="#!">{{ trans('atm.atm') }}</a></li>
            </ul>
        </div>
    </div>
</section>
@endsection
@section('content')
<section class="maininner-container ">
    <div class="container">
        <h2>{{ trans('atm.inside_valley') }}</h2>
        <div class="row">
            @if(isset($insideValley) && !$insideValley->isEmpty())
            @foreach($insideValley as $data)
            @php
            $url = 'javascript:void(0)';
            $hasLink = false;
            if(!empty($data->url)){
            $url = $data->url;
            $hasLink = true;
            }elseif(!empty($data->lat) && !empty($data->long)){
            $url = "http://maps.google.com?q={$data->lat},{$data->long}";
            $hasLink = true;
            }
            @endphp
            <div class="col-xs-12 col-sm-6 col-md-4 col-lg-3">
                <a href="{{ $url }}" class="location-blocks {{ $hasLink == true ? 'has-link' : '' }}">
                    <div class="location-place">
                        {{ $data->title }}
                    </div>
                    @if($hasLink == true)
                    <div class=" location-ico"><i class="fal fa-map-marker-alt"></i></div>
                    @endif
                </a>
            </div>
            @endforeach
            @else
            <div class="col-12">{{ trans('general.no-record-found') }}</div>
            @endif
        </div>
        <h2 class="mrt50">{{ trans('atm.outside_valley') }}</h2>
        <div class="row">
            @if(isset($outsideValley) && !$outsideValley->isEmpty())
            @foreach($outsideValley as $data)
            @php
            $url = 'javascript:void(0)';
            $hasLink = false;
            if(!empty($data->url)){
            $url = $data->url;
            $hasLink = true;
            }elseif(!empty($data->lat) && !empty($data->long)){
            $url = "http://maps.google.com?q={$data->lat},{$data->long}";
            $hasLink = true;
            }
            @endphp
            <div class="col-xs-12 col-sm-6 col-md-4 col-lg-3">
                <a href="{{ $url }}" class="location-blocks {{ $hasLink == true ? 'has-link' : '' }}">
                    <div class="location-place">
                        {{ $data->title }}
                    </div>
                    @if($hasLink == true)
                    <div class=" location-ico"><i class="fal fa-map-marker-alt"></i></div>
                    @endif
                </a>
            </div>
            @endforeach
            @else
            <div class="col-12">{{ trans('general.no-record-found') }}</div>
            @endif
        </div>
    </div>
</section>
@endsection