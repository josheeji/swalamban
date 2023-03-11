@extends('layouts.frontend.app')
@section('title', @$menu->title ?? 'Success Stories')
@section('meta_keys', @$menu->title ?? 'Success Stories')
@section('meta_description', @$menu->title ?? 'Success Stories')

@section('content')
    <!-- Title/Breadcrumb -->
    <section id="pagetitle"
        style="background-image:url({{ isset($menu->image) ? asset('storage/' . @$menu->image) : asset('swabalamban/images/titlebg.jpg') }});">
        <div class="container">
            <h1>{{ @$menu->title ?? 'Success Stories' }}</h1>
            <ul>
                <li><a href="{{ route('home.index') }}">Home</a><i class="fas fa-chevron-right"></i></li>
                <li>{{ @$menu->title ?? 'Success Stories' }} </li>
            </ul>
        </div>
    </section>
    <!-- Title/Breadcrumb END -->
    <section id="success-stories" class="section-padding" style="padding-top: 0;">
        <div class="container">
            <div class="row">
                @include('layouts.frontend.inc.socialmedia')
                @include('layouts.frontend.inc.comments')
                @foreach ($blogs as $item)
                    <div class="col-xs-12 col-sm-12 col-md-6 col-lg-4 col-xl-4 ">
                        <div class="item">
                            <div class="news-update">
                                <div class="publish-date">
                                    <span>{{ date('d M, Y', strtotime($item->published_date)) }}
                                    </span>
                                </div>
                                <div class="news-title notice">
                                    <a href="{{ route('stories.detail', $item->slug) }} " class="">{{ $item->title }}
                                    </a>
                                    @if (now()->subDays(7)->format('y-m-d') <= $item->published_date->format('y-m-d'))
                                        <span>New
                                        </span>
                                    @endif
                                </div>
                                <p>{!! str_limit($item->excerpt ?? $item->description, 100) !!}</p>
                                <div class="read-more">
                                    <a href="{{ route('stories.detail', $item->slug) }} " class="">
                                        {{ trans('general.read-more') }}
                                        <i class="fal fa-plus">
                                        </i>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
                {{ $blogs->links('vendor.pagination.custom') }}
            </div>
        </div>
    </section>
@endsection
