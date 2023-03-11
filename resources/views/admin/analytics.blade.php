@extends('layouts.backend.app')
@section('scripts')
<script src="{{ asset('backend/plugins/apexcharts.js') }}"></script>
<script>
    var options = {
        series: <?php echo $browser_value_json; ?>,
        chart: {
            type: 'donut',
        },
        labels: <?php echo $browser_json; ?>,
        responsive: [{
            breakpoint: 480,
            options: {
                chart: {
                    width: 200
                },
                legend: {
                    position: 'bottom'
                }
            }
        }]
    };

    var chart = new ApexCharts(document.querySelector("#browser-chart"), options);
    chart.render();

    var options = {
        series: [{
            name: "Visitors",
            data: <?php echo $visitors_json; ?>
        }, {
            name: "Page views",
            data: <?php echo $page_views_json; ?>
        }],
        chart: {
            type: 'line',
            zoom: {
                enabled: false
            }
        },
        dataLabels: {
            enabled: false
        },
        stroke: {
            curve: 'straight'
        },
        title: {
            text: 'Visitor Trends by date',
            align: 'left'
        },
        grid: {
            row: {
                colors: ['#f3f3f3', 'transparent'], // takes an array which will be repeated on columns
                opacity: 0.5
            },
        },
        xaxis: {
            categories: <?php echo $dates_json; ?>,
            type: 'datetime'
        }
    };

    var chart = new ApexCharts(document.querySelector("#visitor-chart"), options);
    chart.render();

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
            <h5 class="text-dark font-weight-bold mt-2 mb-2 mr-5">Analytics Report</h5>
            <!--end::Page Title-->
            <!--begin::Actions-->
            <div class="subheader-separator subheader-separator-ver mt-2 mb-2 mr-4 bg-gray-200"></div>
            <span class="text-dark-50 font-weight-bold" id="kt_subheader_total">Manage</span>
            <!--end::Actions-->
        </div>
        <!--end::Info-->

        <!--begin::Toolbar-->
        <div class="d-flex align-items-center">
            <button type="button" class="btn btn-secondary daterange-ranges">
                <i class="fa fa-calendar"></i> <span></span> <b class="caret"></b>
            </button>
        </div>
        <!--end::Toolbar-->
    </div>
</div>
<!--end::Subheader-->
@endsection
@section('content')
<div class="d-flex flex-column-fluid">
    <div class=" container ">
        <div class="row">
            <div class="col-12 col-md-6">
                <div class="card card-custom card-stretch gutter-b">
                    <div class="card-header border-0">
                        <h3 class="card-title font-weight-bolder text-dark">Browser Popularity</h3>
                    </div>
                    <div class="card-body pt-0">
                        <div id="browser-chart"></div>
                    </div>
                </div>
            </div>
            <div class="col-12 col-md-6">
                <div class="card card-custom card-stretch gutter-b">
                    <div class="card-header border-0">
                        <h3 class="card-title font-weight-bolder text-dark">Daily visitors</h3>
                    </div>
                    <div class="card-body pt-0">
                        <div id="visitor-chart"></div>
                    </div>
                </div>
            </div>
            <div class="col-lg-12 col-12">
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
        </div>
    </div>
</div>

<!-- Dashboard content -->
<div class="row">
    <div class="col-lg-12">
        <!-- Main charts -->
        <div class="row">

            <div class="col-lg-6">

                <!-- Sales stats -->
                <div class="panel panel-flat">

                    <div class="panel-body">
                        <div class="chart-container">
                            <div class="chart has-fixed-height" id="line_stacked"></div>
                        </div>
                    </div>
                </div>
                <!-- /sales stats -->

            </div>

            <div class="col-lg-6">
                <div class="panel panel-flat">
                    <div class="panel-body">
                        <div class="chart-container has-scroll">
                            <div class="chart has-fixed-height has-minimum-width" id="pie_donut"></div>
                        </div>
                    </div>
                </div>
            </div>
            @can('master-policy.perform', ['sales-report', 'view'])
            <div class="col-lg-6">
                <div class="panel panel-flat">
                    <div class="panel-heading">
                        <h6 class="panel-title">Sales Reporting</h6>

                    </div>
                    <div class="panel-body">
                        <div class="row">
                            <div class="col-sm-4">
                                <div class="d-flex align-items-center justify-content-center mb-2">
                                    <a href="#" class="btn bg-transparent border-teal text-teal rounded-round border-2 btn-icon mr-3">
                                        <i class="icon-cash4"></i>

                                        <div>
                                            <div class="font-weight-semibold">NPR</div>
                                            <span class="text-muted">{{ number_format($sales_collection_npr,2) }}</span>
                                        </div>
                                    </a>
                                </div>
                                <div class="w-75 mx-auto mb-3" id="new-visitors"></div>
                            </div>

                            <div class="col-sm-4">
                                <div class="d-flex align-items-center justify-content-center mb-2">
                                    <a href="#" class="btn bg-transparent border-warning-400 text-warning-400 rounded-round border-2 btn-icon mr-3">
                                        <i class="icon-cash2"></i>

                                        <div>
                                            <div class="font-weight-semibold">INR</div>
                                            <span class="text-muted">{{ number_format($sales_collection_inr / 1.6,2) }}</span>
                                        </div>
                                    </a>
                                </div>
                                <div class="w-75 mx-auto mb-3" id="new-sessions"></div>
                            </div>

                            <div class="col-sm-4">
                                <div class="d-flex align-items-center justify-content-center mb-2">
                                    <a href="#" class="btn bg-transparent border-indigo-400 text-indigo-400 rounded-round border-2 btn-icon mr-3">
                                        <i class=" icon-cash"></i>

                                        <div>
                                            <div class="font-weight-semibold">USD</div>
                                            <span class="text-muted"><span class="badge badge-mark border-success mr-2"></span> {{ number_format($sales_collection_usd,2) }}</span>
                                        </div>
                                    </a>
                                </div>
                                <div class="w-75 mx-auto mb-3" id="total-online"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            @endcan
        </div>
    </div>
</div>
<!-- /dashboard content -->
@endsection