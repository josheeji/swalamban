@extends('layouts.backend.app')
@section('title', 'Grievance - view')
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
                <h5 class="text-dark font-weight-bold mt-2 mb-2 mr-5">Grievances</h5>
                <!--end::Page Title-->
                <!--begin::Actions-->
                <div class="subheader-separator subheader-separator-ver mt-2 mb-2 mr-4 bg-gray-200"></div>
                <span class="text-dark-50 font-weight-bold" id="kt_subheader_total">view</span>
                <!--end::Actions-->
            </div>
            <!--end::Info-->

            <!--begin::Toolbar-->
            <div class="d-flex align-items-center">
                <a href="{{ route('admin.grievance.index') }}" class="btn btn-outline-success font-weight-bolder"><i
                        class="icon-undo2 position-left"></i> Back</span></a>
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
                    <ul class="list-unstyled row">
                        <li class="col-md-12"></li>
                        <li class="media col-md-6 mb-2">
                            <div class="media-body media-middle text-semibold">
                                {{ $grievance->reference_id }}
                                <div class="media-annotation">Reference ID.</div>
                            </div>
                        </li>
                        <li class="media col-md-6 mb-2">
                            <div class="media-body media-middle text-semibold">
                                {{ $grievance->subject }}
                                <div class="media-annotation">Service</div>
                            </div>
                        </li>
                        <li class="media col-md-6 d-none mb-2">
                            <div class="media-body media-middle text-semibold">
                                {!! $grievance->branch ? $grievance->branch->title : '' !!}
                                <div class="media-annotation">Branch</div>
                            </div>
                        </li>
                        <li class="media col-md-6 d-none mb-2">
                            <div class="media-body media-middle text-semibold">
                                {!! $grievance->department ? $grievance->department->title : '' !!}
                                <div class="media-annotation">Department</div>
                            </div>
                        </li>
                        <li class="media col-md-6 mb-2">
                            <div class="media-body media-middle text-semibold">
                                {!! $grievance->full_name !!}
                                <div class="media-annotation">Name</div>
                            </div>
                        </li>
                        <li class="media col-md-6 mb-2">
                            <div class="media-body media-middle text-semibold">
                                {!! $grievance->email !!}
                                <div class="media-annotation">Email</div>
                            </div>
                        </li>
                        <li class="media col-md-6 mb-2">
                            <div class="media-body media-middle text-semibold">
                                {!! $grievance->mobile !!}
                                <div class="media-annotation">Phone</div>
                            </div>
                        </li>
                        <li class="media col-md-12 mb-2">
                            <div class="media-body media-middle text-semibold">
                                {!! $grievance->message !!}
                                <div class="media-annotation">Complaints</div>
                            </div>
                        </li>
                        <li class="media col-md-12 mb-2">
                            <div class="media-body media-middle text-semibold">
                                {!! $grievance->existing_customer == 1 ? 'Yes' : 'No' !!}
                                <div class="media-annotation">Existing Customer</div>
                            </div>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
@endsection
