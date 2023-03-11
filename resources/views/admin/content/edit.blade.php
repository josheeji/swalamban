@extends('layouts.backend.app')
@section('title', 'Contents - ' . $content->title)
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

        $('.btn-remove-block').on('click', function() {
            var index = $(this).data('index');
            var id = $(this).data('id');
            Swal.fire({
                title: 'Are you sure?',
                text: 'Do you want to remove the content block.',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Yes, remove it!',
                cancelButtonText: 'No, keep it!'
            }).then((result) => {
                if (result.value) {
                    if (id != '') {
                        $.ajax({
                            type: "POST",
                            url: "{{ route('admin.contents.remove-block') }}",
                            data: {
                                'id': id,
                            },
                            dataType: 'html',
                            success: function(response) {
                                if (response != 'success') {
                                    swal("Oops!", 'Cannot remove the content block.', "error");
                                    return;
                                }
                            },
                            error: function(e) {}
                        });
                    }
                    $(this).closest('.content-block').remove();
                }
            });
        });

        $('.btn-remove-block-image').on('click', function() {
            var id = $(this).data('id');
            var index = $(this).data('index');
            Swal.fire({
                title: 'Are you sure?',
                text: 'Do you want to remove the content block.',
                type: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Yes, remove it!',
                cancelButtonText: 'No, keep it!'
            }).then((result) => {
                if (result.value) {
                    if (id != '') {
                        $.ajax({
                            type: "get",
                            url: "{{ route('admin.contents.remove-block-image') }}",
                            data: {
                                'id': id,
                            },
                            dataType: 'html',
                            success: function(response) {
                                if (response != 'success') {
                                    swal("Oops!", 'Cannot remove the content block.', "error");
                                    return;
                                } else {

                                    $('.img-wrap-' + index).remove();
                                }
                            },
                            error: function(e) {}
                        });
                    }
                }
            });
        });

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
                            Swal.fire('Error',
                                'Something went wrong while processing your request.',
                                'error');
                        }
                    });
                }
            })
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
                <span class="text-dark-50 font-weight-bold" id="kt_subheader_total">{!! $content->title !!}</span>
                <!--end::Actions-->
            </div>
            <!--end::Info-->

            <!--begin::Toolbar-->
            <div class="d-flex align-items-center">
                <a href="{{ route('admin.contents.index') }}" class="btn btn-outline-success font-weight-bolder"><i
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
        <div class=" container-fluid">
            {!! Form::open(['route' => ['admin.contents.update', $content->id], 'class' => 'form-horizontal', 'id' => 'validator', 'files' => 'true']) !!}
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
                                        <li
                                            class="nav-item {{ $count != 0 && !isset($langContent[$language['id']]) ? 'emptyContent' : '' }}">
                                            <a class="nav-link {{ $count == 0 ? 'active' : '' }}" data-toggle="tab"
                                                href="#aa-{{ $language['id'] }}">{{ $language['name'] ?? '' }}</a>
                                        </li>
                                        @php $count++; @endphp
                                    @endforeach
                                </ul>
                            @endif
                            <div class="tab-content mt-5">
                                @php $count=0; @endphp
                                @foreach ($languages as $language)
                                    <input type="hidden" name="post[{{ $language['id'] }}]"
                                        value="{{ $language['id'] == $preferredLanguage ? $content->id : $langContent[$language['id']][0]->id ?? '' }}">
                                    <div id="aa-{{ $language['id'] }}"
                                        class="tab-pane fade in {{ $count == 0 ? 'active show' : '' }}">
                                        <div class="form-group">
                                            <label class="control-label">Title <span class="text-danger">*</span></label>
                                            <input type="text" name="multiData[{{ $language['id'] }}][title]"
                                                value="{{ $language['id'] == $preferredLanguage ? $content->title : $langContent[$language['id']][0]->title ?? '' }}"
                                                class="form-control">
                                        </div>
                                        <div class="form-group d-none">
                                            <label class="control-label">Meta Keys <span class="text-danger"></span></label>
                                            <input type="text" name="multiData[{{ $language['id'] }}][meta_keys]"
                                                value="{{ $language['id'] == $preferredLanguage ? $content->meta_keys : $langContent[$language['id']][0]->meta_keys ?? '' }}"
                                                class="form-control">
                                        </div>
                                        <div class="form-group d-none">
                                            <label class="control-label">Meta Description <span
                                                    class="text-danger"></span></label>
                                            <input type="text" name="multiData[{{ $language['id'] }}][meta_desc]"
                                                value="{{ $language['id'] == $preferredLanguage ? $content->meta_desc : $langContent[$language['id']][0]->meta_desc ?? '' }}"
                                                class="form-control">
                                        </div>
                                        <div class="form-group">
                                            <label class="control-label">Short Description</label>
                                            <p class="small">(For iframe enter link)</p>
                                            <textarea name="multiData[{{ $language['id'] }}][excerpt]" id="" cols="30" rows="3"
                                                maxlength="255" class="form-control">{!! $language['id'] == $preferredLanguage ? $content->excerpt : $langContent[$language['id']][0]->excerpt ?? '' !!}</textarea>
                                        </div>
                                        <div class="form-group">
                                            <label class="control-label">Description</label>
                                            <textarea name="multiData[{{ $language['id'] }}][description]" id="" cols="30" rows="10"
                                                class="form-control editor">{!! $language['id'] == $preferredLanguage ? $content->description : $langContent[$language['id']][0]->description ?? '' !!}</textarea>
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
                            <div class="block-wrap my-2 clearfix">
                                @foreach ($blocks as $block)
                                    @include('admin.content.edit-block', [
                                        'index' => $block->id,
                                        'block' => $block,
                                    ])
                                @endforeach
                            </div>
                            <div class="text-right">
                                @if (1 != 1)
                                    <a href="javascript:void(0);" class="btn btn-light btn-shadow btn-add-block"><b><i
                                                class="la la-plus"></i></b> Add Block</a>
                                @endif
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
                            <div class="form-group  d-none">
                                <label class="control-label">Publish At</label>
                                <input type="date" name="publish_at" class="form-control"
                                    value="{{ \Carbon\Carbon::parse($content->publish_at)->format('Y-m-d') }}">
                            </div>
                            <div class="form-group">
                                <div class="checkbox-inline">
                                    <label class="checkbox checkbox-lg">
                                        <input type="checkbox" name="is_active" value="1"
                                            {{ $content->is_active == 1 ? 'checked' : '' }}>
                                        <span></span>Publish ?</label>
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="checkbox-inline">
                                    <label class="checkbox checkbox-lg">
                                        <input type="checkbox" name="show_children" value="1"
                                            {{ $content->show_children == 1 ? 'checked' : '' }}>
                                        <span></span>List Child Menu</label>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="control-label">Meta Keys <span class="text-danger"></span></label>
                                <input type="text" name="meta_keys" value="{{$content->meta_keys}}" class="form-control">
                            </div>
                            
                            <div class="form-group">
                                <label class="control-label">Meta Description <span class="text-danger"></span></label>
                                <input type="text" name="meta_desc" value="{{$content->meta_desc}}" class="form-control">
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
                                        'selected_id' => $content->parent_id,
                                    ])
                                </select>
                            </div>
                            <div class="form-group">
                                <label class="control-label">Layout<span class="text-danger">*</span></label>
                                <select name="layout" class="form-control">
                                    @foreach (PageHelper::pageLayoutOptionList() as $key => $value)
                                        <option value="{{ $key }}"
                                            {{ $content->layout == $key ? 'selected' : '' }}>{{ $value }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            @if (SettingHelper::setting('banner_image') == 1)
                                @if (file_exists('storage/thumbs/' . $content->banner) && $content->banner != '')
                                    <div class="banner-wrap mb-2">
                                        <div class="bg-gray-300 mb-2 px-3 py-2"><small>Existing Banner Preview</small>
                                        </div>
                                        <div class="card card-custom overlay">
                                            <div class="card-body p-0">
                                                <div class="overlay-wrapper">
                                                    <img src="{{ asset('storage/' . $content->banner) }}"
                                                        alt="" class="w-100 rounded" />
                                                </div>
                                                <div class="overlay-layer">
                                                    <a href="#"
                                                        class="btn btn-icon btn-danger btn-shadow btn-delete"
                                                        data-action="{{ route('admin.contents.destroy-image', $content->id) }}"
                                                        data-type="banner"><i class="la la-trash"></i></a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endif
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
                            @if (file_exists('storage/thumbs/' . $content->image) && $content->image != '')
                                <div class="image-wrap mb-2">
                                    <div class="bg-gray-300 my-2 px-3 py-2"><small>Existing Image Preview</small></div>
                                    <div class="card card-custom overlay">
                                        <div class="card-body p-0">
                                            <div class="overlay-wrapper">
                                                <img src="{{ asset('storage/' . $content->image) }}" alt=""
                                                    class="w-100 rounded" />
                                            </div>
                                            <div class="overlay-layer">
                                                <a href="#" class="btn btn-icon btn-danger btn-shadow btn-delete"
                                                    data-action="{{ route('admin.contents.destroy-image', $content->id) }}"
                                                    data-type="image"><i class="la la-trash"></i></a>
                                            </div>
                                        </div>
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
                                        <input type="checkbox" name="show_image" value="1"
                                            {{ $content->show_image == 1 ? 'checked' : '' }}>
                                        <span></span>Show Image on detail view.</label>
                                </div>

                            </div>
                            <div class="form-group">
                                <div class="checkbox-inline">
                                    <label class="checkbox checkbox-lg">
                                        <input type="checkbox" name="is_show_member_link" value="1"
                                            {{ $content->is_show_member_link == 1 ? 'checked' : '' }}>
                                        <span></span>Show member link detail view.</label>
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
