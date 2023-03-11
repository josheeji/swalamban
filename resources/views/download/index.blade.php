@extends('layouts.frontend.app')
@section('title', 'Downloads')
@section('meta_keys', 'download')
@section('meta_description', 'download')
@section('content')
    <!-- Title/Breadcrumb -->
    <section id="pagetitle" style="background-image:url({{ isset($menu->image) ? asset('storage/' . @$menu->image) : asset('swabalamban/images/titlebg.jpg') }});">
        <div class="container">
            <h1>{{ @$menu->title ?? 'Downloads' }}
            </h1>
            <ul>
                <li>
                    <a href="{{ route('home.index') }}">Home
                    </a>
                    <i class="fas fa-chevron-right">
                    </i>
                </li>
                <li>{{ @$menu->title ?? 'Downloads' }}
                </li>
            </ul>
        </div>
    </section>
    <!-- Title/Breadcrumb END -->
    <section id="downloads" class="section-padding" style="padding-top: 0;">
        <div class="container">
            <div class="row">
                @include('layouts.frontend.inc.socialmedia')
                @include('layouts.frontend.inc.comments')
                <div class="col-xs-12 col-sm-12 col-md-12 col-lg-8 col-xl-8">
                    <div class="leftsection">
                        <div class="table-responsive">
                            <table class="table table-striped downloadtable">
                                <thead class="table-heading">
                                    <tr>
                                        <th class="heading text-center">SN
                                        </th>
                                        <th class="heading">Title
                                        </th>
                                        <th class="heading text-center">File Type
                                        </th>
                                        <th>
                                        </th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($downloads as $key => $item)
                                        <tr>
                                            <td class="text-center">{{++$key}}
                                            </td>
                                            <td>
                                                <a href="" class="d-title">{{ $item->title }}
                                                </a>
                                            </td>
                                            @if (file_exists('storage/' . $item->file) && !empty($item->file))
                                                @php
                                                    $a = explode('.', $item->file);
                                                    $class = 'typepdf';
                                                    switch (end($a)) {
                                                        case 'pdf':
                                                            $class = 'typepdf';
                                                            break;

                                                        case 'jpg' || 'jpeg' || 'png' || 'gif':
                                                            $class = 'typeimg';
                                                            break;

                                                        case 'doc' || 'docx' || 'txt':
                                                            $class = 'typedoc';
                                                            break;

                                                        case 'xls' || 'xlsx':
                                                            $class = 'typexls';
                                                            break;

                                                        default:
                                                            $class = 'typepdf';

                                                            break;
                                                    }
                                                @endphp
                                                <td class="text-center">

                                                    <span class="{{ $class }}">{{ strtoupper(end($a)) }}
                                                    </span>
                                                </td>
                                                <td class="text-center">
                                                    <a href="{!! asset('storage/' . $item->file) !!}" class="loding">Download
                                                    </a>
                                                </td>
                                            @endif

                                        </tr>
                                    @endforeach


                            </table>
                        </div>
                    </div>
                </div>
                <div class="col-xs-12 col-sm-12 col-md-12 col-lg-4 col-xl-4">
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
                                    {{-- <a href="{{ asset('storage/' . $item->link) }}"class="time-update" target="_blank"> --}}
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
    </section>
@endsection
