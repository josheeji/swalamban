@extends('layouts.frontend.app')
@section('title', 'Internal Web')
@section('meta_keys', 'Internal Web')
@section('meta_description', 'Internal Web')
@section('content')
    <!-- Title/Breadcrumb -->
    <section id="pagetitle"
        style="background-image:url({{ isset($menu->image) ? asset('storage/' . @$menu->image) : asset('swabalamban/images/titlebg.jpg') }});">
        <div class="container">
            <h1>{{ @$category->title ?? 'Internal Web' }}
            </h1>
            <ul>
                <li>
                    <a href="{{ route('home.index') }}">Home
                    </a>
                    <i class="fas fa-chevron-right">
                    </i>
                </li>
                <li>
                    <a href="{{ route('internal-web.index') }}">Internal Web
                    </a>
                    <i class="fas fa-chevron-right">
                    </i>
                </li>
                <li>{{ @$category->title ?? 'Internal Web' }}
                </li>
            </ul>
        </div>
    </section>
    <!-- Title/Breadcrumb END -->
    <section id="inner-conatiner" class="section-padding" style="padding-top: 0;">
        <div class="container">
            <div class="row">
                @include('layouts.frontend.inc.socialmedia')
                @include('layouts.frontend.inc.comments')
                <div class="col-xs-12 col-sm-12 col-md-12 col-lg-8 col-xl-8">
                    <div class="row">
                        @if ($category->show_upload_file == 1)
                            @include('internalWeb._file_upload')
                        @endif

                        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 col-xl-12">
                            @if (count($files))

                                <div class="leftsection">
                                    @foreach ($files as $year => $items)
                                        @if ($year != '')
                                            <div class="acts">
                                                <h2 class="interanlweb-title">{{ $category->title }} - {{ $year }}
                                                </h2>
                                            </div>
                                            <div class="acts-grp">
                                                <div class="table-responsive">
                                                    <table class="table table-striped downloadtable">
                                                        <thead class="table-heading">
                                                            <tr>
                                                                <th class="heading text-center" width="5%">SN </th>
                                                                <th class="heading" width="75%">Title </th>
                                                                <th class="heading text-center" width="10%">File Type
                                                                </th>
                                                                <th width="10%"></th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            @foreach ($items as $i => $data)
                                                                <tr>
                                                                    <td class="text-center">{{ ++$i }}
                                                                    </td>
                                                                    <td
                                                                        colspan="{{ file_exists('storage/' . $data->file) && !empty($data->file) ? '' : '3' }}">
                                                                        <a href="{{ file_exists('storage/' . $data->file) && !empty($data->file) ? asset('storage/' . $data->file) : '#!' }}"
                                                                            class="d-title"
                                                                            target="{{ file_exists('storage/' . $data->file) && !empty($data->file) ? '_blank' : '' }}">{{ $data->title }}
                                                                        </a>
                                                                    </td>
                                                                    @if (file_exists('storage/' . $data->file) && !empty($data->file))
                                                                        @php
                                                                            $a = explode('.', $data->file);
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
                                                                            <span
                                                                                class="{{ $class }}">{{ strtoupper(end($a)) }}
                                                                            </span>
                                                                        </td>
                                                                        <td class="text-center">
                                                                            <a href="{!! asset('storage/' . $data->file) !!}" class="loding"
                                                                                target="_blank">Download
                                                                            </a>
                                                                        </td>
                                                                    @endif

                                                                </tr>
                                                            @endforeach
                                                    </table>
                                                </div>
                                            </div>
                                        @else
                                            <div class="acts">
                                                <h2 class="interanlweb-title">{{ $category->title }}
                                                </h2>
                                            </div>
                                            <div class="acts-grp">

                                                <div class="table-responsive">
                                                    <table class="table table-striped downloadtable">
                                                        <thead class="table-heading">
                                                            <tr>
                                                                <th class="heading text-center" width="5%">SN </th>
                                                                <th class="heading" width="75%">Title </th>
                                                                <th class="heading text-center" width="10%">File Type
                                                                </th>
                                                                <th width="10%"></th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            @foreach ($items as $i => $data)
                                                                <tr>
                                                                    <td class="text-center">{{ ++$i }}
                                                                    </td>
                                                                    <td
                                                                        colspan="{{ file_exists('storage/' . $data->file) && !empty($data->file) ? '' : '3' }}">
                                                                        <a href="{{ file_exists('storage/' . $data->file) && !empty($data->file) ? asset('storage/' . $data->file) : '#!' }}"
                                                                            class="d-title"
                                                                            target="{{ file_exists('storage/' . $data->file) && !empty($data->file) ? '_blank' : '' }}">{{ $data->title }}
                                                                        </a>
                                                                    </td>
                                                                    @if (file_exists('storage/' . $data->file) && !empty($data->file))
                                                                        @php
                                                                            $a = explode('.', $data->file);
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

                                                                            <span
                                                                                class="{{ $class }}">{{ strtoupper(end($a)) }}
                                                                            </span>
                                                                        </td>
                                                                        <td class="text-center">
                                                                            <a href="{!! asset('storage/' . $data->file) !!}" class="loding"
                                                                                target="_blank">Download
                                                                            </a>
                                                                        </td>
                                                                    @endif

                                                                </tr>
                                                            @endforeach


                                                    </table>
                                                </div>
                                            </div>
                                        @endif
                                    @endforeach
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
                <div class="col-xs-12 col-sm-12 col-md-12 col-lg-4 col-xl-4">
                    <h2 class="interanlweb-title">Categories
                    </h2>

                    <div class="accordion" id="accordionPanelsStayOpenExample">
                        @foreach ($categories as $key => $cat_item)
                            <div class="accordion-item">
                                <h2 class="accordion-header" id="panelsStayOpen-headingOne-{{ $key }}">
                                    <button
                                        class="accordion-button {{ $category->id == $cat_item->id || in_array($category->id, $cat_item->allChild->pluck('id')->toArray()) ? '' : 'collapsed' }}"
                                        type="button" data-bs-toggle="collapse"
                                        data-bs-target="#panelsStayOpen-collapseOne-{{ $key }}"
                                        aria-expanded="{{ $category->id == $cat_item->id || in_array($category->id, $cat_item->allChild->pluck('id')->toArray()) ? 'true' : 'false' }}"
                                        aria-controls="panelsStayOpen-collapseOne-{{ $key }}">
                                        <span>{{ ucfirst($cat_item->title) }}</span>
                                    </button>
                                </h2>
                                <div id="panelsStayOpen-collapseOne-{{ $key }}"
                                    class="accordion-collapse collapse {{ $category->id == $cat_item->id || in_array($category->id, $cat_item->allChild->pluck('id')->toArray()) ? 'show' : '' }}"
                                    aria-labelledby="panelsStayOpen-headingOne-{{ $key }}">
                                    <div class="accordion-body">
                                        <ul class="years-list">
                                            @if ($cat_item->allChild->count() || count($downloads[$key]->keys()))
                                                <li><a href="{{ route('internal-web.category', ['slug' => $cat_item->slug]) }}"
                                                        class="yearly">All</a></li>
                                            @endif
                                            {{-- @if ($downloads[$key]->keys()->count()) --}}
                                            @foreach ($downloads[$key]->keys() as $year)
                                                @if ($year != '')
                                                    <li><a href="{{ route('internal-web.show', ['year' => $year, 'category_slug' => $cat_item->slug]) }}"
                                                            class="yearly">{{ $year }}</a></li>
                                                @endif
                                            @endforeach
                                            {{-- @else --}}
                                            @foreach ($cat_item->allChild as $child)
                                                <li><a href="{{ route('internal-web.category', ['slug' => $child->slug]) }}"
                                                        class="yearly">{{ $child->title }}</a></li>
                                            @endforeach
                                            {{-- @endif --}}
                                        </ul>

                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>

                </div>
            </div>
        </div>
    </section>
@endsection
