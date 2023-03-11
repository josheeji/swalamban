@extends('layouts.backend.app')
@section('title', 'AGM Reports - create')
@section('scripts')
<script>
    $(function() {
        $('#file-container').on('click', function(e) {
            window.open(this.href, 'Filemanager', 'width=900,height=600');
            return false;
        });

        function SetUrl(url) {
            var selector = getCookie('selected');
            if (selector == '2') {
                $('#thumbnail2').val(url);
                $('#feature-img-container2').find('img').attr('src', url).attr('height', '100px');
            } else {
                $('#thumbnail').val(url);
                $('#feature-img-container').find('img').attr('src', url).attr('height', '100px');
            }
        }
    });
    onload = function() {
        var itemText = $('#select  option:selected').val();
        select_drop(itemText);
        debugger
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
            <span class="text-dark-50 font-weight-bold" id="kt_subheader_total">Create</span>
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
    <div class="container-fluid">
        {!! Form::open(array('route' => 'admin.agm-report.store','class'=>'form-horizontal','id'=>'validator', 'files' => 'true')) !!}
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
                            @php
                            $count++;
                            @endphp
                            @endforeach
                        </ul>
                        @endif
                        <div class="tab-content mt-5">
                            @php $count=0; @endphp
                            @foreach($languages as $language)
                            <div id="aa-{{ $language['id'] }}" class="tab-pane fade in {{ ($count == 0) ? 'active show' : '' }}">
                                <div class="form-group">
                                    <label class="control-label">Title <span class="text-danger">*</span></label>
                                    {!! Form::text('title['.$language['id'].']', null, array('class'=>'form-control','placeholder'=>'Download title')) !!}
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
                                    <input type="checkbox" name="is_active" value="1" checked="checked">
                                    <span></span>Publish ?</label>
                            </div>
                        </div>
                        <!-- <div class="form-group">
                            <label class="control-label">Category <span class="text-danger">*</span></label>
                            <select name="category_id" class="form-control">
                                <option value="">Select category</option>
                                @include('admin.download.recursive_options', ['parents' => $categories, 'selected_id' => ""])
                            </select>
                        </div> -->
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
        {!! Form::close() !!}
    </div>
</div>

@endsection