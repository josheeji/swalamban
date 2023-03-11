@extends('layouts.frontend.app')
@section('title', 'Calculator' )
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
            <h1>Bonus Share</h1>
        </div>
    </div>
</section>
<section class="bredcrum-inner">
    <div class="container">
        <div class="titleblock-inner">
            <ul>
                <li><a href="{{ route('home.index') }}"><i class="fas fa-home"></i> Home</a> <i class="fas fa-chevron-right"></i></li>
                <li><a href="{{ route('calculator.index') }}">Calculators</a> <i class="fas fa-chevron-right"></i></li>
                <li>Bonus Share</li>
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
                <form method="post" action="" id="bonusshare-form" autocomplete="off">
                    <div class="col-xs-12 col-sm-6 col-md-6">
                        <div class="form-group">
                            <label>Market Price (Before Book Closure)</label>
                            <input class="form-control" type="number" min="0" id="market-price" name="market_price" placeholder="Numbers Only" required>
                        </div>
                    </div>
                    <div class="col-xs-12 col-sm-6 col-md-6">
                        <div class="form-group">
                            <label>% of Bonus Share</label>
                            <input class="form-control" type="number" min="0" id="percent" name="percent" placeholder="Numbers Only" required>
                        </div>
                    </div>
                    <div class="col-xs-12 col-sm-12 col-md-12 market-value-wrap hidden">
                        <div class="form-group">
                            <label>Market price after bonus share (Rs)</label>
                            <input class="form-control" type="number" min="0" id="market-value" name="market_value" placeholder="Numbers Only" readonly disabled>
                        </div>
                    </div>
                    <div class="col-xs-12 col-sm-6 col-md-6">
                        <p class="add_top_30"><input type="submit" value="Calculate" class="btn_1 rounded btn" id="">
                            <input type="reset" value="Clear" class="btn_1 rounded btn clear-btn" id=""></p>
                    </div>
                </form>
            </div>
            <div class="col-xs-12 col-sm-12 col-md-3 mainsidewrapper">
                <div class="col-xs-12 col-sm-6 col-md-12">
                    <div class="sidebox">
                        <h2>Calculator Category</h2>
                        @php
                        $calcMenu = PageHelper::getMenu("calculator-menu");
                        @endphp
                        @if(isset($calcMenu) && !empty($calcMenu))
                        <div class="sidebox">
                            <h2>Calculators</h2>
                            @php
                            $calcMenu = PageHelper::getMenu("calculator-menu");
                            @endphp
                            @if(isset($calcMenu) && !empty($calcMenu))
                            <ul class="abtlist">
                                @foreach($calcMenu['parent'] as $item)
                                <li class="{{ $item['title'] == 'Bonus Share Adjustment' ? 'active-list' : '' }}">
                                    <a href="{!! $item['url'] !!}" {{ $item['target'] == 1 ? 'target="_blank"' : '' }}><i class="fas fa-chevron-right"></i> {!! $item['title'] !!}</a>
                                </li>
                                @endforeach
                            </ul>
                            @endif
                        </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection