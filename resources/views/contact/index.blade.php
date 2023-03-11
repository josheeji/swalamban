@extends('layouts.frontend.app')
@section('title', 'Contact Us')
@section('style')

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

        // Example starter JavaScript for disabling form submissions if there are invalid fields
        (function() {
            'use strict'

            // Fetch all the forms we want to apply custom Bootstrap validation styles to
            var forms = document.querySelectorAll('.needs-validation')

            // Loop over them and prevent submission
            Array.prototype.slice.call(forms)
                .forEach(function(form) {
                    form.addEventListener('submit', function(event) {
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
    <!-- Title/Breadcrumb -->
    <section id="pagetitle" style="background-image:url({{isset($menu->image) ? asset('storage/'.@$menu->image) : asset('swabalamban/images/titlebg.jpg') }});">
        <div class="container">
            <h1>{{ trans('general.contact-us') }}</h1>
            <ul>
                <li><a href="{{route('home.index')}}">{{trans('general.home')}}</a><i class="fas fa-chevron-right"></i></li>
                <li>{{ trans('general.contact-us') }}</li>
            </ul>
        </div>
    </section>
    <!-- Title/Breadcrumb END -->


    <!-- CONTACT -->
    <section id="getin-touch" class="section-padding">
        <div class="container">
            <div class="row">
                <div class="col-xs-12 col-sm-12 col-md-12 col-lg-3 col-xl-3">
                    <div class="contact">
                        <div>{{ trans('general.contact-us') }}</div>
                        <h2>Get In touch</h2>
                        <div class="line"></div>
                        <p>Come and visit our quarters or simply send us an email anytime you want. We are open to all
                            suggestions from our clients.
                        </p>
                    </div>
                </div>
                @if (!empty(SettingHelper::multiLangSetting('address')))
                    <div class="col-xs-12 col-sm-6 col-md-4 col-lg-3 col-xl-3">
                        <div class="contact-details">
                            <div class="contact-block">
                                <img src="{{ asset('swabalamban/images/address.svg') }}" class="d-block w-100"
                                    alt="Address" title="Address">
                                <div class="contacttitle">Address </div>
                                <span>{!! SettingHelper::multiLangSetting('address') !!}</span>
                            </div>
                        </div>
                    </div>
                @endif
                @if (!empty(SettingHelper::multiLangSetting('contact')))
                    <div class="col-xs-12 col-sm-6 col-md-4 col-lg-3 col-xl-3">
                        <div class="contact-details">
                            <div class="contact-block">
                                <img src="{{ asset('swabalamban/images/phone1.svg') }}" class="d-block w-100" alt="Phone"
                                    title="Phone">
                                <div class="contacttitle">Phone No. </div>
                                <a href="tel-{!! SettingHelper::multiLangSetting('contact') !!}">{!! SettingHelper::multiLangSetting('contact') !!}</a>
    </div>
                        </div>
                    </div>
                @endif
                @if (!empty(SettingHelper::setting('email_address')))
                    <div class="col-xs-12 col-sm-12 col-md-4 col-lg-3 col-xl-3">
                        <div class="contact-details">
                            <div class="contact-block">
                                <img src="{{ asset('swabalamban/images/email.svg') }}" class="d-block w-100" alt="Email" title="Email">
                                <div class="contacttitle">Email </div>
                                <a href="email-{!! SettingHelper::setting('email_address') !!}">{!! SettingHelper::setting('email_address') !!}</a>
                            </div>
                        </div>
                    </div>
                @endif
            </div>
        </div>
        </div>
    </section>
    <!-- CONTACT END -->

    <section id="location" class="inner-contanier">
        <div class="container">
            <div class="row">
                <div class="col-xs-12 col-sm-12 col-md-12 col-lg-6 col-xl-6">
                    @include('layouts.frontend.inc.socialmedia')
                    <div class="map">
                        <iframe
                            src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3532.2122660409914!2d85.32307106498789!3d27.710731732790457!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x39eb1908c874a40b%3A0x87a26cbf3b75037c!2sKamal%20Pokhari!5e0!3m2!1sen!2sus!4v1670231803746!5m2!1sen!2sus"
                            width="100%" height="450" style="border:0;" allowfullscreen="" loading="lazy"
                            referrerpolicy="no-referrer-when-downgrade"></iframe>
                    </div>
                </div>

                <div class="col-xs-12 col-sm-12 col-md-12 col-lg-6 col-xl-6">
                    <form class="contact-form" id="contact-form" action="{{ route('contact.submit') }}" method="POST"
                        autocomplete="off" novalidate>
                        <div class="row ">
                            <div class="comment-box">
                                <h2>{{ trans('contact.send-message') }}</h2>

                                <p>We take great pride in everything that we do, control over products allows us to ensure
                                    our
                                    customers receive the best quality service.</p>
                                @include('layouts.frontend.inc.comments')
                            </div>
                            @if (Session::has('flash_success'))
                                <div class="alert alert-success alert-dismissible flash_message" role="alert">
                                    <strong><i class="icon fa fa-check mr-2"></i></strong> {!! Session::get('flash_success') !!}
                                </div>
                            @endif
                            <div class="col-xs-12 col-sm-12 col-md-6 col-lg-6 col-xl-6">
                                <div class="mb-4  form-floating">
                                    <input type="text" class="form-control" placeholder="First Name" name="f_name"
                                        value="{{ old('f_name') }}" required>
                                    <label for="">First Name </label>
                                    @if ($errors->has('f_name'))
                                        <div class="error text-danger">{{ $errors->first('f_name') }}</div>
                                    @endif
                                </div>
                            </div>
                            <div class="col-xs-12 col-sm-12 col-md-6 col-lg-6 col-xl-6">
                                <div class="mb-4  form-floating">
                                    <input type="text" class="form-control" id="" placeholder="Last Name"
                                        name="l_name" value="{{ old('l_name') }}" required>
                                    <label for="">Last Name </label>
                                    @if ($errors->has('l_name'))
                                        <div class="error text-danger">{{ $errors->first('l_name') }}</div>
                                    @endif
                                </div>
                            </div>
                            <div class="clear"></div>
                            <div class="col-xs-12 col-sm-12 col-md-6 col-lg-6 col-xl-6">
                                <div class="mb-4  form-floating">
                                    <input type="phone" class="form-control" id="" placeholder="phone"
                                        value="{{ old('mobile_no') }}" name="mobile_no" required>
                                    <label for="">Phone no </label>
                                    @if ($errors->has('mobile_no'))
                                        <div class="error text-danger">{{ $errors->first('mobile_no') }}</div>
                                    @endif
                                </div>
                            </div>
                            <div class="col-xs-12 col-sm-12 col-md-6 col-lg-6 col-xl-6">
                                <div class="mb-4  form-floating">
                                    <input type="Subject" min="0" class="form-control" id=""
                                        placeholder="Subject" name="subject" value="{{ old('subject') }}" required>
                                    <label for="">Subject </label>
                                    @if ($errors->has('subject'))
                                        <div class="error text-danger">{{ $errors->first('subject') }}</div>
                                    @endif
                                </div>
                            </div>
                            <div class="clear"></div>

                            <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 col-xl-12">
                                <div class="form-floating mb-4">
                                    <input name="message" class="form-control" type="textarea"
                                        placeholder="Leave a Message here" id="floatingInput"
                                        value="{{ old('message') }}" style="height: 8rem;">
                                    <label for="floatingTextarea">Message</label>
                                    @if ($errors->has('message'))
                                        <div class="error text-danger">{{ $errors->first('message') }}</div>
                                    @endif
                                </div>
                            </div>
                            <div class="clear"></div>
                            <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 col-xl-12">
                                <div class="form-group captcha" style="float: left; position: relative;width: 180px;">
                                    <span>{!! captcha_img('flat') !!}</span>
                                    <a href="javascript:void(0)" id="refresh"
                                        style="top: 7px; position: absolute; right: -22px; z-index: 2;"><i
                                            class="fas fa-sync-alt"></i></a>
                                </div>

                                <div class="form-floating mb-4" style="float: left; margin-left: 30px; width: 150px;">
                                    <input type="text" name="captcha" class="form-control" id="floatingInput"
                                        placeholder="{{ trans('contact.captcha') }}" value="{{ old('captcha') }}"
                                        required>
                                    @if ($errors->has('captcha'))
                                        <div class="error text-danger">{{ $errors->first('captcha') }}</div>
                                    @endif
                                    <label for="floatingInput">{{ trans('contact.captcha') }} *</label>
                                </div>
                                <div class="clear"></div>

                            </div>
                            <div class="col-lg-6 col-md-6">
                                <button type="submit" class="btn">{{ trans('contact.send-message') }}
                                </button>


                            </div>
                        </div>
                    </form>

                </div>
    </section>

@endsection
