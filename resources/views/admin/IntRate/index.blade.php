@extends('layouts.backend.app')

@section('styles')
<link href="{{ asset('backend/plugins/datatables/datatables.bundle.css') }}" rel="stylesheet" type="text/css" />
@endsection

@section('scripts')
<script src="{{ asset('backend/plugins/datatables/datatables.bundle.js') }}"></script>
<script type="text/javascript">
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
                var url = "{{ url('admin/department/sort') }}";
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
                            text: "Department has been sorted.",
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
        $(".change-status").click(function() {
            $object = $(this);
            
            if($object.is(':checked')){
                checked = 1;
            }else{
                checked = 0;
            }
            
            $.ajax({
                type: "POST",
                url: "{{ $intBatch ? route('admin.interest-batch.toggle-status', $intBatch->id) : '' }}",
                data: {
                    '_token': "{{ csrf_token() }}",
                    'checked': checked
                },
                dataType: 'json',
                success: function(response) {
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
                        url: baseUrl + "/admin/department/" + id,
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

    $(".delete-interest-rate").click(function(e) {
        e.preventDefault();

        $object = $(this);
        var id = $object.attr('id');
        var user_type_id = $object.data('user_type_id');
        var url = $(this).attr('href');
        Swal.fire({
            title: 'Are you sure?',
            text: 'You will not be able to recover this !',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Yes, delete it!',
            cancelButtonText: 'No, keep it'
        }).then((result) => {
            if (result.value) {
                window.location.href = url;
            }
        })
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
            <h5 class="text-dark font-weight-bold mt-2 mb-2 mr-5">Interest Rates</h5>
            <!--end::Page Title-->
            <!--begin::Actions-->
            <div class="subheader-separator subheader-separator-ver mt-2 mb-2 mr-4 bg-gray-200"></div>
            <span class="text-dark-50 font-weight-bold" id="kt_subheader_total">Manage</span>
            <!--end::Actions-->
        </div>
        <!--end::Info-->

        <!--begin::Toolbar-->
        <div class="d-flex align-items-center">
            @can('master-policy.perform', ['interest-rate', 'add'])
            <a title="Create Department" href="{{ route('admin.interest-rates.create') }}" class="btn btn-outline-success font-weight-bolder">Create New</a>
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
            <form method="GET">
                <div class="card-header">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-inline">
                                <label class="mr-2">Date Filter :</label>
                                <select name="batch" class="form-control">
                                    @foreach($interestBatches as $batch)
                                        <option value="{{ $batch->id }}" @if(isset($param['batch']) && ($param['batch'] == $batch->id)) selected @endif>{{ $batch->title }}</option>
                                    @endforeach
                                </select>
                                <button class="ml-2 btn btn-primary btn-md">Filter</button>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="float-right">
                                @if($intBatch)
                                    <div class="checkbox-inline">
                                        <label class="checkbox checkbox-lg">
                                            <input type="checkbox" name="" class="change-status" value="1" @if($intBatch && $intBatch->active) checked="checked" @endif>
                                            <span></span>Active
                                        </label>
                                        @if(!$intBatch->active)
                                            <a href="{{ route('admin.interest-rates.delete', $intBatch->id) }}" class="btn btn-danger ml-2 delete-interest-rate">Delete</a>
                                        @endif
                                        <a href="{{ route('admin.interest-rates.edit-active', $intBatch->id) }}" class="btn btn-warning ml-2">Edit</a>
                                    </div>
                                @endif
                            </div>
                        </div>
                        @if($intBatch)
                            <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                                <h2 class="mt-4">{{ $intBatch->title }}</h2>
                            </div>
                        @endif
                    </div>
                </div>
            </form>
            <div class="card-body">
                <div class="maininner-container">
                    @if($intBatch)
                        @foreach($intBatch->interestRates as $index => $rate)
                            <h2>{{$index+1}}. {{ $interestTypes[$rate->type] }}</h2>
                            @if($rate->content != null)
                                {!! $rate->content !!}
                            @else
                                <p class="alert alert-dark">Not data found !!</p>
                            @endif
                        @endforeach
                    @else
                        <p class="alert alert-dark">No data found !!</p>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection