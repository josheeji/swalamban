@extends('layouts.frontend.app')
@section('title', 'Internal Web')
@section('meta_keys', 'Internal Web')
@section('meta_description', 'Internal Web')
@section('content')
    <!-- Title/Breadcrumb -->
    <section id="pagetitle"
        style="background-image:url({{ isset($menu->image) ? asset('storage/' . @$menu->image) : asset('swabalamban/images/titlebg.jpg') }});">
        <div class="container">
            <h1>{{ @$menu->title ?? 'Internal Web' }}
            </h1>
            <ul>
                <li>
                    <a href="{{ route('home.index') }}">Home
                    </a>
                    <i class="fas fa-chevron-right">
                    </i>
                </li>
                <li>{{ @$menu->title ?? 'Internal Web' }}
                </li>
            </ul>
        </div>
    </section>
    <!-- Title/Breadcrumb END -->
    <section id="inner-conatiner" class="section-padding">
        <div class="container">
            <div class="row">
                @include('layouts.frontend.inc.socialmedia')
                @include('layouts.frontend.inc.comments')
                @if ($categories->count())

                    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-8 col-xl-8">
                        @if (count($downloads))
                            <div class="leftsection">
                                <div class="acts">
                                    <h2 class="interanlweb-title">{{ $categories->first()->title }} -
                                        {{ @$downloads[0]->keys()->first() != '' ? @$downloads[0]->keys()->first() :' ' }}
                                    </h2>
                                </div>
                                @if ($downloads[0]->count())
                                    @foreach ($downloads[0]->first() as $key => $items)

                                        <div class="acts-grp">
                                            @if ($key != '')
                                            <div class="months">{{ PageHelper::getMonthName($key) }}
                                            </div>
                                            @else
                                            <div class="months">Uncategorized Month
                                            </div>
                                            @endif

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
                                                        @foreach ($items as $key => $item)
                                                            <tr>
                                                                <td class="text-center">{{ ++$key }}
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

                                                                        <span
                                                                            class="{{ $class }}">{{ strtoupper(end($a)) }}
                                                                        </span>
                                                                    </td>
                                                                    <td class="text-center">
                                                                        <a href="{!! asset('storage/' . $item->file) !!}"
                                                                            class="loding" target="_blank">Download
                                                                        </a>
                                                                    </td>
                                                                @endif

                                                            </tr>
                                                        @endforeach


                                                </table>
                                            </div>
                                        </div>
                                    @endforeach
                                @endif

                            </div>
                        @endif

                    </div>
                    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-4 col-xl-4">
                        <h2 class="interanlweb-title">Categories
                        </h2>

                        <div class="accordion" id="accordionPanelsStayOpenExample">
                            @foreach ($categories as $key => $category)
                                <div class="accordion-item">
                                    <h2 class="accordion-header" id="panelsStayOpen-headingOne-{{ $key }}">
                                        <button class="accordion-button {{ $loop->first ? '' : 'collapsed' }}"
                                            type="button" data-bs-toggle="collapse"
                                            data-bs-target="#panelsStayOpen-collapseOne-{{ $key }}"
                                            aria-expanded="{{ $loop->first ? 'true' : 'false' }}"
                                            aria-controls="panelsStayOpen-collapseOne-{{ $key }}">
                                            <span>{{ ucfirst($category->title) }}</span>
                                        </button>
                                    </h2>
                                    <div id="panelsStayOpen-collapseOne-{{ $key }}"
                                        class="accordion-collapse collapse {{ $loop->first ? 'show' : '' }}"
                                        aria-labelledby="panelsStayOpen-headingOne-{{ $key }}">
                                        <div class="accordion-body">
                                            <ul class="years-list">
                                                {{-- @if ($downloads[$key]->keys()->count()) --}}
                                                @foreach ($downloads[$key]->keys() as $year)
                                                    @if ($year != '')
                                                        <li><a href="{{ route('internal-web.show', ['year' => $year, 'category_slug' => $category->slug]) }}"
                                                                class="yearly">{{ $year }}</a></li>
                                                    @endif
                                                @endforeach
                                                {{-- @else --}}
                                                @foreach ($category->allChild as $child)
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

                @endif
            </div>
        </div>
    </section>
@endsection
