@extends('layouts.frontend.app')
@section('title', $product->title)
@section('meta_description', strip_tags($product->excerpt))
@section('content')
    <section id="pagetitle"
        style="background-image:url({{ isset($product->banner) ? asset('storage/' . @$product->banner) : asset('swabalamban/images/titlebg.jpg') }});">
        <div class="container">
            <h1>{{ @$product->title }}</h1>
            <ul>
                <li><a href="{{ route('home.index') }}">Home</a><i class="fas fa-chevron-right"></i></li>
                <li><a href="{{ route('product.index') }}">{{trans('general.product-and-services')}}</a><i class="fas fa-chevron-right"></i></li>
                {{-- <li><a href="{{ route('product.category', [$product->category->slug]) }}">{!! $product->Category->title !!}</a><i
                        class="fas fa-chevron-right"></i></li> --}}
                <li>{{ @$product->title }}</li>
            </ul>
        </div>
    </section>
    <!-- inner content start -->
    <section id="inner-contanier">
        <div class="container">
            <div class="row">
                @include('layouts.frontend.inc.socialmedia')
                @include('layouts.frontend.inc.comments')
                @if ($product->layout == 2)
                    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-4 col-xl-4">
                        @include('product._aside')
                    </div>
                    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-8 col-xl-8">
                        @include('product._content')
                    </div>
                @elseif($product->layout == 3)
                    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-8 col-xl-8">
                        @include('product._content')
                    </div>
                    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-4 col-xl-4">
                        @include('product._aside')
                    </div>
                @else
                    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 col-xl-12">
                        @include('product._content')
                    </div>
                @endif
            </div>
        </div>
    </section>
@endsection
