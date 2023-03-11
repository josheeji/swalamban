@extends('layouts.backend.app')
@section('styles')
<!--     <link href="{{ asset('backend/plugins/datatables/datatables.bundle.css') }}" rel="stylesheet" type="text/css" /> -->
@endsection
@section('scripts')
      <script src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.10.3/jquery-ui.min.js"></script>
 <!--    <script src="{{ asset('backend/tablednd.js') }}"></script> -->
    <script>

        $(function(){
            $('.defaultTable').dataTable( {
                "pageLength": 50
            } );
            $('#sortable').sortable({
                axis: 'y',
                update: function(event, ui){
                    var data = $(this).sortable('serialize');
                    var url = "{{ url('admin/destination/sort') }}";
                    $.ajax({
                        type: "POST",
                        url: url,
                        datatype: "json",
                        data: {order: data, _token: '{!! csrf_token() !!}'},
                        success: function(data){
                            console.log(data);
                            var obj = jQuery.parseJSON(data);
                            Swal.fire({
                                title: "Success!",
                                text: "Destinations has been sorted.",
                                imageUrl: "{{ asset('backend') }}/thumbs-up.png",
                                timer: 2000,
                                showConfirmButton: false
                            });
                        }
                    });
                }
            });
        });

        $(document).ready(function () {
            $(".defaultTable").on("click", ".change-status", function () {
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
                            url: "{{ route('admin.destination.change-status') }}",
                            data: {
                                'id': id,
                            },
                            dataType: 'json',
                            success: function (response) {
                                console.log(response);
                                Swal.fire("Thank You!", response.message, "success");
                                if (response.response.is_active == 1) {
                                    $($object).children().removeClass('la la-minus');
                                    $($object).children().addClass('la la-check');
                                } else {
                                    $($object).children().removeClass('la la-check');
                                    $($object).children().addClass('la la-minus');
                                }
                            },
                            error: function (e) {
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

            $(".defaultTable").on("click", ".delete", function () {
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
                            url: baseUrl + "/admin/imageresize" + "/" + id,
                            data: {
                                id: id,
                                _method: 'DELETE'
                            },
                            success: function (response) {
                                Swal.fire("Deleted!", response.message, "success");
                                var oTable = $('.defaultTable').dataTable();
                                var nRow = $($object).parents('tr')[0];
                                oTable.fnDeleteRow(nRow);
                            },
                            error: function (e) {
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
                <h4><i class="icon-arrow-left52 position-left"></i> <span class="text-semibold">Home</span> -
                    Image Resize</h4>
            </div>

        </div>
        <div class="breadcrumb-line">
            <ul class="breadcrumb">
                <li><a href="{{ route('admin.dashboard') }}"><i class="icon-home2 position-left"></i> Home</a>
                </li>
                <li class="active">Image Resize</li>
            </ul>
        </div>
    </div>
@endsection
@section('content')
    <div class="panel panel-flat">
        <div class="panel-heading">
            <h5 class="panel-title"><i class="icon-grid3 position-left"></i>Image Resize</h5>
            <div class="heading-elements">
                @can('master-policy.perform', ['imageresize', 'add'])
                <a href="{{ route('admin.imageresize.create') }}" class="btn btn-default legitRipple pull-right">
                    <i class="icon-file-plus position-left"></i>
                    Create New
                    <span class="legitRipple-ripple"></span>
                </a>
                    @endif
            </div>
        </div>
        <div class="panel-body">
            <table class="table datatable-column-search-inputs defaultTable">
                <thead>
                <tr>
                    <th width="50px">S.No.</th>
                    <th>Title</th>
                    <th>Alias</th>
                    <th>Resize Height</th>
                    <th>Resize Width</th>
                    <th>Action</th>
                </tr>
                </thead>
                <tbody id="sortable">

                @foreach($imageresizes as $key=>$imageresize)
                <tr>
                <td>{{ $key+1 }}</td>
                <td>{{ $imageresize->title }}</td>
                <td>{{ $imageresize->alias }}</td>
                <td>{{ $imageresize->image_resize_height }}</td>
                <td>{{ $imageresize->image_resize_width }}</td>
              
           
              {{--  <td>
                    @can('master-policy.perform', ['imageresize', 'changeStatus'])
                        <a href="javascript:void(0)"
                        class="btn btn-primary btn-icon btn-circle legitRipple change-status"
                        id="{{ $imageresize->id }}">
                        @if($imageresize->is_active == 1)
                        <i class="la la-check"></i>
                        @else
                        <i class="la la-minus"></i>
                        @endif
                        </a>
                    @endif
                </td>--}}
                <td>
                    @can('master-policy.perform', ['imageresize', 'edit'])
                        <a href="{{ route('admin.imageresize.edit',$imageresize->id) }}"
                        class="btn btn-success btn-icon btn-circle legitRipple">
                        <i class=" icon-database-edit2"></i>
                        </a>
                    @endif
                        @can('master-policy.perform', ['imageresize', 'delete'])
                            <a href="javascript:void(0)" id="{{ $imageresize->id  }}"
                            class="btn btn-danger btn-icon btn-circle legitRipple delete">
                            <i class="la la-trash"></i>
                            </a>
                        @endif
                </td>
                </tr>
                    @endforeach




                </tbody>
                <tfoot>
                <tr>
                    <th width="50px">S.No.</th>
                    <th>Title</th>
                    <th>Alias</th>
                    <th>Resize Height</th>
                    <th>Resize Width</th>
                    <th>Action</th>
                </tr>
                </tfoot>
            </table>
        </div>
        {{----}}
    </div>
@endsection