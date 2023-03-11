<div class="col-md-3">
    <h3>{{trans('general.other-news-and-events')}}</h3>
    <div class="sidebar">
        <div class="side-menu">
            <ul>
                @if(isset($latest) && !$latest->isEmpty())
                @foreach($latest as $item)
                <li><a href="{{ route('news.show', [$item->category->slug, $item->slug]) }}">{{ $item->title }}</a>
                </li>
                @endforeach
                @endif
            </ul>
        </div>

        <div class="findbranch"><a href="{{route('branch.index')}}"> <img src="{{asset('frontend/images/branch.jpg')}}" alt="Find a Branch Image"> </a> </div>
        <iframe
        src="https://www.facebook.com/plugins/page.php?href=https%3A%2F%2Fwww.facebook.com%2Fjyotilifeinsurance&tabs=timeline&width=340&height=300&small_header=true&adapt_container_width=true&hide_cover=false&show_facepile=false&appId"
        width="100%" height="350" style="border:none;overflow:hidden" scrolling="no" frameborder="0"
        allowfullscreen="true"
        allow="autoplay; clipboard-write; encrypted-media; picture-in-picture; web-share"></iframe>
    </div>
</div>