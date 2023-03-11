@inject('helper', 'App\Helper\Helper')
@inject('layout', 'App\Helper\LayoutHelper')
@inject('menuRepo', 'App\Repositories\MenuRepository')
@inject('pageHelper', 'App\Helper\PageHelper')
@extends('layouts.frontend.app')
@section('title', $news->title)
@section('meta_keys', $news->title)
@section('meta_description', $news->title)
@section('script')
    {!! isset($schema) && !empty($schema) ? $schema : '' !!}
@endsection
@section('content')
    <!-- Title/Breadcrumb -->
    <section id="pagetitle" style="background-image:url({{isset($news->banner) ?  asset('storage/' . $news->banner) : asset('swabalamban/images/titlebg.jpg')  }});">
        <div class="container">
            <h1>{{ $news->title }}
            </h1>
            <ul>
                <li>
                    <a href="{{ route('home.index') }}">Home
                    </a>
                    <i class="fas fa-chevron-right">
                    </i>
                </li>
                <li>
                    <a href="{{ route('news.category',$category->slug) }}">{{ ucfirst($category->title) }}
                    </a>
                    <i class="fas fa-chevron-right">
                    </i>
                </li>
                <li>{{ $news->title }}
                </li>
            </ul>
        </div>
    </section>
    <!-- Title/Breadcrumb END -->
    <section id="internal" class="section-padding" style="padding-top: 0;">
        <div class="container">
            <div class="row">
                @include('layouts.frontend.inc.socialmedia')
                @include('layouts.frontend.inc.comments')
                @if ($news->layout == 3)
                <div class="col-xs-12 col-sm-12 col-md-12 col-lg-4 col-xl-4">
                    <div class="latestnews-events ">
                        <div class="cns-title">
                            <a href="javascript:void(0);" class="">Latest News and Events
                            </a>
                        </div>
                        <ul class="list-categories newseventsside">
                            
                        @foreach ($latest as $item)
                        <li>
                            <div class="notice-1">

                                <div class="notice-date">
                                    <span>On {{ date('d M, Y', strtotime($item->published_date)) }}
                                    </span>
                                    <a href="{{ route('news.show', ['category' => $item->cat_slug, 'slug' => $item->slug]) }}"
                                        class="time-update">{{ $item->published_date->diffForHumans() }}
                                    </a>
                                </div>
                                <div class="notice">
                                    <a href="{{ route('news.show', ['category' => $item->cat_slug, 'slug' => $item->slug]) }}"
                                        class="">{{ $item->title }}
                                    </a>
                                </div>
                            </div>
                            </li>
                        @endforeach
                        </ul>
                    </div>
                    <div class="d-categories">
                        <div class="cns-title">
                            <a href="javascript:void(0);" class="">Download Categories
                            </a>
                        </div>
                        <ul class="list-categories">
                            @foreach ($download_categories as $item)
                                <li>
                                    <a href="{{ route('download.show', $item->slug) }}">{{ $item->title }}
                                    </a>
                                </li>
                            @endforeach
                        </ul>
                    </div>
                </div>
                <div class="col-xs-12 col-sm-12 col-md-12 col-lg-8 col-xl-8">
                    <div class="leftsection">
                        <div class="promotion">
                            @if (file_exists('storage/' . $news->image) && !empty($news->image) && $news->show_image == 1)
                                <a href="javascript:void(0);" class="pro-gallery">
                                    <img src="{{ asset('storage/' . $news->image) }}" class="img-fluid"
                                        alt="{{ $news->title }}" title="{{ $news->title }}">
                                </a>
                            @endif
                            <a href="javascript:void(0)" class="prom-title">{{ $news->title }}
                            </a>
                            <div class="notice-date">
                                <span> On {{ date('d M, Y', strtotime($news->published_date)) }}
                                </span>
                                <a href="javascript:void(0);"
                                    class="time-update">{{ $news->published_date->diffForHumans() }}
                                </a>
                            </div>
                            <p>
                                {!! $news->description !!}
                            </p>

                        </div>
                    </div>
                </div>
                @else
                    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-8 col-xl-8">
                        <div class="leftsection">
                            <div class="promotion">
                                @if (file_exists('storage/' . $news->image) && !empty($news->image) && $news->show_image == 1)
                                    <a href="javascript:void(0);" class="pro-gallery">
                                        <img src="{{ asset('storage/' . $news->image) }}" class="img-fluid"
                                            alt="{{ $news->title }}" title="{{ $news->title }}">
                                    </a>
                                @endif
                                <a href="javascript:void(0)" class="prom-title">{{ $news->title }}
                                </a>
                                <div class="notice-date">
                                    <span> On {{ date('d M, Y', strtotime($news->published_date)) }}
                                    </span>
                                    <a href="javascript:void(0);"
                                        class="time-update">{{ $news->published_date->diffForHumans() }}
                                    </a>
                                </div>
                                <p>
                                    {!! $news->description !!}
                                </p>

                            </div>
                        </div>
                    </div>
                    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-4 col-xl-4">
                        <div class="latestnews-events">
                            <div class="cns-title">
                                <a href="javascript:void(0);" class="">Latest News and Events
                                </a>
                            </div>
                            
                            <ul class="list-categories newseventsside">
                            @foreach ($latest as $item)
                
                                    <li>
                                    <div class="notice-1">

<div class="notice-date">
    <span>On {{ date('d M, Y', strtotime($item->published_date)) }}
    </span>
    <a href="{{ route('news.show', ['category' => $item->cat_slug, 'slug' => $item->slug]) }}"
        class="time-update">{{ $item->published_date->diffForHumans() }}
    </a>
</div>
<div class="notice">
    <a href="{{ route('news.show', ['category' => $item->cat_slug, 'slug' => $item->slug]) }}"
        class="">{{ $item->title }}
    </a>
</div>
</div>
                                    </li>
                                
                                
                            @endforeach
                            </ul>
                        </div>
                        <div class="d-categories">
                            <div class="cns-title">
                                <a href="javascript:void(0);" class="">Download Categories
                                </a>
                            </div>
                            <ul class="list-categories">
                                @foreach ($download_categories as $item)
                                    <li>
                                        <a href="{{ route('download.show', $item->slug) }}">{{ $item->title }}
                                        </a>
                                    </li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                @endif
            </div>
        </div>
        </div>
        </div>
    </section>
@endsection
