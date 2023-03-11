@extends('layouts.backend.app')
@section('scripts')
<script>
    $(function() {
        $(".view-check-all").click(function() {
            $(".view-check").prop('checked', $(this).prop("checked"));
        });

        $(".add-check-all").click(function() {
            $(".add-check").prop('checked', $(this).prop("checked"));
        });

        $(".edit-check-all").click(function() {
            $(".edit-check").prop('checked', $(this).prop("checked"));
        });

        $(".delete-check-all").click(function() {
            $(".delete-check").prop('checked', $(this).prop("checked"));
        });

        $(".status-check-all").click(function() {
            $(".status-check").prop('checked', $(this).prop("checked"));
        });
    });
</script>
@endsection
@section('page-header')
    <style>
        .form-check-input-styled{
            transform: scale(1.8);
        }
    </style>
<!--begin::Subheader-->
<div class="subheader py-2 py-lg-4  subheader-solid " id="kt_subheader">
    <div class=" container-fluid  d-flex align-items-center justify-content-between flex-wrap flex-sm-nowrap">
        <!--begin::Info-->
        <div class="d-flex align-items-center flex-wrap mr-2">
            <!--begin::Page Title-->
            <h5 class="text-dark font-weight-bold mt-2 mb-2 mr-5">Role</h5>
            <!--end::Page Title-->
            <!--begin::Actions-->
            <div class="subheader-separator subheader-separator-ver mt-2 mb-2 mr-4 bg-gray-200"></div>
            <span class="text-dark-50 font-weight-bold" id="kt_subheader_total">Manage Permission</span>
            <!--end::Actions-->
        </div>
        <!--end::Info-->

        <!--begin::Toolbar-->
        <div class="d-flex align-items-center">
            <a href="{{ route('admin.admin-type.index') }}" class="btn btn-default btn-outline-success font-weight-bolder">Back</a>
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
        <form method="post" action="{{ route('admin.admin-access.store',[$adminType->id]) }}">
            <div class="row">
                <div class="col-12">
                    <div class="card card-custom gutter-b">
                        <div class="card-body">
                            <input type="submit" value="Submit" class="btn btn-primary pull-right">
                            <table class="table table-striped table-hover">
                                <thead>
                                    <th width="25%">Module</th>
                                    <th width="15%"> View</th>
                                    <th width="15%"> Add</th>
                                    <th width="15%"> Edit</th>
                                    <th width="15%"> Delete</th>
                                    <th width="15%"> Change Status</th>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td>
                                            &nbsp;
                                        </td>
                                        <td>
                                            <div class="form-check form-check-inline">
                                                <label class="form-check-label">
                                                    <input type="checkbox" class="form-check-input-styled view-check-all">
                                                    &nbsp;Check All
                                                </label>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="form-check form-check-inline">
                                                <label class="form-check-label">
                                                    <input type="checkbox" class="form-check-input-styled add-check-all">
                                                    &nbsp;Check All
                                                </label>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="form-check form-check-inline">
                                                <label class="form-check-label">
                                                    <input type="checkbox" class="form-check-input-styled edit-check-all">
                                                    &nbsp;Check All
                                                </label>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="form-check form-check-inline">
                                                <label class="form-check-label">
                                                    <input type="checkbox" class="form-check-input-styled delete-check-all">
                                                    &nbsp;Check All
                                                </label>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="form-check form-check-inline">
                                                <label class="form-check-label">
                                                    <input type="checkbox" class="form-check-input-styled status-check-all">
                                                    &nbsp;Check All
                                                </label>
                                            </div>
                                        </td>
                                    </tr>
                                    @foreach($module as $item)
                                    <tr>
                                        <td>{{ $item->name }}</td>
                                        <td>
                                            <div class="form-check form-check-inline">
                                                <label class="form-check-label">
                                                    <input type="checkbox" name="view[{{ $item->id }}]" class="form-check-input-styled view-check" {{ (isset($access) && isset($access[$item->id]) && isset($access[$item->id][0]) && $access[$item->id][0]['view'] == 1) ? "checked" : "" }}>
                                                </label>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="form-check form-check-inline">
                                                <label class="form-check-label">
                                                    <input type="checkbox" name="add[{{ $item->id }}]" class="form-check-input-styled add-check" {{ (isset($access) && isset($access[$item->id]) && isset($access[$item->id][0]) && $access[$item->id][0]['add'] == 1) ? "checked" : "" }}>
                                                </label>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="form-check form-check-inline">
                                                <label class="form-check-label">
                                                    <input type="checkbox" name="edit[{{ $item->id }}]" class="form-check-input-styled edit-check" {{ (isset($access) && isset($access[$item->id]) && isset($access[$item->id][0]) && $access[$item->id][0]['edit'] == 1) ? "checked" : "" }}>
                                                </label>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="form-check form-check-inline">
                                                <label class="form-check-label">
                                                    <input type="checkbox" name="delete[{{ $item->id }}]" class="form-check-input-styled delete-check" {{ (isset($access) && isset($access[$item->id]) && isset($access[$item->id][0]) && $access[$item->id][0]['delete'] == 1) ? "checked" : "" }}>
                                                </label>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="form-check form-check-inline">
                                                <label class="form-check-label">
                                                    <input type="checkbox" name="changeStatus[{{ $item->id }}]" class="form-check-input-styled status-check" {{ (isset($access) && isset($access[$item->id]) && isset($access[$item->id][0]) && $access[$item->id][0]['changeStatus'] == 1) ? "checked" : "" }}>
                                                </label>
                                            </div>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                            {!! csrf_field() !!}
                        </div>
                        <div class="card-footer">
                            <button type="submit" class="btn btn-primary">Submit</button>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection