@extends('layouts.frontend.app')
@section('title', trans('general.offers'))
@section('meta_keys', trans('general.offers'))
@section('meta_description', trans('general.offers'))
@section('style')

@endsection
@section('script')

@endsection

@section('content')
    <!-- header area start-->
    <section class="content-pd breadcrumb-wrap">
        <div class="container">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('home.index') }}">{{ trans('general.home') }}</a></li>
                    <li class="breadcrumb-item active" aria-current="page">{{ trans('general.offers') }}</li>
                </ol>
            </nav>
            <h1>{{ trans('general.offers') }}</h1>
        </div>
    </section>
    <!-- header area end-->
    <section class="content-pd inner-content  ">
        <div class="container">
            <div class="row">
                @if (isset($offers) && !empty($offers) && !$offers->isEmpty())
                    @foreach ($offers as $data)
                        @php
                            $url = !empty($data->url) ? $data->url : route('offer.show', $data->slug);
                            $target = $data->target == 1 ? 'target="_blank"' : '';
                        @endphp
                        <div class="col-xs-12 col-sm-6 col-md-4 col-lg-4">
                            <div class="inner-blog-list">
                                <div class="block">
                                    <h3><a href="">{!! $data->title !!}</a></h3>
                                    <p>{!! Helper::ShortText($data->excerpt, 80) !!}</p>
                                    @if (!empty($data->url))
                                        <a href="{{ $data->url }}" class="btn"
                                            @if ($data->link_target) target="_blank" @endif>{!! $data->layout !!}</a>
                                    @else
                                        <a href="{{ route('offer.show', $data->slug) }}" class="btn"
                                            @if ($data->link_target) target="_blank" @endif>
                                            {!! $data->layout !!}</a>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @endforeach
                    @if (isset($offers) && !empty($offers) && !$offers->isEmpty($offers))
                        {!! $offers->appends(request()->query())->links('layouts.frontend.inc.pagination') !!}
                    @endif
                @else
                    <div class="col-12" style="margin-bottom: 120px;">{{ trans('general.no-record-found') }}</div>
                @endif
            </div>
        </div>
    </section>
@endsection
