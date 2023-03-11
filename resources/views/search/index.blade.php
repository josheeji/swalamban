@extends('layouts.frontend.app')
@section('title', 'Search')

@section('content')

    <section id="pagetitle" style="background-image:url({{  asset('swabalamban/images/titlebg.jpg') }});">
        <div class="container">
            <h1>{{ trans('general.search-result') }}</h1>
            <ul>
                <li><a href="{{ route('home.index') }}">Home</a><i class="fas fa-chevron-right"></i></li>
                <li><a href="#">Search</a><i class="fas fa-chevron-right"></i></li>
                <li>{{ trans('general.search-result') }} </li>
            </ul>
        </div>
    </section>

    <section class="main-container" id="main-container">
        <div class="container">
            <div class="row">
                <div class="col-xs-12 col-sm-12 col-md-12 maintxt">
                    <p>There are <strong>{{ !empty($searchResults) ? $searchResults->count() : 0 }}</strong> results for
                        <strong>{{ $keyword }}</strong>.
                    </p>
                    @php
                        $totalPage = 0;
                        $paginate = 0;
                        $pagenateItem = 10;
                    @endphp
                    @if (isset($searchResults) && !empty($searchResults) && $searchResults->count() > 0)
                        @php
                            $totalPage = $searchResults->count() / $pagenateItem;
                            $page = Request::get('page') ? Request::get('page') : 1;
                            $total = $pagenateItem * $page;
                            $paginateKey = 0;

                        @endphp
                        @foreach ($searchResults->groupByType() as $type => $modelSearchResults)
                            @foreach ($modelSearchResults as $key => $searchResult)
                                @php
                                    $paginateKey++;
                                    if ($page = 1) {
                                        $check = $total - $pagenateItem;
                                    }
                                @endphp
                                @if ($check > $paginateKey - 1)
                                    @continue
                                @endif
                                @if ($total < $paginateKey)
                                    @continue
                                @endif
                                @php $paginate=$paginateKey / $pagenateItem; @endphp @php
                                    if ($searchResult->searchable->language_id != Helper::locale()) {
                                        continue;
                                    }
                                    $des = '';

                                    if (isset($searchResult->searchable->excerpt) && !empty($searchResult->searchable->excerpt)) {
                                        $des = Helper::searchKeyword($keyword, $searchResult->searchable->excerpt);
                                    }
                                    if (isset($searchResult->searchable->description) && !empty($searchResult->searchable->description) && empty($des)) {

                                        $des = Helper::searchKeyword($keyword, $searchResult->searchable->description);
                                    }

                                    if (is_array($des)) {
                                        $des = isset($des[0][0]) ? $des[0][0] : $des;
                                        $des = isset($des[0]) ? $des[0] : '';
                                    }

                                    switch ($searchResult->type) {
                                        case 'news':
                                            $url = $searchResult->url;
                                            break;
                                        case 'atm_locations':
                                            $url = url('/atm');
                                            break;
                                        case 'branch_directories':
                                            $url = url('branch');
                                            break;
                                        case 'downloads':
                                            $url = url('/download');
                                            break;
                                        case 'agm_reports':
                                            $url = url('/agm-reports');
                                            break;
                                        case 'financial_reports':
                                            $url = url('/reports');
                                            break;
                                        case 'faqs':
                                            $url = url('/faq');
                                            break;
                                        case 'teams':
                                            $url = url('/');
                                            break;
                                        case 'notices':
                                            $url = $searchResult->url;
                                            break;
                                        default:
                                            $url = $searchResult->url;
                                            break;
                                    }
                                @endphp


                                <div class="searchcard">
                                    <a href="{{ $url }}">{{ $searchResult->title }}</a>
                                    @if (!empty($des))
                                        <div class="searchtxt" >
                                            {!! print_r($des, true) !!}
                                        </div>

                                        <div class="clear"></div>
                                    @endif
                                </div>
                            @endforeach
                        @endforeach

                    @endif
                    <br>
                    <div class="paging text-center">
                        <ul class="pagination">
                            <li>
                                <a
                                    href="{{ url('/search') }}?keyword={{ Request::get('keyword') }}&page={{ round($paginate, 0, PHP_ROUND_HALF_UP) - 1 }}">
                                    <i class="icon icon-arrow-left"></i>
                                </a>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </section>

@endsection
