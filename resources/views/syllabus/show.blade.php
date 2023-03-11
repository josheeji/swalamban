@extends('layouts.frontend.app')
@section('title', 'Syllabus List')

@section('content')
<!-- about start -->
<section class="inner-content">
    <div class="container">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('home.index') }}">{{ trans('general.home') }}</a></li>
                <li class="breadcrumb-item active" aria-current="page">{{ trans('general.syllabus') }}</li>
            </ol>
        </nav>
        <h1>{{ trans('general.syllabus') }}</h1>
        <div class="row">
            <div class="col-md-12 pd-right">
                <div class="table-responsive">
                    <table id="branchlist" class="table table-striped table-bordered ">
                        <thead>
                            <tr>
                                <th>{{ trans('general.s-no') }}</th>
                                <th>{{ trans('general.category_in_syllabus') }}</th>
                                <th>{{ trans('general.designation') }}</th>
                                <th>{{ trans('general.syllabus') }}</th>
                                <th>खुला/आ.प्र.</th>
                            </tr>
                        </thead>
                        <tbody>
                            @if($syllabus && $syllabus->count())
                            @foreach($syllabus as $index => $row)
                            <tr>
                                <td>{{ $row->iteration }}</td>
                                <td>{{ $row->category }}</td>
                                <td>{{ $row->designation }}</td>
                                <td><a target="_blank" href="{{ asset('storage/'.$row->file) }}"><i class="bi bi-cloud-download "></i></a></td>
                                <td>{{ $row->type == 1 ?'खुला':'आ.प्र.' }}</td>
                            </tr>
                            @endforeach
                            @else
                            <tr>
                                <td colspan="2">{{ trans('general.no-record-found') }}</td>
                            </tr>
                            @endif

                        </tbody>

                    </table>
                </div>
                {!! $syllabus->appends(request()->query())->links('layouts.frontend.inc.pagination') !!}
            </div>
        </div>
    </div>
</section>
<!-- about end -->
@endsection