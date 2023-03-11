<h2>{{ trans('general.other_projects') }}</h2>
<div class="sidebar">
    <div class="side-menu">
        @if(isset($latest) && !empty($latest))
        <ul class="">
            @foreach($latest as $item)
            <li><a href="{{ route('project.show', $item->slug) }}">{{ $item->title }}</a> </li>
            @endforeach
        </ul>
        @endif
    </div>
</div>