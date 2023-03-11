{{-- @inject('helper', App\Helper\Helper) --}}
@extends('layouts.backend.app')
@section('scripts')
@endsection
@section('page-header')
<!--begin::Subheader-->
<div class="subheader py-2 py-lg-4  subheader-solid " id="kt_subheader">
    <div class=" container-fluid  d-flex align-items-center justify-content-between flex-wrap flex-sm-nowrap">
        <!--begin::Info-->
        <div class="d-flex align-items-center flex-wrap mr-2">
            <!--begin::Page Title-->
            <h5 class="text-dark font-weight-bold mt-2 mb-2 mr-5">Imports</h5>
            <!--end::Page Title-->
            <!--begin::Actions-->
            <div class="subheader-separator subheader-separator-ver mt-2 mb-2 mr-4 bg-gray-200"></div>
            <span class="text-dark-50 font-weight-bold" id="kt_subheader_total">Branch</span>
            <!--end::Actions-->
        </div>
        <!--end::Info-->

        <!--begin::Toolbar-->
        <div class="d-flex align-items-center">
            <a href="{{ route('admin.branch-directory.index') }}" class="btn btn-outline-success font-weight-bolder">Back</a>
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
        <div class="row">
            <div class="col-12 col-md-8">
                <div class="card card-custom gutter-b">
                    <div class="card-body">
                        {!! Form::open(array('route' => 'admin.import.store-branch','class'=>'form-horizontal','id'=>'download', 'files' => 'true')) !!}
                        <fieldset class="content-group">
                            <div class="form-group">
                                <label class="control-label">File <span class="text-danger">*</span></label>
                                <div class="custom-file">
                                    <input type="file" class="custom-file-input" name="file" id="image-file">
                                    <label class="custom-file-label selected" for="image-file"></label>
                                </div>
                            </div>
                        </fieldset>
                        <div class="text-left col-lg-offset-2">
                            <button type="submit" class="btn btn-primary legitRipple"> Submit <i class="icon-arrow-right14 position-right"></i></button>
                        </div>
                        {!! Form::close() !!}
                    </div>
                </div>
            </div>
            <div class="col-12 col-md-4">
                <a href="{{ asset('backend/samples/branch-sample.xlsx') }}" class="btn btn-primary float-right mr-5" title="Download a Sample File"><i class="las la-file-excel"></i> Download a Sample Excel File</a>
            </div>
        </div>
    </div>
</div>
@endsection