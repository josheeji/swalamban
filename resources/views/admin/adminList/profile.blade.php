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
            <h5 class="text-dark font-weight-bold mt-2 mb-2 mr-5">Roles</h5>
            <!--end::Page Title-->
            <!--begin::Actions-->
            <div class="subheader-separator subheader-separator-ver mt-2 mb-2 mr-4 bg-gray-200"></div>
            <span class="text-dark-50 font-weight-bold" id="kt_subheader_total">Users Edit</span>
            <!--end::Actions-->
        </div>
        <!--end::Info-->

        <!--begin::Toolbar-->
        <div class="d-flex align-items-center">
            <a href="" class="btn btn-default btn-outline-success font-weight-bolder">Back</span></a>
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
        {!! Form::open(array('route' => ['admin.admin-list.update', $admin->id],'class'=>'form-horizontal','id'=>'validator', 'files' => 'true','method' => 'post' )) !!}
        @csrf
        @method('PATCH')
        <div class="row">
            <div class="col-12 col-md-9">
                <div class="card card-custom gutter-b example example-compact">
                    <div class="card-body">
                        <div class="form-group">
                            <div class="checkbox-inline">
                                <label class="checkbox checkbox-lg">
                                    <input type="checkbox" name="is_active" value="1" {{ $admin->is_active == 1 ? 'checked' : '' }}>
                                    <span></span>Publish ?</label>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label">Email <span class="text-danger">*</span></label>
                                {!! Form::text('email', $admin->email ?? "", array('class'=>'form-control','placeholder'=>'Email')) !!}
                            </div>

                        <div class="form-group">
                            <label class="control-label">Role <span class="text-danger">*</span></label>
                                @if(isset($adminType) && !empty($adminType))
                                <select name="admin_type_id" id="" class="form-control">
                                    <option>Select Role</option>
                                    @foreach($adminType as $role)
                                    <option value="{{ $role->id }}" {{ $role->id == $admin->admin_type_id ? 'selected': ''}}>{{ $role->name }}</option>
                                    @endforeach
                                </select>
                                @endif
                        </div>
                        <div class="form-group">
                            <label class="control-label">Password <span class="text-danger">*</span></label>
                                {!! Form::password('password', array('class'=>'form-control','placeholder'=>'Password')) !!}
                        </div>
                        <div class="form-group">
                            <label class="control-label">Confirm Password <span class="text-danger">*</span></label>
                                {!! Form::password('confirm_password', array('class'=>'form-control','placeholder'=>'Confirm Password')) !!}
                            </div>
                    </div>
                    <div class="card-footer">
                        <button type="submit" class="btn btn-primary">Submit</button>
                    </div>
                </div>
            </div>
            <div class="col-12 col-md-3">

            </div>
        </div>
        {!! method_field('PATCH') !!}
        {!! Form::close() !!}
    </div>
</div>
@endsection