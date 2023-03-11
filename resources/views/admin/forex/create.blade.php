@extends('layouts.backend.app')
@section('title', 'Financial Report - create')
@section('scripts')
<script>
    $('form').submit(function() {
        $(this).find("button[type='submit']").prop('disabled', true);
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
            <h5 class="text-dark font-weight-bold mt-2 mb-2 mr-5">Forex</h5>
            <!--end::Page Title-->
            <!--begin::Actions-->
            <div class="subheader-separator subheader-separator-ver mt-2 mb-2 mr-4 bg-gray-200"></div>
            <span class="text-dark-50 font-weight-bold" id="kt_subheader_total">Create</span>
            <!--end::Actions-->
        </div>
        <!--end::Info-->

        <!--begin::Toolbar-->
        <div class="d-flex align-items-center">
            <a href="{{ route('admin.forex.index') }}" class="btn btn-outline-success font-weight-bolder">Back</a>
        </div>
        <!--end::Toolbar-->
    </div>
</div>
<!--end::Subheader-->
@endsection
@section('content')
<!--begin::Entry-->
<div class="d-flex flex-column-fluid">
    <!--begin::Container-->
    <div class="container-fluid">
        {!! Form::open(array('route' => 'admin.forex.store','class'=>'form-horizontal','id'=>'validator', 'files' => 'true')) !!}
        <div class="row">
            <div class="col-12 col-md-12">
                <div class="card card-custom gutter-b">
                    <div class="card-body">
                        <div class="form-group">
                            <label for="" class="">Date</label>
                            <input type="date" class="form-control" name="RTLIST_DATE" value="{{ old('RTLIST_RATE', date('Y-m-d')) }}">
                        </div>
                        @foreach($forexOrders as $index => $forexOrder)
                        <input type="hidden" class="form-control" name="Forex[{{ $index }}][VAR_CRNCY_CODE]" value="{{ old('VAR_CRNCY_CODE	', 'NPR') }}">
                        <div class="row">
                            <div class="col-12 col-md-1 form-group">
                                <label for="">Code</label>
                                <input type="text" class="form-control" name="" value="{{ old('Forex.'.$index.'.FXD_CRNCY_CODE', $forexOrder->code) }}" disabled>
                                <input type="hidden" class="form-control" name="Forex[{{ $index }}][FXD_CRNCY_CODE]" value="{{ old('Forex.'.$index.'.FXD_CRNCY_CODE', $forexOrder->code) }}">
                            </div>
                            <div class="col-12 col-md-1 form-group">
                                <label for="">Unit</label>
                                <input type="text" class="form-control" name="Forex[{{ $index }}][FXD_CRNCY_UNITS]" value="{{ old('Forex.'.$index.'.FXD_CRNCY_UNITS', $forexOrder->unit) }}">
                            </div>
                            <div class="col-12 col-md-4 form-group">
                                <label for="">Buy Rate (DENOMINATION LESS THAN 50)</label>
                                <input type="text" class="form-control" name="Forex[{{ $index }}][BUY_RATE]" value="{{ old('Forex.'.$index.'.BUY_RATE') }}">
                            </div>
                            <div class="col-12 col-md-4 form-group">
                                <label for="">Buy Rate (DENOMINATION 50 & ABOVE)</label>
                                <input type="text" class="form-control" name="Forex[{{ $index }}][BUY_RATE_ABOVE]" value="{{ old('Forex.'.$index.'.BUY_RATE_ABOVE') }}">
                            </div>
                            <div class="col-12 col-md-2 form-group">
                                <label for="">Sell Rate</label>
                                <input type="text" class="form-control" name="Forex[{{ $index }}][SELL_RATE]" value="{{ old('Forex.'.$index.'.SELL_RATE') }}">
                            </div>
                        </div>
                        @endforeach
                    </div>
                    <div class="card-footer">
                        <button type="submit" class="btn btn-primary">Submit</button>
                    </div>
                </div>
            </div>
        </div>
        {!! Form::close() !!}
    </div>
</div>
@endsection