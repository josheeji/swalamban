@extends('layouts.frontend.app')
@section('title', 'Project')
@section('content')
    <section class="inner-content">
        <div class="container">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('home.index') }}">{{ trans('general.home') }}</a></li>
                    <li class="breadcrumb-item active" aria-current="page">{{ trans('general.projects') }}</li>
                </ol>
            </nav>
            <h1>{{ trans('general.projects') }}</h1>

            <div class="row gridwrap ">
                @if(isset($projects) && !$projects->isEmpty())
                    @foreach($projects as $data)
                        @php
                            $route = 'project.show';
                            $url = route($route, [$data->slug]);
                        @endphp
                        <div class="col-lg-3 col-md-4 grid-single">
                            <a href="{{ $url }}">
                                @if(file_exists('storage/thumbs/'.$data->image) && $data->image != '')
                                    <img src="{{ asset('storage/thumbs/'. $data->image) }}" alt="">
                                @else
                                    <img src="{{ asset('frontend/images/no-image.jpg') }}" alt="">
                                @endif
                            </a>
                            <h3><a href="{{ $url }}">{{ $data->title }}</a></h3>
                        </div>
                        <!-- col end -->
                    @endforeach
                @else
                    <div class="col-12">{{ trans('general.no-record-found') }}</div>
                @endif
            </div>
            <!-- row end -->
        </div>
    </section>
@endsection