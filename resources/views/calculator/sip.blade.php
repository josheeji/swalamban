@extends('layouts.frontend.app')
@section('title', 'Calculator' )
@section('style')

@endsection
@section('script')
<script>
    function validateSip() {
        var monthlyInvestment = $('#monthly-investment').val();
        var periodInYears = $('#period-in-years').val();
        var periodInMonths = 0;
        var esitmatedReturnRate = $('#estimated-return-rate').val();
        var monthlyReturnRate = 0;
        var totalInvestment = 0;
        var maturity = 0;
        var estimatedReturn = 0;

        periodInMonths = periodInYears * 12;
        $('#period-in-months').val(periodInMonths);

        monthlyReturnRate = (esitmatedReturnRate / 100) / 12;
        $('#monthly-rate-of-return').val(monthlyReturnRate);

        totalInvestment = monthlyInvestment * periodInYears * 12;
        $('#total-investment').val(totalInvestment);
    }

    $('#sip-form').on('submit', function(e) {
        e.preventDefault();
        var form = $(this);
        var url = form.attr('action');

        $.ajax({
            type: "POST",
            url: url,
            data: form.serialize(),
            dataType: 'json',
            success: function(data) {
                if (data) {
                    $('#maturity').val(data.maturity);
                    $('.maturity-wrap').removeClass('hidden');
                    $('#estimated-return').val(data.estimatedReturn);
                    $('.estimated-wrap').removeClass('hidden');
                }
            }
        });

    })
</script>
@endsection
@section('page-banner')
<section class="bannertop">
    <div class="container">
        <div class="bannerimg parallax">
            <h1>SIP</h1>
        </div>
    </div>
</section>
<section class="bredcrum-inner">
    <div class="container">
        <div class="titleblock-inner">
            <ul>
                <li><a href="{{ route('home.index') }}"><i class="fas fa-home"></i> Home</a> <i class="fas fa-chevron-right"></i></li>
                <li><a href="{{ route('calculator.index') }}">Calculators</a> <i class="fas fa-chevron-right"></i></li>
                <li>SIP</li>
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
                <form method="post" action="{{ route('calculator.calculate-sip') }}" id="sip-form" autocomplete="off">
                    <div class="row">
                        <div class="col-xs-12 col-sm-6 col-md-6">
                            <div class="form-group">
                                <label>Monthly Investment(Rs)</label>
                                <input class="form-control" type="number" min="0" id="monthly-investment" name="monthly_investment" placeholder="Numbers Only " required>
                            </div>
                        </div>
                        <div class="col-xs-12 col-sm-6 col-md-6">
                            <div class="form-group">
                                <label>Period in Years</label>
                                <input class="form-control" type="number" min="0" id="period-in-years" name="period_in_years" placeholder="Numbers Only" onChange="validateSip()" required>
                            </div>
                        </div>
                    </div>
                    <!-- /row -->
                    <div class="row">
                        <div class="col-xs-12 col-sm-6 col-md-6">
                            <div class="form-group">
                                <label>Period in Months</label>
                                <input class="form-control" type="number" min="0" id="period-in-months" name="period_in_months" placeholder="Numbers Only " required>
                            </div>
                        </div>
                        <div class="col-xs-12 col-sm-6 col-md-6">
                            <div class="form-group">
                                <label>Estimated Return Rate(%)</label>
                                <input class="form-control" type="number" min="0" id="estimated-return-rate" name="estimated_return_rate" placeholder="Numbers Only " onChange="validateSip()" required>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-xs-12 col-sm-6 col-md-6">
                            <div class="form-group">
                                <label>Monthly Rate of Return</label>
                                <input class="form-control" type="decimal" id="monthly-rate-of-return" name="monthly_rate_of_return" placeholder="Numbers Only " required>
                            </div>
                        </div>
                        <div class="col-xs-12 col-sm-6 col-md-6">
                            <div class="form-group">
                                <label>Total Investment</label>
                                <input class="form-control" type="number" min="0" id="total-investment" name="total_investment" placeholder="Numbers Only " required>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-xs-12 col-sm-6 col-md-6">
                            <div class="maturity-wrap form-group hidden">
                                <label>Amount you receive in Maturity(Rs)</label>
                                <input class="form-control" type="text" min="0" id="maturity" name="maturity" placeholder="Numbers Only " readonly disabled>
                            </div>
                        </div>
                        <div class="col-xs-12 col-sm-6 col-md-6">
                            <div class="estimated-wrap form-group hidden">
                                <label>Estimated Return(Rs)</label>
                                <input class="form-control" type="text" min="0" id="estimated-return" name="estimated_return" placeholder="Numbers Only " readonly disabled>
                            </div>
                        </div>
                    </div>

                    <p class="add_top_30"><input type="submit" value="Calculate" class="btn_1 rounded btn" id="">
                        <input type="reset" value="Clear" class="btn_1 rounded btn clear-btn" id=""></p>

                </form>
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