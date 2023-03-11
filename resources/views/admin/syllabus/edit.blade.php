@extends('layouts.backend.app')
@section('title', 'Syllabus - ' . $syllabus->designation)
@section('scripts')
<script>
    $(document).ready(function() {
        onload = function() {
            var itemText = $('#select  option:selected').val();
            select_drop(itemText);
        };

        function select_drop(itemText) {
            if (itemText == 'Link') {
                debugger
                $('#file').css("display", "none");
                $('#link').css("display", "block");
            } else {
                debugger
                $('#link').css("display", "none");
                $('#file').css("display", "block");
            }
        }

        $(function() {
            $('#select').on('change', function() {
                var itemText = $(this).find('option:selected').text();
                if (itemText == 'Link') {
                    $('#file').css("display", "none");
                    $('#link').css("display", "block");
                } else {
                    $('#link').css("display", "none");
                    $('#file').css("display", "block");
                }
            });
            $('#syllabus').submit(function() {
                var itemText = $('#select').find('option:selected').text();
                if (itemText == 'Link') {
                    $('#file').remove();
                } else {
                    $('#link').remove();
                }
            });
        });
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
            <h5 class="text-dark font-weight-bold mt-2 mb-2 mr-5">Syllabus</h5>
            <!--end::Page Title-->
            <!--begin::Actions-->
            <div class="subheader-separator subheader-separator-ver mt-2 mb-2 mr-4 bg-gray-200"></div>
            <span class="text-dark-50 font-weight-bold" id="kt_subheader_total">Edit</span>
            <!--end::Actions-->
        </div>
        <!--end::Info-->

        <!--begin::Toolbar-->
        <div class="d-flex align-items-center">
            <a href="{{ route('admin.syllabus.index') }}" class="btn btn-default btn-outline-success font-weight-bolder">Back</a>
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
        {!! Form::open(array('route' => ['admin.syllabus.update', $syllabus->id],'class'=>'form-horizontal','id'=>'validator', 'files' => 'true','novalidate')) !!}
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
                            <input type="hidden" name="post[{{ $language['id'] }}]" value="{{ ($language['id'] == $preferredLanguage) ? $syllabus->id : ($langContent[$language['id']][0]->id ?? "") }}">
                            <div id="aa-{{ $language['id'] }}" class="tab-pane fade in {{ ($count == 0) ? 'active show' : '' }}">
                                <div class="form-group {{ $errors->has('category') ? ' has-error' : '' }}">
                                    <label class="control-label">Category <span class="text-danger">*</span></label>
                                    {!! Form::text('category['.$language['id'].']', ($language['id'] == $preferredLanguage) ? $syllabus->category : ($langContent[$language['id']][0]->category ?? ""), array('class'=>'form-control')) !!}
                                </div>
                                <div class="form-group {{ $errors->has('designation') ? ' has-error' : '' }}">
                                    <label class="control-label">Designation <span class="text-danger">*</span></label>
                                    {!! Form::text('designation['.$language['id'].']', ($language['id'] == $preferredLanguage) ? $syllabus->designation : ($langContent[$language['id']][0]->designation ?? ""), array('class'=>'form-control')) !!}
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
                                    <input type="checkbox" name="is_active" value="1" {{ $syllabus->is_active == 1 ? 'checked' : '' }}>
                                    <span></span>Publish ?</label>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label">Type <span class="text-danger">*</span></label>
                            <select name="type" class="form-control">
                                <option value="1" {{ $syllabus->type == 1 ?'selected' :'' }}>खुला</option>
                                <option value="2" {{ $syllabus->type == 2 ?'selected' :'' }}>आ.प्र.</option>
                            </select>
                        </div>
                        @if(file_exists('storage/'.$syllabus->file) && $syllabus->file != '')
                        <div class="mb-2">
                            <div class="bg-gray-300 my-2 px-3 py-2"><small>Existing Download Preview</small></div>
                            @if(file_exists('storage/'.$syllabus->file) && !empty($syllabus->file))
                            <a href="{!! asset('storage/'.$syllabus->file) !!}" target="_blank"><i class="la la-download"></i></a>
                            @endif
                        </div>
                        @endif
                        <div class="form-group">
                            <label class="control-label">File</label>
                            <br>
                            <span class="text-muted  small">only jpg, jpeg, png, doc, docx and pdf format are allowed.</span>
                            <br>
                            <span class="text-muted  small">Image size should not be greater than 20MB.</span>
                            <div class="custom-file">
                                <input type="file" class="custom-file-input" name="file" id="banner-file">
                                <label class="custom-file-label selected" for="banner-file"></label>
                            </div>
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