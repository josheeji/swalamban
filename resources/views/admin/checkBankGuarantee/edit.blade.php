@extends('layouts.backend.app')
@section('title', 'Check Bank Guarantee - Edit Details')
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
                <span class="text-dark-50 font-weight-bold" id="kt_subheader_total">Edit Details</span>
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
                            <form action="{{ route('admin.check-bank-guarantee.update',$data->id) }}" method="put">
                                <div class="tab-content mt-5">

                                    <div class="form-group">
                                        <label class="control-label">Branch Code </label>
                                        <input type="text" name="branch_code" value="{{ old('branch_code', $data->branch_code)}}"  class="form-control">
                                    </div>
                                    <div class="form-group">
                                        <label class="control-label">Branch Name </label>
                                        <input type="text" name="branch_name" value="{{ old('branch_name', $data->branch_name)}}" class="form-control">
                                    </div>
                                    <div class="form-group">
                                        <label class="control-label">Reference Number </label>
                                        <input type="text" name="ref_no" value="{{ old('ref_no', $data->ref_no)}}" class="form-control">
                                    </div>
                                    <div class="form-group">
                                        <label class="control-label">Applicant </label>
                                        <input type="text" name="applicant" value="{{ old('applicant', $data->applicant)}}" class="form-control">
                                    </div>
                                    <div class="form-group">
                                        <label class="control-label">Beneficiary </label>
                                        <input type="text" name="beneficiary" value="{{ old('beneficiary', $data->beneficiary)}}" class="form-control">
                                    </div>
                                    <div class="form-group">
                                        <label class="control-label">Purpose </label>
                                        <input type="text" name="purpose" value="{{ old('purpose', $data->purpose)}}" class="form-control">
                                    </div>
                                    <div class="form-group">
                                        <label class="control-label">LCY Amount </label>
                                        <input type="number" name="lcy_amount" value="{{ old('lcy_amount', $data->lcy_amount)}}" class="form-control">
                                    </div>
                                    <div class="form-group">
                                        <label class="control-label">Issue Date </label>
                                        <input type="text" name="issued_date" value="{{ $data->issued_date }}" class="form-control">
                                    </div>
                                    <div class="form-group">
                                        <label class="control-label">Expiry Date </label>
                                        <input type="text" name="expiary_date" value="{{ $data->expiary_date }}" class="form-control">
                                    </div>

                                    <div class="form-group">
                                        <input type="submit" value="Update" class="btn btn-default btn-outline-success font-weight-bolder">
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
