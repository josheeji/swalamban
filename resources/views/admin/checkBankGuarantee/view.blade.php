@extends('layouts.backend.app')
@section('title', 'Check Bank Guarantee - View Details')
@section('scripts')
@endsection
@section('page-header')
    <!--begin::Subheader-->
    <div class="subheader py-2 py-lg-4  subheader-solid " id="kt_subheader">
        <div class=" container-fluid  d-flex align-items-center justify-content-between flex-wrap flex-sm-nowrap">
            <!--begin::Info-->
            <div class="d-flex align-items-center flex-wrap mr-2">
                <!--begin::Page Title-->
                <h5 class="text-dark font-weight-bold mt-2 mb-2 mr-5">Check Bank Guarantee</h5>
                <!--end::Page Title-->
                <!--begin::Actions-->
                <div class="subheader-separator subheader-separator-ver mt-2 mb-2 mr-4 bg-gray-200"></div>
                <span class="text-dark-50 font-weight-bold" id="kt_subheader_total">View Details</span>
                <!--end::Actions-->
            </div>
            <!--end::Info-->

            <!--begin::Toolbar-->
            <div class="d-flex align-items-center">
                <a href="{{ route('admin.check-bank-guarantee.index') }}"
                    class="btn btn-default btn-outline-success font-weight-bolder">Back</a>
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
                            <div class="tab-content mt-5">
                                
                                    <div class="form-group">
                                        <label class="control-label">Branch Code </label>
                                        <input type="text" value="{{ $data->branch_code }}" class="form-control" readonly> 
                                    </div>
                                    <div class="form-group">
                                        <label class="control-label">Branch Name </label>
                                        <input type="text" value="{{ $data->branch_name }}" class="form-control" readonly> 
                                    </div>
                                    <div class="form-group">
                                        <label class="control-label">Reference Number </label>
                                        <input type="text" value="{{ $data->ref_no }}" class="form-control" readonly> 
                                    </div>
                                    <div class="form-group">
                                        <label class="control-label">Applicant </label>
                                        <input type="text" value="{{ $data->applicant }}" class="form-control" readonly> 
                                    </div>
                                    <div class="form-group">
                                        <label class="control-label">Beneficiary </label>
                                        <input type="text" value="{{ $data->beneficiary }}" class="form-control" readonly> 
                                    </div>
                                    <div class="form-group">
                                        <label class="control-label">Purpose </label>
                                        <input type="text" value="{{ $data->purpose }}" class="form-control" readonly> 
                                    </div>
                                    <div class="form-group">
                                        <label class="control-label">LCY Amount </label>
                                        <input type="text" value="{{ $data->lcy_amount }}" class="form-control" readonly> 
                                    </div>
                                    <div class="form-group">
                                        <label class="control-label">Issue Date </label>
                                        <input type="text" value="{{ $data->issued_date }}" class="form-control" readonly> 
                                    </div>
                                    <div class="form-group">
                                        <label class="control-label">Expiry Date </label>
                                        <input type="text" value="{{ $data->expiary_date }}" class="form-control" readonly> 
                                    </div>
                                
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
