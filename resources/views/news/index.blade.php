@extends('layouts.frontend.app')
@section('title', @$menu->title ?? 'News & Events')
@section('meta_keys', @$menu->title ?? 'News & Events')
@section('meta_description', @$menu->title ?? 'News & Events')

@section('content')
    <!-- Title/Breadcrumb -->
    <section id="pagetitle" style="background-image:url({{ isset($menu->image) ? asset('storage/'.@$menu->image) : asset('swabalamban/images/titlebg.jpg') }});">
        <div class="container">
            <h1>{{@$menu->title}}
            </h1>
            <ul>
                <li>
                    <a href="{{route('home.index')}}">{{trans('general.home')}}
                    </a>
                    <i class="fas fa-chevron-right">
                    </i>
                </li>
                <li>{{@$menu->title ?? trans('general.news-and-events')}}
                </li>
            </ul>
        </div>
    </section>
    <!-- Title/Breadcrumb END -->
    <section id="news" class="section-padding" style="padding-top: ;">
        <div class="container">
            <div class="row">
                @include('layouts.frontend.inc.socialmedia')
                @include('layouts.frontend.inc.comments')
                @foreach ($news as $item)
                    <div class="col-xs-12 col-sm-12 col-md-6 col-lg-4 col-xl-4 ">
                        <div class="item">
                            <div class="news-update">
                                <div class="publish-date">
                                    <span>on {{ date('d M, Y', strtotime($item->published_date)) }}
                                    </span>
                                    <a href="javascript:void(0);" class="news-category">{{@$item->cat_title}}
                                    </a>
                                </div>
                                <div class="news-title notice">
                                    <a href="{{ route('news.show', ['category'=>$item->cat_slug,'slug' => $item->slug]) }} " class="">{{$item->title}}
                                    </a>
                                    @if (now()->subDays(7)->format('y-m-d') <= $item->published_date->format('y-m-d'))
                                            <span>New
                                            </span>
                                        @endif
                                </div>
                                <p>
                                <!-- {!! str_limit($item->excerpt ?? $item->description, 100)!!} -->
                                {!! str_limit($item->excerpt, 100) ?? str_limit($item->description, 100) !!}
                                </p>
                                <div class="read-more">
                                    <a href="{{ route('news.show', ['category'=>$item->cat_slug,'slug' => $item->slug]) }} " class=""> Read more
                                        <i class="fal fa-plus">
                                        </i>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach

                {{ $news->links('vendor.pagination.custom') }}

                </nav>
            </div>
        </div>
    </section>
@endsection
