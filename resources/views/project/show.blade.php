@inject('helper', 'App\Helper\Helper')
@inject('layout', 'App\Helper\LayoutHelper')
@inject('menuRepo', 'App\Repositories\MenuRepository')
@inject('pageHelper', 'App\Helper\PageHelper')
@php
$adsTop = $pageHelper->advertisements('news', 2);
$adsBottom = $pageHelper->advertisements('news', 4);
@endphp
@extends('layouts.frontend.app')
@section('script')
{!! isset($schema) && !empty($schema) ? $schema : '' !!}
@endsection
@section('content')
@if($project->banner != '' && file_exists('storage/'. $project->banner))
<img src="{{ asset('storage/'.$project->banner) }}" alt="">
@endif
<section class="inner-content">
    <div class="container">
        @include('project._header', ['project' => $project])

        @include('project._content', ['project' => $project])
    </div>
</section>
@endsection