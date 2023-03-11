@extends('layouts.backend.app')
@section('styles')
<link href="vendor/select2/dist/css/select2.min.css" rel="stylesheet" />
@endsection
@section('scripts')
<script src="vendor/select2/dist/js/select2.min.js"></script>
<script>
    $(document).ready(function() {
        $('.select-2').select2({});
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
            <h5 class="text-dark font-weight-bold mt-2 mb-2 mr-5">Layouts</h5>
            <!--end::Page Title-->
            <!--begin::Actions-->
            <div class="subheader-separator subheader-separator-ver mt-2 mb-2 mr-4 bg-gray-200"></div>
            <span class="text-dark-50 font-weight-bold" id="kt_subheader_total">{{ $layout->title }}</span>
            <!--end::Actions-->
        </div>
        <!--end::Info-->

        <!--begin::Toolbar-->
        <div class="d-flex align-items-center">
            <a href="{{ route('admin.layout.index') }}" class="btn btn-default pull-right"><i
                    class="icon-undo2 position-left"></i> Back</span></a>
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
        {!! Form::open(array('route' => ['admin.layout.update',
        $layout->id],'class'=>'form-horizontal','id'=>'validator', 'files' => 'true')) !!}
        <div class="row">
            <div class="col-3 col-md-2">
                <ul class="nav flex-column nav-pills">
                    <li class="nav-item">
                        <a class="nav-link active" href="#left-tab1" data-toggle="tab" aria-expanded="true">
                            <span class="nav-icon"><i class="la la-cog"></i></span>
                            <span class="nav-text">General</span>
                        </a>
                    </li>
                    @foreach($options as $option)
                    @if($option->type == 2 || $option->type == 3)
                    @if ($option->title != 'Top Block 1' && $option->title != 'Top Block 2' && $option->title != 'Top Block 3')
                    <li class="nav-item">
                        <a class="nav-link" href="#left-tab-{{ $option->id }}" data-toggle="tab" aria-expanded="false">
                            <span class="nav-icon"><i class="la la-id-card"></i></span>
                            <span class="nav-text">{{ $option->title }}</span>
                        </a>
                    </li>
                    @endif
                    @endif
                    @endforeach
                </ul>
            </div>
            <div class="col-9 col-md-10">
                <div class="card card-custom gutter-b">
                    <div class="card-body">
                        <div class="tab-content">
                            <div class="tab-pane has-padding active" id="left-tab1">
                                @foreach($options as $option)
                                @if($option->type == 1)
                                <div class="form-group">
                                    <label for="" class="control-label">{{ $option->title }}
                                        <br><small class="text-info">{{ $option->excerpt }}</small>
                                    </label>
                                    <select name="{{ $option->id }}" class="form-control">
                                        <option value="">Select menu</option>
                                        @foreach($menus as $menu)
                                        <option value="{{ $menu->id }}" {{ $menu->id == $option->menu_id ? 'selected' :
                                            '' }}>{!! $menu->title !!}</option>
                                        @endforeach
                                    </select>
                                </div>
                                @endif
                                @endforeach
                            </div>
                            @foreach($options as $option)
                            @if($option->type == 2)
                            <div class="tab-pane has-padding" id="left-tab-{{ $option->id }}">
                                @php
                                $languages = Helper::getLanguages();
                                $isMultiLanguage = SettingHelper::setting('multi_language');
                                @endphp
                                @if($isMultiLanguage)
                                <ul class="nav nav-tabs nav-tabs-line nav-tabs-line-2x">
                                    @php $count=0; @endphp
                                    @foreach($languages as $language)
                                    <li class="nav-item"><a class="nav-link {{ ($count == 0) ? 'active' : '' }}"
                                            data-toggle="tab" href="#aa-{{ $option->id}}-{{ $language['id'] }}">{{
                                            $language['name'] ?? '' }}</a></li>
                                    @php
                                    $count++;
                                    @endphp
                                    @endforeach
                                </ul>
                                @endif
                                <div class="tab-content mt-5">
                                    @php $count=0; @endphp
                                    @foreach($languages as $language)
                                    <div id="aa-{{ $option->id}}-{{ $language['id'] }}"
                                        class="tab-pane fade in {{ ($count == 0) ? 'active show' : '' }}">
                                        <div class="form-group">
                                            <label for="" class="control-label">Description</label>
                                            <textarea class="form-control editor"
                                                name="block[{{ $language['id'] }}][{{ $option->id }}][{{ $option->language_id != $language['id'] && isset($multiContent[$language['id']][$option->id]['id']) ? $multiContent[$language['id']][$option->id]['id'] : '' }}][value]"
                                                id="" cols="30"
                                                rows="5">{{ $option->language_id != $language['id'] && isset($multiContent[$language['id']][$option->id]) ? $multiContent[$language['id']][$option->id]['value'] : $option->value }}</textarea>
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
                                @if($option->slug == 'block-2' && $language)
                                <div class="form-group">
                                    <label class="control-label">Content </label>
                                    <select name="block[content_id][{{ $option->id }}]" class="form-control select-2">
                                        <option value="">Select content to display</option>
                                        @include('admin.content.recursive_options', ['parents' => $contents,
                                        'selected_id' => $option->content_id])
                                    </select>
                                    <div class="small">the content will be shown on home page.</div>
                                </div>
                                @endif
                            </div>
                            @elseif($option->type == 3)
                            <div class="tab-pane d-none has-padding" id="left-tab-{{ $option->id }}">
                                <div class="form-group">
                                    <label class="control-label">Content </label>
                                    <div>
                                        <select name="block3[content_id][{{ $option->id }}]"
                                            class="form-control select-2">
                                            <option value="">Select content to display</option>
                                            @include('admin.content.recursive_options', ['parents' => $contents,
                                            'selected_id' => $option->content_id])
                                        </select>
                                    </div>
                                </div>
                            </div>
                            @endif
                            @endforeach
                        </div>
                    </div>
                    <div class="card-footer">
                        <button type="submit" class="btn btn-primary">Submit</button>
                    </div>
                </div>
            </div>
            <div class="col-12 col-md-3">

            </div>
        </div>
        {!! method_field('PATCH') !!}
        {!! Form::close() !!}
    </div>
</div>
@endsection