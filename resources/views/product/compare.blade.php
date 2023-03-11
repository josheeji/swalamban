@extends('layouts.frontend.app')
@section('title', 'Product Comparasion')
@section('style')
@endsection
@section('script')
<script>
    $('.compare').on('change', function() {
        var p1 = $('#p1 option:selected').val();
        var p2 = $('#p2 option:selected').val();
        var p3 = $('#p3 option:selected').val();
        window.location.href = "{{route('product.compare') }}" + '?p1=' + p1 + '&p2=' + p2 + '&p3=' + p3;
    });
</script>
@endsection
@section('page-banner')
<section class="bannertop">
    <div class="container">
        <div class="bannerimg parallax">
            <h1>{{ trans('general.product-compare') }}</h1>
            <div class="banner-txt">{!! SettingHelper::setting('tagline') !!}</div>
            <ul class="header-bottom-navi">
                <li><a href="{{ route('home.index') }}">{{ trans('general.home') }}</a><i class="fas fa-chevron-right"></i></li>
                <li><a href="javascript:void(0);">{{ trans('general.product-compare') }}</a></li>
            </ul>
        </div>
    </div>
</section>
@endsection
@section('content')
<section class="maininner-container ">
    <div class="container">
        <div class="productcompare-top">{{ trans('general.select-the-products-you-want-to-compare') }}</div>
        <form class="">
            <div class="row">
                <div class="col-xs-12 col-sm-12 col-md-4 col-lg-4">
                    <div class="compareselect">
                        <select name="p1" id="p1" class="js-example-basic-single compare" name="state">
                            <option value="">{{ trans('general.select-product') }}</option>
                            @if(isset($products) && !$products->isEmpty())
                            @foreach($products as $product)
                            <option value="{{ $product->slug }}" {{ request()->has('p1') && request()->get('p1') == $product->slug ? 'selected' : '' }}>{{ $product->title }}</option>
                            @endforeach
                            @endif
                        </select>
                    </div>
                    <div class="produc-compare-detail">
                        @if(isset($product1) && $product1 != '')
                        <h3>{{ $product1->title }}</h3>
                        <p>{{ $product1->excerpt }}</p>
                        <ul class="">
                            <li><span class="font-weight-bold">{{ trans('general.interest-rate') }}: </span>{{ $product1->interest_rate == '' ? 'N/A' : $product1->interest_rate }}</li>
                            <li><span class="font-weight-bold">{{ trans('general.minimum-balance') }}: </span>{{ $product1->minimum_balance == '' ? 'N/A' : $product1->minimum_balance }}</li>
                            <li><span class="font-weight-bold">{{ trans('general.interest-payment') }}: </span>{{ $product1->interest_payment == '' ? 'N/A' : $product1->interest_payment }}</li>
                        </ul>
                        {!! $product1->feature !!}
                        <a href="{{ route('product.show', $product1->slug) }}" class="product-btn1">{!! trans('general.read-more') !!} <i class="fas fa-chevron-right"></i></a>
                        @else
                        {{ trans('general.no-record-found') }}
                        @endif
                    </div>
                </div>
                <div class="col-xs-12 col-sm-12 col-md-4 col-lg-4">
                    <div class="compareselect">
                        <select name="p2" id="p2" class="js-example-basic-single compare" name="state">
                            <option value="">{{ trans('general.select-product') }}</option>
                            @if(isset($products) && !$products->isEmpty())
                            @foreach($products as $product)
                            <option value="{{ $product->slug }}" {{ request()->has('p2') && request()->get('p2') == $product->slug ? 'selected' : '' }}>{{ $product->title }}</option>
                            @endforeach
                            @endif
                        </select>
                    </div>
                    <div class="produc-compare-detail">
                        @if(isset($product2) && $product2 != '')
                        <h3>{{ $product2->title }}</h3>
                        <p>{{ $product2->excerpt }}</p>
                        <ul class="">
                            <li><span class="font-weight-bold">{{ trans('general.interest-rate') }}: </span>{{ $product2->interest_rate == '' ? 'N/A' : $product2->interest_rate }}</li>
                            <li><span class="font-weight-bold">{{ trans('general.minimum-balance') }}: </span>{{ $product2->minimum_balance == '' ? 'N/A' : $product2->minimum_balance }}
                            <li>
                            <li><span class="font-weight-bold">{{ trans('general.interest-payment') }}: </span>{{ $product2->interest_payment == '' ? 'N/A' : $product2->interest_payment }}</li>
                        </ul>
                        {!! $product2->feature !!}
                        <a href="{{ route('product.show', $product2->slug) }}" class="product-btn1">{!! trans('general.read-more') !!} <i class="fas fa-chevron-right"></i></a>
                        @else
                        {{ trans('general.no-record-found') }}
                        @endif
                    </div>
                </div>
                <div class="col-xs-12 col-sm-12 col-md-4 col-lg-4">
                    <div class="compareselect">
                        <select name="p3" id="p3" class="js-example-basic-single compare" name="state">
                            <option value="">{{ trans('general.select-product') }}</option>
                            @if(isset($products) && !$products->isEmpty())
                            @foreach($products as $product)
                            <option value="{{ $product->slug }}" {{ request()->has('p3') && request()->get('p3') == $product->slug ? 'selected' : '' }}>{{ $product->title }}</option>
                            @endforeach
                            @endif
                        </select>
                    </div>
                    <div class="produc-compare-detail">
                        @if(isset($product3) && $product3 != '')
                        <h3>{{ $product3->title }}</h3>
                        <p>{{ $product3->excerpt }}</p>
                        <ul class="">
                            <li><span class="font-weight-bold">{{ trans('general.interest-rate') }}: </span>{{ $product3->interest_rate == '' ? 'N/A' : $product3->interest_rate }}</li>
                            <li><span class="font-weight-bold">{{ trans('general.minimum-balance') }}: </span>{{ $product3->minimum_balance == '' ? 'N/A' : $product3->minimum_balance }}</li>
                            <li><span class="font-weight-bold">{{ trans('general.interest-payment') }}: </span>{{ $product3->interest_payment == '' ? 'N/A' : $product3->interest_payment }}</li>
                        </ul>
                        {!! $product3->feature !!}
                        <a href="{{ route('product.show', $product3->slug) }}" class="product-btn1">{!! trans('general.read-more') !!} <i class="fas fa-chevron-right"></i></a>
                        @else
                        {{ trans('general.no-record-found') }}
                        @endif
                    </div>
                </div>
            </div>
        </form>
    </div>

    </div>
</section>
@endsection