@extends('layouts.frontend.app')
@section('title', 'Careers')

@section('content')
<section class="inner-content">
    <div class="container">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('home.index') }}">{{ trans('general.home') }}</a></li>
                <li class="breadcrumb-item active" aria-current="page">{{ trans('general.careers') }}</li>
            </ol>
        </nav>
        <h1>{{ trans('general.careers') }}</h1>
        <div class="row">
            <div class="col-md-8 pd-right">
                @if($downloads && $downloads->count())
                <div class="table-responsive">
                    <table id="branchlist" class="table table-striped table-bordered ">
                        <thead>
                            <tr>
                                <th>{{ trans('general.title') }}</th>
                                <th style="width:11rem">{{ trans('general.downloads') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($downloads as $index => $download)
                            <tr>
                                <td><a href="#">{{ $download->title }}</a> <br><small><i class="bi bi-calendar-check"></i> {{ Helper::formatDate($download->published_date,6) }}
                                    </small></td>

                                <td>
                                    @if(file_exists('storage/'.$download->file) && $download->file != '')
                                    <a target="_blank" href="{{ asset('storage/' . $download->file) }}" class="btn hvr-sweep-to-right" target="_blank"><i class="bi bi-cloud-download "></i> {{ trans('general.download') }} </a>
                                    @else
                                    <a href="#!" class="btn hvr-sweep-to-right"><i class="bi bi-cloud-download "></i> {{ trans('general.download') }}</a>
                                    @endif
                                </td>
                            </tr>
                            @endforeach

                        </tbody>

                    </table>
                </div>
                {!! $downloads->appends(request()->query())->links('layouts.frontend.inc.pagination') !!}
                @else
                {{ trans('general.no-record-found') }}
                @endif
            </div>
            @if(isset($categories) && $categories->count())
            <div class="col-md-4">
                <div class="sidebar">
                    <div class="side-menu">
                        <ul>
                            @forelse($categories as $row)
                            <li>
                                <a href="{{ route('download.show',$row->slug) }}">{{ $row->title }} </a>
                            </li>
                            @empty
                            @endforelse

                        </ul>
                    </div>
                </div>
            </div>
            @endif
        </div>
    </div>
</section>
@endsection