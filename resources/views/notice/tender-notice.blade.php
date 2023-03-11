@extends('layouts.frontend.app')
@section('title', 'News & Events' )
@section('style')

@endsection
@section('script')

@endsection

@section('page-banner')
<section class="bannertop">
    <div class="container">
        <div class="bannerimg parallax">
            <h1>Procurement Notice</h1>
        </div>
    </div>
</section>
<section class="bredcrum-inner">
    <div class="container">
        <div class="titleblock-inner">
            <ul>
                <li>
                    <a href="{{ route('home.index') }}"><i class="fas fa-home"></i> Home</a> <i class="fas fa-chevron-right"></i>
                </li>
                <li><a href="#!">Notice & Publications <i class="fas fa-chevron-right"></i></a></li>
                <li>Procurement Notice</li>
            </ul>
        </div>
    </div>
</section>
@endsection


@section('content')
<section id="inner-content">
    <div class="container">
        @if(isset($notices) && !empty($notices) && !$notices->isEmpty())
        <table class="download-table " id="">
            <thead>
                <tr>
                    <th width="5%">S.No.</th>
                    <th width="50%">Title</th>
                    <th width="20%" style=" text-align:center">Publish Date</th>
                    <th width="20%" style=" text-align:center">Submission Date</th>
                </tr>
            </thead>
            <tbody>
                @foreach($notices as $index => $data)
                <tr>
                    <td style=" text-align:center">{{ $index+1 }}</td>
                    <td><a href="{{ route('tender-notice.show', $data->slug) }}">{!! $data->title !!}</a></td>
                    <td style=" text-align:center"><i class="icon-clock"></i></span> {{ Helper::formatDate($data->start_date, 12) }}</td>
                    <td style=" text-align:center"><i class="icon-clock"></i></span> {{ Helper::formatDate($data->end_date, 12) }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
        @if(isset($notices) && !empty($notices) && !$notices->isEmpty($notices))
        {!! $notices->appends(request()->query())->links('layouts.frontend.inc.pagination') !!}
        @endif
        @else
        <div class="row">
            <div class="col-12">No record(s) found.</div>
        </div>
        @endif
    </div>
</section>
@endsection