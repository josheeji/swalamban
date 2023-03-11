@extends('layouts.backend.app')
@section('title', 'AGM Reports - create')
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
            $('#download').submit(function() {
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
<div class="subheader py-2 py-lg-4  subheader-solid " id="kt_subheader">
    <div class=" container-fluid  d-flex align-items-center justify-content-between flex-wrap flex-sm-nowrap">
        <!--begin::Info-->
        <div class="d-flex align-items-center flex-wrap mr-2">
            <!--begin::Page Title-->
            <h5 class="text-dark font-weight-bold mt-2 mb-2 mr-5">AGM Reports</h5>
            <!--end::Page Title-->
            <!--begin::Actions-->
            <div class="subheader-separator subheader-separator-ver mt-2 mb-2 mr-4 bg-gray-200"></div>
            <span class="text-dark-50 font-weight-bold" id="kt_subheader_total">Edit</span>
            <!--end::Actions-->
        </div>
        <!--end::Info-->

        <!--begin::Toolbar-->
        <div class="d-flex align-items-center">
            <a href="{{ route('admin.agm-report.index') }}" class="btn btn-outline-success font-weight-bolder">Back</a>
        </div>
        <!--end::Toolbar-->
    </div>
</div>
@endsection
@section('content')
<div class="d-flex flex-column-fluid">
    <!--begin::Container-->
    <div class=" container-fluid">
        {!! Form::open(array('route' => ['admin.agm-report.update', $report->id],'class'=>'form-horizontal','id'=>'validator', 'files' => 'true','novalidate')) !!}
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
                            <li class="nav-item {{ ($count !=0 && !isset($langContent[$language['id']])) ? 'emptyContent' : '' }}"><a class="nav-link {{ ($count == 0) ? 'active' : '' }}" data-toggle="tab" href="#aa-{{ $language['id'] }}">{{ $language['name'] ?? '' }}</a>
                            </li>
                            @php $count++; @endphp
                            @endforeach
                        </ul>
                        @endif
                        <div class="tab-content mt-5">
                            @php $count=0; @endphp

                            @foreach($languages as $language)
                            <input type="hidden" name="post[{{ $language['id'] }}]" value="{{ ($language['id'] == $preferredLanguage) ? $report->id : ($langContent[$language['id']][0]->id ?? "") }}">
                            <div id="aa-{{ $language['id'] }}" class="tab-pane fade in {{ ($count == 0) ? 'active show' : '' }}">
                                <div class="form-group">
                                    <label class="control-label">Title <span class="text-danger">*</span></label>
                                    {!! Form::text('title['.$language['id'].']', ($language['id'] == $preferredLanguage) ? $report->title : ($langContent[$language['id']][0]->title ?? ""), array('class'=>'form-control','placeholder'=>'Title')) !!}
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
                        <button type="submit" class="btn btn-primary"> Update</button>
                    </div>
                </div>
            </div>
            <div class="col-12 col-md-4">
                <div class="card card-custom gutter-b">
                    <div class="card-body">
                        <div class="form-group">
                            <div class="checkbox-inline">
                                <label class="checkbox checkbox-lg">
                                    <input type="checkbox" name="is_active" value="1" {{ $report->is_active == 1 ? 'checked' : '' }}>
                                    <span></span>Publish ?</label>
                            </div>
                        </div>
                        <!-- <div class="form-group">
                            <label class="control-label">Category <span class="text-danger">*</span></label>
                            <select name="category_id" class="form-control">
                                <option value="">Select category</option>
                                @include('admin.download.recursive_options', ['parents' => $categories, 'selected_id' => $report->category_id])
                            </select>
                        </div> -->

                        @if(file_exists('storage/'.$report->file) && $report->file != '')
                        <div class="mb-2">
                            <div class="bg-gray-300 my-2 px-3 py-2"><small>Existing Download Preview</small></div>
                            @if(file_exists('storage/'.$report->file) && !empty($report->file))
                            <a href="{!! asset('storage/'.$report->file) !!}" target="_blank"><i class="la la-download"></i></a>
                            @elseif($report->type == 'Link')
                            <a href="{!! $report->file !!}"><i class="la la-link"></i></a>
                            @endif
                        </div>
                        @endif
                        <div class="form-group">
                            <label class="control-label">File <span class="text-danger">*</span></label>
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