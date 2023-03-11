@extends('layouts.backend.app')
@section('title', 'News - create')
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
                <h5 class="text-dark font-weight-bold mt-2 mb-2 mr-5">News</h5>
                <!--end::Page Title-->
                <!--begin::Actions-->
                <div class="subheader-separator subheader-separator-ver mt-2 mb-2 mr-4 bg-gray-200"></div>
                <span class="text-dark-50 font-weight-bold" id="kt_subheader_total">Create</span>
                <!--end::Actions-->
            </div>
            <!--end::Info-->

            <!--begin::Toolbar-->
            <div class="d-flex align-items-center">
                <a href="{{ route('admin.news.index') }}"
                    class="btn btn-default btn-outline-success font-weight-bolder">Back</a>
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
            {!! Form::open([
                'route' => 'admin.news.store',
                'class' => 'form-horizontal',
                'id' => 'validator',
                'files' => 'true',
            ]) !!}
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
                                        <li class="nav-item"><a class="nav-link {{ $count == 0 ? 'active' : '' }}"
                                                data-toggle="tab"
                                                href="#aa-{{ $language['id'] }}">{{ $language['name'] ?? '' }}</a></li>
                                        @php $count++; @endphp
                                    @endforeach
                                </ul>
                            @endif
                            <div class="tab-content mt-5">
                                @php $count=0; @endphp

                                @foreach ($languages as $language)
                                    <div id="aa-{{ $language['id'] }}"
                                        class="tab-pane fade in {{ $count == 0 ? 'active show' : '' }}">
                                        <div class="form-group">
                                            <label class="control-label">Title <span class="text-danger">*</span></label>
                                            {!! Form::text('title[' . $language['id'] . ']', null, ['class' => 'form-control']) !!}
                                        </div>

                                        <div class="form-group">
                                            <label class="control-label">Short Description</label>
                                            {!! Form::textarea('excerpt[' . $language['id'] . ']', null, [
                                                'class' => 'form-control',
                                                'rows' => 3,
                                                'maxlength' => 250,
                                            ]) !!}
                                        </div>

                                        <div class="form-group">
                                            <label class="control-label">Description <span
                                                    class="text-danger">*</span></label>
                                            {!! Form::textarea('description[' . $language['id'] . ']', null, ['class' => 'form-control editor']) !!}
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
                            <div class="form-group">
                                <label class="control-label">Publish Date <span class="text-danger">*</span></label>
                                {!! Form::date('published_date', null, ['class' => 'form-control']) !!}
                            </div>
                            <div class="form-group">
                                <label class="control-label">Category <span class="text-danger">*</span></label>
                                <select name="category_id" id="" class="form-control">
                                    <option value="">Select Category</option>
                                    @if (isset($categories))
                                        @include('admin.downloadCategory.recursive_options', [
                                            'parents' => $categories,
                                            'selected_id' => old('category_id'),
                                        ])
                                    @endif
                                </select>
                            </div>
                            <div class="form-group">
                                <label class="control-label">Documents</label>
                                <span class="text-muted float-right">Downloadable files</span>
                                <div class="custom-file">
                                    <input type="file" class="custom-file-input" name="document" id="document-file">
                                    <label class="custom-file-label selected" for="document-file"></label>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="control-label">Layout<span class="text-danger">*</span></label>

                                <select name="layout" class="form-control">
                                    @foreach (PageHelper::pageLayoutOptionList() as $key => $value)
                                        <option value="{{ $key }}"
                                            {{ $key == ConstantHelper::NEWS_DEFAULT_LAYOUT ? 'selected' : '' }}>
                                            {{ $value }}</option>
                                    @endforeach
                                </select>
                            </div>
                            @if (SettingHelper::setting('banner_image') == 1)
                                <div class="form-group">
                                    <label class="control-label">Banner</label>
                                    <span class="text-muted float-right small">Preferred size:
                                        {{ Helper::preferredSize('news', 'banner') }}</span>
                                    <div class="custom-file">
                                        <input type="file" class="custom-file-input" name="banner" id="banner-file">
                                        <label class="custom-file-label selected" for="banner-file"></label>
                                    </div>
                                </div>
                            @endif
                            <div class="form-group">
                                <label class="control-label">Image</label>
                                <span class="text-muted float-right small">Preferred size:
                                    {{ Helper::preferredSize('news', 'image') }}</span>
                                <div class="custom-file">
                                    <input type="file" class="custom-file-input" name="image" id="image-file">
                                    <label class="custom-file-label selected" for="image-file"></label>
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="checkbox-inline">
                                    <label class="checkbox checkbox-lg">
                                        <input type="checkbox" name="show_image" value="1" checked="checked">
                                        <span></span>Show Image on detail view.</label>
                                </div>
                            </div>
                            {{-- <div class="form-group">
                            <div class="checkbox-inline">
                                <label class="checkbox checkbox-lg">
                                    <input type="checkbox" name="show_in_notification" value="1" checked="checked">
                                    <span></span>Show in notification</label>
                            </div>
                        </div> --}}
                            {{-- <div class="form-group">
                                <label for="" class="control-label">Publish In</label>
                                {!! Form::checkbox('type[]', 2, true, ['id' => 'news', 'class' => 'type-check']) !!}
                                <label for="news">News</label>&nbsp;
                                {!! Form::checkbox('type[]', 3, '', ['id' => 'csr', 'class' => 'type-check']) !!}
                                <label for="csr">CSR</label>
                            </div> --}}

                            <div class="form-group d-none">
                                <label class="control-label">Show in Notification</label>
                                {!! Form::checkbox('show_in_notification', 1, false) !!}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            {!! Form::close() !!}
        </div>
    </div>
@endsection
