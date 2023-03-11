@extends('layouts.frontend.app')
@section('title', 'Calculators' )
@section('style')

@endsection
@section('script')
<script>
    $('#bonusshare-form').on('submit', function(e) {
        e.preventDefault();
        var marketPrice = $('#market-price').val();
        var percent = $('#percent').val();
        var parValue = 0;
        parValue = (1 + (percent / 100))
        var value = parseFloat(marketPrice);
        parValue = value / parValue;
        $('.market-value-wrap').removeClass('hidden');
        $('#market-value').val(round2Fixed(parValue));
    });

    function round2Fixed(value) {
        value = +value;

        if (isNaN(value))
            return NaN;

        // Shift
        value = value.toString().split('e');
        value = Math.round(+(value[0] + 'e' + (value[1] ? (+value[1] + 2) : 2)));

        // Shift back
        value = value.toString().split('e');
        return (+(value[0] + 'e' + (value[1] ? (+value[1] - 2) : -2))).toFixed(2);
    }
    $('.clear-btn').on('click', function() {
        $('.market-value-wrap').addClass('hidden');
    });
</script>
@endsection
@section('page-banner')
<section class="bannertop">
    <div class="container">
        <div class="bannerimg parallax">
            <h1>Calculators</h1>
        </div>
    </div>
</section>
<section class="bredcrum-inner">
    <div class="container">
        <div class="titleblock-inner">
            <ul>
                <li><a href="{{ route('home.index') }}"><i class="fas fa-home"></i> Home</a> <i class="fas fa-chevron-right"></i></li>
                <li>Calculators</li>
            </ul>
        </div>
    </div>
</section>
@endsection
@section('content')
<section id="inner-content">
    <div class="container">
        <div class="row">
            <div class="col-xs-12 col-sm-12 col-md-9 maintxt">
            </div>
            <div class="col-xs-12 col-sm-12 col-md-3 mainsidewrapper">
                <div class="col-xs-12 col-sm-6 col-md-12">
                    <div class="sidebox">
                        <h2>Calculators</h2>
                        @php
                        $calcMenu = PageHelper::getMenu("calculator-menu");
                        @endphp
                        @if(isset($calcMenu) && !empty($calcMenu))
                        <ul class="abtlist">
                            @php
                            static $i = 0;
                            @endphp
                            @foreach($calcMenu['parent'] as $item)
                            <li class="{{ $i == 0 ? 'active-list' : '' }}">
                                <a href="{!! $item['url'] !!}" {{ $item['target'] == 1 ? 'target="_blank"' : '' }}><i class="fas fa-chevron-right"></i> {!! $item['title'] !!}</a>
                            </li>
                            @php
                            $i++;
                            @endphp
                            @endforeach
                        </ul>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection