    <!-- FOOTER--->
    <section id="footer" class="section-padding">
        <div class="container">
            <div class="row">
                <div class="col-xs-12  col-sm-12 col-md-6 col-lg-7 col-xl-8">
                    <div class="our-footer">
                        <h2 class="cns-title">{{ trans('general.subscribe-newsletter') }} </h2>

                    </div>
                </div>
                <div class="col-xs-12 col-sm-12 col-md-6  col-lg-5 col-xl-4">
                    <div class="input-group">
                        <input type="email" id="subscription-email" class="form-control"
                            placeholder="Your Email Address..." aria-label="Your Email Address..."
                            aria-describedby="basic-addon2">
                        <a href="#!" class="input-group-text subscribe" id="basic-addon2"> Subscribe </a>
                    </div>
                    <div class="d-none" id="message-subscription"></div>

                </div>
            </div>
            <div class="footerline"></div>
            <div class="row footer-details">
                <div class="col-xs-12 col-sm-12 col-lg-3">
                    <h6>{{ trans('general.contact-details') }}</h6>
                    <ul class="info-list">
                        @if (SettingHelper::multiLangSetting('address'))
                            <li>
                                {!! SettingHelper::multiLangSetting('address') !!}</li>
                        @endif
                        @if (SettingHelper::multiLangSetting('contact'))
                            <li>
                                <span>Tel : {!! SettingHelper::multiLangSetting('contact') !!}
                                </span>
                            </li>
                        @endif
                        @if (SettingHelper::multiLangSetting('fax'))
                            <li>
                                <span>Fax : <a href="tel : {!! SettingHelper::multiLangSetting('fax') !!}">{!! SettingHelper::multiLangSetting('fax') !!}</a>
                                </span>
                            </li>
                        @endif

                        @if (SettingHelper::setting('email_address'))
                            <li>
                                <span>E-mail : <a href="mailto:{!! SettingHelper::setting('email_address') !!}"> {!! SettingHelper::setting('email_address') !!} </a>
                                </span>
                            </li>
                        @endif


                    </ul>
                    <div class="social-network">
                        <ul class="social-icon">
                            @if (!empty(SettingHelper::setting('facebook')))
                                <li>
                                    <a class="" href="{!! SettingHelper::setting('facebook') !!}">
                                        <i class="fab fa-facebook-f"></i>
                                    </a>
                                </li>
                            @endif
                            @if (!empty(SettingHelper::setting('twitter')))
                                <li>
                                    <a class="" href="{!! SettingHelper::setting('twitter') !!}">
                                        <i class="fab fa-twitter"></i>
                                    </a>
                                </li>
                            @endif
                            @if (!empty(SettingHelper::setting('instagram')))
                                <li>
                                    <a class="" href="{!! SettingHelper::setting('instagram') !!}">
                                        <i class="fab fa-instagram"></i>
                                    </a>
                                </li>
                            @endif
                        </ul>
                    </div>
                </div>
                @if (!empty($widget1))

                    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-5">
                        <h6>{{ trans('general.quick-links') }}</h6>
                        <div class="row quick-link">
                            @foreach ($widget1['parent'] as $item)
                                <div class="col-xs-12 col-sm-6 col-md-6 col-lg-6">
                                    <a href="{{ $item['url'] ?? '' }}" {{ $item['target'] }}>{{ $item['title'] }} </a>
                                </div>
                            @endforeach

                        </div>
                    </div>
                @endif
                @if (!empty($widget2))
                    <div class="col-xs-12 col-sm-6 col-lg-2">
                        <h6>{{ trans('general.product-services') }}</h6>
                        <ul class="info-list">
                            @foreach ($widget2['parent'] as $item)
                                <li>
                                    <a href="{{ $item['url'] ?? '' }}" {{ $item['target'] }}>{{ $item['title'] }} </a>
                                </li>
                            @endforeach
                        </ul>
                    </div>
                @endif
                @if (!empty($widget3))
                    <div class="col-xs-12 col-sm-6 col-lg-2">
                        <h6>{{ trans('general.office-network') }}</h6>
                        <ul class="info-list">
                            @foreach ($widget3['parent'] as $item)
                                <li>
                                    <a href="{{ $item['url'] ?? '' }}" {{ $item['target'] }}>{{ $item['title'] }}
                                    </a>
                                </li>
                            @endforeach
                        </ul>
                    </div>
                @endif
            </div>
        </div>
        </div>
    </section>
    <!-- FOOTER--END-->
    <!-- BELOW-FOOTER -->
    <div class="below-footer">
        <div class="container">
            <div class="row">
                <div class="col-xs-12 col-sm-12 col-sm-6 col-lg-6">
                    <div class="copyright">
                        <span>© 2022, Swabalamban Laghubitta Bittiya Sanstha Ltd. All Rights Reserved. </span>
                    </div>
                </div>
                <div class="col-xs-12 col-sm-12 col-sm-6 col-lg-6">
                    <div class="link">
                        <span>Designed and maintained by <a href="#">Peace Nepal Dot Com </a>
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- BELOW-FOOTER -END---->

