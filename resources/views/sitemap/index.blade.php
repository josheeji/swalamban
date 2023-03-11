@inject('helper', 'App\Helper\Helper')
@inject('layout', 'App\Helper\LayoutHelper')
@inject('menuRepo', 'App\Repositories\MenuRepository')
@extends('layouts.frontend.app')
@section('title', 'Sitemap')
@section('style')
    <style>
        .hero-body::before {
            background: url("{{ asset('kumari/images/page-title/sitemap.jpg') }}") no-repeat center top;
            background-size: cover
        }

        .content ul {
            margin-left: 0
        }

        #content-main-wrap {
            min-height: 450px;
        }

        .tab-block {
            min-height: 350px;
        }
    </style>
@endsection
@section('script')
@endsection
@section('content')
    <section id="pagetitle" style="background-image:url({{ asset('swabalamban/images/titlebg.jpg') }});">
        <div class="container">
            <h1>{{ trans('general.sitemap') }}</h1>
            <ul>
                <li><a href="{{ route('home.index') }}">Home</a><i class="fas fa-chevron-right"></i></li>
                <li>{{ trans('general.sitemap') }} </li>
            </ul>
        </div>
    </section>
    <div id="content-main-wrap" class="is-clearfix">
        <section class="section has-background-primary-light is-clearfix">
            <div class="container">
                <div class="columns ">
                    <div class="column is-12">
                        <div class="box content">
                            <div class="columns ">
                                {{-- @if (isset($topMenuItems['parent']) && !empty($topMenuItems['parent']))
                                    @foreach ($topMenuItems['parent'] as $key => $item)
                                        <div class="column is-3">
                                            <a href="{!! $item['url'] !!}" {!! $item['target'] !!}>
                                                <h3 class="widget-title ">{!! $item['title'] !!}</h3>
                                            </a>
                                        </div>
                                    @endforeach
                                @endif --}}
                                @if (isset($menuItems['parent']) && !empty($menuItems['parent']))
                                    @foreach ($menuItems['parent'] as $key => $item)
                                        <div class="column is-3">
                                            @if (array_key_exists($key, $menuItems['child']))
                                                <h3 class="widget-title ">{!! $item['title'] !!}</h3>

                                                @foreach ($menuItems['child'][$key] as $lvl2 => $item2)
                                                    <div class="ps-3">
                                                        <a href="{!! $item2['url'] !!}" {!! $item2['target'] !!}>
                                                            <strong>{!! $item2['title'] !!}</strong>
                                                        </a>
                                                    </div>
                                                    @if (array_key_exists($lvl2, $menuItems['child']))
                                                        <ul class="list  no-style list-arrow-dropright">
                                                            @foreach ($menuItems['child'][$lvl2] as $lvl3 => $item3)
                                                                <li>
                                                                    <a href="{!! $item3['url'] !!}"
                                                                        {!! $item3['target'] !!}>
                                                                        {!! $item3['title'] !!}
                                                                    </a>
                                                                </li>
                                                            @endforeach
                                                        </ul>
                                                    @endif
                                                @endforeach
                                            @else
                                                <a href="{!! $item['url'] !!}" {!! $item['target'] !!}>
                                                    <h3 class="widget-title ">{!! $item['title'] !!}</h3>
                                                </a>
                                            @endif
                                        </div>
                                    @endforeach
                                @endif
                            </div>
                        </div>

                        <div class="box content">
                            <h3 class="widget-title ">Others </h3>
                            <div class="columns">
                                <div class="column is-3">
                                    @if (isset($usefulLinkItems) && !empty($usefulLinkItems))
                                        <strong>{{ trans('footer.about-us') }}</strong>
                                        <ul class="list no-style list-arrow-dropright">
                                            @foreach ($usefulLinkItems['parent'] as $item)
                                                @if (isset($item['title']) && !empty($item['title']))
                                                    <li> <a href="{{ isset($item['url']) ? $item['url'] : '' }}"
                                                            {{ isset($item['target']) && !empty($item['target']) ? 'target="_blank"' : '' }}>
                                                            {!! $item['title'] !!} </a> </li>
                                                @endif
                                            @endforeach
                                        </ul>
                                    @endif
                                </div>
                                <div class="column is-3">
                                    @if (isset($featureItems) && !empty($featureItems))
                                        <strong>{{ trans('footer.quick-links') }}</strong>
                                        <ul class="list no-style list-arrow-dropright">
                                            @foreach ($featureItems['parent'] as $item)
                                                @if (isset($item['title']) && !empty($item['title']))
                                                    <li> <a href="{{ isset($item['url']) ? $item['url'] : '' }}"
                                                            {{ isset($item['target']) && !empty($item['target']) ? 'target="_blank"' : '' }}>
                                                            {!! $item['title'] !!} </a> </li>
                                                @endif
                                            @endforeach
                                        </ul>
                                    @endif
                                </div>
                                <div class="column is-3">
                                    @if (isset($serviceItems) && !empty($serviceItems))
                                        <strong>{{ trans('footer.Useful Links') }}</strong>
                                        <ul class="list no-style list-arrow-dropright">
                                            @foreach ($serviceItems['parent'] as $item)
                                                @if (isset($item['title']) && !empty($item['title']))
                                                    <li> <a href="{{ isset($item['url']) ? $item['url'] : '' }}"
                                                            {{ isset($item['target']) && !empty($item['target']) ? 'target="_blank"' : '' }}>
                                                            {!! $item['title'] !!} </a> </li>
                                                @endif
                                            @endforeach
                                        </ul>
                                    @endif
                                </div>
                                <div class="column is-3">
                                    @if (isset($informationItems) && !empty($informationItems))
                                        <strong>{{ trans('footer.Information') }}</strong>
                                        <ul class="list no-style list-arrow-dropright">
                                            @foreach ($informationItems['parent'] as $item)
                                                @if (isset($item['title']) && !empty($item['title']))
                                                    <li> <a href="{{ isset($item['url']) ? $item['url'] : '' }}"
                                                            {{ isset($item['target']) && !empty($item['target']) ? 'target="_blank"' : '' }}>
                                                            {!! $item['title'] !!} </a> </li>
                                                @endif
                                            @endforeach
                                        </ul>
                                    @endif
                                </div>
                            </div>

                        </div>

                    </div>
                    <!-- .column -->
                </div>
                <!-- .columns -->


            </div>
        </section>
    </div>
@endsection
