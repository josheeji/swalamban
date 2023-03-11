@inject('helper', 'App\Helper\Helper')
@inject('layout', 'App\Helper\LayoutHelper')
@inject('menuRepo', 'App\Repositories\MenuRepository')
@extends('layouts.frontend.app')
@section('title', 'Feature Account Types' )
@section('style')
<style>
    .hero-body::before {
        background: url("{{ asset('kumari/images/page-title/featured-bg.jpg') }}") no-repeat center top;
        background-size: cover
    }

    .blog-post {
        position: relative;
    }

    .opennow {
        position: absolute;
        margin-top: -48px;
        right: 20px;
    }

    .opennow a {
        font-size: 13px;
    }

    .opennow .button.is-rounded {
        padding-right: 1.5rem;
        padding-left: 1.5rem
    }

    .featured-bg {
        background: url("{{asset('kumari/images/featured-bg.jpg') }}") no-repeat center center;
        height: 450px;
        background-size: cover
    }

    .featured-wrap {
        margin-top: -200px;
    }

    .hero_title {
        color: #fff !important;
        margin-bottom: 30px !important;
        line-height: 1.6 !important
    }

    .hero_title span {
        color: #fff !important;
    }

    .page-title .button {
        background: #f8c300;
        color: #111;
    }

    .hero_content_info .button {

        background: #f8c300 !important;
        color: #111 !important;
    }

    .hero_content_info .btnwhite {

        background: #fff !important;
        color: #007ea4 !important;
        margin-right: 8px;
        margin-bottom: 10px;
    }

    .blog-post .entry-footer .button::after {
        display: none;
    }

    .blog-list.style-2 .blog-post .entry-content {
        min-height: 58px;
        overflow: hidden;
    }

    .blog-list.style-2 .blog-post .entry-content p,
    .blog-list.style-2 .blog-post .entry-footer p {
        font-size: 0.75rem;
        margin-bottom: 0; height: 40px; overflow: hidden;
    }

    .hero_content_info {margin-top: 45px;}

         @media (min-width: 769px) and (max-width: 1300px) {
.column.is-3 {width: 33%}
.blog-post {height: 440px;}

     }
</style>
@if(isset($page) && !empty($page->banner))
<style>
    .featured-bg {
        background: url("{{asset('storage/'. $page->banner) }}") no-repeat center center;
        height: 450px;
        background-size: cover
    }
</style>
@endif
@endsection
@section('script')
@endsection
@section('content')
<div id="header-bottom-wrap" class="is-clearfix">
    <div id="header-bottom" class="site-header-bottom">
        <div id="header-bottom-inner" class="site-header-bottom-inner ">
            <div class="container">
                <div class="hero_content_info">
                    <div class="columns">
                        <div class="column is-6 is-6-desktop is-8-tablet">
                            <div class="hero_title">
                                @if(isset($page) && !empty($page))
                                {!! $page->excerpt !!}
                                @endif
                            </div>
                            @if(!empty($page->link))
                            <a href="{{ $page->link }}" class="button is-rounded btnwhite" target="_blank">{{ trans('general.access-your-application-status') }}</a>
                            @endif
                            <a href="{{ url('/products') }}" class="button is-rounded">View all account types</a>
                            <div class="clear"></div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- contaier end -->
            <section>
                <div class="featured-bg"></div>
            </section>
            <!-- .slider -->
        </div>
        <!-- #header-bottom-inner -->
    </div>
    <!-- #header-bottom -->
</div>
<!-- #header-bottom-wrap -->
<div id="content-main-wrap" class="is-clearfix">
    <div id="content-area" class="site-content-area">
        <div id="content-area-inner" class="site-content-area-inner">
            <section class="section  has-background-primary-light is-clearfix">
                <div class="container">
                    <div class="featured-wrap">
                        <div class="blog-list style-2  columns is-variable is-4 is-multiline">
                            @if(isset($products) && !empty($products) && !$products->isEmpty())
                            @foreach($products as $product)
                            <div class="column is-3">
                                <article class="blog-post">
                                    <figure class="post-image">
                                        <a href="{!! url('/products/'.$product->slug) !!}">
                                            @if(!empty($product->image) &&file_exists('storage/thumbs/'.$product->image) )
                                            <img src="{{ asset('storage/thumbs/'. $product->image) }}" alt="Image of {{$product->title}}">
                                            @else
                                            <img src="{{ asset('frontend/images/noimage.JPG') }}" alt="Default Image for Products">
                                            @endif
                                        </a>
                                    </figure>
                                    <div class="entry-header">
                                        <h2 class="entry-title"> <a href="{!! url('/products/'.$product->slug) !!}">{!! $product->title !!}</a> </h2>
                                    </div>
                                    <!-- .entry-header -->
                                    <div class="entry-content">
                                        <p>{!! $helper::shortText($product->excerpt) !!}</p>
                                    </div>
                                    <!-- .entry-content -->

                                    <div class="entry-footer">
                                        <a href="{!! url('/products/'.$product->slug) !!}" class="button">{{ trans('general.read-more') }}</a>
                                    </div>
                                    @if(isset($product->link) && !empty($product->link))
                                    <div class="opennow">
                                        <a target="_blank" href="{{ $product->link }}" class="button is-rounded" target="_blank">{{ trans('general.open-now') }}</a>
                                    </div>
                                    @endif
                                </article>
                                <!-- .blog-post -->
                            </div>
                            @endforeach
                            @endif
                            <div class="clear"></div>
                        </div>

                        <div class="has-text-centered">
                            <a href="{{ url('/products') }}" class="button is-rounded ">{{ trans('general.view-all-account-types') }}</a>
                        </div>
                    </div>
                </div>
            </section>
        </div>
        <!-- #content-area-inner -->
    </div>
    <!-- #content-area -->
</div>
@endsection