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
            <h5 class="text-dark font-weight-bold mt-2 mb-2 mr-5">Users</h5>
            <!--end::Page Title-->
            <!--begin::Actions-->
            <div class="subheader-separator subheader-separator-ver mt-2 mb-2 mr-4 bg-gray-200"></div>
            <span class="text-dark-50 font-weight-bold" id="kt_subheader_total">Create</span>
            <!--end::Actions-->
        </div>
        <!--end::Info-->

        <!--begin::Toolbar-->
        <div class="d-flex align-items-center">
            <a href="{{ route('admin.admin-list.index', [$adminTypeId]) }}" class="btn btn-default btn-outline-success font-weight-bolder">Back</a>
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
        {!! Form::open(array('route' => ['admin.admin-list.store', $adminTypeId],'class'=>'form-horizontal','id'=>'validator', 'files' => 'true')) !!}
        <div class="row">
            <div class="col-12 col-md-9">
                <div class="card card-custom gutter-b">
                    <div class="card-body">
                        <div class="form-group">
                            <div class="checkbox-inline">
                                <label class="checkbox checkbox-lg">
                                    <input type="checkbox" name="is_active" value="1" checked="checked">
                                    <span></span>Publish ?</label>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label">Email <span class="text-danger">*</span></label>
                            {!! Form::email('email', null, array('class'=>'form-control')) !!}
                        </div>
                        <div class="form-group">
                            <label class="control-label">Password <span class="text-danger">*</span></label>
                            {!! Form::password('password', array('class'=>'form-control')) !!}
                        </div>
                        <div class="form-group">
                            <label class="control-label">Confirm Password <span class="text-danger">*</span></label>
                            {!! Form::password('confirm_password', array('class'=>'form-control')) !!}
                        </div>
                    </div>
                    <div class="card-footer">
                        <button type="submit" class="btn btn-primary">Submit</button>
                    </div>
                </div>
            </div>
        </div>
        <input type="hidden" name="admin_type_id" value="{{ $adminTypeId }}">
        {!! Form::close() !!}
    </div>
</div>
@endsection