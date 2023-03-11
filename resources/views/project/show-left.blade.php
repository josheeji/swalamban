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
        <div class="row">
            <div class="col-md-8 pd-right">
                @include('project._content', ['project' => $project])
            </div>
            <div class="col-md-4">
                @include('project._aside', ['latest' => $latest, 'placement' => 2])
            </div>
        </div>
    </div>
</section>
@endsection