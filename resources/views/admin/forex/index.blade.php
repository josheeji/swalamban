@extends('layouts.backend.app')
@section('title', 'Forex - manage')
@section('styles')
@endsection
@section('scripts')
<script>
    $(".btn-delete").on('click', function() {
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
                    url: baseUrl + "/admin/forex/{{ $date }}",
                    data: {
                        _method: 'DELETE'
                    },
                    success: function(response) {
                        Swal.fire("Deleted!", response.message, "success");
                        location.reload();
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
<!--begin::Subheader-->
<div class="subheader py-2 py-lg-4  subheader-solid " id="kt_subheader">
    <div class=" container-fluid  d-flex align-items-center justify-content-between flex-wrap flex-sm-nowrap">
        <!--begin::Info-->
        <div class="d-flex align-items-center flex-wrap mr-2">
            <!--begin::Page Title-->
            <h5 class="text-dark font-weight-bold mt-2 mb-2 mr-5">Forex</h5>
            <!--end::Page Title-->
            <!--begin::Actions-->
            <div class="subheader-separator subheader-separator-ver mt-2 mb-2 mr-4 bg-gray-200"></div>
            <span class="text-dark-50 font-weight-bold" id="kt_subheader_total">Manage</span>
            <!--end::Actions-->
        </div>
        <!--end::Info-->

        <!--begin::Toolbar-->
        <div class="d-flex align-items-center">
            @can('master-policy.perform', ['forex', 'add'])
            <a title="Create Content" href="{{ route('admin.forex.create') }}" class="btn btn-outline-success font-weight-bolder">Create New</a>
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
                <form action="{{ route('admin.forex.index') }}" method="get">
                    <div class="row">
                        <div class="col-12 col-md-3">
                            <div class="form-group">
                                <input type="date" class="form-control" name="date" value="{{ request()->has('date') ? request()->get('date') : date('Y-m-d') }}">
                            </div>
                        </div>
                        <div class="col-12 col-md-2">
                            <input type="submit" value="Search" class="btn btn-info">
                        </div>
                        <div class="col-12 col-md-7">
                            @if(isset($forexes) && !$forexes->isEmpty())
                            <a href="javascript:void(0);" class="btn btn-icon btn-circle btn-danger btn-delete float-right"><i class="la la-trash"></i></a>
                            <a href="{{ route('admin.forex.edit', request()->has('date') ? request()->get('date') : date('Y-m-d')) }}" class="btn btn-icon btn-circle btn-light float-right mr-1" title="Edit"><i class="la la-pencil"></i></a>
                            @endif
                            <a href="{{ route('admin.forex.import') }}" class="btn btn-icon btn-circle btn-secondary float-right mr-1" title="Import"><i class="la la-file-import"></i></a>
                            <a href="{{ asset('backend/samples/forex-sample.xlsx') }}" class="btn btn-icon btn-circle btn-primary float-right mr-1" title="Sample File"><i class="las la-file-excel"></i></a>
                        </div>
                    </div>
                </form>
                <div class="datatable datatable-bordered datatable-head-custom datatable-default datatable-primary datatable-loaded" id="kt_datatable">
                    <table class="table">
                        <thead>
                            <tr>
                                <th width="50">S.No.</th>
                                <th>Currency</th>
                                <th>Unit</th>
                                <th>CB RATE FOR DENOMINATION LESS THAN 50</th>
                                <th>CB RATE FOR DENOMINATION 50 & ABOVE AND NCB RATE</th>
                                <th>SELLING</th>
                            </tr>
                        </thead>
                        <tfoot>
                            <tr>
                                <th>S.No.</th>
                                <th>Currency</th>
                                <th>Unit</th>
                                <th>CB RATE FOR DENOMINATION LESS THAN 50</th>
                                <th>CB RATE FOR DENOMINATION 50 & ABOVE AND NCB RATE</th>
                                <th>SELLING</th>
                            </tr>
                        </tfoot>
                        <tbody>
                            @if(isset($forexes) && !$forexes->isEmpty())
                            @foreach($forexes as $index => $forex)
                            <tr>
                                <td>{{ $index + 1 }}</td>
                                <td>{{ isset($forexOrders[$forex->FXD_CRNCY_CODE]) ? $forexOrders[$forex->FXD_CRNCY_CODE] : '' }}({{ $forex->FXD_CRNCY_CODE }})</td>
                                <td>{{ $forex->FXD_CRNCY_UNITS }}</td>
                                <td>{{ $forex->BUY_RATE ? $forex->BUY_RATE : 'N.A.' }}</td>
                                <td>{{ $forex->BUY_RATE_ABOVE ? $forex->BUY_RATE_ABOVE : 'N.A.' }}</td>
                                <td>{{ $forex->SELL_RATE ? $forex->SELL_RATE : 'N.A.' }}</td>
                            </tr>
                            @endforeach
                            @else
                            <tr>
                                <td colspan="6">No record(s) found.</td>
                            </tr>
                            @endif
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection