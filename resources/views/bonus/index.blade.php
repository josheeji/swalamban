@extends('layouts.frontend.app')
@section('title', 'Right Shares')
@section('script')
<script type="text/javascript">
    $('.bonus-form').on('submit', function(e) {
        e.preventDefault();
        $('.bonus-form button').attr('disabled', true);
        $.ajax({
            type: 'POST',
            data: $('.bonus-form').serialize(),
            url: "{{ route('bonus.search') }}",
            success: function(data) {
                $(".result-wrap").html(data);
                $('.bonus-form button').removeAttr('disabled');
            }
        });
    });
</script>
@endsection
@section('content')
<section class="bannertop">
    <div class="container">
        <div class="bannerimg parallax">
            <h1>Right Eligibility</h1>
        </div>
    </div>
</section>
<section class="bredcrum-inner">
    <div class="container">
        <div class="titleblock-inner">
            <ul>
                <li>
                    <a href="{{ route('home.index') }}"><i class="fas fa-home"></i> Home</a> <i class="fas fa-chevron-right"></i>
                </li>
                <li>Right Eligibility</li>
            </ul>
        </div>
    </div>
</section>

<section class="maininner-container">
    <div class="container">
        <div class="row">
            <div class="col-xs-12 col-sm-12 col-md-9 maintxt">
                @include('layouts.frontend.inc.alert')
                <form class="default-form bonus-form" method="post" action="{{ route('bonus.search') }}">
                    <div class="row">
                        <div class="col-xs-12 col-sm-12 col-md-12 form-group">
                            <label for="">Company Name</label>
                            <select name="category_id" id="" class="form-control" required>
                                @if(isset($categories) && !empty($categories))
                                @foreach($categories as $category)
                                <option value="{{ $category->id }}">{{ $category->title }}</option>
                                @endforeach
                                @endif
                            </select>
                        </div>

                        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 form-group">
                            <label for="">{{ trans('contact.full-name') }}</label>
                            <input class="form-control" type="text" name="name" placeholder="" required>
                        </div>

                        <div class="col-xs-12 col-sm-12 col-md-6 col-lg-6 form-group hidden">
                            <label for="">Fathers Name</label>
                            <input class="form-control" type="text" name="" placeholder="">
                        </div>

                        <div class="col-xs-12 col-sm-12 col-md-6 col-lg-6 form-group">
                            <label for="">BOID (16 digits)</label>
                            <input class="form-control" type="text" name="boid" placeholder="" required>
                        </div>

                        <div class="col-xs-12 col-sm-12 col-md-6 col-lg-6 form-group">
                            <label for="">Shareholder No.</label>
                            <input class="form-control" type="text" name="shareholder_no" placeholder="">
                        </div>

                        <div class="col-xs-12 col-sm-12 col-md-6 col-lg-6 form-group">
                            <button class="btn mr10" type="submit">Submit</button>
                            <button class="btn" type="reset">Clear</button>
                        </div>

                    </div>
                </form>
                <div class="result-wrap">
                    @include('bonus.search')
                </div>
            </div>

            <div class="col-xs-12 col-sm-12 col-md-3 mainsidewrapper">
                <div class="row">

                    <div class="col-xs-12 col-sm-6 col-md-12">
                        <div class="sidebox">
                            <h2>
                                {{ trans('general.press-release') }} <a href="{{ route('press-release') }}" class="customview">View All <i class="fas fa-chevron-right"></i></a>
                            </h2>
                            @php
                            $pressReleases = PageHelper::pressReleases();
                            @endphp
                            @if(isset($pressReleases))
                            <ul class="noticesection">
                                @foreach($pressReleases as $pressRelease)
                                <li>
                                    <div class="noticedate">{{ Helper::formatDate($pressRelease->start_date, 13) }}<span>{{ Helper::formatDate($pressRelease->start_date, 14) }}</span></div>
                                    <a href="{{ route('press-release.show', $pressRelease->slug) }}">{{ $pressRelease->title }}</a>
                                </li>
                                @endforeach
                            </ul>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection