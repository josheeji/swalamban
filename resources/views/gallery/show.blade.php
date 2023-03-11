@extends('layouts.frontend.app')
@section('title', 'Gallery : ' . $gallery->title)
@section('meta_keys', 'gallery,image,' . $gallery->title)
@section('meta_description', $gallery->title)
@section('content')
    <!-- Title/Breadcrumb -->
    <section id="pagetitle" style="background-image:url({{isset($gallery->banner) ?  asset('storage/' . @$gallery->banner) : asset('swabalamban/images/titlebg.jpg')  }});">
        <div class="container">
            <h1>{{@$gallery->title ?? 'Gallery'}}
            </h1>
            <ul>
                <li>
                    <a href="{{route('home.index')}}">{{trans('general.home')}}
                    </a>
                    <i class="fas fa-chevron-right">
                    </i>
                </li>
                <li>
                    <a href="{{route('gallery.index')}}">{{ trans('general.gallery') }}
                    </a>
                    <i class="fas fa-chevron-right">
                    </i>
                </li>
                <li>{{@$gallery->title ?? 'Gallery'}}
                </li>
            </ul>
        </div>
    </section>
    <!-- Title/Breadcrumb END -->

    <!-- Inner-Gallery -->
    <section id="inner-gallery" class="section-padding">
        <div class="container">
            <div id="lightgallery" class="row">
                @foreach ($galleryImages as $item)
                    @if ($item->image != '' && file_exists('storage/' . $item->image))
                        <a href="{{ asset('storage/' . $item->image) }}" data-lg-size="1600-2400"
                            class="col-xs-12 col-sm-6 col-md-6 col-lg-4 col-lg-4 ">
                            <img class="img-fluid" alt="img1" src="{{ asset('storage/' . $item->image) }}" />
                        </a>
                    @endif
                @endforeach
            </div>
            <div class="row">
                @include('layouts.frontend.inc.socialmedia')
                @include('layouts.frontend.inc.comments')
                {{ $galleryImages->links('vendor.pagination.custom') }}
            </div>
    </section>

@endsection
@section('script')
    <script type="text/javascript">
        lightGallery(document.getElementById('lightgallery'), {
            plugins: [lgZoom, lgThumbnail],
            speed: 500,
            animateThumb: false,
            zoomFromOrigin: false,
            allowMediaOverlap: true,
            toggleThumb: true,
        });
    </script>
@endsection
