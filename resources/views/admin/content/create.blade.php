@extends('layouts.backend.app')
@section('title', 'Contents - create')
@section('styles')

@endsection
@section('scripts')
    <script>
        $('form').submit(function() {
            $(this).find("button[type='submit']").prop('disabled', true);
        });
        $('.btn-add-block').on('click', function() {
            var totalBlocks = $('.block-wrap .content-block').length;
            index = totalBlocks == 0 ? 0 : $('.block-wrap .content-block:last').data('index');
            if (totalBlocks >= 10) {
                swal({
                    title: "Max limit reached!",
                    type: 'warning',
                    text: "New block cannot be added.",
                    timer: 2000,
                    showConfirmButton: false
                });
                return;
            }
            $.ajax({
                type: "GET",
                url: "{{ route('admin.contents.block') }}",
                data: {
                    'index': index + 1,
                },
                dataType: 'html',
                success: function(response) {
                    $('.block-wrap').append(response);
                }
            });
            return;
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
                <h5 class="text-dark font-weight-bold mt-2 mb-2 mr-5">Contents</h5>
                <!--end::Page Title-->
                <!--begin::Actions-->
                <div class="subheader-separator subheader-separator-ver mt-2 mb-2 mr-4 bg-gray-200"></div>
                <span class="text-dark-50 font-weight-bold" id="kt_subheader_total">Create</span>
                <!--end::Actions-->
            </div>
            <!--end::Info-->

            <!--begin::Toolbar-->
            <div class="d-flex align-items-center">
                <a href="{{ route('admin.contents.index') }}" class="btn btn-outline-success font-weight-bolder">Back</a>
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
                'route' => 'admin.contents.store',
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
                                                href="#aa-{{ $language['id'] }}">{{ $language['name'] ?? '' }}</a>
                                        </li>
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
                                            <label class="control-label">Title <span class="text-danger">*</span></label>
                                            <input type="text" name="multiData[{{ $language['id'] }}][title]"
                                                class="form-control">
                                        </div>

                                        <div class="form-group">
                                            <label class="control-label">Short Description</label>
                                            <p class="small">(For iframe enter link)</p>
                                            <textarea name="multiData[{{ $language['id'] }}][excerpt]" id="" cols="30" rows="3" maxlength="255"
                                                class="form-control"></textarea>
                                        </div>

                                        <div class="form-group">
                                            <label class="control-label">Description</label>
                                            <textarea name="multiData[{{ $language['id'] }}][description]" id="" cols="30" rows="10"
                                                class="form-control editor"></textarea>
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
                            <div class="block-wrap my-2 clearfix"></div>
                            <div class="text-right">
                                @if (1 != 1)
                                    <a href="javascript:void(0);" class="btn btn-light btn-shadow btn-add-block"><b><i
                                                class="la la-plus"></i></b> Add Block</a>
                                @endif
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
                            <div class="form-group d-none">
                                <label class="control-label">Publish At</label>
                                <input type="date" name="publish_at" class="form-control"
                                    value="{{ old('publish_at', date('Y-m-d')) }}">
                            </div>
                            <div class="form-group">
                                <div class="checkbox-inline">
                                    <label class="checkbox checkbox-lg">
                                        <input type="checkbox" name="is_active" value="1" checked="checked">
                                        <span></span>Publish ?</label>
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="checkbox-inline">
                                    <label class="checkbox checkbox-lg">
                                        <input type="checkbox" name="show_children" value="1">
                                        <span></span>List Child Menu</label>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="control-label">Meta Keys <span class="text-danger"></span></label>
                                <input type="text" name="meta_keys" class="form-control">
                            </div>

                            <div class="form-group">
                                <label class="control-label">Meta Description <span class="text-danger"></span></label>
                                <input type="text" name="meta_desc" class="form-control">
                            </div>
                            <div class="my-2 p-3 bg-gray-400">
                                <small class="text-dark text-hint">Displays children pages as paginated list on detail
                                    view.</small>
                            </div>
                            <div class="form-group">
                                <label class="control-label">Parent<span class="text-danger">*</span></label>
                                <select name="parent_id" class="form-control">
                                    <option value="">Parent Itself</option>
                                    @include('admin.content.recursive_options', [
                                        'parents' => $parents,
                                        'selected_id' => '',
                                    ])
                                </select>
                            </div>
                            <div class="form-group">
                                <label class="control-label">Layout<span class="text-danger">*</span></label>
                                <select name="layout" class="form-control">
                                    @foreach (PageHelper::pageLayoutOptionList() as $key => $value)
                                        <option value="{{ $key }}" {{ $key == 3 ? 'selected' : '' }}>
                                            {{ $value }}</option>
                                    @endforeach
                                </select>
                            </div>
                            @if (SettingHelper::setting('banner_image') == 1)
                                <div class="form-group">
                                    <label class="control-label">Banner</label>
                                    <small class="text-dark-50 float-right">Preferred size:
                                        {{ Helper::preferredSize('content', 'banner') }}</small>
                                    <div class="custom-file">
                                        <input type="file" class="custom-file-input" name="banner" id="banner-file">
                                        <label class="custom-file-label selected" for="banner-file"></label>
                                    </div>
                                </div>
                            @endif
                            <div class="form-group">
                                <label class="control-label">Image</label>
                                <small class="text-dark-50 float-right">Preferred size:
                                    {{ Helper::preferredSize('content', 'image') }}</small>
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
                            <div class="form-group">
                                <div class="checkbox-inline">
                                    <label class="checkbox checkbox-lg">
                                        <input type="checkbox" name="is_show_member_link" value="1"
                                            checked="checked">
                                        <span></span>Show member link detail view.</label>
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
