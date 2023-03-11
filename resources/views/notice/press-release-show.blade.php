@extends('layouts.frontend.app')
@section('title', $notice->title)
@section('meta_keys', $notice->title)
@section('meta_description', $notice->title)
@section('style')

@endsection
@section('script')

@endsection
@section('content')

    <section id="pagetitle" style="background-image:url(images/titlebg.jpg);">
        <div class="container">
            <h1>{{ trans('general.notice') }}</h1>
            <ul>
                <li><a href="{{ route('home.index') }}">Home</a><i class="fas fa-chevron-right"></i></li>
                <li><a href="{{ route('press-release') }}">{{ trans('general.notice') }}</a><i
                        class="fas fa-chevron-right"></i></li>
                <li>{!! $notice->title !!}</li>
            </ul>
        </div>
    </section>
    <section id="inner-contanier">
        <div class="container">
            <div class="row">
                <div class="col-lg-9">


                    @if (isset($notice->image) && file_exists('storage/thumbs/' . $notice->image) && $notice->show_image == 1)
                        <img class="img-fluid"
                            alt="Image of Surya Jyoti Life Insurance's Notice titled {{ $notice->title }}"
                            src="{{ asset('storage/' . $notice->image) }}">
                    @endif


                    {!! $notice->description !!}
                    @if ($notice->link && file_exists('storage/' . $notice->link))
                        <a href="{{ asset('storage/' . $notice->link) }}" alt="" class="btn btn-primary"
                            target="_blank"> Download</a>
                    @endif


                    <br>
                    <div class="sharethis-inline-share-buttons"></div>
                </div>



                <div class="col-lg-3 sidebar">
                    <h3>Other Notices</h3>
                    @if (isset($latest) && !empty($latest))
                        <div class="side-menu">
                            <ul>
                                @foreach ($latest as $item)
                                    <li><a href="{{ route('press-release.show', $item->slug) }}">{{ $item->title }}</a>
                                    </li>
                                @endforeach
                            </ul>
                            <div class="clear"></div>
                        </div>
                    @endif
                </div>
            </div>
            <!-- Main row end-->

        </div>
        <!-- Container end-->
    </section>
@endsection
