@extends('layouts.frontend.app')
@section('title', @$menu->title ?? 'Gallery')
@section('meta_keys', @$menu->title ?? 'Gallery')
@section('meta_description', @$menu->title ?? 'Gallery')

@section('page-banner')

@endsection
@section('content')
    <!-- Title/Breadcrumb -->
    <section id="pagetitle" style="background-image:url({{isset($menu->image) ?  asset('storage/' . @$menu->image) : asset('swabalamban/images/titlebg.jpg')  }});">
        <div class="container">
            <h1>{{ @$menu->title ?? 'Gallery' }}</h1>
            <ul>
                <li><a href="{{ route('home.index') }}">{{ trans('general.home') }}</a><i class="fas fa-chevron-right"></i></li>
                <li>{{ @$menu->title ?? 'Gallery' }} </li>
            </ul>
        </div>
    </section>
    <!-- Title/Breadcrumb END -->



    <section id="gallery-list" class="section-padding">
        <div class="container">
            <div class="row">
                @include('layouts.frontend.inc.socialmedia')
                @include('layouts.frontend.inc.comments')
                @foreach ($galleries as $item)
                    <div class="col-xs-12 col-sm-6 col-md-6 col-lg-4 col-lg-4 ">
                        <div class="card">
                            <a href="{{route('gallery.show',$item->slug)}}" class="view">
                                <img src="{{ asset('storage/' . $item->image) }}" class="img-fluid"
                                    alt="{{ $item->title }}" title="{{ $item->title }}">
                            </a>
                            <div class="card-body">
                                <a href="{{route('gallery.show',$item->slug)}}" class="card-title">{{ $item->title }}
                                </a>
                            </div>
                        </div>
                    </div>
                @endforeach


                {{ $galleries->links('vendor.pagination.custom') }}
            </div>
    </section>


@endsection
