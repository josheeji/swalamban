@extends('layouts.backend.app')
@section('title', 'Testimonials - Create')
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
            <h5 class="text-dark font-weight-bold mt-2 mb-2 mr-5">Testimonials</h5>
            <!--end::Page Title-->
            <!--begin::Actions-->
            <div class="subheader-separator subheader-separator-ver mt-2 mb-2 mr-4 bg-gray-200"></div>
            <span class="text-dark-50 font-weight-bold" id="kt_subheader_total">Create</span>
            <!--end::Actions-->
        </div>
        <!--end::Info-->

        <!--begin::Toolbar-->
        <div class="d-flex align-items-center">
            <a href="{{ route('admin.testimonials.index') }}" class="btn btn-outline-success font-weight-bolder">Back</a>
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
        {!! Form::open(['route' => 'admin.testimonials.store', 'class' => 'form-horizontal', 'id' => 'validator', 'files' =>
        'true']) !!}
        <div class="row">
            <div class="col-12 col-md-8">
                <div class="card card-custom gutter-b">
                    <div class="card-body">
                        @php
                        $languages = Helper::getLanguages();
                        $isMultiLanguage = SettingHelper::setting('multi_language');
                        @endphp
                        @if ($isMultiLanguage)
                        <ul class="nav nav-tabs nav-tabs-line nav-tabs-line-2x">
                            @php $count=0; @endphp
                            @foreach ($languages as $language)
                            <li class="nav-item"><a class="nav-link {{ $count == 0 ? 'active' : '' }}" data-toggle="tab"
                                    href="#aa-{{ $language['id'] }}">{{ $language['name'] ?? '' }}</a></li>
                            @php
                            $count++;
                            @endphp
                            @endforeach
                        </ul>
                        @endif
                        <div class="tab-content mt-5">
                            @php $count=0; @endphp
                            @foreach ($languages as $language)
                            <div id="aa-{{ $language['id'] }}"
                                class="tab-pane fade in {{ $count == 0 ? 'active show' : '' }}">
                                <div class="form-group">
                                    <label class="control-label">Name <span class="text-danger">*</span></label>
                                    {!! Form::text('name[' . $language['id'] . ']', null, ['class' => 'form-control',
                                    'placeholder' => 'Enter Name']) !!}
                                </div>
                                <div class="form-group">
                                    <label class="control-label">Designation <span class="text-danger">*</span></label>
                                    {!! Form::text('designation[' . $language['id'] . ']', null, ['class' => 'form-control'])
                                    !!}
                                </div>
                                {{-- <div class="form-group d-none">
                                    <label class="control-label">Excerpt</label>
                                    {!! Form::textarea('excerpt[' . $language['id'] . ']', null, ['class' =>
                                    'form-control', 'id' => '', 'maxlength' => 255, 'rows' => 3]) !!}
                                </div> --}}
                                <div class="form-group">
                                    <label class="control-label">Description</label>
                                    {!! Form::textarea('description[' . $language['id'] . ']', null, ['class' =>
                                    'form-control editor']) !!}
                                </div>
                            </div>
                            @php
                            $count++;
                            if (!$isMultiLanguage) {
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
                            <div class="checkbox-inline">
                                <label class="checkbox checkbox-lg">
                                    <input type="checkbox" name="show_in_notification" value="1">
                                    <span></span>Show in Notification.</label>
                            </div>
                        </div> --}}
                        @if (SettingHelper::setting('visible_in') == 1)
                        @include('admin.inc.visible', ['visibleIn' => ''])
                        @endif
                        {{-- @if (SettingHelper::setting('banner_image') == 1)
                        <div class="form-group d-none">
                            <label class="control-label">Banner</label>
                            <span class="text-muted float-right small">Preferred size:
                                {{ Helper::preferredSize('offer', 'banner') }}</span>
                            <div class="custom-file">
                                <input type="file" class="custom-file-input" name="banner" id="banner-file">
                                <label class="custom-file-label selected" for="banner-file"></label>
                            </div>
                        </div>
                        @endif --}}
                        <div class="form-group">
                            <label class="control-label">Image</label>
                            <span class="text-muted float-right small">Preferred size:
                                {{ Helper::preferredSize('offer', 'image') }}</span>
                            <div class="custom-file">
                                <input type="file" class="custom-file-input" name="image" id="image-file">
                                <label class="custom-file-label selected" for="image-file"></label>
                            </div>
                        </div>
                        {{-- <div class="form-group d-none">
                            <label class="control-label">Link/Url</label>
                            {!! Form::text('url', null, ['class' => 'form-control', 'placeholder' => 'Page link']) !!}
                        </div> --}}
                        {{-- <div class="form-group d-none">
                            <div class="checkbox-inline">
                                <label class="checkbox checkbox-lg">
                                    <input type="checkbox" name="link_target" value="1">
                                    <span></span>Open link in new tab.
                                </label>
                            </div>
                        </div> --}}
                    </div>
                </div>
            </div>
        </div>
        {!! Form::close() !!}
    </div>
</div>
@endsection