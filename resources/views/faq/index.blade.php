@extends('layouts.frontend.app')
@section('title', 'FAQs')
@section('style')
@endsection
@section('script')
    <script>
        $('.btn-faq').on('click', function() {
            $('.btn-faq').removeClass('isactive');
            var index = $(this).data('index');
            $(this).addClass('isactive');
            $('.bs-example').hide();
            $('.faq-' + index).removeClass('d-none').show();
        })
    </script>
@endsection
@section('content')

    <section class="content-pd breadcrumb-wrap">
        <div class="container">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('home.index') }}">{{ trans('general.home') }}</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('faq.index') }}">{{ trans('general.faq') }}</a></li>
                    <li class="breadcrumb-item active" aria-current="page">{{ $category?->title }}</li>
                </ol>
            </nav>
            <h1> {{ $category?->title }}</h1>
        </div>
    </section>


    <!-- inner content start -->
    <section class="content-pd inner-content  ">
        <div class="container">
            <div class="row">
                <div class="col-lg-9 col-md-8 pd-right ">
                    <div class="faq accordion" id="accordionExample">
                        @if (isset($category) && !empty($category))
                            <div class="accordion-item">
                                @foreach ($category->activeFaq as $idx => $item)
                                    <h2 class="accordion-header" id="heading-{{ $idx }}">
                                        <button class="accordion-button {{ $idx == 0 ? '' : 'collapsed' }}" type="button"
                                            data-bs-toggle="collapse" data-bs-target="#collapse-{{ $idx }}"
                                            aria-expanded="{{ $idx == 0 ? 'true' : 'false' }}"
                                            aria-controls="collapse- {{ $idx }}">
                                            {!! $item->question !!} </button>
                                    </h2>
                                    <div id="collapse-{{ $idx }}"
                                        class="accordion-collapse collapse {{ $idx == 0 ? 'show' : '' }}"
                                        aria-labelledby="heading-{{ $idx }}" data-bs-parent="#accordionExample">
                                        <div class="accordion-body"> {!! $item->answer !!}</div>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            {{ trans('general.no-record-found') }}
                        @endif

                        <br>

                        <div class="sharethis-inline-share-buttons"></div>
                    </div>
                    <!-- accordian end -->
                </div>
                <div class="col-lg-3 col-md-4 ">
                    @if (isset($faqCategories) && !$faqCategories->isEmpty())
                        <h3>{{ trans('general.categories') }}</h3>
                        <div class="side-menu">
                            <ul>
                                @foreach ($faqCategories as $cat)
                                    <li><a href="{{ route('faq.category', $cat->slug) }}">{{ $cat?->title }}</a></li>
                                @endforeach
                            </ul>
                            <div class="clear"></div>
                        </div>
                    @endif

                    <div class="findbranch"><a href="{{ route('branch.index') }}"> <img
                                src="{{ asset('frontend/images/branch.jpg') }}" alt="Find a Branch Image"> </a> </div>
                    <iframe
                        src="https://www.facebook.com/plugins/page.php?href=https%3A%2F%2Fwww.facebook.com%2Fjyotilifeinsurance&tabs=timeline&width=340&height=300&small_header=true&adapt_container_width=true&hide_cover=false&show_facepile=false&appId"
                        width="100%" height="350" style="border:none;overflow:hidden" scrolling="no" frameborder="0"
                        allowfullscreen="true"
                        allow="autoplay; clipboard-write; encrypted-media; picture-in-picture; web-share"></iframe>
                </div>
            </div>
    </section>
    <!-- inner content end -->
@endsection
