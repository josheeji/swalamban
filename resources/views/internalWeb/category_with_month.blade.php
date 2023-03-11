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
    <section id="inner-conatiner" class="section-padding">
        <div class="container">
            <div class="row">
                @include('layouts.frontend.inc.socialmedia')
                @include('layouts.frontend.inc.comments')
                <div class="col-xs-12 col-sm-12 col-md-12 col-lg-8 col-xl-8">
                    <div class="row">
                        @if ($category->show_upload_file == 1)
                            <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 col-xl-12">
                                @if (Session::has('flash_success'))
                                    <div class="alert alert-success alert-dismissible flash_message" role="alert">
                                        <strong><i class="icon fa fa-check mr-2"></i></strong> {!! Session::get('flash_success') !!}
                                        <button type="button" class="btn-close" data-bs-dismiss="alert"
                                            aria-label="Close"></button>
                                    </div>
                                @endif
                                <form class="contact-form" enctype="multipart/form-data" method="POST"
                                    action="{{ route('internal-web.upload') }}">
                                    {!! csrf_field() !!}
                                    <div class="career-form">
                                        <div class="row ">
                                            <input type="hidden" value="{{ $category->id }}" name="category_id">
                                            <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 col-xl-12">
                                                <div class="mb-4">
                                                    <label for="floatingTextarea">Title <span
                                                            class="asterisk">*</span></label>
                                                    <input type="text" class="form-control" placeholder="Title"
                                                        name="title" value="{{ old('title') }}" required="">
                                                    @if ($errors->has('title'))
                                                        <div class="error text-danger">{{ $errors->first('title') }}</div>
                                                    @endif
                                                </div>

                                            </div>
                                            <div class="col-xs-12 col-sm-12 col-md-4 col-lg-4 col-xl-4">
                                                <div class="mb-4">
                                                    <label for="floatingTextarea">Attach File <span
                                                            class="asterisk">*</span></label>
                                                    <input type="file" class="form-control" placeholder="file"
                                                        name="file" value="{{ old('file') }}" required="">
                                                    @if ($errors->has('file'))
                                                        <div class="error text-danger">{{ $errors->first('file') }}</div>
                                                    @endif
                                                </div>

                                            </div>
                                            <div class="col-xs-12 col-sm-12 col-md-3 col-lg-3 col-xl-3">
                                                <div class="mb-4">
                                                    <label for="floatingTextarea">Year <span
                                                            class="asterisk">*</span></label>
                                                    <select name="year" class="form-control">
                                                        <option value="">Select Year</option>
                                                        @foreach (PageHelper::year() as $value)
                                                            <option value="{{ $value }}">
                                                                {{ $value }}</option>
                                                        @endforeach
                                                    </select>
                                                    @if ($errors->has('year'))
                                                        <div class="error text-danger">{{ $errors->first('year') }}</div>
                                                    @endif
                                                </div>

                                            </div>
                                            <div class="col-xs-12 col-sm-12 col-md-3 col-lg-3 col-xl-3">
                                                <div class="mb-4">
                                                    <label for="floatingTextarea">Month <span
                                                            class="asterisk">*</span></label>
                                                    <select name="month" class="form-control">
                                                        <option value="">Select Month</option>
                                                        @foreach (PageHelper::month() as $key => $value)
                                                            <option value="{{ $key }}">
                                                                {{ $value }}</option>
                                                        @endforeach
                                                    </select>
                                                    @if ($errors->has('month'))
                                                        <div class="error text-danger">{{ $errors->first('month') }}</div>
                                                    @endif
                                                </div>

                                            </div>
                                            <div class="col-xs-12 col-sm-12 col-md-2 col-lg-2 col-xl-2">
                                                <button class="btn btn-primary tw-mt-30" type="submit">Upload File</button>
                                            </div>
                                        </div>

                                    </div>
                                </form>
                            </div>
                        @endif

                        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 col-xl-12">
                            @if (count($files))

                                <div class="leftsection">
                                    @foreach ($files as $key => $items)
                                        @if ($key != '')
                                            <div class="acts">
                                                <h2 class="interanlweb-title">{{ $category->title }} - {{ $key }}
                                                </h2>
                                            </div>
                                            {{-- @dd($items) --}}
                                            @foreach ($items as $month => $item)
                                                <div class="acts-grp">
                                                    @if ($month != '')
                                                        <div class="months">{{ PageHelper::getMonthName($month) }}
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
                                                                @foreach ($item as $i => $data)
                                                                    <tr>
                                                                        <td class="text-center">{{ ++$i }}
                                                                        </td>
                                                                        <td>
                                                                            <a href=""
                                                                                class="d-title">{{ $data->title }}
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
                                                                                <a href="{!! asset('storage/' . $data->file) !!}"
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
                                        @else
                                            <div class="acts">
                                                <h2 class="interanlweb-title">{{ $category->title }} - Uncategorized Year
                                                </h2>
                                            </div>
                                            <div class="acts-grp">

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
                                                            @foreach ($items->first() as $i => $data)
                                                                <tr>
                                                                    <td class="text-center">{{ ++$i }}
                                                                    </td>
                                                                    <td>
                                                                        <a href=""
                                                                            class="d-title">{{ $data->title }}
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
                                                                            <a href="{!! asset('storage/' . $data->file) !!}"
                                                                                class="loding" target="_blank">Download
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
