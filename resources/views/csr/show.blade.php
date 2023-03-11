@php
$adsTop = $pageHelper->advertisements('news', 2);
$adsBottom = $pageHelper->advertisements('news', 4);
@endphp
@extends('layouts.frontend.app')
@section('script')
{!! isset($schema) && !empty($schema) ? $schema : '' !!}
@endsection
@section('content')
@include('csr._header', ['news' => $news])
<section class="maininner-container ">
    <div class="container">
        <div class="row">
            <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 maintxt">
                @include('csr._content', ['news' => $news])
            </div>
        </div>
</section>
@endsection