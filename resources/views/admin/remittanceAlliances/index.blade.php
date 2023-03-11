@inject('helper', App\Helper\Helper)
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
                "targets": 5
            }]
        });

        $('#sortable').sortable({
            axis: 'y',
            update: function(event, ui) {
                var data = $(this).sortable('serialize');
                var url = "{{ url('admin/remittance-alliance/sort') }}";
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
                            text: "Remittance Alliances has been sorted.",
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
                        url: "{{ route('admin.remittance-alliance.change-status') }}",
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
                                Swal.fire('Error', 'Something went wrong while processing your request.', 'error')
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
                        url: baseUrl + "/admin/remittance-alliance" + "/" + id,
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
            });
        });
    });
</script>
@endsection
@section('page-header')
<div class="page-header page-header-default">
    <div class="page-header-content">
        <div class="page-title">
            <h4>
                <i class="icon-arrow-left52 position-left"></i> <span class="text-semibold">{{ $title }}</span> - <span class="small">Manage</span>
                @can('master-policy.perform', ['remittance-alliances', 'add'])
                <a title="Create Content" href="{{ route('admin.remittance-alliance.create') }}" class="btn btn-default legitRipple pull-right">
                    <i class="icon-file-plus position-left"></i> Create New <span class="legitRipple-ripple"></span>
                </a>
                @endif
            </h4>
        </div>

    </div>
    <div class="breadcrumb-line">
        <ul class="breadcrumb">
            <li><a href="{{ route('admin.dashboard') }}"><i class="icon-home2 position-left"></i> Home</a></li>
            <li class="active">{{ $title }}</li>
        </ul>
    </div>
</div>
@endsection
@section('content')
<div class="panel panel-flat">
    <div class="panel-body">
        <table class="table datatable-column-search-inputs defaultTable">
            <thead>
                <tr>
                    <th width="50px">S.No.</th>
                    <th>Title</th>
                    <th>Image</th>
                    <th>Visible In</th>
                    <th width="80px">Status</th>
                    <th class="text-center" width="12%">Action</th>
                </tr>
            </thead>
            <tfoot>
                <tr>
                    <th width="50px">S.No.</th>
                    <th>Title</th>
                    <th>Image</th>
                    <th>Visible In</th>
                    <th width="80px">Status</th>
                    <th class="text-center">Action</th>
                </tr>
            </tfoot>
            <tbody id="sortable">
                @can('master-policy.perform', ['remittance-alliances', 'edit'])
                @php $edit_access = 1; @endphp
                @endcan
                @can('master-policy.perform', ['remittance-alliances', 'delete'])
                @php $delete_access = 1; @endphp
                @endcan
                @can('master-policy.perform', ['remittance-alliances', 'changeStatus'])
                @php $status_access = 1; @endphp
                @endcan
                @foreach($posts as $index=>$item)
                <tr id="item-{{ $item->id }}">
                    <td>{{ $index+1 }}</td>
                    <td>{{ $item->title }}</td>
                    <td>
                        @if(!empty($item->image))
                        <img src="{{ asset('storage/thumbs/'.$item->image) }}" style="height: 60px;" class="displayimage" alt="">
                        @endif
                    </td>
                    <td>
                        @php
                        switch($item->visible_in){

                        case 2:
                        echo '<span class="label label-default">Overseas</span>';
                        break;
                        default:
                        echo '<span class="label bg-purple">Local</span>';
                        break;
                        }
                        @endphp
                    </td>
                    <td>
                        @if(isset($status_access) && $status_access == true)
                        <a title="Change Status" href="javascript:void(0)" class="btn btn-primary btn-icon btn-circle legitRipple change-status" id="{{ $item->id }}">
                            @if($item->is_active == 1)
                            <i class="la la-check"></i>
                            @else
                            <i class="la la-minus"></i>
                            @endif
                        </a>
                        @else
                        @if($item->is_active == 1)
                        <i class="la la-check"></i>
                        @else
                        <i class="la la-minus"></i>
                        @endif
                        @endif
                    </td>
                    <td class="text-center">
                        @if(isset($edit_access) && $edit_access == true)
                        <a title="Edit Image" href="{{ route('admin.remittance-alliance.edit',$item->id) }}" class="btn btn-success bg-slate-700 btn-icon btn-circle legitRipple">
                            <i class=" la la-pencil"></i>
                        </a>
                        @endif
                        @if(isset($delete_access) && $delete_access == true)
                        <a title="Delete Content" href="javascript:void(0)" id="{{ $item->id  }}" class="btn btn-danger btn-icon btn-circle legitRipple delete"><i class="la la-trash"></i></a>
                        @endif
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection