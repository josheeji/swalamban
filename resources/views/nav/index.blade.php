@extends('layouts.frontend.app')
@section('title', 'Nav Details')
@section('script')
<script src="{{ asset('frontend/js/jquery.canvasjs.min.js') }}"></script>
<script>
    window.onload = function() {
        var dataPoints = {!! $data !!};
        var options = {
            animationEnabled: true,
            title: {
                text: "Net Asset Value"
            },
            axisX: {
                valueFormatString: "DD MMM",
                crosshair: {
                    enabled: true,
                    snapToDataPoint: true
                }
            },
            axisY: {
                title: "NAV",
                // valueFormatString: "$##0.00",
                crosshair: {
                    enabled: true,
                    snapToDataPoint: true,
                    labelFormatter: function(e) {
                        return "$" + CanvasJS.formatNumber(e.value, "##0.00");
                    }
                }
            },
            data: [{
                type: "area",
                dataPoints: dataPoints
            }]
        };

        $("#chartContainer").CanvasJSChart(options);
    }
    $('#form-table').on('submit', function(e) {
        e.preventDefault();
        var formData = {
            'category': $("#form-table #schema option:selected").val(),
            'type': $('#form-table #type option:selected').val()
        };
        $.ajax({
                type: 'POST', // define the type of HTTP verb we want to use (POST for our form)
                url: "{{ route('nav.table') }}", // the url where we want to POST
                data: formData, // our data object
                dataType: 'html', // what type of data do we expect back from the server
                encode: true
            })
            // using the done promise callback
            .done(function(data) {

                // log data to the console so we can see
                $('.table-wrap').html(data);

                // here we will handle errors and validation messages
            });

        // stop the form from submitting the normal way and refreshing the page
        event.preventDefault();
    })
</script>
@endsection
@section('content')
<section class="bannertop">
    <div class="container">
        <div class="bannerimg parallax">
            <h1>Nav Details</h1>
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
                <li>Nav Details</li>
            </ul>
        </div>
    </div>
</section>
<section class="maininner-container">
    <div class="container">
        <div class="row">
            <div class="col-xs-12 col-sm-12 col-md-9 maintxt">

                <div class="graph-wrap">
                    @include('nav.chart', ['data' => $data, 'categories' => $categories])
                </div>

                <div class="graphsearch">
                    <form id="form-table" method="post">
                        <div class="graph-div">
                            <span>Scheme</span>
                            <select id="schema" name="category">
                                @if(isset($categories))
                                @foreach($categories as $category)
                                <option value="{{ $category->id }}">{{ $category->title }}</option>
                                @endforeach
                                @endif
                            </select>
                        </div>
                        <div class="graph-div">
                            <span>Type</span>
                            <select id="type" name="type">
                                <option value="1">Weekly</option>
                                <option value="2">Monthly</option>
                            </select>
                        </div>
                        <div class="graph-div">
                            <button>Search</button>
                        </div>
                    </form>
                </div>
                <div class="table-wrap"></div>
            </div>
            <div class="col-xs-12 col-sm-12 col-md-3 mainsidewrapper">
                <div class="row">
                    <div class="col-xs-12 col-sm-6 col-md-12">
                        <div class="sidebox">
                            <h2>
                                {{ trans('general.press-release') }} <a href="{{ route('press-release') }}" class="customview">View All <i class="fas fa-chevron-right"></i></a>
                            </h2>
                            @php
                            $pressReleases = PageHelper::pressReleases();
                            @endphp
                            @if(isset($pressReleases))
                            <ul class="noticesection">
                                @foreach($pressReleases as $pressRelease)
                                <li>
                                    <div class="noticedate">{{ Helper::formatDate($pressRelease->start_date, 13) }}<span>{{ Helper::formatDate($pressRelease->start_date, 14) }}</span></div>
                                    <a href="{{ route('press-release.show', $pressRelease->slug) }}">{{ $pressRelease->title }}</a>
                                </li>
                                @endforeach
                            </ul>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection