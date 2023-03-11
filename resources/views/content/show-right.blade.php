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
    @include('content._header', ['content' => $content])
    <section class="inner-contanier">
        <div class="container">
            <div class="row">

                <div class="col-xs-12 col-sm-12 col-md-12 col-lg-8 col-xl-8">
                    @include('layouts.frontend.inc.socialmedia')
                    @include('layouts.frontend.inc.comments')
                    <div class="leftsection">
                        @include('content._content', ['content' => $content])
                    </div>
                </div>
                <div class="col-xs-12 col-sm-12 col-md-12 col-lg-4 col-xl-4">
                    <div class="row">
                        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 col-xl-12">
                            @if (isset($content->show_children) && $content->show_children == 1)
                                <div class="graycard">
                                    @include('content._aside', ['content' => $content])
                                </div>
                            @endif

                        </div>
                        <div class="col-xs-12 col-sm-12 col-md-6 col-lg-12 col-xl-12">
                            <div class="cns-title">
                                <a href="javascript:void(0);" class="">Download Categories
                                </a>
                                <ul class="list-categories">
                                    @foreach ($categories as $item)
                                        <li>
                                            <a href="{{ route('download.show', $item->slug) }}">{{ $item->title }}
                                            </a>
                                        </li>
                                    @endforeach
                                </ul>
                            </div>
                            <div class="latest-notice">
                                <div class="cns-title">
                                    <a href="javascript:void(0);" class="">Latest Notice
                                    </a>
                                </div>
                                @foreach ($notices as $item)
                                    <div class="notice-1">

                                        <div class="notice-date">
                                            @isset($item->end_date)
                                            <span>Expires On {{ date('d M, Y', strtotime($item->end_date)) }}
                                            </span>
                                            @endisset
                                            {{-- <a href="{{ asset('storage/' . $item->link) }}"
                                                class="time-update" target="_blank"> --}}
                                                {{ $item->start_date->diffForHumans() }}
                                            {{-- </a> --}}
                                        </div>
                                        <div class="notice">
                                            <a href="{{ asset('storage/' . $item->link) }}" class="" target="_blank">{{ $item->title }}
                                            </a>
                                            @if (now()->subDays(7)->format('y-m-d') <= $item->start_date->format('y-m-d'))
                                                <span>New
                                                </span>
                                            @endif

                                        </div>
                                        <div class="dotted">
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>

                    </div>
                </div>

            </div>
        </div>
    </section>
@endsection
