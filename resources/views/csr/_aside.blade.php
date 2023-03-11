@php
$title = $news->title;
$title = isset($news->parent) ? $news->parent->title : $title;
$title = isset($news->parent->parent) ? $news->parent->parent->title : $title;
$title = isset($news->parent->parent->parent) ? $news->parent->parent->parent->title : $title;
@endphp

<div class="card-category">
    <h3 class="sidebar-title">{{ trans('general.categories') }}</h3>
    @php
    $menuList = PageHelper::newsCategories();
    @endphp
    @if(isset($menuList) && !empty($menuList))
    <ul class="">
        @foreach($menuList as $item)
        <li><a href="{{ route('news.category', $item->slug) }}"><i class="fas fa-chevron-right"></i>{{ $item->title }}</a> </li>
        @endforeach
    </ul>
    @endif
</div>
<div class="card-blog-list">
    <h3 class="sidebar-title">{{ trans('general.latest-updates') }} <a href="{{ route('news.index') }}" class="customview float-md-right d-none">View All</a></h3>
    @php
    $news = PageHelper::news($news->id);
    @endphp
    @if(isset($news))
    <ul class="noticesection">
        @foreach($news as $data)
        @if($data->category)
        <li>
            <div class="listings-left">
                <a href="{{ route('news.show', [$data->category->slug, $data->slug]) }}">
                    @if(file_exists('storage/'.$data->image) && $data->image != '')
                    <img src="{{ asset('storage/'. $data->image) }}" alt="">
                    @else
                    <img src="{{ asset('frontend/images/no-img.jpg') }}" alt="">
                    @endif
                </a>
            </div>
            <div class="listings-right">
                <a href="{{ route('news.show', [$data->category->slug, $data->slug]) }}">{{ $data->title }}</a>
                <div class="listing-date">{{ Helper::formatDate($data->published_date) }}</div>
            </div>
        </li>
        @endif
        @endforeach
    </ul>
    @endif
</div>
@php
$advertisements = PageHelper::advertisements('', $placement);
@endphp
@if(isset($advertisements) && !empty($advertisements))
@foreach($advertisements as $key => $item)
@if(!empty($item['link']))
<a href="{{ $item['link'] }}" target="_blank">
    @endif
    <img src="{{ asset('storage/' . $item['image']) }}" alt="">
    @if(!empty($item['link']))
</a>
@endif
@endforeach
@endif