@extends('layouts.backend.app')
@section('styles')
<link href="{{ asset('backend/plugins/datatables/datatables.bundle.css') }}" rel="stylesheet" type="text/css" />
@endsection
@section('scripts')
<script src="{{ asset('backend/plugins/datatables/datatables.bundle.js') }}"></script>
<script>
    $(function() {
        $('.defaultTable').dataTable({
            "pageLength": 50,
            "columnDefs": [{
                "orderable": false,
                "targets": 6
            }]
        });
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
            <h5 class="text-dark font-weight-bold mt-2 mb-2 mr-5">Applicants</h5>
            <!--end::Page Title-->
            <!--begin::Actions-->
            <div class="subheader-separator subheader-separator-ver mt-2 mb-2 mr-4 bg-gray-200"></div>
            <span class="text-dark-50 font-weight-bold" id="kt_subheader_total">Manage</span>
            <!--end::Actions-->
        </div>
        <!--end::Info-->

        <!--begin::Toolbar-->
        <div class="d-flex align-items-center">

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
                    <table class="table datatable-column-search-inputs defaultTable">
                        <thead>
                            <tr>
                                <th width="50px">S.No.</th>
                                <th>Career</th>
                                <th>Name</th>
                                <th>Email</th>
                                <th>Contact No.</th>
                                <th>Reference ID</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tfoot>
                            <tr>
                                <th width="50px">S.No.</th>
                                <th>Career</th>
                                <th>Name</th>
                                <th>Email</th>
                                <th>Contact No.</th>
                                <th>Reference ID</th>
                                <th>Action</th>
                            </tr>
                        </tfoot>
                        <tbody id="sortable">
                            @can('master-policy.perform', ['applicant', 'edit'])
                            @php $edit_access = 1; @endphp
                            @endcan
                            @can('master-policy.perform', ['applicant', 'delete'])
                            @php $delete_access = 1; @endphp
                            @endcan
                            @can('master-policy.perform', ['applicant', 'changeStatus'])
                            @php $status_access = 1; @endphp
                            @endcan
                            @can('master-policy.perform', ['applicant', 'view'])
                            @php $view_access = 1; @endphp
                            @endcan
                            @foreach($applicants as $index=>$item)
                            <tr id="item-{{ $item->id }}">
                                <td>{{ $index+1 }}</td>
                                <td>{{ $item->career ? $item->career->title : '' }}</td>
                                <td>{{ $item->full_name }}</td>
                                <td>{{ $item->email }}</td>
                                <td>{{ $item->contact_no }}</td>
                                <td>{{ $item->reference_id }}</td>
                                <td>
                                    @if(isset($view_access) && $view_access == true)
                                    <a title="View Applicant" href="{{ route('admin.applicant.show',$item->id) }}" class="btn btn-success btn-icon btn-circle legitRipple"><i class="la la-eye"></i></a>
                                    @endif
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection