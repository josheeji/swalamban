@extends('layouts.frontend.app')
@section('title', $service->title )
@section('style')

@endsection
@section('script')
    @if(isset($schema))
        {!! $schema !!}
    @endif
@endsection

@section('content')
    <div class="banner-area" id="banner-area"
         @if(isset($service->banner)  && file_exists('storage/'.$service->banner))
         style="background-image:url({{asset('storage/'.$service->banner)}});"
         @else
         style="background-image:url({{asset('frontend/images/banner/banner2.jpg')}});"
            @endif
    >
        <div class="container">
            <div class="row ">
                <div class="col">
                    <div class="banner-heading">
                        <h1 class="banner-title">{!! $service->title !!}</h1>
                        <ol class="breadcrumb">
                            <li><a href="{{route('home.index')}}">{{trans('general.home')}}</a></li>
                            <li><a href="{{route('services.index')}}">Service</a></li>
                            <li>{!! $service->title !!}</li>
                        </ol>
                    </div>
                </div>
                <!-- Col end-->
            </div>
            <!-- Row end-->
        </div>
        <!-- Container end-->
    </div>
    <!-- Banner area end-->

    <section class="main-container" id="main-container">
        <div class="container">
            <div class="row">
                <div class="col-xs-12 col-sm-12 col-md-12">
                    {!! $service->description !!}
                </div>
            </div>
        </div>
    </section>
@endsection
