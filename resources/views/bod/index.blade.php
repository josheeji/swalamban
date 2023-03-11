@extends('layouts.frontend.app')
@section('title', 'Board of Directors')

@section('content')
    <section class="content-pd breadcrumb-wrap" id="pagetitle">
        <div class="container">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('home.index') }}">{{ trans('general.home') }}</a></li>
                    <li class="breadcrumb-item active" aria-current="page">{{ trans('general.bod') }}</li>
                </ol>
            </nav>
            <h1>{{ trans('general.bod') }}</h1>
        </div>
    </section>
    <!-- inner content start -->
    <section class="content-pd inner-content  ">
        <div class="container">
            <div class="row  grid_bodlist team ">
                <!-- chairman -->
                @if (!empty($chairman))
                    <div class="col-xs-12  col-sm-6 offset-sm-3 offset-md-4 col-md-5 offset-lg-4 col-lg-4 chairman">
                        <div class="grid_bod">
                            <img data-bs-toggle="modal" data-bs-target="#exampleModal-0"
                                src="{{ asset('storage/' . $chairman->photo) }}"
                                alt="Management team, photo of {{ $chairman->full_name }}">
                            <div class="grid_bod-desc">
                                <h2>{{ $chairman->full_name }} </h2>
                                <h3>{{ $chairman->designation }}</h3>
                                <div class="contact-details">
                                    <ul>
                                        <li><i class="bi bi-telephone  "></i> {{ $chairman->phone }} </li>
                                        <li><i class="bi bi-envelope"></i><a
                                                href="mailto:{{ $chairman->email }}">{{ $chairman->email }}</a> </li>
                                        @if (isset($chairman->tenure))
                                            <li><i class="bi bi-clock"></i> Tenure : {{ $chairman->tenure }} </li>
                                        @endif
                                        @if (isset($chairman->date))
                                            <li><i class="bi bi-calendar-check"></i> {{ $chairman->date }} </li>
                                        @endif
                                    </ul>
                                    <div class="clear"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-xs-12"></div>
                    <div class="modal fade" id="exampleModal-0" tabindex="-1" aria-labelledby="exampleModalLabel"
                        aria-hidden="true">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="exampleModalLabel">{{ $chairman->full_name }} </h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal"
                                        aria-label="Close"></button>
                                </div>
                                <div class="modal-body">
                                    <div class="teampopup">
                                        <div style="text-align:center;">
                                            <img src="{{ asset('storage/' . $chairman->photo) }}"
                                                alt="Management team, photo of {{ $chairman->full_name }}">
                                        </div>

                                        <h3>{{ $chairman->designation }}</h3>
                                        <div class="contact-details">
                                            <ul>
                                                <li><i class="bi bi-telephone  "></i> {{ $chairman->phone }} </li>
                                                <li><i class="bi bi-envelope"></i><a
                                                        href="mailto:{{ $chairman->email }}">{{ $chairman->email }}</a>
                                                </li>
                                                @if (isset($chairman->tenure))
                                                    <li><i class="bi bi-clock"></i> Tenure : {{ $chairman->tenure }}
                                                    </li>
                                                @endif
                                                @if (isset($chairman->date))
                                                    <li><i class="bi bi-calendar-check"></i> {{ $chairman->date }} </li>
                                                @endif
                                            </ul>
                                            <div class="clear"></div>
                                        </div>
                                        {!! $chairman->description !!}
                                    </div>
                                </div>

                            </div>
                        </div>
                    </div>
                @endif
                <!-- chairman -->

                @foreach ($team as $item)
                    <div class="col-xs-12 col-sm-6 col-md-4 col-lg-4 col-xl-3">
                        <div class="grid_bod">
                            <img data-bs-toggle="modal" data-bs-target="#exampleModal-{{ $item->id }}"
                                src="{{ asset('storage/' . $item->photo) }}"
                                alt="Management team, photo of {{ $item->full_name }}">
                            <div class="grid_bod-desc">
                                <h2>{{ $item->full_name }} </h2>
                                <h3>{{ $item->designation }}</h3>
                                <div class="contact-details">
                                    <ul>
                                        <li><i class="bi bi-telephone  "></i> {{ $item->phone }} </li>
                                        <li><i class="bi bi-envelope"></i><a
                                                href="mailto:{{ $item->email }}">{{ $item->email }}</a> </li>
                                        @if (isset($item->tenure))
                                            <li><i class="bi bi-clock"></i> Tenure : {{ $item->tenure }} </li>
                                        @endif
                                        @if (isset($item->date))
                                            <li><i class="bi bi-calendar-check"></i> {{ $item->date }} </li>
                                        @endif
                                    </ul>
                                    <div class="clear"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- col end -->
                    <div class="modal fade" id="exampleModal-{{ $item->id }}" tabindex="-1"
                        aria-labelledby="exampleModalLabel" aria-hidden="true">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="exampleModalLabel">{{ $item->full_name }} </h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal"
                                        aria-label="Close"></button>
                                </div>
                                <div class="modal-body">
                                    <div class="teampopup">
                                        <img src="{{ asset('storage/' . $item->photo) }}"
                                            alt="Management team, photo of {{ $item->full_name }}">
                                        <h3>{{ $item->designation }}</h3>
                                        <div class="contact-details">
                                            <ul>
                                                <li><i class="bi bi-telephone  "></i> {{ $item->phone }} </li>
                                                <li><i class="bi bi-envelope"></i><a
                                                        href="mailto:{{ $item->email }}">{{ $item->email }}</a>
                                                </li>
                                                @if (isset($item->tenure))
                                                    <li><i class="bi bi-clock"></i> Tenure : {{ $item->tenure }} </li>
                                                @endif
                                                @if (isset($item->date))
                                                    <li><i class="bi bi-calendar-check"></i> {{ $item->date }} </li>
                                                @endif
                                            </ul>
                                            <div class="clear"></div>
                                        </div>
                                        {!! $item->description !!}
                                    </div>
                                </div>

                            </div>
                        </div>
                    </div>
                @endforeach
                <br>
            </div>
            <div class="sharethis-inline-share-buttons"></div>
        </div>
    </section>
    <!-- inner content end -->

@endsection
