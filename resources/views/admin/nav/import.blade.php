@extends('layouts.backend.app')
@section('title', 'NAVs - import')
@section('styles')

@endsection
@section('scripts')

@endsection
@section('page-header')
<!--begin::Subheader-->
<div class="subheader py-2 py-lg-4  subheader-solid " id="kt_subheader">
    <div class=" container-fluid  d-flex align-items-center justify-content-between flex-wrap flex-sm-nowrap">
        <!--begin::Info-->
        <div class="d-flex align-items-center flex-wrap mr-2">
            <!--begin::Page Title-->
            <h5 class="text-dark font-weight-bold mt-2 mb-2 mr-5">NAVs</h5>
            <!--end::Page Title-->
            <!--begin::Actions-->
            <div class="subheader-separator subheader-separator-ver mt-2 mb-2 mr-4 bg-gray-200"></div>
            <span class="text-dark-50 font-weight-bold" id="kt_subheader_total">Import</span>
            <!--end::Actions-->
        </div>
        <!--end::Info-->

        <!--begin::Toolbar-->
        <div class="d-flex align-items-center">
            <a href="{{ route('admin.navs.index') }}" class="btn btn-outline-success font-weight-bolder"><i class="icon-undo2 position-left"></i> Back</span></a>
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
    <div class=" container-fluid">
        {!! Form::open(array('route' => 'admin.navs.store-import','class'=>'form-horizontal','id'=>'validator', 'files' => 'true')) !!}
        <div class="row">
            <div class="col-12 col-md-9">
                <div class="card card-custom gutter-b">
                    <div class="card-body">
                        <fieldset class="content-group">
                            <div class="form-group">
                                <label class="control-label">Title <span class="text-danger">*</span></label>
                                {!! Form::text('title', null, array('class'=>'form-control')) !!}
                            </div>
                            <div class="form-group">
                                <label class="control-label">File<span class="text-danger">*</span></label>
                                <div class="custom-file">
                                    <input type="file" class="custom-file-input" name="file" id="image-file">
                                    <label class="custom-file-label selected" for="image-file"></label>
                                </div>
                            </div>
                        </fieldset>
                        <div class="text-left col-lg-offset-2">
                            <button type="submit" class="btn btn-primary legitRipple">Submit <i class="icon-arrow-right14 position-right"></i></button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        {!! Form::close() !!}
    </div>
</div>
@endsection