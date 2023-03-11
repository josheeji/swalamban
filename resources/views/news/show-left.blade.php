@extends('layouts.frontend.app')
@section('title', $news->title)
@section('meta_keys', $news->title)
@section('meta_description', $news->description)
@section('script')
    {!! isset($schema) && !empty($schema) ? $schema : '' !!}
@endsection
@section('content')
    @include('news._header', ['news' => $news])
    <section class="content-pd inner-content">
        <div class="container">
            <div class="row">
            </div>
            <!-- Main row end-->
        </div>
        <!-- Container end-->
    </section>
@endsection
