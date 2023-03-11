@extends('layouts.frontend.app')
@section('title', 'Grievance Handling Officer')
@section('style')
    <style>
        .error {
            color: red;
        }
    </style>
@endsection
@section('script')
    <script type="text/javascript">
        $('form').submit(function() {
            $(this).find("button[type='submit']").prop('disabled', true);
        });

        $('#refresh').click(function() {
            $.ajax({
                type: 'GET',
                url: "{{ url('refreshcaptcha') }}",
                success: function(data) {
                    $(".captcha span").html(data.captcha);
                }
            });
        });
    </script>
@endsection
@section('content')
    <div class="banner-area" id="banner-area"
        style="background-image:url({{ asset('frontend/images/banner/banner2.jpg') }});">
        <div class="container">
            <div class="row ">
                <div class="col">
                    <div class="banner-heading">
                        <h1 class="banner-title">Grievance Handling Officer</h1>
                        <ol class="breadcrumb">
                            <li><a href="{{ route('home.index') }}">Home</a></li>
                            <li>Grievance Handling Officer</li>
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
                <div class="col-xs-12 col-sm-12 col-md-9 maintxt">
                    <p>Share your experiences with our services and help assist you in a better way. You can fill the
                        form
                        below to file your complaint, please share as much details as possible with your contact
                        information.</p>
                    <!-- ajax contact form -->
                    @if (Session::has('flash_success'))
                        <div class="alert alert-success alert-dismissible" role="alert">
                            <strong><i class="icon fa fa-check mr-2"></i></strong> {!! Session::get('flash_success') !!}
                        </div>
                    @endif
                    <div class="box-border">
                        <form class="contact-form" id="grievancehandeling" method="post"
                            action="{{ route('grievance.store') }}">
                            {!! csrf_field() !!}
                            <div class="row">
                                <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                                    <select name="branch_id" id="" hidden>
                                        <option value="">{{ trans('contact.select-branch') }}</option>
                                        @if ($branch)
                                            @foreach ($branch as $data)
                                                <option value="{{ $data->id }}"
                                                    {{ old('branch_id') == $data->id ? 'selected' : '' }}>
                                                    {{ $data->title }}</option>
                                            @endforeach
                                        @endif
                                    </select>
                                    @if ($errors->has('branch_id'))
                                        <div class="error">{{ $errors->first('branch_id') }}</div>
                                    @endif
                                </div>

                                <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                                    <div class="form-group">
                                        <select name="department_id" id="department" class="form-control d-none">
                                            <option value="">{{ trans('contact.select-department') }}</option>
                                            @if (isset($department) && !empty($department) && !$department->isEmpty())
                                                @foreach ($department as $data)
                                                    <option value="{{ $data->id }}"
                                                        {{ old('department_id') == $data->id ? 'selected' : '' }}>
                                                        {{ $data->title }}</option>
                                                @endforeach
                                            @endif
                                        </select>
                                        @if ($errors->has('department_id'))
                                            <div class="error">{{ $errors->first('department_id') }}</div>
                                        @endif
                                    </div>
                                </div>

                                <div class="col-xs-12 col-sm-6 col-md-6 col-lg-6">
                                    <div class="form-group">
                                        <input class="form-control" type="text" value="{{ old('full_name') }}"
                                            name="full_name" placeholder="{{ trans('contact.name') }}" required>
                                        @if ($errors->has('full_name'))
                                            <div class="error">{{ $errors->first('full_name') }}</div>
                                        @endif
                                    </div>
                                </div>
                                <div class="col-xs-12 col-sm-6 col-md-6 col-lg-6">
                                    <div class="form-group">
                                        <input class="form-control" type="tel" value="{{ old('mobile') }}"
                                            name="mobile" placeholder="{{ trans('contact.phone') }}" required>
                                        @if ($errors->has('phone'))
                                            <div class="error">{{ $errors->first('phone') }}</div>
                                        @endif
                                    </div>
                                </div>
                                <div class="col-xs-12 col-sm-6 col-md-6 col-lg-6">
                                    <div class="form-group">
                                        <input class="form-control" type="email" value="{{ old('email') }}"
                                            name="email" placeholder="{{ trans('contact.email') }}" required="required">
                                        @if ($errors->has('email'))
                                            <div class="error">{{ $errors->first('email') }}</div>
                                        @endif
                                    </div>
                                </div>
                                <div class="col-xs-12 col-sm-6 col-md-6 col-lg-6">
                                    <div class="form-group">
                                        <input class="form-control" type="text" value="{{ old('subject') }}"
                                            name="subject" placeholder="Subject" required="required">
                                        @if ($errors->has('subject'))
                                            <div class="error">{{ $errors->first('subject') }}</div>
                                        @endif
                                    </div>
                                </div>
                                <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                                    <div class="form-group">
                                        <textarea class="form-control text-area-grievance" name="message"
                                            placeholder="{{ trans('general.grievance-message') }}" required rows="10">{{ old('message') }}</textarea>
                                        @if ($errors->has('message'))
                                            <div class="error">{{ $errors->first('message') }}</div>
                                        @endif
                                    </div>
                                </div>
                                <div class="col-xs-12 col-sm-12 col-md-6 col-lg-6">
                                    <p style="margin-bottom:5px">Are you ICAN Student/Member?</p>
                                    <label for="yes" class="label"><input type="radio" name="existing_customer"
                                            id="yes" value="1" {{ old('existing_customer', 1) == 1 ? 'checked' : '' }}>
                                        Yes</label>
                                    <label for="no" class="label"><input type="radio" name="existing_customer"
                                            id="no" value="0" {{ old('existing_customer', 0) == 0 ? 'checked' : '' }}>
                                        No</label>
                                    @if ($errors->has('existing_customer'))
                                        <div class="error">{{ $errors->first('existing_customer') }}</div>
                                    @endif
                                </div>
                                <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                                    <label for="grant_authorization" class="g-authorized"><input type="checkbox"
                                            name="grant_authorization" id="grant_authorization" value="1" required
                                            {{ old('grant_authorization') == 1 ? 'checked' : '' }}>
                                        I authorize ICAN & it's representative to call me or SMS me with reference to my
                                        application.</label>
                                </div>
                                <div class="col-xs-12 col-sm-7 col-md-3 col-lg-3">
                                    <div class="form-group">
                                        <div class="captcha">
                                            <span>{!! captcha_img('flat') !!}</span>
                                            <a href="javascript:void(0)" id="refresh"
                                                style="top: 7px; position: absolute; right: -6px; z-index: 2;"><i
                                                    class="fa fa-refresh"></i></a>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-xs-12 col-sm-5 col-md-6 col-lg-3">
                                    <div class="form-group">
                                        <input id="captcha" type="text" class="form-control" placeholder="Enter Captcha"
                                            name="captcha" required>
                                        @if ($errors->has('captcha'))
                                            <div class="error">{{ $errors->first('captcha') }}</div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <button class="btn btn-primary" type="submit">{{ trans('contact.submit') }}</button>
                            </div>


                        </form>
                    </div>


                </div>
                <div class="col-xs-12 col-sm-12 col-md-3 mainsidewrapper">
                    <div class="card-blog-list">
                        <h3 class="sidebar-title">{{ trans('general.information-officer') }}</h3>
                        <ul class="abtlist">
                            @if (Helper::grievanceImage() && file_exists('storage/thumbs/' . Helper::grievanceImage()))
                                <li><img src="{!! asset('storage/thumbs/' . Helper::grievanceImage()) !!}" alt="{!! Helper::grievanceHandler() !!}"></li>
                            @endif
                            <li>{!! Helper::grievanceHandler() !!}</li>
                            @if (Helper::grievanceAddress())
                                <li>{!! Helper::grievanceAddress() !!}</li>
                            @endif
                            @if (Helper::grievanceContact())
                                <li>{!! Helper::grievanceContact() !!}</li>
                            @endif
                            <li>
                                <a href="mailto:{!! Helper::grievanceEmail() !!}">{!! Helper::grievanceEmail() !!}</a>
                            </li>
                        </ul>
                    </div>
                    {{-- <a class="blue-btns " href="https://gunaso.nrb.org.np/" target="_blank">NRB Grievance</a> --}}

                </div>
            </div>
        </div>
    </section>
@endsection
