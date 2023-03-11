@extends('layouts.frontend.app')
@section('title', 'Member')
@section('meta_keys', 'Member')
@section('meta_description', 'Member')
@section('style')

@endsection
@section('script')

@endsection
@section('content')
    <div class="banner-area" id="banner-area"
        style="background-image:url('{{ asset('frontend/images/banner/banner5.jpg') }}');">
        <div class="container">
            <div class="row ">
                <div class="col">
                    <div class="banner-heading">
                        <h1 class="banner-title">Member</h1>
                        <ol class="breadcrumb">
                            <li><a href="{{ route('home.index') }}">Home</a></li>
                            <li>Member</li>
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
                <div class="col-xs-12 col-sm-12 col-md-2 col-lg-2 offset-lg-2">
                    {{-- <a href="https://old.ican.org.np/member/signUp" class="cardstyle" target="_blank">
                        <img class="blockimages" src="{{ asset('frontend/images/students/block4.svg') }}" alt="">
                        <div class="blocktitle">New Member Registration </div>
                    </a> --}}
                </div>
                <div class="col-xs-12 col-sm-12 col-md-4 col-lg-4 ">
                    <a href="https://old.ican.org.np/member" class="cardstyle" target="_blank">
                        <img class="blockimages" src="{{ asset('frontend/images/students/block5.svg') }}" alt="">
                        <div class="blocktitle">Existing Member Login</div>
                    </a>
                </div>
            </div>
        </div>
        <!-- Container end-->
    </section>
@endsection
