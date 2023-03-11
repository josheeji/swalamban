<h1>{{ $project->title }}</h1>
@if(file_exists('storage/' . $project->image) && $project->image != '' && $project->show_image)
<img class="blog-detail-img mb-3" alt="" src="{{ asset('storage/'. $project->image ) }}">
@endif
{!! $project->description !!}
@if(!empty($project->document))
<a href="{{ asset('storage/'.$project->document) }}" target="_blank" class="btn"><i class="fa fa-file-pdf"></i> View Document</a>
@endif

{{--<nav class="pagination single-post is-centered d-none">--}}
{{-- @if(isset($prev) && !empty($prev))--}}
{{-- <a href="{{ url('/news/'.$prev->slug) }}" class="pagination-previous">Prev</a>--}}
{{-- @endif--}}
{{-- @if(isset($next) && !empty($next))--}}
{{-- <a href="{{ url('/news/'.$next->slug) }}" class="pagination-next">Next</a>--}}
{{-- @endif--}}
{{--</nav>--}}