{{-- @inject('helper', App\Helper\Helper) --}}
@extends('layouts.backend.app')
@section('scripts')
<script>
    $('form').submit(function() {
        $(this).find("button[type='submit']").prop('disabled', true);
    });

    $(".btn-delete").on("click", function() {
        $object = $(this);
        var action = $object.data('action');
        var imageType = $object.data('type');
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
                    url: action,
                    type: "POST",
                    data: {
                        type: imageType
                    },
                    dataType: "json",
                    success: function(response) {
                        Swal.fire("Deleted!", response.message, "success");
                        $('.' + imageType + '-wrap').remove();
                    },
                    error: function(xhr, ajaxOptions, thrownError) {
                        Swal.fire('Error', 'Something went wrong while processing your request.', 'error');
                    }
                });
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
            <h5 class="text-dark font-weight-bold mt-2 mb-2 mr-5">Popups</h5>
            <!--end::Page Title-->
            <!--begin::Actions-->
            <div class="subheader-separator subheader-separator-ver mt-2 mb-2 mr-4 bg-gray-200"></div>
            <span class="text-dark-50 font-weight-bold" id="kt_subheader_total">Edit</span>
            <!--end::Actions-->
        </div>
        <!--end::Info-->

        <!--begin::Toolbar-->
        <div class="d-flex align-items-center">
            <a href="{{ route('admin.popup.index') }}" class="btn btn-default btn-outline-success font-weight-bolder"><i class="icon-undo2 position-left"></i> Back</span></a>
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
        {!! Form::open(array('route' => ['admin.popup.update', $popup->id],'class'=>'form-horizontal','id'=>'validator', 'files' => 'true', 'enctype'=>'multipart/form-data')) !!}
        <div class="row">
            <div class="col-12 col-md-8">
                <div class="card card-custom gutter-b">
                    <div class="card-body">
                        <div class="form-group">
                            <label class="control-label">Title <span class="text-danger">*</span></label>
                            {!! Form::text('title', $popup->title ?? "", array('class'=>'form-control','placeholder'=>'Post title')) !!}
                        </div>

                        <div class="form-group">
                            <div class="checkbox-inline">
                                <label class="checkbox checkbox-lg">
                                    <input type="checkbox" name="show_title" value="1" {{ $popup->show_title == 1 ? 'checked': '' }}><span></span> Show Title
                                </label>
                            </div>
                        </div>
                        <div class="form-group d-none">
                            <label class="control-label">Description</label>
                            {!! Form::textarea('description', $popup->description, array('class'=>'form-control editor', 'id'=>'editor')) !!}
                        </div>
                    </div>
                    <div class="card-footer">
                        <button type="submit" class="btn btn-primary">Submit</button>
                    </div>
                </div>
            </div>
            <div class="col-12 col-md-4">
                <div class="card card-custom gutter-b">
                    <div class="card-body">
                        <div class="form-group">
                            <div class="checkbox-inline">
                                <label class="checkbox checkbox-lg">
                                    <input type="checkbox" name="is_active" value="1" {{ $popup->is_active == 1 ? 'checked': '' }}><span></span>Publish ?
                                </label>
                            </div>
                        </div>
                        @if(file_exists('storage/thumbs/' . $popup->image) && $popup->image != '')
                        <div class="bg-gray-300 my-2 px-3 py-2"><small>Existing Popup Preview</small></div>
                        <img src="{{ asset('storage/thumbs/' . $popup->image) }}" class="" alt="">
                        @endif
                        <div class="form-group">
                            <label class="control-label">Image</label>
                            <span class="text-muted float-right small"></span>
                            <div class="custom-file">
                                <input type="file" class="custom-file-input" name="image" id="image-file">
                                <label class="custom-file-label selected" for="image-file"></label>
                            </div>
                        </div>
                        <div class="form-group d-none">
                            <div class="checkbox-inline">
                                <label class="checkbox checkbox-lg">
                                    <input type="checkbox" name="show_image" value="1" {{ $popup->show_image == 1 ? 'checked': '' }}><span></span>Show Image
                                </label>
                            </div>
                        </div>
                        <div class="form-group d-none">
                            <div class="checkbox-inline">
                                <label class="checkbox checkbox-lg">
                                    <input type="checkbox" name="show_in_notification" value="1" {{ $popup->show_in_notification == 1 ? 'checked': '' }}><span></span>Show in notification
                                </label>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="control-label">URL <span class="text-danger">*</span></label>
                            {!! Form::text('url', $popup->url, array('class'=>'form-control','placeholder'=>'URL')) !!}
                        </div>

                        <div class="form-group">
                            <label class="control-label">Button Label</label>
                            {!! Form::text('btn_label', $popup->btn_label, ['class' => 'form-control']) !!}
                        </div>

                        <div class="form-group">
                            <div class="checkbox-inline">
                                <label class="checkbox checkbox-lg">
                                    <input type="checkbox" name="target" value="1" {{ $popup->target == 1 ? 'checked': '' }}><span></span>Open link in new tab
                                </label>
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="checkbox-inline">
                                <label class="checkbox checkbox-lg">
                                    <input type="checkbox" name="show_button" value="1" {{ $popup->show_button == 1 ? 'checked': '' }}><span></span>Show Button
                                </label>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            {!! method_field('PATCH') !!}
            {!! Form::close() !!}
        </div>
    </div>
</div>
@endsection