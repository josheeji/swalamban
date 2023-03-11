@extends('layouts.backend.app')
@section('styles')

@endsection
@section('scripts')

    <script>

        $(function () {
            $(".type-check-all").click(function () {
                $(".type-check").prop('checked', $(this).prop("checked"));
            });
            $(".view-check-all").click(function () {
                $(".view-check").prop('checked', $(this).prop("checked"));
            });
        });

        $(".btn-delete").on("click", function () {
            $object = $(this);
            var action = $object.data('action');
            var imageType = $object.data('type');
            Swal.fire({
                title: 'Are you sure?',
                text: 'You will not be able to recover thissss !',
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
                        success: function (response) {
                            Swal.fire("Deleted!", response.message, "success");
                            $('.' + imageType + '-wrap').remove();
                        },
                        error: function (xhr, ajaxOptions, thrownError) {
                            Swal.fire('Error', 'Something went wrong while processing your request.', 'error');
                        }
                    });
                }
            })
        });

        $('form').submit(function () {
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
                <h5 class="text-dark font-weight-bold mt-2 mb-2 mr-5">{{$title}}</h5>
                <!--end::Page Title-->
                <!--begin::Actions-->
                <div class="subheader-separator subheader-separator-ver mt-2 mb-2 mr-4 bg-gray-200"></div>
                <span class="text-dark-50 font-weight-bold" id="kt_subheader_total">Edit</span>
                <!--end::Actions-->
            </div>
            <!--end::Info-->

            <!--begin::Toolbar-->
            <div class="d-flex align-items-center">
                <a href="{{ route('admin.'.$route.'.index') }}"
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
            {!! Form::open(array('route' => ['admin.'.$route.'.update', $edit_data->id],'class'=>'form-horizontal','id'=>'validator', 'files' => 'true')) !!}
            <div class="row">
                <div class="col-12 col-md-9">
                    <div class="card card-custom gutter-b">
                        <div class="card-body">
                            <div class="form-group">
                                <div class="checkbox-inline">
                                    <label class="checkbox checkbox-lg">
                                        <input type="checkbox" name="is_active" value="1"
                                               checked="checked" {{ $edit_data->is_active == 1 ? 'checked' : '' }}>
                                        <span></span>Publish ?</label>
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="control-label">Link</label>
{{--                                <span class="text-muted float-right">Preferred size: 512px / 384px or 800px / 600px</span>--}}
                                <div class="custom-file">
                                    {!! Form::text('image', $edit_data->image, array('class' => 'form-control')) !!}
                                    <label class="control-label" for="image-file"></label>
                                </div>
                            </div>

                        </div>
                    </div>
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
                                        <li class="nav-item"><a class="nav-link {{ ($count == 0) ? 'active' : '' }}"
                                                                data-toggle="tab"
                                                                href="#aa-{{ $language['id'] }}">{{ $language['name'] ?? '' }}</a>
                                        </li>
                                        @php $count++; @endphp
                                    @endforeach
                                </ul>
                            @endif
                            <div class="tab-content mt-5">
                                @php $count=0; @endphp

                                @foreach($languages as $language)
                                    <input type="hidden" name="update[{{ $language['id'] }}]"
                                           value="{{ ($language['id'] == $preferredLanguage) ? $edit_data->id : ($langContent[$language['id']][0]->id ?? "") }}">

                                    <div id="aa-{{ $language['id'] }}"
                                         class="tab-pane fade in {{ ($count == 0) ? 'active show' : '' }}">
                                        <div class="form-group">
                                            <input type="hidden" name="updateData[{{$language['id']}}]"
                                                   value="{{$language['id']}}">
                                            <label class="control-label">Title <span
                                                        class="text-danger">*</span></label>
                                            {!! Form::text('title['.$language['id'].']', ($language['id'] == $preferredLanguage) ? $edit_data->title : ($langContent[$language['id']][0]->title ?? ""), array('class'=>'form-control')) !!}
                                        </div>
                                        <div class="form-group">
                                            <label class="control-label">Icon <span
                                                        class="text-danger">*</span></label>
                                            {!! Form::text('value['.$language['id'].']', ($language['id'] == $preferredLanguage) ? $edit_data->value : ($langContent[$language['id']][0]->value ?? ""), array('class'=>'form-control')) !!}
                                        </div>
                                        @php
                                            $count++;
                                            if(!$isMultiLanguage){
                                            break;
                                            }
                                        @endphp
                                    </div>
                                @endforeach
                            </div>

                            <div class="card-footer">
                                <button type="submit" class="btn btn-primary">Submit</button>
                            </div>
                        </div>
                    </div>
                </div>
                @if(file_exists('storage/thumbs/' . $edit_data->image) && $edit_data->image != '')
                    <div class="image-wrap">
                        <div class="bg-gray-300 my-2 px-3 py-2"><small>Existing Image Preview</small></div>
                        <div class="card card-custom overlay">
                            <div class="card-body p-0">
                                <div class="overlay-wrapper">
                                    <img src="{{ asset('storage/' . $edit_data->image) }}" alt=""
                                         class="w-100 rounded"/>
                                </div>
                                <div class="overlay-layer">
                                    <a href="#" class="btn btn-icon btn-danger btn-shadow btn-delete"
                                       data-action="{{ route('admin.'.$route.'.destroy-image', $edit_data->id) }}"
                                       data-type="image"><i class="la la-trash"></i></a>
                                </div>
                            </div>
                        </div>
                    </div>
                @endif
                {!! method_field('PATCH') !!}
                {!! Form::hidden('id', $edit_data->id)!!}
                {!! Form::close() !!}
            </div>
        </div>
    </div>
@endsection