@extends('layouts.frontend.app')
@section('style')
<link href="{{ asset('frontend/css/emiculator.css') }}" rel="stylesheet" />
<link href="{{ asset('frontend/css/emi.css') }}" rel="stylesheet" />
@endsection
@section('script')
{!! isset($schema) && !empty($schema) ? $schema : '' !!}
@endsection
@section('page-banner')
<section class="bannertop">
    <div class="container">
        <div class="bannerimg parallax">
            <h1>{!! trans('general.emi-calculator') !!}</h1>
            <div class="banner-txt"></div>
            <ul class="header-bottom-navi">
                <li><a href="{{ route('home.index') }}">{{ trans('general.home') }}</a><i class="fas fa-chevron-right"></i></li>

                <li><a href="javascript:void(0);">{!! trans('general.emi-calculator') !!}</a></li>
            </ul>
        </div>
    </div>
</section>
@endsection
@section('content')
<section class="maininner-container ">
    <div class="container">
        <div class="row">
            <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 maintxt">
            <div class="emic-calculator inner-emic-calc"></div>
            </div>
        </div>
</section>
@endsection