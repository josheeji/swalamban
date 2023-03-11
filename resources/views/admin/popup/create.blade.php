@extends('layouts.backend.app')
@section('Popups - create')
@section('scripts')
<script>
    $('form').submit(function() {
        $(this).find("button[type='submit']").prop('disabled', true);
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
            <span class="text-dark-50 font-weight-bold" id="kt_subheader_total">Create</span>
            <!--end::Actions-->
        </div>
        <!--end::Info-->

        <!--begin::Toolbar-->
        <div class="d-flex align-items-center">
            <a href="{{ route('admin.popup.index') }}" class="btn btn-default btn-outline-success font-weight-bolder">Back</a>
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
        {!! Form::open(array('route' => 'admin.popup.store','class'=>'form-horizontal','id'=>'validator', 'files' => 'true', 'enctype'=>'multipart/form-data' )) !!}
        <div class="row">
            <div class="col-12 col-md-8">
                <div class="card card-custom gutter-b">
                    <div class="card-body">

                        <div class="form-group">
                            <label class="control-label">Title <span class="text-danger">*</span></label>
                            {!! Form::text('title', null, array('class'=>'form-control','placeholder'=>'Title')) !!}
                        </div>
                        <div class="form-group">
                            <div class="checkbox-inline">
                                <label class="checkbox checkbox-lg">
                                    <input type="checkbox" name="show_title" value="1" checked="checked">
                                    <span></span>Show title</label>
                            </div>
                        </div>

                        <div class="form-group d-none">
                            <label class="control-label">Description</label>
                            {!! Form::textarea('description', null, array('class'=>'form-control editor', 'id'=>'editor')) !!}
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
                                    <input type="checkbox" name="is_active" value="1" checked="checked">
                                    <span></span>Publish ?</label>
                            </div>
                        </div>
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
                                    <input type="checkbox" name="show_image" value="1" checked="checked">
                                    <span></span>Show Image</label>
                            </div>
                        </div>
                        <div class="form-group d-none">
                            <div class="checkbox-inline">
                                <label class="checkbox checkbox-lg">
                                    <input type="checkbox" name="show_in_notification" value="1" checked="checked">
                                    <span></span>Show in notification</label>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label">URL</label>
                            <div class="{{ $errors->has('url') ? ' has-error' : '' }}">
                                {!! Form::text('url', null, array('class'=>'form-control')) !!}
                            </div>
                            @if ($errors->has('url'))
                            <span class="help-block">
                                <strong>{{ $errors->first('url') }}</strong>
                            </span>
                            @endif
                        </div>

                        <div class="form-group">
                            <label class="control-label">Button Label</label>
                            {!! Form::text('btn_label', '', ['class' => 'form-control']) !!}
                        </div>

                        <div class="form-group">
                            <div class="checkbox-inline">
                                <label class="checkbox checkbox-lg">
                                    <input type="checkbox" name="target" value="1" checked="checked">
                                    <span></span>Open link in new tab</label>
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="checkbox-inline">
                                <label class="checkbox checkbox-lg">
                                    <input type="checkbox" name="show_button" value="1" checked="checked">
                                    <span></span>Show Button</label>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        {{ csrf_field() }}
        {!! Form::close() !!}
    </div>
</div>
@endsection