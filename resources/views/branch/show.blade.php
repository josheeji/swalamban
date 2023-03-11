@extends('layouts.frontend.app')
@section('title', 'Branch Location')
@section('style')

@endsection
@section('script')
{!! $schema !!}
@endsection
@section('page-banner')

@endsection
@section('content')
<section class="inner-content">
    <div class="container">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('home.index') }}">{{ trans('general.home') }}</a></li>
                <li class="breadcrumb-item"><a href="{{ route('branch.index') }}">{{ trans('branch.branch_location') }}</a></li>
                <li class="breadcrumb-item active" aria-current="page">{{ $branch->title }}</li>
            </ol>
        </nav>
        <h1>{{ $branch->title }}</h1>

        <div class="row">

            @php
            $url = 'javascript:void(0)';
            $hasLink = false;
            if(!empty($branch->url)){
            $url = $branch->url;
            $hasLink = true;
            }elseif(!empty($branch->lat) && !empty($branch->long)){
            $url = "http://maps.google.com?q={$branch->lat},{$branch->long}";
            $hasLink = true;
            }
            @endphp
            <div class="col-lg-4 col-md-6">
                <div class="card-box branch">
                    <h2>{{ $branch->title }}</h2>
                    <div class="contactdesc">
                        <ul>
                            <li><i class="bi bi-person"></i> {{ trans('branch.manager') }}: {{ $branch->fullname }}</li>
                            <li><i class="bi bi-geo-alt"></i> {{ $branch->address }}</li>
                            <li><i class="bi bi-telephone"></i> {!! $branch->phone !!}</li>
                            <li><i class="bi bi-envelope"></i> <a href="mailto:{{ $branch->email }}"> {{ $branch->email }}</a> </li>
                        </ul>
                        <div class="clear"></div>
                    </div>
                </div>
            </div>
            <!-- col end -->
        </div>
        <!-- row end -->
    </div>
</section>
@endsection