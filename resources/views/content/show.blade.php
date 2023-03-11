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
                <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 col-xl-12">
                    @include('layouts.frontend.inc.socialmedia')
                    @include('layouts.frontend.inc.comments')
                    <div class="leftsection">
                        {!! $content->description !!}
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
