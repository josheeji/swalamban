@extends('layouts.frontend.app')
@section('title', 'Student')
@section('meta_keys', 'Student')
@section('meta_description', 'Student')
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
                        <h1 class="banner-title">Student</h1>
                        <ol class="breadcrumb">
                            <li><a href="{{ route('home.index') }}">Home</a></li>
                            <li>Student</li>
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
                <div class="col-xs-12 col-sm-12 col-md-4 col-lg-4">
                    <a href="https://old.ican.org.np/student/signUp" class="cardstyle" target="_blank">
                        <img class="blockimages" src="{{ asset('frontend/images/students/block1.svg') }}" alt="">
                        <div class="blocktitle">Online New Student Registration</div>
                    </a>
                </div>
                <div class="col-xs-12 col-sm-12 col-md-4 col-lg-4">
                    <a href="https://old.ican.org.np/student" class="cardstyle" target="_blank">
                        <img class="blockimages" src="{{ asset('frontend/images/students/block2.svg') }}" alt="">
                        <div class="blocktitle">Student Login</div>
                    </a>
                </div>
                <div class="col-xs-12 col-sm-12 col-md-4 col-lg-4">
                    <a href="http://new.ican.org.np/online-ca-membership-examination-form" class="cardstyle" >
                        <img class="blockimages" src="http://new.ican.org.np/frontend/images/students/block3.svg" alt="">
                        <div class="blocktitle">Online CA Membership Examination</div>
                    </a>
                </div>
            </div>
        </div>
        <!-- Container end-->
    </section>
@endsection
