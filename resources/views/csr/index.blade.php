@extends('layouts.frontend.app')
@section('title', 'News & Events' )
@section('style')

@endsection
@section('script')

@endsection
@section('page-banner')
<section class="bannertop">
    <div class="container">
        <div class="bannerimg parallax">
            <h1>{{ trans('general.csr') }}</h1>
            <div class="banner-txt"></div>
            <ul class="header-bottom-navi">
                <li><a href="{{ route('home.index') }}">{{ trans('general.home') }}</a><i class="fas fa-chevron-right"></i></li>
                <li><a href="javascript:void(0);">{{ trans('general.csr') }}</a></li>
            </ul>
        </div>
    </div>
</section>
@endsection
@section('content')
<section class="maininner-container ">
    <div class="container">
        <div class="row">
            @if(isset($news) && !$news->isEmpty())
            @foreach($news as $data)
            @php
            $url = route('csr.show', $data->slug);
            @endphp
            <div class="col-xs-12 col-sm-6 col-md-4 col-lg-4">
                <div class="inner-blog-list">
                    <a class="inner-blog-img" href="{{ $url }}">
                        @if(file_exists('storage/thumbs/'.$data->image) && $data->image != '')
                        <img src="{{ asset('storage/thumbs/'. $data->image) }}" alt="">
                        @else
                        <img src="{{ asset('frontend/images/no-img.jpg') }}" alt="">
                        @endif
                    </a>
                    <div class="blog-box">
                        <div class="entry-header">
                            <div class="post-meta">{{ Helper::formatDate($data->published_date) }}</div>
                            <a href="{{ $url }}" class="blogtitles">{{ $data->title }}</a>
                        </div>
                        <div class="blog-txt-inner">{{ Helper::ShortText($data->excerpt, 60) }}</div>
                        <a href="{{ $url }}" class="btn-footer">{{ trans('general.read-more') }} <i class="fas fa-chevron-right"></i></a>
                    </div>
                </div>
            </div>
            @endforeach
            @if(isset($news) && !empty($news) && !$news->isEmpty($news))
            {!! $news->appends(request()->query())->links('layouts.frontend.inc.pagination') !!}
            @endif
            @else
            <div class="col-12">{{ trans('general.no-record-found') }}</div>
            @endif
        </div>
    </div>
</section>
@endsection