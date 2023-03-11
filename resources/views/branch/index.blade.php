@extends('layouts.frontend.app')
@section('content')
@section('title', @$menu->title ?? 'Branches')

@section('content')

    <!-- Title/Breadcrumb -->
    <section id="pagetitle" style="background-image:url({{isset($menu->image) ? asset('storage/'.@$menu->image) : asset('swabalamban/images/titlebg.jpg') }});">
        <div class="container">
            <h1>{{@$menu->title ?? 'Branches'}}</h1>
            <ul>
                <li><a href="{{route('home.index')}}">{{trans('general.home')}}</a><i class="fas fa-chevron-right"></i></li>
                <li>{{@$menu->title ?? 'Branches'}} </li>
            </ul>
        </div>
    </section>

    <section id="branches" >
        <div class="container">
            <div class="row">
                @include('layouts.frontend.inc.socialmedia')
                @foreach ($branches as $item)
                <div class="col-xs-12 col-sm-4 col-md-4 col-lg-3 col-xl-3">
                    <div class="branch-block">
                        <div>{{$item->title}}</div>
                        <ul>
                            <li> Address: {{$item->address}}</li>
                            <li> Phone No.: <a href="+977-{{$item->phone}}">{{$item->phone}}</a></li>
                        </ul>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </section>

@endsection
