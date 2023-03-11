@extends('layouts.backend.app')
@section('scripts')
<script>
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
                        url: "{{ route('admin.notice.change-status') }}",
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
                        url: baseUrl + "/admin/notice" + "/" + id,
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
                @can('master-policy.perform',['notice','changeStatus'])
                <a href="{{ route('admin.notice.create') }}" class="btn btn-default legitRipple pull-right">
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
                    <th>Image</th>
                    <th>Link</th>
                    <th width="80px">Status</th>
                    <th class="text-center" width="160px">Action</th>
                </tr>
            </thead>

            <tfoot>
                <tr>
                    <th width="50px">S.No.</th>
                    <th>Image</th>
                    <th>Link</th>
                    <th width="80px">Status</th>
                    <th class="text-center">Action</th>
                </tr>
            </tfoot>
            <tbody id="sortable">
                @foreach($notices as $key=>$notice)
                <tr id="item-{{ $notice->id }}">
                    <td>{{ $key+1 }}</td>
                    <td>
                        @if(file_exists('storage/'.$notice->image) && $notice->image !== '')
                        <img src="{!! asset('storage/'.$notice->image)!!}" style="width:100px;height: 100px;">
                        @endif
                    </td>
                    <td>{{ $notice->link }}</td>
                    <td>
                        @can('master-policy.perform',['notice','changeStatus'])
                        <a href="javascript:void(0)" class="btn btn-primary btn-icon btn-circle legitRipple change-status" id="{{ $notice->id }}">
                            @if($notice->is_active == 1)
                            <i class="la la-check"></i>
                            @else
                            <i class="la la-minus"></i>
                            @endif
                        </a>
                        @endif
                    </td>
                    <td>
                        @can('master-policy.perform',['notice','edit'])
                        <a href="{{ route('admin.notice.edit',$notice->id) }}" class="bg-slate-700 btn btn-success btn-icon btn-circle legitRipple">
                            <i class=" la la-pencil"></i>
                        </a>
                        @endif
                        @can('master-policy.perform',['notice','delete'])
                        <a href="javascript:void(0)" id="{{ $notice->id  }}" class="btn btn-danger btn-icon btn-circle legitRipple delete">
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
@endsection