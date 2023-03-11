@extends('layouts.backend.app')
@section('title', 'Testimonial - ' . $testimonial->title)
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
                            Swal.fire('Error',
                                'Something went wrong while processing your request.',
                                'error');
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
            <h5 class="text-dark font-weight-bold mt-2 mb-2 mr-5">Testimonial</h5>
            <!--end::Page Title-->
            <!--begin::Actions-->
            <div class="subheader-separator subheader-separator-ver mt-2 mb-2 mr-4 bg-gray-200"></div>
            <span class="text-dark-50 font-weight-bold" id="kt_subheader_total">{!! $testimonial->name !!}</span>
            <!--end::Actions-->
        </div>
        <!--end::Info-->

        <!--begin::Toolbar-->
        <div class="d-flex align-items-center">
            <a href="{{ route('admin.testimonials.index') }}" class="btn btn-outline-success font-weight-bolder"><i
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
        {!! Form::open(['route' => ['admin.testimonials.update', $testimonial->id], 'class' => 'form-horizontal', 'id'
        =>
        'validator', 'files' => 'true']) !!}
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
                            <input type="hidden" name="post[{{ $language['id'] }}]" value="{{ $language['id'] == $preferredLanguage ? $testimonial->id : $langContent[$language['id']][0]->id ?? '' }}">
                            <div id="aa-{{ $language['id'] }}"
                                class="tab-pane fade in {{ $count == 0 ? 'active show' : '' }}">
                                <div class="form-group">
                                    <label class="control-label">Name <span class="text-danger">*</span></label>
                                    {!! Form::text('name['.$language['id'].']', ($language['id'] == $preferredLanguage) ? $testimonial->name : ($langContent[$language['id']][0]->name ?? ""), array('class'=>'form-control')) !!}
                                </div>
                                {{-- <div class="form-group d-none">
                                    <label class="control-label">Link Name <span class="text-danger">*</span></label>
                                    {!! Form::text('layout[' . $language['id'] . ']', $language['id'] ==
                                    $preferredLanguage ? $testimonial->layout : $langContent[$language['id']][0]->layout
                                    ??
                                    '', ['class' => 'form-control', 'placeholder' => 'link Name']) !!}
                                </div> --}}
                                <div class="form-group">
                                    <label class="control-label">Designation</label>
                                    {!! Form::text('designation[' . $language['id'] . ']', $language['id'] ==
                                    $preferredLanguage ? $testimonial->designation :
                                    $langContent[$language['id']][0]->designation ??
                                    '', ['class' => 'form-control']) !!}
                                </div>
                                <div class="form-group">
                                    <label class="control-label">Description <span class="text-danger">*</span></label>
                                    {!! Form::textarea('description[' . $language['id'] . ']', $language['id'] ==
                                    $preferredLanguage ? $testimonial->description :
                                    $langContent[$language['id']][0]->description ?? '', ['class' => 'form-control
                                    editor']) !!}
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
                                    <input type="checkbox" name="is_active" value="1" {{ $testimonial->is_active == 1 ?
                                    'checked' : '' }}><span></span>Publish ?
                                </label>
                            </div>
                        </div>
                        <div class="form-group d-none">
                            <div class="checkbox-inline">
                                <label class="checkbox checkbox-lg">
                                    <input type="checkbox" name="show_in_notification" value="1" {{
                                        $testimonial->show_in_notification == 1 ? 'checked' : '' }}><span></span>Show in
                                    Notification.
                                </label>
                            </div>
                        </div>
                        @if (SettingHelper::setting('visible_in') == 1)
                        @include('admin.inc.visible', ['visibleIn' => $testimonial->visible_in])
                        @endif
                        @if (SettingHelper::setting('banner_image') == 1)
                        @if (file_exists('storage/thumbs/' . $testimonial->banner) && $testimonial->banner != '')
                        <div class="banner-wrap mb-2 d-none">
                            <div class="bg-gray-300 mb-2 px-3 py-2"><small>Existing Banner Preview</small></div>
                            <div class="card card-custom overlay">
                                <div class="card-body p-0">
                                    <div class="overlay-wrapper">
                                        <img src="{{ asset('storage/' . $testimonial->banner) }}" alt=""
                                            class="w-100 rounded" />
                                    </div>
                                    <div class="overlay-layer">
                                        <a href="#" class="btn btn-icon btn-danger btn-shadow btn-delete"
                                            data-action="{{ route('admin.offers.destroy-image', $testimonial->id) }}"
                                            data-type="banner"><i class="la la-trash"></i></a>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @endif
                        {{-- <div class="form-group d-none">
                            <label class="control-label">Banner</label>
                            <span class="text-muted float-right small">Preferred size:
                                {{ Helper::preferredSize('offer', 'banner') }}</span>
                            <div class="custom-file">
                                <input type="file" class="custom-file-input" name="banner" id="banner-file">
                                <label class="custom-file-label selected" for="banner-file"></label>
                            </div>
                        </div> --}}
                        @endif
                        @if (file_exists('storage/thumbs/' . $testimonial->image) && $testimonial->image != '')
                        <div class="image-wrap mb-2">
                            <div class="bg-gray-300 my-2 px-3 py-2"><small>Existing Image Preview</small></div>
                            <div class="card card-custom overlay">
                                <div class="card-body p-0">
                                    <div class="overlay-wrapper">
                                        <img src="{{ asset('storage/' . $testimonial->image) }}" alt=""
                                            class="w-100 rounded" />
                                    </div>
                                    <div class="overlay-layer">
                                        <a href="#" class="btn btn-icon btn-danger btn-shadow btn-delete"
                                            data-action="{{ route('admin.offers.destroy-image', $testimonial->id) }}"
                                            data-type="image"><i class="la la-trash"></i></a>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @endif
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
                            {!! Form::text('url', $testimonial->url, ['class' => 'form-control', 'placeholder' => 'Page
                            link']) !!}
                        </div> --}}
                        {{-- <div class="form-group d-none">
                            <div class="checkbox-inline">
                                <label class="checkbox checkbox-lg">
                                    <input type="checkbox" name="link_target" value="1" {{ $testimonial->link_target ==
                                    1 ?
                                    'checked' : '' }}><span></span>Open link in new
                                    tab.
                                </label>
                            </div>
                        </div> --}}
                    </div>
                </div>
            </div>
        </div>
        {!! method_field('PATCH') !!}
        {!! Form::close() !!}
    </div>
</div>
@endsection