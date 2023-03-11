@extends('layouts.backend.app')
@section('title', 'Product Category - create')
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
            <h5 class="text-dark font-weight-bold mt-2 mb-2 mr-5">Product Category</h5>
            <!--end::Page Title-->
            <!--begin::Actions-->
            <div class="subheader-separator subheader-separator-ver mt-2 mb-2 mr-4 bg-gray-200"></div>
            <span class="text-dark-50 font-weight-bold" id="kt_subheader_total">Create</span>
            <!--end::Actions-->
        </div>
        <!--end::Info-->

        <!--begin::Toolbar-->
        <div class="d-flex align-items-center">
            <a href="{{ route('admin.account-type-category.index') }}"
                class="btn btn-outline-success font-weight-bolder">Back</a>
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
        {!! Form::open(array('route' =>'admin.account-type-category.store','class'=>'form-horizontal','id'=>'validator',
        'files'
        => 'true')) !!}
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
                            <li class="nav-item"><a class="nav-link {{ ($count == 0) ? 'active' : '' }}"
                                    data-toggle="tab" href="#aa-{{ $language['id'] }}">{{ $language['name'] ?? '' }}</a>
                            </li>
                            @php
                            $count++;
                            @endphp
                            @endforeach
                        </ul>
                        @endif
                        <div class="tab-content mt-5">
                            @php $count=0; @endphp
                            @foreach($languages as $language)
                            <div id="aa-{{ $language['id'] }}"
                                class="tab-pane fade in {{ ($count == 0) ? 'active show' : '' }}">
                                <div class="form-group">
                                    <label class="control-label">Title <span class="text-danger">*</span></label>
                                    {!! Form::text('title['.$language['id'].']', null, array('class'=>'form-control'))
                                    !!}
                                </div>
                                <div class="form-group">
                                    <label class="control-label">Short Description</label>
                                    {!! Form::textarea('excerpt['.$language['id'].']', null,
                                    array('class'=>'form-control editor', 'id'=>'', 'maxlength' => 255, "rows" => 3))
                                    !!}
                                </div>
                                {{-- <div class="form-group d-none">
                                    <label class="control-label">Description <span class="text-danger">*</span></label>
                                    {!! Form::textarea('description['.$language['id'].']', null,
                                    array('class'=>'form-control editor', 'id'=>'editor')) !!}
                                </div> --}}
                            </div>
                            @php
                            $count++;
                            if(!$isMultiLanguage){
                            break;
                            }
                            @endphp
                            @endforeach
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
                        {{-- <div class="form-group d-none">
                            <label class="control-label">Layout<span class="text-danger">*</span></label>
                            <select name="layout" class="form-control">
                                @foreach(PageHelper::pageLayoutOptionList() as $key => $value)
                                <option value="{{ $key }}" {{ $key==3 ? 'selected' : '' }}>{{ $value }}</option>
                                @endforeach
                            </select>
                        </div> --}}

                        @if(SettingHelper::setting('banner_image') == 1)
                        <div class="form-group d-none">
                            <label class="control-label">Banner</label>
                            <span class="text-muted float-right small">Preferred size: {{
                                Helper::preferredSize('account-type', 'banner') }}</span>
                            <div class="custom-file">
                                <input type="file" class="custom-file-input" name="banner" id="banner-file">
                                <label class="custom-file-label selected" for="banner-file"></label>
                            </div>
                        </div>
                        @endif
                        <div class="form-group">
                            <label class="control-label">Image</label>
                            <span class="text-muted float-right small">Preferred size: {{
                                Helper::preferredSize('account-type', 'image') }}</span>
                            <div class="custom-file">
                                <input type="file" class="custom-file-input" name="image" id="image-file">
                                <label class="custom-file-label selected" for="image-file"></label>
                            </div>
                        </div>
                        {{-- <div class="form-group d-none">
                            <label for="" class="control-label">URL</label>
                            {!! Form::text('link', null, array('class'=>'form-control','placeholder'=>'URL')) !!}
                        </div> --}}
                        {{-- <div class="form-group d-none">
                            <label for="" class="control-label">Type<span class="text-danger">*</span></label>
                            <select name="type" class="form-control">
                                <option value="">Select Type</option>
                                <option value="service" {{ request('type')=='service' ? 'selected' :'' }}>Service
                                </option>
                                <option value="loan" {{ request('type')=='loan' ? 'selected' :'' }}>Loan</option>
                            </select>
                        </div> --}}
                        <div class="form-group d-none">
                            <label for="" class="control-label">Download Form</label>
                            {{-- <select name="download_id" class="form-control">
                                <option value="">Select download form</option>
                                @if(isset($downloads) && !empty($downloads))
                                @foreach($downloads as $download)
                                <option value="{{ $download->id }}" {{ old("download_id")==$download->id ? 'selected' :
                                    '' }}>{{ $download->title }}</option>
                                @endforeach
                                @endif
                            </select> --}}
                        </div>
                        <div class="form-group">
                            {{-- <div class="checkbox-inline d-none">
                                <label class="checkbox checkbox-lg">
                                    <input type="checkbox" name="show_image">
                                    <span></span>Show Image on detail view.</label>
                            </div> --}}
                        </div>
                    </div>
                </div>
            </div>
        </div>
        {!! Form::close() !!}
    </div>
</div>
@endsection