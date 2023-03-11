@extends('layouts.backend.app')
@section('scripts')

@endsection
@section('page-header')
<!--begin::Subheader-->
<div class="subheader py-2 py-lg-4  subheader-solid " id="kt_subheader">
    <div class=" container-fluid  d-flex align-items-center justify-content-between flex-wrap flex-sm-nowrap">
        <!--begin::Info-->
        <div class="d-flex align-items-center flex-wrap mr-2">
            <!--begin::Page Title-->
            <h5 class="text-dark font-weight-bold mt-2 mb-2 mr-5">Advertisements</h5>
            <!--end::Page Title-->
            <!--begin::Actions-->
            <div class="subheader-separator subheader-separator-ver mt-2 mb-2 mr-4 bg-gray-200"></div>
            <span class="text-dark-50 font-weight-bold" id="kt_subheader_total">Edit</span>
            <!--end::Actions-->
        </div>
        <!--end::Info-->

        <!--begin::Toolbar-->
        <div class="d-flex align-items-center">
            <a href="{{ route('admin.ads.index') }}" class="btn btn-default btn-outline-success font-weight-bolder"><i class="icon-undo2 position-left"></i> Back</span></a>
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
    <div class=" container-fluid">
        {!! Form::open(array('route' => ['admin.ads.update', $ad->id],'class'=>'form-horizontal','id'=>'validator', 'files' => 'true')) !!}
        <div class="row">
            <div class="col-12 col-md-9">
                <div class="card card-custom gutter-b">
                    <div class="card-body">
                        <div class="form-group">
                            <div class="checkbox-inline">
                                <label class="checkbox checkbox-lg">
                                    <input type="checkbox" name="is_active" value="1" {{ $ad->is_active == 1 ? 'checked' : '' }}>
                                    <span></span>Publish ?</label>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label">Placement</label>
                            <select name="placement_id" class="form-control">
                                <option value="">Select a placement</option>
                                @foreach($placement as $item)
                                <option value="{{ $item->id }}" {{ ($ad->placement_id == $item->id) ? "selected" : "" }}> {{ $item->title }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group">
                            <label class="control-label">Image</label>
                            <div class="custom-file">
                                <input type="file" class="custom-file-input" name="image" id="image-file">
                                <label class="custom-file-label selected" for="image-file"></label>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label">Link</label>
                            {!! Form::text('link', $ad->link ?? '', array('class'=>'form-control','placeholder'=>'Link')) !!}
                        </div>
                        <div class="d-none">
                            @include('admin.inc.visible-ads', ['visibleIn' => $ad->visible_in])
                        </div>
                    </div>
                    <div class="card-footer">
                        <button type="submit" class="btn btn-primary"> Update</button>
                    </div>
                </div>
            </div>
            <div class="col-12 col-md-3">
                @if(file_exists('storage/'.$ad->image) && $ad->image != '')
                <div>
                    <div class="bg-gray-300 my-2 px-3 py-2"><small>Existing Advertisement Preview</small></div>
                    <img src="{{ asset('storage/'.$ad->image) }}" class="displayimage" alt="">
                </div>
                @endif
            </div>
        </div>
        {!! method_field('PATCH') !!}
        {!! Form::close() !!}
    </div>
</div>
@endsection