@extends('layouts.frontend.app')
@section('title', $notice->title )
@section('style')

@endsection
@section('script')
@endsection
@section('page-banner')
<section class="bannertop">
    <div class="container">
        <div class="bannerimg parallax">
            <h1>{{ $notice->title }}</h1>
        </div>
    </div>
</section>
<section class="bredcrum-inner">
    <div class="container">
        <div class="titleblock-inner">
            <ul class="noticeul">
                <li>
                    <a href="{{ route('home.index') }}"><i class="fas fa-home"></i> Home</a> <i class="fas fa-chevron-right"></i>
                </li>
                <li><a href="#!">Notice & Publications<i class="fas fa-chevron-right"></i></a></li>
                <li><a href="{{route('tender-notice')}}">Procurement Notice<i class="fas fa-chevron-right"></i></a></li>
                <li>{{ $notice->title }}</li>
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
                @if(isset($notice->image) && file_exists('storage/thumbs/'.$notice->image) && $notice->show_image == 1)
                <figure class="post-image">
                    <img src="{{ asset('storage/thumbs/'.$notice->image) }}" alt="{{ $notice->title }}">
                </figure>
                @endif
                <ul class="innerdates">
                    @if($notice->start_date)
                    <li>
                        <span class="icon"><i class="icon-clock"></i></span> Publish Date:
                        <span>{{ Helper::formatDate($notice->start_date, 12) }}</span>
                    </li>
                    @endif
                    @if($notice->end_date)
                    <li>
                        <span class="icon"><i class="icon-clock"></i></span> Submission Date:
                        <span>{{ Helper::formatDate($notice->end_date, 12) }}</span>
                    </li>
                    @endif
                </ul>
                {!! $notice->description !!}
            </div>
            <div class="col-xs-12 col-sm-12 col-md-3 mainsidewrapper">
                <div class="sidebox">
                    <h2>{{ trans('general.other-tender-notice') }}</h2>
                </div>    
            
            
            
                @if(isset($latest) && !empty($latest))
                @foreach($latest as $item)
                <div class="box">
                    <article class="media">
                        <div class="media-content">
                            <div class="content">
                                <a href="{{ route('tender-notice.show', $item->slug) }}">{{ $item->title }}</a>
                                <p>{{ Helper::formatDate($item->start_date, 12) }}</p>
                            </div>
                        </div>
                    </article>
                </div>
                @endforeach
                @endif
            </div>
        </div>
    </div>
</section>
@endsection