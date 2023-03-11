@extends('layouts.frontend.app')
@section('title', 'Products List')
@section('style')
@endsection
@section('script')
@endsection

@section('page-banner')

@endsection
@section('content')
    <section id="pagetitle" style="background-image:url({{isset($menu->image) ?  asset('storage/' . @$menu->image) : asset('swabalamban/images/titlebg.jpg')  }});">
        <div class="container">
            <h1>{{ @$menu->title ?? trans('general.product-and-services') }}
            </h1>
            <ul>
                <li>
                    <a href="{{ route('home.index') }}">Home
                    </a>
                    <i class="fas fa-chevron-right">
                    </i>
                </li>
                <li>{{ @$menu->title ?? trans('general.product-and-services') }}
                </li>
            </ul>
        </div>
    </section>
    <section id="inner-contanier">
        <div class="container">
            <div class="row product news newslist gallerylist ">
                @include('layouts.frontend.inc.socialmedia')
                @include('layouts.frontend.inc.comments')
                @if (isset($products) && !empty($products) && !$products->isEmpty())
                    @foreach ($products as $data)
                        @if (isset($data->category->slug) && !empty($data->category->slug))
                            @php
                                $route = 'product.show';
                                $url = route($route, [$data->slug]);
                            @endphp
                            <div class="col-xs-6 col-sm-6 col-md-4 col-lg-3 col-xl-3 servicelist">
                                <div class="product-single">
                                    <a href="{{ $url }}" class="service-block serviceinner">
                                        @if (file_exists('storage/' . $data->image) && $data->image != '')
                                            <img src="{{ asset('storage/' . $data->image) }}"
                                                alt="Image of {{ $data->title }}">
                                        @else
                                            <img src="{{ asset('frontend/images/noimage.JPG') }}"
                                                alt="Default Image for Products">
                                        @endif

                                    <div>{{ $data->title }}</div>
                                    </a>
                                </div>

                            </div>
                        @endif
                    @endforeach
                    {!! $products->appends(request()->query())->links('layouts.frontend.inc.pagination') !!}
                @else
                    <div class="col-12">{{ trans('general.no-record-found') }}</div>
                @endif
            </div>
            <!-- row end -->
        </div>
    </section>
@endsection
