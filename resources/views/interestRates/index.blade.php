@extends('layouts.frontend.app')
@section('script')
{!! isset($schema) && !empty($schema) ? $schema : '' !!}
<script type="text/javascript">
    $('select[name="batch"]').change(function(e){
        e.preventDefault();

        $('form.filter-interest-rates').submit();
    });
</script>
@endsection
@section('content')
<section class="bannertop">
    <div class="container">
        <div class="bannerimg parallax">
            <h1>Interest Rates</h1>
            
            <ul class="header-bottom-navi">
                <li><a href="{{ route('home.index') }}">{{ trans('general.home') }}</a><i class="fas fa-chevron-right"></i></li>
                <li><a href="javascript:void(0);">Interest Rates</a></li>
            </ul>
        </div>
    </div>
</section>
<section class="maininner-container ">
    <div class="container">
        <div class="row">
            <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 mb-2">
                <form method="GET" class="filter-interest-rates">
                    <div class="form-inline">
                        <label class="mr-2">Date Filter :</label>
                        <select name="batch" class="form-control">
                            @foreach($interestBatches as $batch)
                                <option value="{{ $batch->id }}" @if(isset($param['batch']) && ($param['batch'] == $batch->id)) selected @endif>{{ $batch->title }}</option>
                            @endforeach
                        </select>
                    </div>
                </form>
            </div>
            @if($intBatch)
                <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                    <h2>{{ $intBatch->title }}</h2>
                </div>
            @endif
            <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 maintxt">
                @php
                    $counter = 1;
                @endphp
                @if($intBatch)
                    @foreach($intBatch->interestRates as $key=>$interestRate)
                        @if($interestRate->content != null)
                            <h3>{{ $counter.'. '.$interestTypes[$interestRate->type] }}</h3>
                            {!! $interestRate->content !!}
                            @php
                                $counter++;
                            @endphp
                        @endif
                    @endforeach
                @endif
            </div>
        </div>
</section>
@endsection