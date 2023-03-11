@extends('layouts.frontend.app')
@section('title', $category->title)
@section('style')
@endsection
@section('script')
@endsection

@section('page-banner')

@endsection
@section('content')
    <section id="pagetitle" style="background-image:url({{ isset($category->banner) asset('storage/' . $category->banner) : asset('swabalamban/images/titlebg.jpg') }});">
        <div class="container">
            <h1>{{ @$category->title }}</h1>
            <ul>
                <li><a href="{{ route('home.index') }}">Home</a><i class="fas fa-chevron-right"></i></li>
                <li>{{ @$category->title }}</li>
            </ul>
        </div>
    </section>
    <section id="inner-contanier">
        <div class="container">
            <div class="row">
                @if (isset($products) && !empty($products) && !$products->isEmpty())
                    @foreach ($products as $data)
                        @php
                            $route = 'product.show';
                            $url = route($route, [$data->category->slug, $data->slug]);
                        @endphp
                        <div class="col-xs-12 col-sm-12 col-md-4 col-lg-3 col-xl-3">
                            <div class="product-single " style="margin:0; margin-bottom:1.5rem;">
                                <a href="{{ $url }}">
                                    @if (file_exists('storage/' . $data->image) && $data->image != '')
                                        <img src="{{ asset('storage/' . $data->image) }}"
                                            alt="Image of {{ $data->title }}">
                                    @else
                                        <img src="{{ asset('frontend/images/noimage.JPG') }}"
                                            alt="Default Image for Products">
                                    @endif
                                </a>
                                <div class="card-desc">
                                    <a href="{{ $url }}">
                                        <h2>{{ $data->title }}</h2>
                                    </a>

                                </div>
                            </div>

                        </div>
                    @endforeach
                    {!! $products->appends(request()->query())->links('layouts.frontend.inc.pagination') !!}
                @else
                    <div class="col-12">{{ trans('general.no-record-found') }}</div>
                @endif
            </div>
        </div>
    </section>
@endsection
