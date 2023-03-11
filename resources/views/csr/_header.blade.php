@section('title', $news->title )
@section('style')
@if($news->banner != '' && file_exists('storage/' . $news->banner))
<style>
    .bannertop {
        background: #f9f9f9 url("{{ asset('storage/' . $news->banner) }}") no-repeat top center !important;
    }
</style>
@endif
@endsection
@section('script')

@endsection
@section('page-banner')
<section class="bannertop">
    <div class="container">
        <div class="bannerimg parallax">
            <h1>{!! $news->title !!}</h1>
            <div class="banner-txt">{!! $news->excerpt !!}</div>
            <ul class="header-bottom-navi">
                <li>
                    <a href="{{ route('home.index') }}">{{ trans('general.home') }}</a><i class="fas fa-chevron-right"></i>
                </li>
                @if($news->category)
                <li class=""><a href="{{ route('csr.index') }}">{!! $news->category->title !!}</a> <i class="fas fa-chevron-right"></i></li>
                @endif
                <li><a href="javascript:void(0);">{!! $news->title !!}</a></li>
            </ul>
        </div>
    </div>
</section>
@endsection