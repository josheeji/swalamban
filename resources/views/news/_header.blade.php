<section class="content-pd breadcrumb-wrap">
    <div class="container">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{route('home.index')}}">{{trans('general.home')}}</a></li>
                <li class="breadcrumb-item"><a href="{{route('news.index')}}">{{trans('general.news-and-events')}}</a></li>
                {{-- @if($news->category)
                <li class="breadcrumb-item">
                    <a href="{{ route('news.category', [$news->category->slug]) }}">{!! $news->category->title !!}</a>
                </li>
                @endif --}}
                <li class="breadcrumb-item active" aria-current="page">{!! $news->title !!}</li>
            </ol>
        </nav>
        <h1> {!! $news->title !!}</h1>
    </div>
</section>