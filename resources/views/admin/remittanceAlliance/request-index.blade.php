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
                    url: baseUrl + "/admin/remittance-alliance-request" + "/" + id,
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
</script>
@endsection
@section('page-header')
<div class="page-header page-header-default">
    <div class="page-header-content">
        <div class="page-title">
            <h4><i class="icon-arrow-left52 position-left"></i> <span class="text-semibold">{{ $title }}</span> - <span class="small">Manage</span></h4>
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
    @php $languages = Helper::getLanguages(); @endphp
    <div class="panel-body">
        <table class="table datatable-column-search-inputs defaultTable">
            <thead>
                <tr>
                    <th width="50px">S.No.</th>
                    <th>Name</th>
                    <th>Subject</th>
                    <th>Contact</th>
                    <th>Email</th>
                    <th class="text-center" width="160px">Action</th>
                </tr>
            </thead>
            <tfoot>
                <tr>
                    <th width="50px">S.No.</th>
                    <th>Name</th>
                    <th>Subject</th>
                    <th>Contact</th>
                    <th>Email</th>
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
                @foreach($data as $index=>$item)
                <tr id="item-{{ $item->id }}">
                    <td>{{ $index+1 }}</td>
                    <td>{{ $item->name }}</td>
                    <td>{{ $item->subject }}</td>
                    <td>{{ $item->phone }}</td>
                    <td>{{ $item->email }}</td>
                    <td class="text-center">
                        <a title="View" href="{{ route('admin.remittance-alliance-request.show',$item->id) }}" class="btn btn-success btn-icon btn-circle legitRipple">
                            <i class=" icon-eye"></i>
                        </a>
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