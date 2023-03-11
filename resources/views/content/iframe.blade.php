@extends('layouts.frontend.app')
@section('title', $content->title)
@section('meta_keys', $content->meta_keys)
@section('meta_description', $content->meta_desc)
@section('script')
    <script>
        {!! isset($schema) && !empty($schema) ? $schema : '' !!}
    </script>
@endsection
@section('content')
    <!-- Title/Breadcrumb -->
    <section id="pagetitle"
        style="background-image:url({{ isset($content->banner) ? asset('storage/' . $content->banner) : asset('swabalamban/images/titlebg.jpg') }});">
        <div class="container">
            <h1>{{ @$content->title }}</h1>
            <ul>
                <li><a href="#!">Home</a><i class="fas fa-chevron-right"></i></li>
                <li>{{ @$content->title }}</li>
            </ul>
        </div>
    </section>
    <!-- Title/Breadcrumb END -->
    <section id="inner-contanier">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    @if (file_exists('storage/' . $content->image) && $content->image != '' && $content->show_image == 1)
                        <img class="img-fluid" alt="Image is of {{ $content->title }}"
                            src="{{ asset('storage/' . $content->image) }}">
                    @endif
                    <iframe src="{{ $content->excerpt }}" title="{!! $content->description !!}"
                        style="width: 100% ; height:600px; border:0">
                    </iframe>
                    @if (isset($content->show_children) && $content->show_children == 1)
                        @include('content._show_inner', ['content' => $content])
                    @endif
                </div>
            </div>
        </div>
    </section>
@endsection
