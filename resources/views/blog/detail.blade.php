@extends('layouts.frontend.app')
@section('title', $blog->title ?? 'Stories')
@section('scripts')
    {!! $schema !!}
@endsection
@section('page-banner')

@endsection
@section('content')
    <!-- Title/Breadcrumb -->
    <section id="pagetitle"
        style="background-image:url({{ isset($blog->banner) ? asset('storage/' . $blog->banner) : asset('swabalamban/images/titlebg.jpg') }});">
        <div class="container">
            <h1>{{ $blog->title }}
            </h1>
            <ul>
                <li>
                    <a href="{{ route('home.index') }}">Home
                    </a>
                    <i class="fas fa-chevron-right">
                    </i>
                </li>
                <li>
                    <a href="{{ route('stories.index') }}">{{ @$menu->title ?? 'Stories' }}
                    </a>
                    <i class="fas fa-chevron-right">
                    </i>
                </li>
                <li>{{ $blog->title }}
                </li>
            </ul>
        </div>
    </section>
    <!-- Title/Breadcrumb END -->
    <section id="internal" class="section-padding">
        <div class="container">
            <div class="row">
                @include('layouts.frontend.inc.socialmedia')
                @include('layouts.frontend.inc.comments')

                <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 col-xl-12">
                    <div class="leftsection">
                        <div class="promotion">

                            <h2>{{ $blog->title }}</h2>
                            <div class="notice-date">
                                <span> On {{ date('d M, Y', strtotime($blog->published_date)) }}
                                </span>
                                <a href="javascript:void(0);"
                                    class="time-update">{{ $blog->published_date->diffForHumans() }}
                                </a>
                            </div>
                            @if (file_exists('storage/' . $blog->image) && !empty($blog->image) && $blog->show_image == 1)
                                <div class="pro-gallery">
                                    <img src="{{ asset('storage/' . $blog->image) }}" class="img-fluid"
                                        alt="{{ $blog->title }}" title="{{ $blog->title }}">
                                </div>
                            @endif
                            <p>
                                {!! $blog->description !!}
                            </p>
                            @if (file_exists('storage/' . $blog->document) && $blog->document != '')
                                <a href="{{ asset('storage/'.$blog->document) }}" class="btn">Download File</a>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
        </div>
        </div>
    </section>
@endsection
