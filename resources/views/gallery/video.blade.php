@extends('layouts.frontend.app')
@section('title', 'Video Gallery')
@section('meta_keys', 'ican,video,galelry')
@section('meta_description', 'video')
@section('style')

@endsection
@section('page-banner')

@endsection
@section('content')
    <!-- Title/Breadcrumb -->
    <section id="pagetitle"
        style="background-image:url({{ isset($menu->image) ? asset('storage/' . @$menu->image) : asset('swabalamban/images/titlebg.jpg') }});">
        <div class="container">
            <h1>{{ @$menu->title ?? 'Videos' }}</h1>
            <ul>
                <li><a href="{{ route('home.index') }}">{{ trans('general.home') }}</a><i class="fas fa-chevron-right"></i>
                </li>
                <li>{{ @$menu->title ?? 'Videos' }} </li>
            </ul>
        </div>
    </section>
    <!-- Title/Breadcrumb END -->

    <section class="main-container" id="main-container">
        <div class="container">
            <div class="row">
                @if (isset($videos) && !empty($videos) && !$videos->isEmpty())
                    @foreach ($videos as $video)
                        <div class="col-md-4">
                            <p>
                                <iframe width="100%" height="250" src="{{ $video->link }}" frameborder="0"
                                    allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture"
                                    allowfullscreen></iframe>
                            </p>
                        </div>
                    @endforeach
                    {!! $videos->appends(request()->query())->links('vendor.pagination.custom') !!}
                @else
                    <div class="col-12">{{ trans('general.no-record-found') }}</div>
                @endif
            </div>
            <!-- row end -->
        </div>
    </section>
@endsection
