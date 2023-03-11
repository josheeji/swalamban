@extends('layouts.frontend.app')

@section('title', $career->title)
@section('style')
@endsection
@section('script')
    <script>
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
@section('page-banner')
@endsection
@section('content')

    <!-- Title/Breadcrumb -->
    <section id="pagetitle" style="background-image:url({{ asset('swabalamban/images/titlebg.jpg') }});">
        <div class="container">
            <h1>{{ $career->title }}
            </h1>
            <ul>
                <li>
                    <a href="{{ route('home.index') }}">Home
                    </a>
                    <i class="fas fa-chevron-right">
                    </i>
                </li>
                <li>
                    <a href="{{ route('career.index') }}">Career
                    </a>
                    <i class="fas fa-chevron-right">
                    </i>
                </li>
                <li>{{ $career->title }}
                </li>
            </ul>
        </div>
    </section>
    <!-- Title/Breadcrumb END -->
    <section id="inner-contanier" class="section-padding">
        <div class="container">
            <div class="row">
                @include('layouts.frontend.inc.socialmedia')
                @include('layouts.frontend.inc.comments')
                <div class="col-xs-12 col-sm-12 col-md-12 col-lg-8 col-xl-8">
                    <div class="leftsection">
                        @if (Session::has('flash_success'))
                            <div class="alert alert-success alert-dismissible flash_message" role="alert">
                                <strong><i class="icon fa fa-check mr-2"></i></strong> {!! Session::get('flash_success') !!}
                            </div>
                        @endif
                        <form class="contact-form" enctype="multipart/form-data" method="POST"
                            action="{{ route('career.store') }}">
                            {!! csrf_field() !!}
                            <div class="career-form">
                                <div class="row ">
                                    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 col-xl-12">
                                        <div class="mb-4  form-floating">
                                            <input type="text" class="form-control" placeholder=""
                                                value="" disabled>
                                            <label for="">{{ $career->title }}</label>
                                        </div>
                                    </div>
                                    <input type="hidden" name="career_id" value="{{$career->id}}">
                                    <div class="col-xs-12 col-sm-12 col-md-6 col-lg-4 col-xl-4">
                                        <div class="mb-4  form-floating">
                                            <input type="text" class="form-control" placeholder="Full Name"
                                                name="full_name" value="{{old('full_name')}}" required="">
                                            <label for="">Full Name </label>
                                            @if ($errors->has('full_name'))
                                                <div class="error text-danger">{{ $errors->first('full_name') }}</div>
                                            @endif
                                        </div>
                                    </div>

                                    <div class="col-xs-12 col-sm-12 col-md-6 col-lg-4 col-xl-4">
                                        <div class="mb-4  form-floating">
                                            <input type="text" class="form-control" placeholder="Permanent Address"
                                                name="p_address" value="{{old('p_address')}}" required="">
                                            <label for="">Permanent Address </label>
                                            @if ($errors->has('p_address'))
                                                <div class="error text-danger">{{ $errors->first('p_address') }}</div>
                                            @endif
                                        </div>
                                    </div>

                                    <div class="col-xs-12 col-sm-12 col-md-6 col-lg-4 col-xl-4">
                                        <div class="mb-4  form-floating">
                                            <input type="text" class="form-control" placeholder="Temporary Address"
                                                name="t_address" value="{{old('t_address')}}">
                                            <label for="">Temporary Address</label>
                                            @if ($errors->has('t_address'))
                                                <div class="error text-danger">{{ $errors->first('t_address') }}</div>
                                            @endif
                                        </div>
                                    </div>

                                    <div class="col-xs-12 col-sm-12 col-md-6 col-lg-4 col-xl-4">
                                        <div class="mb-4  form-floating">
                                            <input type="text" class="form-control" placeholder="Mobile No."
                                                name="contact_no" value="{{old('contact_no')}}" required="">
                                            <label for="">Mobile No.</label>
                                            @if ($errors->has('contact_no'))
                                                <div class="error text-danger">{{ $errors->first('contact_no') }}</div>
                                            @endif
                                        </div>
                                    </div>

                                    <div class="col-xs-12 col-sm-12 col-md-6 col-lg-4 col-xl-4">
                                        <div class="mb-4  form-floating">
                                            <input type="email" class="form-control" placeholder="Email" name="email"
                                                value="{{old('email')}}" required="">
                                            <label for="">Email</label>
                                            @if ($errors->has('email'))
                                                <div class="error text-danger">{{ $errors->first('email') }}</div>
                                            @endif
                                        </div>
                                    </div>

                                    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-6 col-xl-6">
                                        <div class="mb-4">
                                            <label for="floatingTextarea">Attach Your Resume (Less than 2MB) <span
                                                    class="asterisk">*</span></label>
                                            <input type="file" class="form-control" placeholder="Resume" name="resume"
                                                value="{{old('resume')}}" required="">
                                            @if ($errors->has('resume'))
                                                <div class="error text-danger">{{ $errors->first('resume') }}</div>
                                            @endif
                                            <span class="attach-file">Supported formats jpeg, jpg, png, pdf, doc and
                                                docx</span>
                                        </div>

                                    </div>

                                    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-6 col-xl-6">
                                        <div class="mb-4">
                                            <label for="floatingTextarea">Attach Your Cover Letter (Less than 2MB)<span
                                                    class="asterisk">*</span></label>
                                            <input type="file" class="form-control" placeholder="Cover Letter"
                                                name="cover_letter" value="{{old('cover_letter')}}" required="">
                                            @if ($errors->has('cover_letter'))
                                                <div class="error text-danger">{{ $errors->first('cover_letter') }}</div>
                                            @endif
                                        </div>
                                    </div>


                                    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 col-xl-12">
                                        <div class="form-floating mb-4">
                                            <input name="message" class="form-control" type="textarea"
                                                placeholder="Leave a Message here" id="floatingInput" value="{{old('message')}}"
                                                style="height: 8rem;">
                                            <label for="floatingTextarea">Message</label>
                                            @if ($errors->has('message'))
                                                <div class="error text-danger">{{ $errors->first('message') }}</div>
                                            @endif
                                        </div>
                                    </div>



                                    <div class="col-xs-4 col-sm-4 col-md-4 col-lg-3 col-xl-3">
                                        <div class="form-floating captcha">
                                            <span>{!! captcha_img('flat') !!}</span>
                                            <a href="javascript:void(0)" id="refresh"
                                                style="top: 7px; position: absolute; right: -5px; z-index: 2;"><i
                                                    class="fa fa-sync-alt"></i></a>
                                        </div>
                                    </div>

                                    <div class="col-xs-4 col-sm-4 col-md-4 col-lg-2 col-xl-2">
                                        <div class="form-floating">
                                            <input id="captcha" type="text" class="form-control"
                                                placeholder="Enter-captcha" name="captcha" required="">
                                            <label for="">captcha</label>
                                            @if ($errors->has('captcha'))
                                                <div class="error text-danger">{{ $errors->first('captcha') }}</div>
                                            @endif
                                        </div>
                                    </div>
                                </div>

                                <div class="col-xs-4 col-sm-4 col-md-4 col-lg-2 col-xl-2">
                                    <button class="btn btn-primary tw-mt-30"
                                        type="submit">{{ trans('general.apply-now') }}</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
                <div class="col-xs-12 col-sm-12 col-md-12 col-lg-4 col-xl-4">

                    <div class="latest-notice">
                        <div class="cns-title">
                            <a href="javascript:void(0);" class="">{{ trans('general.latest-notice') }}
                            </a>
                        </div>
                        @foreach ($notices as $item)
                            <div class="notice-1">

                                <div class="notice-date">
                                    @isset($item->end_date)
                                        <span>Expires On {{ date('d M, Y', strtotime($item->end_date)) }}
                                        </span>
                                    @endisset
                                    {{-- <a href="{{ asset('storage/' . $item->link) }}"class="time-update"> --}}
                                        {{ $item->start_date->diffForHumans() }}
                                    {{-- </a> --}}
                                </div>
                                <div class="notice">
                                    <a href="{{ asset('storage/' . $item->link) }}" class="">{{ $item->title }}
                                    </a>
                                    @if (now()->subDays(7)->format('y-m-d') <= $item->start_date->format('y-m-d'))
                                        <span>New
                                        </span>
                                    @endif

                                </div>
                                <div class="dotted">
                                </div>
                            </div>
                        @endforeach
                    </div>
                    <div class="cns-title">
                        <a href="javascript:void(0);" class="">Download Categories
                        </a>
                        <ul class="list-categories">
                            @foreach ($categories as $item)
                                <li>
                                    <a href="{{ route('download.show', $item->slug) }}">{{ $item->title }}
                                    </a>
                                </li>
                            @endforeach
                        </ul>
                    </div>
                </div>


            </div>
        </div>
        </div>
        </div>
    </section>
@endsection
