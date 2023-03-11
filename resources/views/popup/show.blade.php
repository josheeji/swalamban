@extends('layouts.frontend.app')
@section('style')
@endsection
@section('scripts')
    {!! $schema !!}
@endsection
@section('content')

    <div class="banner-area" id="banner-area"
         style="background-image:url({{asset('frontend/images/banner/banner2.jpg')}});">
        <div class="container">
            <div class="row ">
                <div class="col">
                    <div class="banner-heading">
                        <h1 class="banner-title">{!! $popup->title !!}</h1>
                        <ol class="breadcrumb">
                            <li><a href="{{route('home.index')}}">{{trans('general.home')}}</a></li>
                            <li>{!! $popup->title !!}</li>
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
                <div class="col-12">
                    @if($popup->show_image == 1 && !empty($popup->image) && file_exists('storage/' . $popup->image))
                        <img src="{{ asset('storage/' . $popup->image) }}" class="" alt="">
                    @endif
                    {!! $popup->description !!}
                    @if($popup->show_button && !empty($popup->btn_label))
                        <div class="my-2">
                            <a class="btn btn-primary"
                               href="{{ $popup->url }}" {{ $popup->target == 1 ? 'target="_blank"' : '' }}>{{ $popup->btn_label }}</a>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </section>
@endsection
