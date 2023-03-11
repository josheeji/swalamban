@inject('helper', 'App\Helper\Helper')
@inject('financialModel', 'App\Models\FinancialReport')
@inject('layout', 'App\Helper\LayoutHelper')
@inject('menuRepo', 'App\Repositories\MenuRepository')
@extends('layouts.frontend.app')
@section('title', 'Reports')
@section('style')

@endsection

@section('script')

@endsection
@section('content')

    <!-- product start -->
    @if (isset($categories) && count($categories) > 0)
        <section class="content-pd product " style="background:#fff">
            <div class="container">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('home.index') }}">{{ trans('general.home') }}</a>
                        </li>
                        @if (isset($category) && !empty($category))
                            <li class="breadcrumb-item">{{ trans('general.reports-and-disclosures') }}
                            </li>
                            <li class="breadcrumb-item active" aria-current="page">{{ $category->title }}</li>
                        @else
                            <li class="breadcrumb-item active" aria-current="page">
                                {{ trans('general.reports-and-disclosures') }}
                            </li>
                        @endif
                    </ol>
                </nav>
                <h1>{{ isset($category) && !empty($category) ? $category->title : trans('general.reports-and-disclosures') }}
                </h1>

                <ul class="nav nav-tabs mb-3 justify-content-center" id="pills-tab" role="tablist">
                    @foreach ($company as $k => $comp)
                        <li class="nav-item" role="presentation">
                            <button @if ($k == 1) class="nav-link active" @else class="nav-link" @endif
                                id="pills-{{ $comp->id }}-tab" data-bs-toggle="pill"
                                data-bs-target="#pills-{{ $comp->id }}" type="button" role="tab"
                                aria-controls="pills-{{ $comp->id }}" aria-selected="false">{{ $comp->name }}</button>
                        </li>
                    @endforeach
                </ul>
                <div class="tab-content" id="pills-tabContent">
                    @foreach ($company as $key => $item)
                        <div @if ($key == 1) class="tab-pane fade show active" @else class="tab-pane fade" @endif
                            id="pills-{{ $item->id }}" role="tabpanel" aria-labelledby="pills-{{ $item->id }}">
                            <div class="row">
                                <div class="col-md-12 pd-right">
                                    @php
                                        $reports = $financialModel
                                            ->where('is_active', 1)
                                            ->where('company_id', $item->id)
                                            ->orderBy('display_order', 'asc')
                                            ->orderBy('created_at', 'desc');
                                        if (!empty($category)) {
                                            $reports = $reports->where('category_id', $category->id);
                                        }
                                        $reports = $reports->where('language_id', Helper::locale())->paginate(100);
                                    @endphp
                                    <div class="row gridlist ">
                                        @if (isset($reports) && !$reports->isEmpty())
                                            @foreach ($reports as $report)
                                                <div class="col-lg-4">
                                                    <div class="grid">
                                                        <div class="grid-desc">

                                                            <h2><a href="{{ asset('storage/' . $report->file) }}">{{ $report->title }}
                                                                </a></h2>
                                                            <a href="{{ asset('storage/' . $report->file) }}"><i
                                                                    class="bi bi-file-earmark-pdf"></i>
                                                                Download</a>
                                                        </div>

                                                    </div>
                                                </div>
                                                <!-- col end -->
                                                <!-- <br> -->
                                            @endforeach
                                            {!! $reports->appends(request()->query())->links('layouts.frontend.inc.pagination') !!}
                                        @else
                                            <p>{{ trans('general.no-record-found') }}</p>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
                <!-- tab block end -->
            </div>
        </section>
    @endif
    <!-- product end -->
@endsection
