@extends('layouts.backend.app')
@section('title', 'Jyoti Care - view')
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
            <h5 class="text-dark font-weight-bold mt-2 mb-2 mr-5">Jyoti Care</h5>
            <!--end::Page Title-->
            <!--begin::Actions-->
            <div class="subheader-separator subheader-separator-ver mt-2 mb-2 mr-4 bg-gray-200"></div>
            <span class="text-dark-50 font-weight-bold" id="kt_subheader_total">View</span>
            <!--end::Actions-->
        </div>
        <!--end::Info-->

        <!--begin::Toolbar-->
        <div class="d-flex align-items-center">
            <a href="{{ route('admin.jyoti-care.index') }}" class="btn btn-outline-success font-weight-bolder"><i class="icon-undo2 position-left"></i> Back</span></a>
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
                <table class="table table-striped">
                    <tbody>
                        <tr>
                            <th>Full Name</th>
                            <td>{{ $data->full_name }}</td>
                        </tr>
                        <tr>
                            <th>Contact</th>
                            <td>{!! $data->mobile_no !!}</td>
                         
                        </tr>
                        <tr>
                            <th>Email</th>
                            <td>{!! $data->email_address !!}</td>
                        </tr>
                        <tr>
                            <th>Address</th>
                            <td>{!! $data->address !!}</td>
                        </tr>
                        <tr>
                            <th>Qualification</th>
                            <td>{!! $data->qualification !!}</td>
                        </tr>
                        <tr>
                            <th>Branch</th>
                            <td>{!! $data->branch !!}</td>
                        </tr>
                        <tr>
                            <th>Category</th>
                            <td>{!! $data->status_category !!}</td>
                        </tr>
                        <tr>
                            <th>Citizenship</th>
                            <td> <img src="{{asset('storage/'. $data->citizenship_file)}}" alt=""> </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection