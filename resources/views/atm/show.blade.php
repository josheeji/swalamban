@extends('layouts.frontend.app')
@section('title', 'Branch Location')
@section('style')
@endsection
@section('script')
{!! $schema !!}
@endsection
@section('page-banner')
<section class="bannertop">
    <div class="container">
        <div class="bannerimg parallax">
            <h1>{{ $atm->title }}</h1>
            <div class="banner-txt"></div>
            <ul class="header-bottom-navi">
                <li><a href="{{ route('home.index') }}">{{ trans('general.home') }}</a><i class="fas fa-chevron-right"></i></li>
                <li><a href="{{ route('atm.index') }}">{{ trans('atm.atm_location') }}</a><i class="fas fa-chevron-right"></i>
                <li>
                <li><a href="#!">{{ $atm->title }}</a></li>
            </ul>
        </div>
    </div>
</section>
@endsection
@section('content')
<section class="maininner-container ">
    <div class="container">
        <div class="row">
            <div class="col-12">
                <div class="table-responsive">
                    <table class="table is-fullwidth is-bordered is-striped is-hoverable defaultTable">
                        <thead>
                            <tr>
                                <th>{{ trans('atm.atm') }}</th>
                                <th class="d-none">Province</th>
                                <th class="d-none">District</th>
                                <th width="150px">{{ trans('branch.view-in-map') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            <td>{{ $atm->title }}</td>
                            <td class="d-none">{{ $atm->province ? $atm->province->title : '' }}</td>
                            <td class="d-none">{{ $atm->district ? $atm->district->title : '' }}</td>
                            <td>
                                @if($atm->url)
                                <a href="{!! $atm->url !!}" target="_blank"><i class="fas fa-map-marker-alt"></i> {{ trans('branch.locate') }}</a>
                                @elseif(!empty($atm->lat) && !empty($atm->long))
                                <a href="http://maps.google.com?q={{ !empty($atm->lat) && !empty($atm->long) ? $atm->lat.','.$atm->long : ''}}" target="_blank"><i class="fas fa-map-marker-alt"></i> {{ trans('branch.locate') }}</a>
                                @endif
                            </td>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection