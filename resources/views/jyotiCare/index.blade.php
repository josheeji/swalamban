@extends('layouts.frontend.app')
@section('title', 'Jyoti Care' )
@section('style')

@endsection
@section('script')
<script type="text/javascript">
    $('form').submit(function () {
            $(this).find("button[type='submit']").prop('disabled', true);
        });

        $('#refresh').click(function () {
            $.ajax({
                type: 'GET',
                url: "{{ url('refreshcaptcha') }}",
                success: function (data) {
                    $(".captcha span").html(data.captcha);
                }
            });
        });

        // Example starter JavaScript for disabling form submissions if there are invalid fields
        (function () {
            'use strict'

            // Fetch all the forms we want to apply custom Bootstrap validation styles to
            var forms = document.querySelectorAll('.needs-validation')

            // Loop over them and prevent submission
            Array.prototype.slice.call(forms)
                .forEach(function (form) {
                    form.addEventListener('submit', function (event) {
                        if (!form.checkValidity()) {
                            event.preventDefault()
                            event.stopPropagation()
                        }

                        form.classList.add('was-validated')
                    }, false)
                })
        })()
</script>
@endsection
@section('content')
<style>
    .error {
        color: red;
    }
</style>

<section class="content-pd breadcrumb-wrap">
    <div class="container">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{route('home.index')}}">{{trans('general.home')}}</a></li>
                <li class="breadcrumb-item active" aria-current="page">{{trans('general.jyoti-care')}}</li>
            </ol>
        </nav>
        <h1> {{trans('general.jyoti-care')}} </h1>
    </div>
</section>



<!-- inner content start -->
<section class="content-pd inner-content contactpage  ">
    <div class="container">
        <div class="row">
            <div class="col-lg-9 col-md-8 pd-right">
                <div class="card-box contact-form">
                    @if(Session::has('flash_success'))
                    <div class="alert alert-success alert-dismissible" role="alert">
                        <strong><i class="icon fa fa-check mr-2"></i></strong> {!! Session::get('flash_success') !!}
                    </div>
                    @endif
                    <h3>{{trans('general.jyoti-care-registration')}} </h3>
                    <form class="contact-form" id="contact-form" action="{{ route('jyoti-care.submit') }}" method="POST"
                        autocomplete="off" novalidate>
                        <div class="row">
                            <div class="col-md-6 ">
                                <div class="mb-3 form-floating">
                                    <input type="text" name="full_name" class="form-control" id="floatingInput"
                                        placeholder="Full Name" value="{{old('full_name')}}">
                                        @if($errors->has('full_name'))
                                        <div class="error">{{ $errors->first('full_name') }}</div>
                                        @endif
                                    <label for="floatingInput">{{trans('contact.full-name')}}</label>
                                </div>
                            </div>
                            <div class="col-md-6 ">
                                <div class="form-floating mb-3">
                                    <input type="text" name="mobile_no" class="form-control" id="floatingInput"
                                        placeholder="Mobile Number" value="{{old('mobile_no')}}">
                                        @if($errors->has('mobile_no'))
                                        <div class="error">{{ $errors->first('mobile_no') }}</div>
                                        @endif
                                    <label for="floatingInput">{{trans('contact.mobile-number')}}</label>
                                </div>
                            </div>
                            <div class="col-md-6 ">
                                <div class="mb-3 form-floating">
                                    <input type="text" name="address" class="form-control" id="floatingInput"
                                        placeholder="Address" value="{{old('address')}}">
                                        @if($errors->has('address'))
                                        <div class="error">{{ $errors->first('address') }}</div>
                                        @endif
                                    <label for="floatingInput">{{trans('contact.address')}}</label>
                                </div>
                            </div>
                            <div class="col-md-6 ">
                                <div class="form-floating mb-3">
                                    <input type="email" name="email_address" class="form-control" id="floatingInput"
                                        placeholder="Email address" value="{{old('email_address')}}">
                                        @if($errors->has('email_address'))
                                        <div class="error">{{ $errors->first('email_address') }}</div>
                                        @endif
                                    <label for="floatingInput">{{trans('contact.email-address')}}</label>
                                </div>
                            </div>

                            <div class="col-md-6 ">
                                <select class="form-select mb-3" name="qualification" required>
                                    <option value="">{{trans('contact.qualification')}}</option>
                                    <option value="Below SEE">Below SEE</option>
                                    <option value="SEE">SEE</option>
                                    <option value="Intermediate">Intermediate</option>
                                    <option value="Bachelor">Bachelor </option>
                                    <option value="Master">Master</option>
                                    <option value="Phd">Phd</option>
                                </select>
                                @if($errors->has('qualification'))
                                <div class="error">{{ $errors->first('qualification') }}</div>
                                @endif
                            </div>
                            <div class="col-md-6 ">
                                <select class="form-select mb-3" name="branch" required>
                                    <option value="">{{trans('branch.branch_location')}}</option>
                                    <option value="Amanagar">Amanagar</option>
                                    <option value="Arghakhanchi">Arghakhanchi</option>
                                    <option value="Attariya">Attariya</option>
                                    <option value="Baglung">Baglung</option>
                                    <option value="Baitadi">Baitadi</option>
                                    <option value="Bajhang">Bajhang</option>
                                    <option value="Bajura">Bajura</option>
                                </select>
                                @if($errors->has('branch'))
                                <div class="error">{{ $errors->first('branch') }}</div>
                                @endif

                            </div>
                            <div class="col-md-6 ">
                                <select class="form-select mb-3" name="status_category" required>
                                    <option value="">{{trans('contact.status')}}</option>
                                    <option value="Business">Business</option>
                                    <option value="Employee">Employee</option>
                                    <option value="Students">Students</option>
                                    <option value="Other">Other</option>
                                </select>
                                @if($errors->has('status_category'))
                                <div class="error">{{ $errors->first('status_category') }}</div>
                                @endif
                            </div>

                            <div class="col-md-6 ">
                                <div class="mb-3">
                                    <label for="formFile"
                                        class="form-label">{{trans('contact.upload-citizenship')}}</label>
                                    <input class="form-control" name="citizenship_file" type="file" id="formFile">
                                </div>

                            </div>
                            <div class="col-md-12">
                                <div class="form-group captcha" style="float: left; position: relative;width: 180px;">
                                    <span>{!! captcha_img('flat') !!}</span>
                                    <a href="javascript:void(0)" id="refresh"
                                        style="top: 7px; position: absolute; right: -22px; z-index: 2;"><i
                                            class="fa fa-refresh"></i></a>
                                </div>

                                <div class="form-floating mb-3" style="float: left;margin-left: 30px; width: 150px;">
                                    <input type="text" name="captcha" class="form-control" id="floatingInput"
                                        placeholder="Enter Captcha" value="{{ old('captcha') }}">
                                    @if($errors->has('captcha'))
                                    <div class="error">{{ $errors->first('captcha') }}</div>
                                    @endif
                                    <label for="floatingInput">{{trans('contact.captcha')}} *</label>
                                </div>
                                <div class="clear"></div>

                            </div>
                            <div class="col-md-12">
                                <button class="btn btn-primary">{{trans('contact.submit')}}<i
                                        class="bi bi-arrow-right"></i></button>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="sharethis-inline-share-buttons"></div>
            </div>

            <div class="col-lg-3 col-md-4">
                <div class="findbranch"><a href="{{route('branch.index')}}"> <img
                            src="{{asset('frontend/images/branch.jpg')}}" alt="Find a Branch Image"> </a></div>
                <iframe
                    src="https://www.facebook.com/plugins/page.php?href=https%3A%2F%2Fwww.facebook.com%2Fjyotilifeinsurance&tabs=timeline&width=340&height=300&small_header=true&adapt_container_width=true&hide_cover=false&show_facepile=false&appId"
                    width="100%" height="350" style="border:none;overflow:hidden" scrolling="no" frameborder="0"
                    allowfullscreen="true"
                    allow="autoplay; clipboard-write; encrypted-media; picture-in-picture; web-share"></iframe>

            </div>
        </div>
    </div>
</section>
<!-- inner content end -->

@endsection