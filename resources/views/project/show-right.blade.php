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
        <br>
        <div class="row">
            <div class="col-md-4">
                @include('project._aside', ['latest' => $latest])
            </div>
            <div class="col-md-8 pd-left">
                @include('project._content', ['project' => $project])
            </div>
        </div>
    </div>
</section>
@endsection