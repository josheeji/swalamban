@extends('layouts.frontend.app')
@section('title', 'Calculator' )
@section('style')

@endsection
@section('script')
<script>
    $('#transaction-type').on('change', function() {
        var type = $(this).val();
        if (type == 'sell') {
            $('.investor-type-wrap').removeClass('hidden');
            $('#investor-type').attr('required', 'required');
            $('.selling-price-wrap').removeClass('hidden');
            $('#selling-price').attr('required', 'required');
        }
        if (type == 'buy') {
            $('.investor-type-wrap').addClass('hidden');
            $('#investor-type').removeAttr('required');
            $('.selling-price-wrap').addClass('hidden');
            $('#selling-price').removeAttr('required');
        }
    });

    $('#buysell-form').on('submit', function(e) {
        e.preventDefault();
        var form = $(this);
        var url = form.attr('action');
        $.ajax({
            type: "POST",
            url: url,
            data: form.serialize(),
            dataType: 'html',
            success: function(data) {
                if (data) {
                    $('.result').html(data);
                }
            }
        });
    });

    $(document).ready(function() {
        $('.clear-btn').on('click', function() {
            $('.investor-type-wrap').addClass('hidden');
            $('#investor-type').removeAttr('required');
            $('.selling-price-wrap').addClass('hidden');
            $('#selling-price').removeAttr('required');
            $('.result').html('');
        });
    });
</script>
@endsection
@section('page-banner')
<section class="bannertop">
    <div class="container">
        <div class="bannerimg parallax">
            <h1>Buy Sell</h1>
        </div>
    </div>
</section>
<section class="bredcrum-inner">
    <div class="container">
        <div class="titleblock-inner">
            <ul>
                <li><a href="{{ route('home.index') }}"><i class="fas fa-home"></i> Home</a> <i class="fas fa-chevron-right"></i></li>
                <li><a href="{{ route('calculator.index') }}">Calculators</a> <i class="fas fa-chevron-right"></i></li>
                <li>Buy Sell</li>
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
                <form method="post" action="{{ route('calculator.calculate-buysell') }}" id="buysell-form" autocomplete="off">
                    <div class="row">
                        <div class="col-xs-12">
                            <div class="form-group">
                                <label>Transaction Type</label>
                                <select name="type" id="transaction-type" class="form-control">
                                    <option value="buy">Buy</option>
                                    <option value="sell">Sell</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <!-- /row -->
                    <div class="row">
                        <div class="col-xs-12 col-sm-6 col-md-6 investor-type-wrap hidden">
                            <div class="form-group">
                                <label>Investor Type</label>
                                <select name="investor_type" id="investor-type" class="form-control">
                                    <option value="1">Individual</option>
                                    <option value="2">Institutional</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-xs-12 col-sm-6 col-md-6">
                            <div class="form-group">
                                <label>No. of units</label>
                                <input class="form-control" type="number" min="0" id="no-of-units" name="no_of_units" placeholder="Numbers Only " required>
                            </div>
                        </div>
                        <div class="col-xs-12 col-sm-6 col-md-6">
                            <div class="form-group">
                                <label>Buying Price</label>
                                <input class="form-control" type="decimal" id="buying-price" name="buying_price" placeholder="Numbers Only " required>
                            </div>
                        </div>
                        <div class="col-xs-12 col-sm-6 col-md-6 selling-price-wrap hidden">
                            <div class="form-group">
                                <label>Selling Price</label>
                                <input class="form-control" type="number" min="0" id="selling-price" name="selling_price" placeholder="Numbers Only ">
                            </div>
                        </div>
                    </div>

                    <p class="add_top_30"><input type="submit" value="Calculate" class="btn_1 rounded btn" id="">
                        <input type="reset" value="Clear" class="btn_1 rounded btn clear-btn" id=""></p>
                </form>
                <div class="result"></div>
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
                            @foreach($calcMenu['parent'] as $item)
                            <li class="{{ $item['title'] == 'Share Calculator' ? 'active-list' : '' }}">
                                <a href="{!! $item['url'] !!}" {{ $item['target'] == 1 ? 'target="_blank"' : '' }}><i class="fas fa-chevron-right"></i> {!! $item['title'] !!}</a>
                            </li>
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