@extends('layouts.backend.app')
@section('title', 'Financial Report - import')
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
            <span class="text-dark-50 font-weight-bold" id="kt_subheader_total">Import</span>
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
        {!! Form::open(array('route' => 'admin.forex.store-import','class'=>'form-horizontal','id'=>'validator', 'files' => 'true')) !!}
        <div class="row">
            <div class="col-12 col-md-12">
                <div class="card card-custom gutter-b">
                    <div class="card-body">
                        <div class="form-group">
                            <label for="" class="">Date <span class="text-danger">*</span></label>
                            <input type="date" class="form-control" name="RTLIST_DATE" value="{{ old('RTLIST_RATE', date('Y-m-d')) }}">
                        </div>
                        <div class="form-group">
                            <label class="control-label">File <span class="text-danger">*</span></label>
                            <div class="custom-file">
                                <input type="file" class="custom-file-input" name="file" id="image-file">
                                <label class="custom-file-label selected" for="image-file"></label>
                            </div>
                        </div>
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