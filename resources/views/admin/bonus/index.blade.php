@extends('layouts.backend.app')
@section('title', 'Bonus - manage')
@section('styles')
<link href="{{ asset('backend/plugins/datatables/datatables.bundle.css') }}" rel="stylesheet" type="text/css" />
@endsection
@section('scripts')
<script src="{{ asset('backend/plugins/datatables/datatables.bundle.js') }}"></script>
<script>
    $(function() {
        $(".change-status").on('click', function() {
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
                        url: "{{ route('admin.bonus.change-status') }}",
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

        $(".delete").on("click", function() {
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
                        url: baseUrl + "/admin/bonus/" + id,
                        data: {
                            id: id,
                            _method: 'DELETE'
                        },
                        success: function(response) {
                            Swal.fire("Deleted!", response.message, "success");
                            $object.closest('tr').remove();
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

        $('.btn-flush').on('click', function(e) {
            e.preventDefault();
            Swal.fire({
                title: 'Are you sure?',
                text: 'You will not be able to recover this!',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Yes, delete it!',
                cancelButtonText: 'No, keep it'
            }).then((result) => {
                if (result.value) {
                    window.location = "{{ route('admin.bonus.flush') }}"
                } else {
                    return;
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
            <h5 class="text-dark font-weight-bold mt-2 mb-2 mr-5">Bonus Shares</h5>
            <!--end::Page Title-->
            <!--begin::Actions-->
            <div class="subheader-separator subheader-separator-ver mt-2 mb-2 mr-4 bg-gray-200"></div>
            <span class="text-dark-50 font-weight-bold" id="kt_subheader_total">Manage</span>
            <!--end::Actions-->
        </div>
        <!--end::Info-->

        <!--begin::Toolbar-->
        <div class="d-flex align-items-center">
            @can('master-policy.perform', ['bonus', 'edit'])
            <a title="Flush Data" href="javascript:void(0);" class="btn btn-outline-danger btn-flush font-weight-bolder mr-2">
                <i class="la la-trash position-left"></i>Trash <span class="legitRipple-ripple"></span>
            </a>
            @endif

            @can('master-policy.perform', ['bonus', 'add'])
            <a title="Create Branch" href="{{ route('admin.bonus.import') }}" class="btn btn-outline-success font-weight-bolder" style="margin-right: 15px">
                <i class="icon-file-plus position-left"></i>Import <span class="legitRipple-ripple"></span>
            </a>
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
                <form class="row" method="get">
                    <div class="col-md-3">
                        <input type="text" name="keyword" class="form-control" placeholder="Search (Enter Name or BOID)" />
                    </div>
                    <div class="col-md-8"></div>
                    <div class="col-md-1">
                        <input type="submit" value="Search" class="btn btn-primary float-right">
                    </div>
                </form>
            </div>
        </div>
        <div class="card card-custom gutter-b">
            <div class="card-body">
                <div class="datatable datatable-bordered datatable-head-custom datatable-default datatable-primary datatable-loaded" id="kt_datatable">
                    <table class="table">
                        <thead>
                            <tr>
                                <th width="50">S.No.</th>
                                <th>Title</th>
                                <th>BOID</th>
                                <th>Name</th>
                                <th>Actual Bonus</th>
                                <th>Total Kitta</th>
                                <th width="80">Status</th>
                                <th width="120">Action</th>
                            </tr>
                        </thead>
                        <tfoot>
                            <tr>
                                <th>S.No.</th>
                                <th>Title</th>
                                <th>BOID</th>
                                <th>Name</th>
                                <th>Actual Bonus</th>
                                <th>Total Kitta</th>
                                <th>Status</th>
                                <th>Action</th>
                            </tr>
                        </tfoot>
                        <tbody>
                            @if(isset($data) && $data->isEmpty())
                            <tr>
                                <td colspan="9">No record found.</td>
                            </tr>
                            @else
                            @can('master-policy.perform', ['bonus', 'edit'])
                            @php $edit_access = 1; @endphp
                            @endcan
                            @can('master-policy.perform', ['bonus', 'delete'])
                            @php $delete_access = 1; @endphp
                            @endcan
                            @can('master-policy.perform', ['bonus', 'changeStatus'])
                            @php $status_access = 1; @endphp
                            @endcan
                            @php
                            $index = $data->hasPages() ? $data->firstItem() : 1;
                            @endphp
                            @foreach($data as $key => $item)
                            <tr>
                                <td>{{ $index++ }}</td>
                                <td>{!! $item->category ? $item->category->title : '' !!}</td>
                                <td>{{ $item->boid }}</td>
                                <td>{{ $item->name }}</td>
                                <td>{!! $item->actual_bonus !!}</td>
                                <td>{!! $item->total !!}</td>
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
                                    <a title="Edit" href="{{ route('admin.bonus.edit',$item->id) }}" class="bg-slate-700 btn btn-success btn-icon btn-circle legitRipple">
                                        <i class=" la la-pencil"></i>
                                    </a>
                                    @endif
                                    @if(isset($delete_access) && $delete_access == true)
                                    <a title="Delete" href="javascript:void(0)" id="{{ $item->id  }}" class="btn btn-danger btn-icon btn-circle legitRipple delete"><i class="la la-trash"></i></a>
                                    @endif
                                </td>
                            </tr>
                            @endforeach
                            @endif
                        </tbody>
                    </table>
                    @if(isset($data) && !$data->isEmpty())
                    {!! $data->appends(request()->query())->links('admin.inc.pagination') !!}
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection