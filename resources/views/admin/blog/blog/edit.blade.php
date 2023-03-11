@extends('layouts.backend.app')
@section('title', 'Stories - ' . $blog->title)
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
            url: "{{ route('admin.blogs.block') }}",
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
                        url: "{{ route('admin.blogs.remove-block') }}",
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
                        url: "{{ route('admin.blogs.remove-block-image') }}",
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
                        Swal.fire('Error', 'Something went wrong while processing your request.', 'error');
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
            <h5 class="text-dark font-weight-bold mt-2 mb-2 mr-5">Stories</h5>
            <!--end::Page Title-->
            <!--begin::Actions-->
            <div class="subheader-separator subheader-separator-ver mt-2 mb-2 mr-4 bg-gray-200"></div>
            <span class="text-dark-50 font-weight-bold" id="kt_subheader_total">Edit</span>
            <!--end::Actions-->
        </div>
        <!--end::Info-->

        <!--begin::Toolbar-->
        <div class="d-flex align-items-center">
            <a href="{{ route('admin.blogs.index') }}" class="btn btn-default btn-outline-success font-weight-bolder">Back</span></a>
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
        {!! Form::open(array('route' => ['admin.blogs.update', $blog->id],'class'=>'form-horizontal','id'=>'validator', 'files' => 'true','novalidate')) !!}
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
                            <input type="hidden" name="post[{{ $language['id'] }}]" value="{{ ($language['id'] == $preferredLanguage) ? $blog->id : ($langContent[$language['id']][0]->id ?? "") }}">
                            <div id="aa-{{ $language['id'] }}" class="tab-pane fade in {{ ($count == 0) ? 'active show' : '' }}">
                                <div class="form-group">
                                    <label class="control-label">Title <span class="text-danger">*</span></label>
                                    {!! Form::text('title['.$language['id'].']', ($language['id'] == $preferredLanguage) ? $blog->title : ($langContent[$language['id']][0]->title ?? ""), array('class'=>'form-control')) !!}
                                </div>

                                <div class="form-group">
                                    <label class="control-label">Short Description</label>
                                    {!! Form::textarea('excerpt['.$language['id'].']', ($language['id'] == $preferredLanguage) ? $blog->excerpt : ($langContent[$language['id']][0]->excerpt ?? ""), array('class'=>'form-control', 'rows' => 3, 'maxlength' => 255 )) !!}
                                </div>
                                <div class="form-group">
                                    <label class="control-label">Description</label>
                                    {!! Form::textarea('description['.$language['id'].']', ($language['id'] == $preferredLanguage) ? $blog->description : ($langContent[$language['id']][0]->description ?? ""), array('class'=>'form-control editor')) !!}
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

                        <div class="block-wrap my-2 clearfix">
                            @foreach($blocks as $block)
                            @include('admin.content.edit-block', ['index' => $block->id, 'block' => $block])
                            @endforeach
                        </div>
{{--                        <div class="text-right">--}}
{{--                            <a href="javascript:void(0);" class="btn btn-light btn-shadow btn-add-block"><b><i class="la la-plus"></i></b> Add Block</a>--}}
{{--                        </div>--}}
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
                                    <input type="checkbox" name="is_active" value="1" {{ $blog->is_active == 1 ? 'checked' : '' }}>
                                    <span></span>Publish ?</label>
                            </div>
                        </div>
                        <div class="form-group d-none">
                            <label class="control-label">Category <span class="text-danger">*</span></label>
                            <select name="category_id" class="form-control">
                                <option value="">Select category</option>
                                @if(isset($categories) && !empty($categories))
                                @foreach($categories as $category)
                                <option value="{{ $category->id }}" {{ $category->id == $blog->category_id ? 'selected' : '' }}>{{ $category->title }}</option>
                                @endforeach
                                @endif
                            </select>
                        </div>
                        <div class="form-group">
                            <label class="control-label">Publish Date<span class="text-danger">*</span></label>
                            {!! Form::date('published_date', $blog->published_date, array('class'=>'form-control')) !!}
                        </div>
                        <div class="form-group">
                            <label class="control-label">Documents</label>
                            <span class="text-muted float-right">Downloadable files</span>
                            <div class="custom-file">
                                <input type="file" class="custom-file-input" name="document" id="document-file">
                                <label class="custom-file-label selected" for="document-file"></label>
                            </div>
                            @if(file_exists('storage/' . $blog->document) && $blog->document != '')
                            <div class="document-wrap">
                                <div class="bg-gray-300 my-2 px-3 py-2"><small>Existing Document Preview</small></div>
                                <a href="{{ asset('storage/'.$blog->document) }}" class="btn btn-icon btn-light" target="_blank"><i class="la la-eye"></i></a>
                                <a href="#" class="btn btn-icon btn-danger btn-shadow btn-delete" data-action="{{ route('admin.stories.destroy-image', $blog->id) }}" data-type="document"><i class="la la-trash"></i></a>
                            </div>
                            @endif
                        </div>
                        @if (file_exists('storage/thumbs/' . $blog->banner) && $blog->banner != '')
                        <div class="banner-wrap">
                            <div class="bg-gray-300 mb-2 px-3 py-2"><small>Existing Banner Preview</small></div>
                            <div class="card card-custom overlay">
                                <div class="card-body p-0">
                                    <div class="overlay-wrapper">
                                        <img src="{{ asset('storage/' . $blog->banner) }}" alt=""
                                            class="w-100 rounded" />
                                    </div>
                                    <div class="overlay-layer">
                                        <a href="#" class="btn btn-icon btn-danger btn-shadow btn-delete"
                                            data-action="{{ route('admin.news.destroy-image', $blog->id) }}"
                                            data-type="banner"><i class="la la-trash"></i></a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endif
                        <div class="form-group">
                            <label class="control-label">Banner</label>
                            <span class="text-muted float-right small">Preferred size:
                                {{ Helper::preferredSize('blogs', 'banner') }}</span>
                            <div class="custom-file">
                                <input type="file" class="custom-file-input" name="banner" id="banner-file">
                                <label class="custom-file-label selected" for="banner-file"></label>
                            </div>
                        </div>

                        @if(file_exists('storage/thumbs/' . $blog->image) && $blog->image != '')
                        <div class="image-wrap mb-2">
                            <div class="bg-gray-300 my-2 px-3 py-2"><small>Existing Image Preview</small></div>
                            <div class="card card-custom overlay">
                                <div class="card-body p-0">
                                    <div class="overlay-wrapper">
                                        <img src="{{ asset('storage/' . $blog->image) }}" alt="" class="w-100 rounded" />
                                    </div>
                                    <div class="overlay-layer">
                                        <a href="#" class="btn btn-icon btn-danger btn-shadow btn-delete" data-action="{{ route('admin.stories.destroy-image', $blog->id) }}" data-type="image"><i class="la la-trash"></i></a>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @endif
                        <div class="form-group">
                            <label class="control-label">Image</label>
                            <small class="text-dark-50 float-right">Preferred size: {{ Helper::preferredSize('blog', 'image') }}</small>
                            <div class="custom-file">
                                <input type="file" class="custom-file-input" name="image" id="image-file">
                                <label class="custom-file-label selected" for="image-file"></label>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="checkbox-inline">
                                <label class="checkbox checkbox-lg">
                                    <input type="checkbox" name="show_image" value="1" {{ $blog->show_image == 1 ? 'checked' : '' }}>
                                    <span></span>Show Image On Detail View.</label>
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
