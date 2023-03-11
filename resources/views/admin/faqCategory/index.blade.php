@extends('layouts.backend.app')
@section('styles')
<link href="{{ asset('backend/plugins/datatables/datatables.bundle.css') }}" rel="stylesheet" type="text/css" />
@endsection
@section('scripts')
<script src="{{ asset('backend/plugins/datatables/datatables.bundle.js') }}"></script>
<script>
    $(document).ready(function() {
        $('.defaultTable').dataTable({
            "pageLength": 50,
            "columnDefs": [{
                "orderable": false,
                "targets": 3
            }]
        });

        $(".defaultTable").on("click", ".change-status", function() {
            $object = $(this);
            var id = $object.attr('id');
            Swal.fire({
                title: 'Are you sure?',
                text: 'Do you want to change the status',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Yes, change it!',
                cancelButtonText: 'No, keep it'
            }).then((result) => {
                if (result.value) {
                    $.ajax({
                        type: "POST",
                        url: "{{ route('admin.faq-category.change-status') }}",
                        data: {
                            'id': id
                        },
                        dataType: 'json',
                        success: function(response) {
                            console.log(response);
                            Swal.fire("Thank You!", response.message, "success");
                            if (response.response.is_active == 1) {
                                $($object).children().removeClass('la-minus');
                                $($object).children().addClass('la-check');
                            } else {
                                $($object).children().removeClass('la-check');
                                $($object).children().addClass('la-minus');
                            }
                        },
                        error: function(e) {
                            if (e.responseJSON.message) {
                                Swal.fire('Error', e.responseJSON.message, 'error');
                            } else {
                                Swal.fire('Error', 'Something went wrong while processing your request.', 'error')
                            }
                        }
                    });
                }
            })
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
                        url: baseUrl + "/admin/faq-category" + "/" + id,
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
                                Swal.fire('Error', 'Something went wrong while processing your request.', 'error')
                            }
                        }
                    });
                }
            })
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
            <h5 class="text-dark font-weight-bold mt-2 mb-2 mr-5">FAQ</h5>
            <!--end::Page Title-->
            <!--begin::Actions-->
            <div class="subheader-separator subheader-separator-ver mt-2 mb-2 mr-4 bg-gray-200"></div>
            <span class="text-dark-50 font-weight-bold" id="kt_subheader_total">Manage</span>
            <!--end::Actions-->
        </div>
        <!--end::Info-->

        <!--begin::Toolbar-->
        <div class="d-flex align-items-center">
            @can('master-policy.perform', ['content', 'add'])
            <a title="Create Content" href="{{ route('admin.faq-category.create') }}" class="btn btn-outline-success font-weight-bolder">Create New</a>
            @endif
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
                                <th>Title</th>
                                <th width="80px">Status</th>
                                <th class="text-center" width="180px">Action</th>
                            </tr>
                        </thead>
                        <tfoot>
                            <tr>
                                <th width="50px">S.No.</th>
                                <th>Title</th>
                                <th width="80px">Status</th>
                                <th class="text-center">Action</th>
                            </tr>
                        </tfoot>
                        <tbody>
                            @can('master-policy.perform', ['faq', 'edit'])
                            @php $edit_access = 1; @endphp
                            @endcan
                            @can('master-policy.perform', ['faq', 'delete'])
                            @php $delete_access = 1; @endphp
                            @endcan
                            @can('master-policy.perform', ['faq', 'changeStatus'])
                            @php $status_access = 1; @endphp
                            @endcan
                            @foreach($type as $key=>$item)
                            <tr>
                                <td>{{ $key+1 }}</td>
                                <td>{{ $item->title }}</td>
                                <td>
                                    @if(isset($status_access) && $status_access == true)
                                    <a href="javascript:void(0)" class="btn btn-primary btn-icon btn-circle legitRipple @can('master-policy.perform', ['faq', 'changeStatus']) change-status @endcan" id="{{ $item->id }}">
                                        @if($item->is_active == 1)
                                        <i class="la la-check"></i>
                                        @else
                                        <i class="la la-minus"></i>
                                        @endif
                                    </a>
                                    @endif
                                </td>
                                <td class="text-center">
                                    @if(isset($edit_access) && $edit_access == true)
                                    <a href="{{ route('admin.faq.index',[$item->id]) }}" class="btn btn-success btn-icon btn-circle legitRipple">
                                        <i class="la la-list"></i>
                                    </a>
                                    @endif
                                    @if(isset($edit_access) && $edit_access == true)
                                    <a href="{{ route('admin.faq-category.edit',$item->id) }}" class="btn bg-slate-700 btn-success btn-icon btn-circle legitRipple">
                                        <i class="la la-pencil"></i>
                                    </a>
                                    @endif
                                    @if(isset($delete_access) && $delete_access == true)
                                    <a href="javascript:void(0)" id="{{ $item->id  }}" class="btn btn-danger btn-icon btn-circle legitRipple delete">
                                        <i class="la la-trash"></i>
                                    </a>
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