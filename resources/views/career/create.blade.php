@extends('layouts.frontend.app')
@section('title', 'Careers' )
@section('style')

@endsection
@section('script')
<script>
    $('form').submit(function() {
        $(this).find("button[type='submit']").prop('disabled', true);
    });

    const fileInput = document.querySelector('.file-resume input[type=file]');
    fileInput.onchange = () => {
        if (fileInput.files.length > 0) {
            const fileName = document.querySelector('.file-resume .resume-label');
            fileName.textContent = fileInput.files[0].name;
        }
    }

    const fileIn = document.querySelector('.file-cover input[type=file]');
    fileIn.onchange = () => {
        if (fileIn.files.length > 0) {
            const fileName = document.querySelector('.cover-label');
            fileName.textContent = fileIn.files[0].name;
        }
    }

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
<section class="bannertop">
    <div class="container">
        <div class="bannerimg parallax">
            <h1>{{ trans('general.careers') }}</h1>
            <div class="banner-txt"></div>
            <ul class="header-bottom-navi">
                <li>
                    <a href="{{ route('home.index') }}">{{ trans('general.home') }}</a><i class="fas fa-chevron-right"></i>
                </li>
                <li><a href="{{ route('career.index') }}">{{ trans('general.careers') }}</a> <i class="fas fa-chevron-right"></i></li>
                <li><a href="{{ route('career.show', $career->slug) }}">{{ $career->title }}</a><i class="fas fa-chevron-right"></i></li>
                <li><a href="javascript:void(0);">{{ trans('career.apply') }}</a></li>
            </ul>
        </div>
    </div>
</section>
@endsection
@section('content')
<section class="maininner-container ">
    <div class="container">
        <div class="row">
            <div class="col-xs-12 col-sm-12 col-md-8 col-lg-9 padding-right maintxt">
                @include('layouts.frontend.inc.alert')
                @if(!Session::has('flash_success'))
                <h2>{{ $career->title }}</h2>
                <form class="" id="contactform" enctype="multipart/form-data" method="post" action="{{ route('career.store', $career->slug) }}">
                    {!! csrf_field() !!}
                    <div class="row">
                        <div class="col-xs-12 col-sm-6 col-md-6 col-lg-6">
                            <div class="form-group">
                                <label>{{ trans('contact.full-name') }} <span class="text-danger">*</span></label>
                                <input class="form-control" type="tel" name="full_name" placeholder="" value="{{ old('full_name') }}">
                            </div>
                        </div>
                        <div class="col-xs-12 col-sm-6 col-md-6 col-lg-6">
                            <div class="form-group">
                                <label>{{ trans('general.address') }} <span class="text-danger">*</span></label>
                                <input class="form-control" type="text" name="address" placeholder="" value="{{ old('address') }}">
                            </div>
                        </div>
                        <div class="col-xs-12 col-sm-6 col-md-6 col-lg-6">
                            <div class="form-group">
                                <label>{{ trans('contact.phone') }} <span class="text-danger">*</span></label>
                                <input class="form-control" type="tel" name="contact_no" placeholder="" value="{{ old('contact_no') }}">
                            </div>
                        </div>
                        <div class="col-xs-12 col-sm-6 col-md-6 col-lg-6">
                            <div class="form-group">
                                <label>{{ trans('contact.email') }} <span class="text-danger">*</span></label>
                                <input class="form-control" type="email" name="email" placeholder="" value="{{ old('email') }}">
                            </div>
                        </div>

                        <div class="col-xs-12 col-sm-6 col-md-6 col-lg-6">
                            <div class="form-group">
                                <div class="custom-file file-resume">
                                    
                                    <input type="file" name="resume" class="custom-file-input" id="resume">
                                    <label class="custom-file-label resume-label" for="resume">Upload Resume <span class="text-danger">*</span></label>
                                </div>
                            </div>
                        </div>

                        <div class="col-xs-12 col-sm-6 col-md-6 col-lg-6">
                            <div class="form-group">
                                <div class="custom-file file-cover">
                                    <input type="file" name="cover_letter" class="custom-file-input" id="cover-letter">
                                    <label class="custom-file-label cover-label" for="cover-letter">Cover Letter <span class="text-danger">*</span></label>
                                </div>
                            </div>
                        </div>

                        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                            <div class="form-group">
                                <label>Your Message <span class="text-danger">*</span></label>
                                <textarea style=" height: 100px;" class="form-control" maxlength="250" name="message" placeholder="">{{ old('message') }}</textarea>
                            </div>
                        </div>

                        <div class="col-xs-7 col-sm-6 col-md-6 col-lg-4">
                            <div class="form-group ">
                                <div class="captcha career-cap">
                                    <span>{!! captcha_img('flat') !!}</span>
                                    <a href="javascript:void(0)" id="refresh" class="fresh" style=""><i class="fas fa-sync-alt"></i></a>
                                </div>
                            </div>
                        </div>

                        <div class="col-xs-12 col-sm-6 col-md-6 col-lg-4">
                            <div class="form-group ">
                                <input id="captcha" type="text" class="form-control" placeholder="Enter Captcha" name="captcha">
                            </div>
                        </div>
                        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                            <div class="form-group">
                                <button class="btn_1 rounded btn" type="submit">{{ trans('general.apply-now') }}</button>
                            </div>
                        </div>

                    </div>
                </form>
                @endif
            </div>
            <div class="col-xs-12 col-sm-12 col-md-4 col-lg-3">
                <div class="careerlisting">
                <h3 class="sidebar-title">Other Vacancies </h3>
                @if(isset($careers))
                <ul>
                     @foreach($careers as $career)   
                    <li>
                        <a href="#!"><a href="{{ route('career.show', $career->slug) }}">{{ $career->title }}</a></a>
                        <div class="careerdate">{{ Helper::formatDate($career->publish_to) }}</div>
                    </li>
                    @endforeach
                </ul>
                @endif
                </div>
                
            </div>
        </div>
    </div>
</section>
@endsection