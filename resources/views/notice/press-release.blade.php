@extends('layouts.frontend.app')
@section('title', trans('general.notice'))
@section('meta_keys', trans('general.notice'))
@section('meta_description', trans('general.notice'))
@section('style')

@endsection
@section('script')

@endsection
@section('content')
    <!-- Title/Breadcrumb -->
    <section id="pagetitle" style="background-image:url({{ isset($menu->image) ? asset('storage/' . @$menu->image) : asset('swabalamban/images/titlebg.jpg') }});">
        <div class="container">
            <h1>{{ trans('general.notice') }}</h1>
            <ul>
                <li><a href="{{ route('home.index') }}">{{ trans('general.home') }}</a><i class="fas fa-chevron-right"></i>
                </li>
                <li>{{ trans('general.notice') }} </li>
            </ul>
        </div>
    </section>
    <!-- Title/Breadcrumb END -->


    <section id="notice" class="section-padding">
        <div class="container">
            <div class="row">

                @include('layouts.frontend.inc.socialmedia')
                @foreach ($notices as $item)
                    <div class="col-xs-12 col-sm-12 col-md-6 col-lg-6 col-xl-4">
                        <div class="notice-block">
                            <div class="notice-date">
                                @isset($item->end_date)
                                    <span>Expires On
                                        {{ date('d M, Y', strtotime($item->end_date)) }} </span>
                                @endisset

                                {{-- <a href="{{ asset('storage/' . $item->link) }}"
                                    class="time-update" target="_blank"> --}}
                                    {{ $item->start_date->diffForHumans() }}
                                {{-- </a> --}}
                            </div>

                            <a href="{{ asset('storage/' . $item->link) }}" class="notice-title" target="_blank">{{$item->title}} </a>

                        </div>
                    </div>
                @endforeach

                {{ $notices->links('vendor.pagination.custom') }}
            </div>
    </section>

@endsection
