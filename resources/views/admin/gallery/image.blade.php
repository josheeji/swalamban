@extends('layouts.backend.app')
@section('title', 'Galleries - images')
@section('styles')
<link href="{{ asset('backend/css/bootstrap_limitless.min.css') }}" rel="stylesheet" type="text/css">
@endsection
@section('scripts')
<script src="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.3.1/js/bootstrap.bundle.min.js"></script>
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
                        url: "{{ route('admin.gallery.change-status') }}",
                        data: {
                            'id': id,
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
                }
            });
        });

        $(".gallery").on("click", ".delete", function() {
            $object = $(this);
            var id = $object.attr('id');
            var gallery = $object.data('gallery');
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
                        url: baseUrl + "/admin/gallery/image" + "/" + gallery + "/" + id,
                        data: {
                            id: id,
                            _method: 'DELETE'
                        },
                        success: function(response) {
                            Swal.fire("Deleted!", response.message, "success");
                            $("#imageItem-" + id).remove();
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
<!--begin::Subheader-->
<div class="subheader py-2 py-lg-4  subheader-solid " id="kt_subheader">
    <div class=" container-fluid  d-flex align-items-center justify-content-between flex-wrap flex-sm-nowrap">
        <!--begin::Info-->
        <div class="d-flex align-items-center flex-wrap mr-2">
            <!--begin::Page Title-->
            <h5 class="text-dark font-weight-bold mt-2 mb-2 mr-5">Galleries</h5>
            <!--end::Page Title-->
            <!--begin::Actions-->
            <div class="subheader-separator subheader-separator-ver mt-2 mb-2 mr-4 bg-gray-200"></div>
            <span class="text-dark-50 font-weight-bold" id="kt_subheader_total">Images</span>
            <!--end::Actions-->
        </div>
        <!--end::Info-->

        <!--begin::Toolbar-->
        <div class="d-flex align-items-center">
            <a href="{{ route('admin.gallery.index') }}" class="btn btn-default btn-outline-success font-weight-bolder">Back</a>
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
        <div class="row">
            <div class="col-12 col-md-12">
                @can('master-policy.perform',['gallery','add'])
                <div class="card">
                    <div class="card-header">
                        <a title="Upload Images" href="{{ route('admin.gallery-image.create', [$gallery->id]) }}" class="btn btn-dark float-right">Upload Images</a>
                    </div>
                </div>
                @endif
                <div class="gallery mt-3">
                    <div class="row">
                        @forelse($images as $image)
                        <div class="col-sm-5 col-md-3 mt-2" id="imageItem-{{ $image->id }}">
                            <div class="card">
                                <div class="">
                                    <img class="card-img img-fluid" src="{{ asset('storage/thumbs/'.$image->image) }}" alt="" style="width: 100%">
                                    <div class="card-img-actions-overlay card-img my-2 text-center">
                                        <a href="{{ asset('storage/thumbs/'.$image->image) }}" class="btn btn-icon btn-outline-info btn-circle" data-popup="lightbox" rel="group"><i class="la la-plus"></i></a>
                                        <a href="javascript:void(0)" id="{{ $image->id  }}" data-gallery="{{ $gallery->id }}" class="btn btn-icon btn-outline-danger btn-circle delete"><i class="la la-trash"></i></a>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @empty
                        <div class="col-sm-12">
                            <div class="card">
                                <div class="card-body">Sorry, no image has been uploaded yet.</div>
                            </div>
                        </div>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection