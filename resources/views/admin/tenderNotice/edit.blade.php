@extends('layouts.backend.app')
@section('title', 'Tender Notice - ' . $content->title)
@section('styles')

@endsection
@section('scripts')
<script>
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
            <h5 class="text-dark font-weight-bold mt-2 mb-2 mr-5">Tender Notice</h5>
            <!--end::Page Title-->
            <!--begin::Actions-->
            <div class="subheader-separator subheader-separator-ver mt-2 mb-2 mr-4 bg-gray-200"></div>
            <span class="text-dark-50 font-weight-bold" id="kt_subheader_total">Edit</span>
            <!--end::Actions-->
        </div>
        <!--end::Info-->

        <!--begin::Toolbar-->
        <div class="d-flex align-items-center">
            <a href="{{ route('admin.tender-notice.index') }}" class="btn btn-default btn-outline-success font-weight-bolder">Back</span></a>
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
        {!! Form::open(array('route' => ['admin.tender-notice.update', $content->id],'class'=>'form-horizontal','id'=>'validator', 'files' => 'true')) !!}
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
                            <li class="nav-item"><a class="nav-link {{ ($count == 0) ? 'active' : '' }}" data-toggle="tab" href="#aa-{{ $language['id'] }}">{{ $language['name'] ?? '' }}</a>
                            </li>
                            @php $count++; @endphp
                            @endforeach
                        </ul>
                        @endif
                        <div class="tab-content mt-5">
                            @php $count=0; @endphp
                            @foreach($languages as $language)
                            <input type="hidden" name="post[{{ $language['id'] }}]" value="{{ ($language['id'] == $preferredLanguage) ? $content->id : ($langContent[$language['id']][0]->id ?? "") }}">
                            <div id="aa-{{ $language['id'] }}" class="tab-pane fade in {{ ($count == 0) ? 'active show' : '' }}">
                                <div class="form-group {{ $errors->has('title') ? ' has-error' : '' }}">
                                    <label class="control-label">Title <span class="text-danger">*</span></label>
                                    {!! Form::text('title['.$language['id'].']', ($language['id'] == $preferredLanguage) ? $content->title : ($langContent[$language['id']][0]->title ?? ""), array('class'=>'form-control','placeholder'=>'Banner title')) !!}
                                </div>
                                <div class="form-group">
                                    <label class="control-label">Excerpt</label>
                                    {!! Form::textarea('excerpt['.$language['id'].']', ($language['id'] == $preferredLanguage) ? $content->excerpt : ($langContent[$language['id']][0]->excerpt ?? ""), array('class'=>'form-control', 'id'=>'', 'rows' => 3, 'maxlength' => '250')) !!}
                                </div>
                                <div class="form-group">
                                    <label class="control-label">Description</label>
                                    {!! Form::textarea('description['.$language['id'].']', ($language['id'] == $preferredLanguage) ? $content->description : ($langContent[$language['id']][0]->description ?? ""), array('class'=>'form-control editor', 'id'=>'editor')) !!}
                                </div>
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
                                    <input type="checkbox" name="is_active" value="1" {{ $content->is_active == 1 ? 'checked' : '' }}>
                                    <span></span>Publish ?</label>
                            </div>
                        </div>
                        @if(file_exists('storage/thumbs/' . $content->image) && $content->image != '')
                        <div class="image-wrap mb-2">
                            <div class="bg-gray-300 my-2 px-3 py-2"><small>Existing Press Release Preview</small></div>
                            <div class="card card-custom overlay">
                                <div class="card-body p-0">
                                    <div class="overlay-wrapper">
                                        <img src="{{ asset('storage/' . $content->image) }}" alt="" class="w-100 rounded" />
                                    </div>
                                    <div class="overlay-layer">
                                        <a href="#" class="btn btn-icon btn-danger btn-shadow btn-delete" data-action="{{ route('admin.tender-notice.destroy-image', $content->id) }}" data-type="image"><i class="la la-trash"></i></a>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @endif
                        <div class="form-group">
                            <label class="control-label">Image<span class="text-danger">*</span></label>
                            <span class="text-muted float-right small">Preferred size: 512px / 384px or 800px / 600px</span>
                            <div class="custom-file">
                                <input type="file" class="custom-file-input" name="image" id="image-file">
                                <label class="custom-file-label selected" for="image-file"></label>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label">Publish Date <span class="text-danger">*</span></label>
                            {!! Form::date('start_date', $content->start_date, array('class'=>'form-control','placeholder'=>'Start Date')) !!}
                        </div>
                        <div class="form-group">
                            <label class="control-label">Submission Date <span class="text-danger">*</span></label>
                            {!! Form::date('end_date', $content->end_date, array('class'=>'form-control','placeholder'=>'End Date')) !!}
                        </div>
                    </div>
                </div>
            </div>
        </div>
        {!! method_field('PATCH') !!}
        {!! Form::close() !!}
    </div>
</div>
@endsection