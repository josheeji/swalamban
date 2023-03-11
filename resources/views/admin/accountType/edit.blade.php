@extends('layouts.backend.app')
@section('title', 'Product - '. $account_type->title)
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
                        Swal.fire('Error', 'Something went wrong while processing your request.', 'error');
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
            <h5 class="text-dark font-weight-bold mt-2 mb-2 mr-5">Product</h5>
            <!--end::Page Title-->
            <!--begin::Actions-->
            <div class="subheader-separator subheader-separator-ver mt-2 mb-2 mr-4 bg-gray-200"></div>
            <span class="text-dark-50 font-weight-bold" id="kt_subheader_total">{!! $account_type->title !!}</span>
            <!--end::Actions-->
        </div>
        <!--end::Info-->

        <!--begin::Toolbar-->
        <div class="d-flex align-items-center">
            <a href="{{ route('admin.account-type.index') }}" class="btn btn-outline-success font-weight-bolder"><i class="icon-undo2 position-left"></i> Back</span></a>
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
        {!! Form::open(array('route' => ['admin.account-type.update', $account_type->id],'class'=>'form-horizontal','id'=>'validator', 'files' => 'true')) !!}
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
                            <input type="hidden" name="post[{{ $language['id'] }}]" value="{{ ($language['id'] == $preferredLanguage) ? $account_type->id : ($langContent[$language['id']][0]->id ?? "") }}">
                            <div id="aa-{{ $language['id'] }}" class="tab-pane fade in {{ ($count == 0) ? 'active show' : '' }}">
                                <div class="form-group">
                                    <label class="control-label">Title <span class="text-danger">*</span></label>
                                    {!! Form::text('title['.$language['id'].']', ($language['id'] == $preferredLanguage) ? $account_type->title : ($langContent[$language['id']][0]->title ?? ""), array('class'=>'form-control')) !!}
                                </div>
                                {{-- <div class="form-group new">--}}
                                {{-- <label class="control-label">Minimum Balance</label>--}}
                                {{-- {!! Form::text('minimum_balance['.$language['id'].']', ($language['id'] == $preferredLanguage) ? $account_type->minimum_balance : ($langContent[$language['id']][0]->minimum_balance ?? ""), array('class'=>'form-control')) !!}--}}
                                {{-- </div>--}}
                                {{-- <div class="form-group new">--}}
                                {{-- <label class="control-label">Interest Rate in Percent</label>--}}
                                {{-- {!! Form::text('interest_rate['.$language['id'].']', ($language['id'] == $preferredLanguage) ? $account_type->interest_rate : ($langContent[$language['id']][0]->interest_rate ?? ""), array('class'=>'form-control')) !!}--}}
                                {{-- </div>--}}
                                {{-- <div class="form-group new">--}}
                                {{-- <label class="control-label">Interest Payment</label>--}}
                                {{-- {!! Form::text('interest_payment['.$language['id'].']', ($language['id'] == $preferredLanguage) ? $account_type->interest_payment : ($langContent[$language['id']][0]->interest_payment ?? ""), array('class'=>'form-control')) !!}--}}
                                {{-- </div>--}}
                                <div class="form-group">
                                    <label class="control-label">Short Description</label>
                                    {!! Form::textarea('excerpt['.$language['id'].']', ($language['id'] == $preferredLanguage) ? $account_type->excerpt : ($langContent[$language['id']][0]->excerpt ?? ""), array('class'=>'form-control editor', 'id'=>'', 'maxlength' => 255, 'rows' => 3)) !!}
                                </div>
                                {{-- <div class="form-group">--}}
                                {{-- <label class="control-label">Feature</label>--}}
                                {{-- {!! Form::textarea('feature['.$language['id'].']', ($language['id'] == $preferredLanguage) ? $account_type->feature : ($langContent[$language['id']][0]->feature ?? ""), array('class'=>'form-control editor', 'id'=>'editor')) !!}--}}
                                {{-- </div>--}}
                                <div class="form-group">
                                    <label class="control-label">Description <span class="text-danger">*</span></label>
                                    {!! Form::textarea('description['.$language['id'].']', ($language['id'] == $preferredLanguage) ? $account_type->description : ($langContent[$language['id']][0]->description ?? ""), array('class'=>'form-control editor', 'id'=>'editor')) !!}
                                </div>
                                {{-- <div class="form-group">--}}
                                {{-- <label class="control-label">Terms and Conditions</label>--}}
                                {{-- {!! Form::textarea('terms_and_conditions['.$language['id'].']', ($language['id'] == $preferredLanguage) ? $account_type->terms_and_conditions : ($langContent[$language['id']][0]->terms_and_conditions ?? ""), array('class'=>'form-control editor', 'id'=>'editor')) !!}--}}
                                {{-- </div>--}}
                                {{-- <div class="form-group">--}}
                                {{-- <label class="control-label">faq</label>--}}
                                {{-- {!! Form::textarea('faq['.$language['id'].']', ($language['id'] == $preferredLanguage) ? $account_type->faq : ($langContent[$language['id']][0]->faq ?? ""), array('class'=>'form-control editor', 'id'=>'editor')) !!}--}}
                                {{-- </div>--}}
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
                                    <input type="checkbox" name="is_active" value="1" {{ $account_type->is_active == 1 ? 'checked' : '' }}><span></span>Publish ?
                                </label>
                            </div>
                        </div>
                        <div class="form-group d-none">
                            <div class="checkbox-inline">
                                <label class="checkbox checkbox-lg">
                                    <input type="checkbox" name="is_featured" value="1" {{ $account_type->is_featured == 1 ? 'checked' : '' }}><span></span>Is featured?</label>
                            </div>
                        </div>
                        @if(SettingHelper::setting('visible_in') == 1)
                        @include('admin.inc.visible', ['visibleIn' => $account_type->visible_in])
                        @endif

                        <div class="form-group">
                            <label class="control-label">Layout<span class="text-danger">*</span></label>
                            <select name="layout" class="form-control">
                                @foreach(PageHelper::pageLayoutOptionList() as $key => $value)
                                <option value="{{ $key }}" {{ $account_type->layout == $key ? 'selected' : '' }}>{{ $value }}</option>
                                @endforeach
                            </select>
                        </div>

                        @if(SettingHelper::setting('banner_image') == 1)
                        @if(file_exists('storage/thumbs/' . $account_type->banner) && $account_type->banner != '')
                        <div class="banner-wrap mb-2">
                            <div class="bg-gray-300 mb-2 px-3 py-2"><small>Existing Banner Preview</small></div>
                            <div class="card card-custom overlay">
                                <div class="card-body p-0">
                                    <div class="overlay-wrapper">
                                        <img src="{{ asset('storage/' . $account_type->banner) }}" alt="" class="w-100 rounded" />
                                    </div>
                                    <div class="overlay-layer">
                                        <a href="#" class="btn btn-icon btn-danger btn-shadow btn-delete" data-action="{{ route('admin.account-type.destroy-image', $account_type->id) }}" data-type="banner"><i class="la la-trash"></i></a>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @endif
                        <div class="form-group">
                            <label class="control-label">Banner</label>
                            <span class="text-muted float-right small">Preferred size: {{ Helper::preferredSize('account-type', 'banner') }}</span>
                            <div class="custom-file">
                                <input type="file" class="custom-file-input" name="banner" id="banner-file">
                                <label class="custom-file-label selected" for="banner-file"></label>
                            </div>
                        </div>
                        @endif
                        @if(file_exists('storage/thumbs/' . $account_type->image) && $account_type->image != '')
                        <div class="image-wrap mb-2">
                            <div class="bg-gray-300 my-2 px-3 py-2"><small>Existing Image Preview</small></div>
                            <div class="card card-custom overlay">
                                <div class="card-body p-0">
                                    <div class="overlay-wrapper">
                                        <img src="{{ asset('storage/' . $account_type->image) }}" alt="" class="w-100 rounded" />
                                    </div>
                                    <div class="overlay-layer">
                                        <a href="#" class="btn btn-icon btn-danger btn-shadow btn-delete" data-action="{{ route('admin.account-type.destroy-image', $account_type->id) }}" data-type="image"><i class="la la-trash"></i></a>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @endif
                        <div class="form-group">
                            <label class="control-label">Image</label>
                            <span class="text-muted float-right small">Preferred size: {{ Helper::preferredSize('account-type', 'image') }}</span>
                            <div class="custom-file">
                                <input type="file" class="custom-file-input" name="image" id="image-file">
                                <label class="custom-file-label selected" for="image-file"></label>
                            </div>
                        </div>

                        <div class="form-group d-none">
                            <label for="" class="control-label">URL</label>
                            {!! Form::text('link', $account_type->link, array('class'=>'form-control','placeholder'=>'URL')) !!}
                        </div>

                        <div class="form-group d-none">
                            <label for="" class="control-label">Download Form</label>
                            <select name="download_id" class="form-control">
                                <option value="">Select download form</option>
                                @if(isset($downloads) && !empty($downloads))
                                @foreach($downloads as $download)
                                <option value="{{ $download->id }}" {{ old("download_id") == $download->id || $account_type->download_id == $download->id ? 'selected' : '' }}>{{ $download->title }}</option>
                                @endforeach
                                @endif
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="" class="control-label">Category</label>
                            <select name="category_id" class="form-control">
                                <option value="">Select Category</option>
                                @foreach ($categories as $item)
                                <option value="{{ $item->id }}" {{ old("category_id")==$item->id || $account_type->category_id == $item->id ? 'selected' : '' }}>{{ $item->title }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group">
                            <div class="checkbox-inline">
                                <label class="checkbox checkbox-lg">
                                    <input type="checkbox" name="show_image" value="1" {{ $account_type->show_image == 1 ? 'checked' : '' }}>
                                    <span></span>Show Image on detail view.</label>
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
