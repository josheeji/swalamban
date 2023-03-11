@extends('layouts.backend.app')
@section('title', 'Dashboard')
@section('styles')

@endsection
@section('scripts')
<script src="{{ asset('backend/plugins/apexcharts.js') }}"></script>
<script>
    $(document).ready(function() {
        // Daterange picker
        // ------------------------------
        $('.daterange-ranges').daterangepicker({
                startDate: moment().subtract(29, 'days'),
                endDate: moment(),
                minDate: '01/01/2012',
                maxDate: moment(),
                dateLimit: {
                    days: 60
                },
                ranges: {
                    'Today': [moment(), moment()],
                    'Yesterday': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
                    'Last 7 Days': [moment().subtract(6, 'days'), moment()],
                    'Last 30 Days': [moment().subtract(29, 'days'), moment()],
                    'This Month': [moment().startOf('month'), moment().endOf('month')],
                    'Last Month': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')],
                    'This Year': [moment().startOf('year'), moment().endOf('year')],
                    'Last Year': [moment().subtract(1, 'year').startOf('year'), moment().subtract(1, 'year').endOf('year')],
                },
                opens: 'left',
                applyClass: 'btn-small bg-slate-600 btn-block',
                cancelClass: 'btn-small btn-default btn-block',
                format: 'MM/DD/YYYY'
            },
            function(start, end) {
                $('.daterange-ranges span').html(start.format('MMMM D') + ' - ' + end.format('MMMM D'));
                window.location.href = "{{ route('admin.dashboard') }}?start_date=" + start.format('YYYY-MM-DD') + "&end_date=" + end.format('YYYY-MM-DD')
            }
        );

        $('.daterange-ranges span').html(moment().subtract(7, 'days').format('MMMM D') + ' - ' + moment().format('MMMM D'));
    });
</script>
@endsection
@section('page-header')
<!--begin::Subheader-->
<div class="subheader py-2 py-lg-4  subheader-solid " id="kt_subheader">
    <div class=" container-fluid  d-flex align-items-center justify-content-between flex-wrap flex-sm-nowrap">
        <!--begin::Info-->
        <div class="d-flex align-items-center flex-wrap mr-2">
            <!--begin::Page Title-->
            <h5 class="text-dark font-weight-bold mt-2 mb-2 mr-5">Dashboard</h5>
            <!--end::Page Title-->
            <!--begin::Actions-->
            <div class="subheader-separator subheader-separator-ver mt-2 mb-2 mr-4 bg-gray-200"></div>
            <span class="text-dark-50 font-weight-bold" id="kt_subheader_total">Manage</span>
            <!--end::Actions-->
        </div>
        <!--end::Info-->


    </div>
</div>
<!--end::Subheader-->
@endsection
@section('content')
<!--begin::Entry-->
<div class="d-flex flex-column-fluid">
    <div class=" container ">
        <div class="row">

            <div class="col-12 col-md-6 d-none">
                <div class="card card-custom card-stretch gutter-b">
                    <div class="card-header border-0">
                        <h3 class="card-title font-weight-bolder text-dark">Browser Popularity</h3>
                    </div>
                    <div class="card-body pt-0">
                        <div id="browser-chart"></div>
                    </div>
                </div>
            </div>
            <div class="col-12 col-md-6 d-none">
                <div class="card card-custom card-stretch gutter-b">
                    <div class="card-header border-0">
                        <h3 class="card-title font-weight-bolder text-dark">Daily visitors</h3>
                    </div>
                    <div class="card-body pt-0">
                        <div id="visitor-chart"></div>
                    </div>
                </div>
            </div>
            @if(isset($visitedPages))
            <div class="col-lg-8 col-12">
                <div class="card card-custom card-stretch gutter-b">
                    <div class="card-header border-0">
                        <h3 class="card-title font-weight-bolder text-dark">Most Visited Pages</h3>
                    </div>
                    <div class="card-body pt-0">
                        <div class="table-responsive">
                            <table class="table">
                                <tr>
                                    <th width="">Title</th>
                                    <th>Page</th>
                                    <th width="10%">Views</th>
                                </tr>
                                @foreach($visitedPages as $page)
                                <tr>
                                    <td>{{ $page['pageTitle'] }}</td>
                                    <td>{{$page['url']}}</td>
                                    <td>{{$page['pageViews']}}</td>
                                </tr>
                                @endforeach
                            </table>
                        </div>
                    </div>
                </div>
            </div>
            @endif
            <div class="col-lg-4 col-12">
                <div class="card card-custom card-stretch gutter-b">
                    <div class="card-header border-0">
                        <h3 class="card-title font-weight-bolder text-dark">Search Trends</h3>
                    </div>
                    <div class="card-body pt-0">
                        <div class="table-responsive">
                            <table class="table">
                                <tr>
                                    <th>Keyword</th>
                                    <th>Count</th>
                                </tr>
                                @foreach($searchReport as $report)
                                <tr>
                                    <td>{{ $report->keyword }}</td>
                                    <td>{{ $report->total }}</td>
                                </tr>
                                @endforeach
                            </table>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-4 col-12">
                <div class="card card-custom card-stretch gutter-b">
                    <div class="card-header border-0 pt-5">
                        <h3 class="card-title font-weight-bolder ">Google Analytics</h3>
                    </div>
                    <div class="card-body d-flex flex-column">
                        <div class="pt-5">
                            <p class="text-center font-weight-normal font-size-lg pb-7">
                                Notes: Google Analytics is used to track website activity such as session duration, pages per session, bounce rate etc. of individuals using the site, along with the information on the source of the traffic
                            </p>
                            <a href="{{ route('admin.google-analytics') }}" class="btn btn-success btn-shadow-hover font-weight-bolder w-100 py-3">View Report</a>
                        </div>
                    </div>
                    <!--end::Body-->
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
