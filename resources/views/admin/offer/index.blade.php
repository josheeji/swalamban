@extends('layouts.backend.app')
@section('title', 'Offers - manage')
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
                    "defaultContent": "-",
                    "orderable": false,
                    "targets": 4
                }]
            });

            $('#sortable').sortable({
                axis: 'y',
                update: function(event, ui) {
                    var data = $(this).sortable('serialize');
                    var url = "{{ url('admin/offers/sort') }}";
                    console.log(data);
                    $.ajax({
                        type: "POST",
                        url: url,
                        datatype: "json",
                        data: {
                            order: data,
                            language_id: "{{ request()->language_id ?? 1 }}",
                            _token: '{!! csrf_token() !!}'
                        },
                        success: function(data) {
                            var obj = jQuery.parseJSON(data);
                            Swal.fire({
                                title: "Success!",
                                text: "Offers has been sorted.",
                                type: 'success',
                                timer: 2000,
                                showConfirmButton: false
                            });
                        }
                    });
                }
            });
        });

        $(document).ready(function() {
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
                            url: "{{ route('admin.offers.change-status') }}",
                            data: {
                                'id': id,
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
                                    Swal.fire('Error',
                                        'Something went wrong while processing your request.',
                                        'error')
                                }
                            }
                        });
                    }
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
                            url: baseUrl + "/admin/offers" + "/" + id,
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
                <h5 class="text-dark font-weight-bold mt-2 mb-2 mr-5">Offers</h5>
                <!--end::Page Title-->
                <!--begin::Actions-->
                <div class="subheader-separator subheader-separator-ver mt-2 mb-2 mr-4 bg-gray-200"></div>
                <span class="text-dark-50 font-weight-bold" id="kt_subheader_total">Manage</span>
                <!--end::Actions-->
            </div>
            <!--end::Info-->

            <!--begin::Toolbar-->
            <div class="d-flex align-items-center">
                @can('master-policy.perform', ['offer', 'add'])
                    <a title="Create Content" href="{{ route('admin.offers.create') }}"
                        class="btn btn-outline-success font-weight-bolder">Create New</a>
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
                    <div class="datatable datatable-bordered datatable-head-custom datatable-default datatable-primary datatable-loaded"
                        id="kt_datatable">
                        <table class="table datatable-column-search-inputs defaultTable">
                            <thead>
                                <tr>
                                    <th width="50">S.No.</th>
                                    <th>Title</th>
                                    {{-- <th width="50">Image</th> --}}
                                    <th class="{{ SettingHelper::setting('visible_in') == 1 ? '' : 'd-none' }}">Visible In
                                    </th>
                                    <th width="80">Status</th>
                                    <th class="text-center" width="120">Action</th>
                                </tr>
                            </thead>
                            <tfoot>
                                <tr>
                                    <th>S.No.</th>
                                    <th>Title</th>
                                    {{-- <th>Image</th> --}}
                                    <th class="{{ SettingHelper::setting('visible_in') == 1 ? '' : 'd-none' }}">Visible In
                                    </th>
                                    <th>Status</th>
                                    <th class="text-center">Action</th>
                                </tr>
                            </tfoot>
                            <tbody id="sortable">
                                @can('master-policy.perform', ['offer', 'edit'])
                                    @php $edit_access = 1; @endphp
                                @endcan
                                @can('master-policy.perform', ['offer', 'delete'])
                                    @php $delete_access = 1; @endphp
                                @endcan
                                @can('master-policy.perform', ['offer', 'changeStatus'])
                                    @php $status_access = 1; @endphp
                                @endcan
                                @foreach ($offers as $index => $item)
                                    <tr id="item-{{ $item->id }}">
                                        <td>{{ $index + 1 }}</td>
                                        <td>{{ $item->title }}</td>
                                        <td class="{{ SettingHelper::setting('visible_in') == 1 ? '' : 'd-none' }}">
                                            {!! PageHelper::visibleInLabel($item->visible_in) !!}</td>
                                        <td>
                                            @if (isset($status_access) && $status_access == true)
                                                <a title="Change Status" href="javascript:void(0)"
                                                    class="btn btn-primary btn-icon btn-circle legitRipple change-status"
                                                    id="{{ $item->id }}">
                                                    @if ($item->is_active == 1)
                                                        <i class="la la-check"></i>
                                                    @else
                                                        <i class="la la-minus"></i>
                                                    @endif
                                                </a>
                                            @endif
                                        </td>
                                        <td class="text-center">
                                            @if (isset($edit_access) && $edit_access == true)
                                                <a title="Edit" href="{{ route('admin.offers.edit', $item->id) }}"
                                                    class="bg-slate-700 btn btn-success btn-icon btn-circle legitRipple">
                                                    <i class=" la la-pencil"></i>
                                                </a>
                                            @endif
                                            @if (isset($delete_access) && $delete_access == true)
                                                <a title="Delete" href="javascript:void(0)" id="{{ $item->id }}"
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
