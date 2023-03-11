@extends('layouts.frontend.app')
@section('script')
{!! isset($schema) && !empty($schema) ? $schema : '' !!}
@endsection
@section('content')
@include('csr._header', ['news' => $news])
<section class="maininner-container ">
    <div class="container">
        <div class="row">
            <div class="col-xs-12 col-sm-12 col-md-4 col-lg-3">
                @include('csr._aside', ['placement' => 2])
            </div>
            <div class="col-xs-12 col-sm-12 col-md-8 col-lg-9 padding-right maintxt">
                @include('csr._content', ['news' => $news])
            </div>
        </div>
</section>
@endsection