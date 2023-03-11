@extends('layouts.frontend.app')
@section('title', 'Forex')
@section('style')

@endsection
@section('script')
@endsection
@section('page-banner')
<section class="bannertop">
    <div class="container">
        <div class="bannerimg parallax">
            <h1>{{ trans('forex.forex_heading') }}</h1>
            <div class="banner-txt"></div>
            <ul class="header-bottom-navi">
                <li>
                    <a href="{{ route('home.index') }}">{{ trans('general.home') }}</a><i class="fas fa-chevron-right"></i>
                </li>
                <li><a href="javascript:void(0);">{{ trans('forex.forex_heading') }}</a></li>
            </ul>
        </div>
    </div>
</section>
@endsection
@section('content')
<section class="maininner-container ">
    <div class="container">
        <div class="row">
            <div class="col-xs-12 col-sm-12">
                <form class="row forex-form"  method="get" action="{{ url()->full() }}">
                    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 currentdate"><p>Listing Existing Currency of Date: {{$date}}.</p></div>
                    <div class="form-group col-xs-12 col-sm-4 col-md-4 col-lg-4">
                        <input type="date" class="form-control" name="date" autocomplete="off" value="{{request('date') ?? date('Y-m-d')}}">
                    </div>
                    <div class="form-group col-xs-12 col-sm-4 col-md-4 col-lg-4">
                        <select name="currency" class="form-control">
                            <option value="">{{ trans('forex.select_currency') }}</option>
                            @foreach($currency as $item)
                            <option value="{{ $item['FXD_CRNCY_CODE'] }}" {{ (request('currency') == $item['FXD_CRNCY_CODE']) ? "selected" : "" }}>{{ $item['FXD_CRNCY_CODE'] }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group col-xs-12 col-sm-4 col-md-4 col-lg-4">
                        <button class="btn btn-success" type="submit">{{ trans('forex.find') }}</button>
                    </div>
                    
                </form>
            </div>
            @if(isset($forexes) && !$forexes->isEmpty())
            
            <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 maintxt">
                <div class="item wow fadeInUp ">
                    <div class="forex-block table-responsive">
                        <table class="forex-table table ">
                            <tbody>
                                <tr>
                                    <th class="forexcountry">Country</th>    
                                    <th style="width:10%;">Unit</th>
                                    <th>CB RATE FOR DENOMINATION LESS THAN 50</th>
                                    <th>CB RATE FOR DENOMINATION 50 & ABOVE AND NCB RATE</th>
                                    <th style="width:10%;">Sell</th>
                                </tr>
                                @foreach($forexes as $index => $forex)
                                <tr>
                                    
                                <td><div class="forex-country"><img src="{{ asset('frontend/images/flags/currency_flags/'.strtolower($forex->FXD_CRNCY_CODE).'.svg') }}" alt="icon">{{$forex->FXD_CRNCY_CODE}}</div></td>
                                    <td>{{ $forex->FXD_CRNCY_UNITS }}</td>
                                    <td>{{ $forex->BUY_RATE }}</td>
                                    <td>{{ $forex->BUY_RATE_ABOVE }}</td>
                                    <td>{{ $forex->SELL_RATE }}</td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            
            @else
            <div class="col-12">{{ trans('general.no-record-found') }}</div>
            @endif
        </div>
</section>
<!-- import content layouts and modules -->
<div id="content-main-wrap" class="is-clearfix d-none">
    <section class="section has-background-primary-light is-clearfix">
        <div class="container">
            <form method="get" action="{{ url()->full() }}">
                <div class="columns">
                    <div class="column is-3">
                        <div class="field">
                            <div class="control">
                                <div class="select">
                                    <input type="date" class="input" name="date" autocomplete="off" value="{{request('date') ?? date('Y-m-d')}}">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="column is-3">
                        <div class="field">
                            <div class="control">
                                <div class="select">
                                    <select name="currency">
                                        <option value="">{{ trans('forex.select_currency') }}</option>
                                        @foreach($currency as $item)
                                        <option value="{{ $item['FXD_CRNCY_CODE'] }}" {{ (request('currency') == $item['FXD_CRNCY_CODE']) ? "selected" : "" }}>{{ $item['FXD_CRNCY_CODE'] }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="column is-3">
                        <div class="field ">
                            <div class="control ">
                                <button class="button is-rounded" type="submit">{{ trans('forex.find') }}</button>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="columns">
                    <div class="column is-12" style="color: #007ea4;font-weight: bold;">
                        Listing Existing Currency of Date: {{$date}}. Indicative Rates Upto USD 2,000.00 Only.
                    </div>
                </div>
            </form>
            <br>
            <div class="columns  is-multiline  forex-list  ">
                @foreach($forex as $item)
                <div class="column is-3">
                    <div class="card">
                        <div class="card-content">
                            <div class="content">
                                <p class="title is-5"><img style="width: 20px;" src="{{ asset('kumari/images/flags/currency_flags/'.$item['FXD_CRNCY_CODE'].'.svg') }}"> {{ $item['forex_name'] }}
                                </p>
                                <div class=" forex-col"><strong>{{ trans('forex.unit') }}</strong> <br>{{ $item['FXD_CRNCY_UNITS'] }}
                                </div>
                                <div class=" forex-col"><strong>{{ trans('forex.buy') }}</strong> <br>{{ $item['BUY_RATE'] }}
                                </div>
                                <div class=" forex-col"><strong>{{ trans('forex.sell') }}</strong> <br>{{ $item['SELL_RATE'] }}
                                </div>
                                <div class="clear"></div>
                            </div>
                        </div>
                    </div>
                </div> <!-- column is 4 end -->
                @endforeach
                <div class="clear"></div>
            </div>
        </div>
    </section>
    <!-- .content-with-sidebar -->
</div>
<!-- #content-main-wrap -->
@endsection