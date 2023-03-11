@inject('helper', 'App\Helper\Helper')
@extends('layouts.backend.app')
@section('styles')
<link href="{{ asset('backend/plugins/datatables/datatables.bundle.css') }}" rel="stylesheet" type="text/css" />
@endsection
@section('scripts')
<script type="text/javascript">
    $(document).ready(function() {
        $('.sidebar-category').find('#travel').addClass('nav-item nav-item-submenu nav-item-expanded nav-item-open');
        $('.sidebar-category').find('#booking').addClass('active');
        $('.sidebar-category').find('#travel').find('ul').show();
    });
</script>
<script src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.10.3/jquery-ui.min.js"></script>
<script src="{{ asset('backend/tablednd.js') }}"></script>
<script>
    $(function() {
        $('.defaultTable').dataTable({
            "pageLength": 50
        });
        $('#sortable').sortable({
            axis: 'y',
            update: function(event, ui) {
                var data = $(this).sortable('serialize');
                var url = "{{ url('admin/booking/sort') }}";
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
                            text: "Booking has been sorted.",
                            imageUrl: "{{ asset('backend') }}/thumbs-up.png",
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
                        url: "{{ route('admin.booking.change-status') }}",
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
                        url: baseUrl + "/admin/booking" + "/" + id,
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

<script type="text/javascript">
    $(function() {
        $(document).on('click', '.view', function(e) {
            var package_name = $(this).data("package")
            var destination_name = $(this).data("destination")
            var no_person = $(this).data("no_person")
            var accomodation = $(this).data("accomodation")
            var transportation = $(this).data("transportation")
            var places = $(this).data("places")
            var email = $(this).data('email');
            var mobile = $(this).data('mobile');
            var date = $(this).data('departure-date');
            var message = $(this).data('message');
            var name = $(this).data('name');
            $('#mymodal').modal('show');
            $("#packagename").html(package_name);
            $("#destinationname").html(destination_name);
            $("#no_person").html(no_person);
            $("#accomodation").html(accomodation);
            $("#transportation").html(transportation);
            $("#places").html(places);
            $("#email").html(email);
            $("#mobile-no").html(mobile);
            $("#appointment-date").html(date);
            $("#message").html(message);
            $('#name').html(name);
        });
    });
</script>
@endsection
@section('page-header')
<div class="page-header page-header-default">
    <div class="page-header-content">
        <div class="page-title">
            <h4><i class="icon-arrow-left52 position-left"></i> <span class="text-semibold">Home</span> -
                Bookings</h4>
        </div>

    </div>
    <div class="breadcrumb-line">
        <ul class="breadcrumb">
            <li><a href="{{ route('admin.dashboard') }}"><i class="icon-home2 position-left"></i> Home</a>
            </li>
            <li class="active">Bookings</li>
        </ul>
    </div>
</div>
@endsection
@section('content')
<div class="panel panel-flat">
    {{--
        <div class="panel-heading">
            <h5 class="panel-title"><i class="icon-grid3 position-left"></i>Bookings</h5>
            <div class="heading-elements">
                <a href="{{ route('admin.booking.create') }}" class="btn btn-default legitRipple pull-right">
    <i class="icon-file-plus position-left"></i>
    Create New
    <span class="legitRipple-ripple"></span>
    </a>
</div>
</div> --}}
<div class="panel-body">
    <table class="table datatable-column-search-inputs defaultTable">
        <thead>
            <tr>
                <th width="50px">S.No.</th>
                <th>Name</th>
                <th>Package</th>
                <th>Appointment Date</th>
                <th>Action</th>

            </tr>
        </thead>
        <tbody id="sortable">


            @foreach($bookings as $key=>$item)
            <tr id="item-{{ $item->id }}">
                <td>{{ $key+1 }}</td>
                <td>{{ $item->f_name }}&nbsp;{{$item->l_name}}</td>
                <td> {{(isset($item->package) && !is_null($item->package->title))
                                                        ?  $item->package->title
                                                        : ""}}</td>
                <td>{{ Helper::formatDate($item->departure_date) }}</td>
                <!-- <td>
                    <a title="Change Status" href="javascript:void(0)" class="btn btn-primary btn-icon btn-circle legitRipple change-status" id="{{ $item->id }}">
                        @if($item->is_active == 1)
                        <i class="la la-check"></i>
                        @else
                        <i class="la la-minus"></i>
                        @endif

                    </a>
                </td> -->
                <td>


                    <a title="View Booking" class="view btn btn-success btn-icon btn-circle legitRipple" data-id="{{$item->id}}" data-destination="{{(isset($item->country) && !is_null($item->country->country_name))
                                                        ?  $item->country->country_name
                                                        : ""}}" data-package="{{(isset($item->package) && !is_null($item->package->title))
                                                        ?  $item->package->title
                                                        : ""}}" data-no_person="{{$item->no_person}}" data-transportation="{{(isset($item->package) && !is_null($item->package->transportation))
                                                        ?  $item->package->transportation
                                                        : ""}}" data-accomodation="{{(isset($item->package) && !is_null($item->package->accommodation))
                                                        ?  $item->package->accommodation
                                                        : ""}}" data-places="{{(isset($item->package) && !is_null($item->package->start_end))
                                                        ?  $item->package->start_end
                                                        : ""}}" data-departure-date="{{(isset($item->departure_date) && !is_null($item->departure_date))
                                                        ?  Helper::formatDate($item->departure_date)
                                                        : ""}}" data-mobile="{{(isset($item->mobile_no) && !is_null($item->mobile_no))
                                                        ?  $item->mobile_no : ""}}" data-email="{{(isset($item->email) && !is_null($item->email))
                                                        ?  $item->email : ""}}" data-message="{{(isset($item->message) && !is_null($item->message))
                                                        ?  $item->message : ""}}" data-name="{{ $item->f_name . ' '. $item->l_name }}" data-toggle="modal" data-target="#mymodal">
                        <i class="icon-eye2"></i>
                    </a>

                    <!-- <a title="Delete Booking" href="javascript:void(0)" id="{{ $item->id  }}" class="btn btn-danger btn-icon btn-circle legitRipple delete">
                        <i class="la la-trash"></i>
                    </a> -->

                </td>

            </tr>
            @endforeach


        </tbody>
        <tfoot>
            <tr>
                <th width="50px">S.No.</th>
                <th>Name</th>
                <th>Package</th>
                <th>Appointment Date</th>
                <th>Action</th>
            </tr>
        </tfoot>
    </table>
</div>
</div>

<div id="mymodal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">View Booking Details</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <input type="hidden" id="id" name="id">
                <!-- <b><label>Destination</label></b>
                <div id="destinationname"></div><br> -->
                <b><label>Name</label></b>
                <div id="name"></div><br>
                <b><label>Package</label></b>
                <div id="packagename"></div><br>
                <b><label>Email</label></b>
                <div id="email"></div><br>
                <b><label>Mobile No.</label></b>
                <div id="mobile-no"></div><br>
                <b><label>Appointment Date</label></b>
                <div id="appointment-date"></div><br>
                <b><label>Message</label></b>
                <div id="message"></div><br>
                <!-- <b><label>No of Person</label></b>
                <div id="no_person"></div><br>
                <b><label>Accommodations</label></b>
                <div id="accomodation"></div><br>
                <b><label>Transportation</label></b>
                <div id="transportation"></div><br>
                <b><label>Places</label></b>
                <div id="places"></div><br> -->
            </div>
        </div>
    </div>
</div>


@endsection