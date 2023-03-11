@extends('layouts.backend.app')
@section('title', 'Grivance - manage')
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
                    "targets": 5
                }]
            });
        });

        $(".defaultTable").on("click", ".delete", function() {
            $object = $(this);
            var id = $object.attr('id');
            Swal.fire({
                title: 'Are you sure?',
                text: 'You will not be able to recover this !',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Yes, delete it!',
                cancelButtonText: 'No, keep it'
            }).then((result) => {
                if (result.value) {
                    $.ajax({
                        type: "POST",
                        url: baseUrl + "/admin/grievance" + "/" + id,
                        data: {
                            id: id,
                            _method: 'DELETE'
                        },
                        success: function(response) {
                            Swal.fire("Deleted!", response.message, "success");
                            var oTable = $('.defaultTable').dataTable();
                            var nRow = $($object).parents('tr')[0];
                            oTable.fnDeleteRow(nRow);
                        },
                        error: function(e) {
                            if (e.responseJSON.message) {
                                Swal.fire('Error', e.responseJSON.message, 'error');
                            } else {
                                Swal.fire('Error',
                                    'Something went wrong while processing your request.',
                                    'error')
                            }
                        }
                    });
                }
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
                <h5 class="text-dark font-weight-bold mt-2 mb-2 mr-5">{{ $title }}</h5>
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
                    <div class="datatable datatable-bordered datatable-head-custom datatable-default datatable-primary datatable-loaded"
                        id="kt_datatable">
                        <table class="table datatable-column-search-inputs defaultTable">
                            <thead>
                                <tr>
                                    <th width="50">S.No.</th>
                                    <th>Ref. No.</th>
                                    <th>Product/Services</th>
                                    <th class="d-none">Branch</th>
                                    <th class="d-none">Department</th>
                                    <th class="text-center" width="120">Action</th>
                                </tr>
                            </thead>
                            <tfoot>
                                <tr>
                                    <th width="50">S.No.</th>
                                    <th>Ref. No.</th>
                                    <th>Subject</th>
                                    <th class="d-none">Branch</th>
                                    <th class="d-none">Department</th>
                                    <th class="text-center">Action</th>
                                </tr>
                            </tfoot>
                            <tbody id="sortable">
                                @can('master-policy.perform', ['grievance', 'edit'])
                                    @php $edit_access = 1; @endphp
                                @endcan
                                @can('master-policy.perform', ['grievance', 'delete'])
                                    @php $delete_access = 1; @endphp
                                @endcan
                                @can('master-policy.perform', ['grievance', 'changeStatus'])
                                    @php $status_access = 1; @endphp
                                @endcan
                                @foreach ($grievances as $index => $item)
                                    <tr id="item-{{ $item->id }}">
                                        <td>{{ $index + 1 }}</td>
                                        <td>{{ $item->reference_id }}</td>
                                        <td>{{ $item->subject }}</td>
                                        <td class="d-none">{{ $item->branch ? $item->branch->title : '' }}</td>
                                        <td class="d-none">{{ $item->department ? $item->department->title : '' }}
                                        </td>
                                        <td class="text-center">
                                            <a title="View" href="{{ route('admin.grievance.show', $item->id) }}"
                                                class="btn btn-success btn-icon btn-circle legitRipple">
                                                <i class=" la la-eye"></i>
                                            </a>
                                            @if (isset($delete_access) && $delete_access == true)
                                                <a title="Delete Content" href="javascript:void(0)"
                                                    id="{{ $item->id }}"
                                                    class="btn btn-danger btn-icon btn-circle legitRipple delete"><i
                                                        class="la la-trash"></i></a>
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
