@inject('helper', App\Helper\Helper)
@extends('layouts.backend.app')
@section('styles')
<link href="{{ asset('backend/plugins/datatables/datatables.bundle.css') }}" rel="stylesheet" type="text/css" />
@endsection
@section('scripts')
<script src="{{ asset('backend/plugins/datatables/datatables.bundle.js') }}"></script>
<script>
    $(function() {
        // $('.defaultTable').dataTable({
        //     "pageLength": 50,
        //     "columnDefs": [{
        //         "orderable": false,
        //         "targets": 6
        //     }]
        // });
        $('#sortable').sortable({
            axis: 'y',
            update: function(event, ui) {
                var data = $(this).sortable('serialize');
                var url = "{{ url('admin/remittance/sort') }}";
                $.ajax({
                    type: "POST",
                    url: url,
                    datatype: "json",
                    data: {
                        order: data,
                        _token: '{!! csrf_token() !!}'
                    },
                    success: function(data) {
                        console.log(data);
                        var obj = jQuery.parseJSON(data);
                        Swal.fire({
                            title: "Success!",
                            text: "Remittance has been sorted.",
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
                        url: "{{ route('admin.remittance.change-status') }}",
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
                        url: baseUrl + "/admin/remittance/" + id,
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
<div class="page-header page-header-default">
    <div class="page-header-content">
        <div class="page-title">
            <h4>
                <i class="icon-arrow-left52 position-left"></i> <span class="text-semibold">{{ $title }}</span> - <span class="small">Manage</span>
                @can('master-policy.perform', ['remittance', 'add'])
                <a title="Create Remittance" href="{{ route('admin.remittance.create', ['type' => 'kumari-paying-alliance']) }}" class="btn btn-default legitRipple pull-right">
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
        <form method="get">
            <div class="col-md-2 col-xs-2 col-sm-2">
                <input type="text" name="keyword" class="form-control" placeholder="Search" />
            </div>
            <input type="submit" value="Search" class="btn btn-success">
        </form>
        <table class="table datatable-column-search-inputs defaultTable">
            <thead>
                <tr>
                    <th width="50px">S.No.</th>
                    <th>Title</th>
                    <th>Country</th>
                    <th>Address</th>
                    <th>Visible In</th>
                    <th width="80px">Status</th>
                    <th class="text-center" width="180px">Action</th>
                </tr>
            </thead>
            <tfoot>
                <tr>
                    <th width="50px">S.No.</th>
                    <th>Title</th>
                    <th>Country</th>
                    <th>Address</th>
                    <th>Visible In</th>
                    <th width="80px">Status</th>
                    <th class="text-center">Action</th>
                </tr>
            </tfoot>
            <tbody id="sortable">
                @can('master-policy.perform', ['remittance', 'edit'])
                @php $edit_access = 1; @endphp
                @endcan
                @can('master-policy.perform', ['remittance', 'delete'])
                @php $delete_access = 1; @endphp
                @endcan
                @can('master-policy.perform', ['remittance', 'changeStatus'])
                @php $status_access = 1; @endphp
                @endcan
                @foreach($remittance as $key=>$item)
                <tr id="item-{{ $item->id }}">
                    <td>{{ $key  + 1 }}</td>
                    <td>{!! $item->title !!}</td>
                    <td>{!! ($item->country) ? $item->country->country_name : '' !!}</td>
                    <td>{!! $item->address !!}</td>
                    <td>
                        {!! $helper->visibleInLabelRemit($item->visible_in) !!}
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
                        @endif
                    </td>
                    <td class="text-center">
                        @if(isset($edit_access) && $edit_access == true)
                        <a title="Edit Remittance" href="{{ route('admin.remittance.edit',$item->id) }}" class="btn bg-slate-700 btn-success btn-icon btn-circle legitRipple">
                            <i class=" la la-pencil"></i>
                        </a>
                        @endif
                        @if(isset($delete_access) && $delete_access == true)
                        <a title="Delete Remittance" href="javascript:void(0)" id="{{ $item->id  }}" class="btn btn-danger btn-icon btn-circle legitRipple delete"><i class="la la-trash"></i></a>
                        @endif
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
        {!! $remittance->appends(request()->query())->links('admin.inc.pagination') !!}
    </div>
</div>
@endsection