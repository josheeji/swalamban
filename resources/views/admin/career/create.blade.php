@extends('layouts.backend.app')
@section('titile', 'Carrers - create')
@section('styles')

@endsection
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
            <h5 class="text-dark font-weight-bold mt-2 mb-2 mr-5">Careers</h5>
            <!--end::Page Title-->
            <!--begin::Actions-->
            <div class="subheader-separator subheader-separator-ver mt-2 mb-2 mr-4 bg-gray-200"></div>
            <span class="text-dark-50 font-weight-bold" id="kt_subheader_total">Create</span>
            <!--end::Actions-->
        </div>
        <!--end::Info-->

        <!--begin::Toolbar-->
        <div class="d-flex align-items-center">
            <a href="{{ route('admin.career.index') }}" class="btn btn-default btn-outline-success font-weight-bolder">Back</a>
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
        {!! Form::open(array('route' => 'admin.career.store','class'=>'form-horizontal','id'=>'validator', 'files' => 'true')) !!}
        <div class="row">
            <div class="col-12 col-md-8">
                <div class="card card-custom gutter-b">
                    <div class="card-body">
                        @php
                        $languages = Helper::getLanguages();
                        $isMultiLanguage = SettingHelper::setting('multi_language');
                        @endphp
                        @if($isMultiLanguage)
                        <ul class="nav nav-tabs nav-tabs-line nav-tabs-line-2x">
                            @php $count=0; @endphp
                            @foreach($languages as $language)
                            <li class="nav-item"><a class="nav-link {{ ($count == 0) ? 'active' : '' }}" data-toggle="tab" href="#aa-{{ $language['id'] }}">{{ $language['name'] ?? '' }}</a></li>
                            @php $count++; @endphp
                            @endforeach
                        </ul>
                        @endif
                        <div class="tab-content mt-5">
                            @php $count=0; @endphp
                            @foreach($languages as $language)
                            <div id="aa-{{ $language['id'] }}" class="tab-pane fade in {{ ($count == 0) ? 'active show' : '' }}">
                                <div class="form-group">
                                    <label class="control-label">Title<span class="text-danger">*</span></label>
                                    {!! Form::text('title['.$language['id'].']', null, array('class'=>'form-control')) !!}
                                </div>
                                <div class="form-group d-none">
                                    <label class="control-label">Description <span class="text-danger">*</span></label>
                                    {!! Form::text('description['.$language['id'].']', null, array('class'=>'form-control editor', 'id'=>'editor')) !!}
                                </div>
                            </div>
                            @php
                            $count++;
                            if(!$isMultiLanguage){
                            break;
                            }
                            @endphp
                            @endforeach
                           <div class="form-group">
                                <label class="control-label">File <span class="text-danger">*</span></label>
                                <div class="custom-file">
                                    <input type="file" class="custom-file-input" name="file" id="career-file">
                                    <label class="custom-file-label selected" for="career-file"></label>
                                </div>
                                <br>
                                <span class="text-muted  small">only jpg, jpeg, png, doc, docx and pdf format are allowed.</span>
                                <br>
                                <span class="text-muted  small">Image size should not be greater than 5MB.</span>
                            </div>
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
                            <label class="control-label">Publish Date <span class="text-danger">*</span></label>
                            {!! Form::date('publish_from', null, array('class'=>'form-control','placeholder'=>'Publish Date')) !!}
                        </div>
                        <div class="form-group">
                            <label class="control-label">End Date <span class="text-danger">*</span></label>
                            {!! Form::date('publish_to', null, array('class'=>'form-control','placeholder'=>'End Date')) !!}
                        </div>
                        <div class="form-group">
                            <label class="control-label">Location </label>
                            {!! Form::text('location', null, array('class'=>'form-control','placeholder'=>'Location')) !!}
                        </div>
                        <div class="form-group">
                            <label class="control-label">Opening </label>
                            {!! Form::text('opening', null, array('class'=>'form-control','placeholder'=>'Opening')) !!}
                        </div>
                    </div>
                </div>
            </div>
        </div>
        {!! Form::close() !!}
    </div>
</div>
@endsection
