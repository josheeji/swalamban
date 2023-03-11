<nav aria-label="breadcrumb">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{ route('home.index') }}">{{ trans('general.home') }}</a></li>
        <li class="breadcrumb-item"><a href="{{ route('project.index') }}">{{ trans('general.projects') }}</a></li>
        <li class="breadcrumb-item active" aria-current="page">{!! $project->title !!}</li>
    </ol>
</nav>