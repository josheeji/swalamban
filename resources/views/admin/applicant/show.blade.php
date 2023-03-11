@extends('layouts.backend.app')
@section('styles')
<link href="{{ asset('backend/plugins/datatables/datatables.bundle.css') }}" rel="stylesheet" type="text/css" />
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
            <h5 class="text-dark font-weight-bold mt-2 mb-2 mr-5">Applicants</h5>
            <!--end::Page Title-->
            <!--begin::Actions-->
            <div class="subheader-separator subheader-separator-ver mt-2 mb-2 mr-4 bg-gray-200"></div>
            <span class="text-dark-50 font-weight-bold" id="kt_subheader_total">View</span>
            <!--end::Actions-->
        </div>
        <!--end::Info-->

        <!--begin::Toolbar-->
        <div class="d-flex align-items-center">
            <a href="{{ route('admin.applicant.index') }}" class="btn btn-outline-success font-weight-bolder"><i class="icon-undo2 position-left"></i> Back</span></a>
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
        <div class="card card-custom gutter-b">
            <div class="card-body">
                <div class="datatable datatable-bordered datatable-head-custom datatable-default datatable-primary datatable-loaded" id="kt_datatable">
                    <table class="table table-striped">
                        @if($applicant->career)
                            <tr>
                                <th>Applied For</th>
                                <td>{{ $applicant->career->title }}</td>
                            </tr>
                        @endif
                        <tr>
                            <th>Name</th>
                            <td>{{ $applicant->full_name }}</td>
                        </tr>
                        <tr>
                            <th>Reference ID</th>
                            <td>{{ $applicant->reference_id }}</td>
                        </tr>
                        <tr>
                            <th>Address</th>
                            <td>{{ $applicant->address }}</td>
                        </tr>
                        <tr>
                            <th>Contact No</th>
                            <td>{{ $applicant->contact_no }}</td>
                        </tr>
                        <tr>
                            <th>Email</th>
                            <td><a href="mailto:{{ $applicant->email }}">{{ $applicant->email }}</a></td>
                        </tr>
                        <tr>
                            <th>Message</th>
                            <td>{!! $applicant->message !!}</td>
                        </tr>
                        <tr>
                            <th>Resume</th>
                            <td><a href="{{ asset('storage/'. $applicant->resume) }}" target="_blank">Resume</a></td>
                        </tr>
                        <tr>
                            <th>Cover Letter</th>
                            <td><a href="{{ asset('storage/'. $applicant->cover_letter) }}" target="_blank">Cover Letter</a></td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection