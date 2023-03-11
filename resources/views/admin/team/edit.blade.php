@extends('layouts.backend.app')
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
                <h5 class="text-dark font-weight-bold mt-2 mb-2 mr-5">Teams</h5>
                <!--end::Page Title-->
                <!--begin::Actions-->
                <div class="subheader-separator subheader-separator-ver mt-2 mb-2 mr-4 bg-gray-200"></div>
                <span class="text-dark-50 font-weight-bold" id="kt_subheader_total">Edit</span>
                <!--end::Actions-->
            </div>
            <!--end::Info-->

            <!--begin::Toolbar-->
            <div class="d-flex align-items-center">
                <a href="{{ route('admin.team.index') }}"
                    class="btn btn-default btn-outline-success font-weight-bolder">Back</span></a>
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
            {!! Form::open(['route' => ['admin.team.update', $team->id], 'class' => 'form-horizontal', 'id' => 'validator', 'files' => 'true', 'novalidate']) !!}
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
                                        @php $count++; @endphp
                                    @endforeach
                                </ul>
                            @endif
                            <div class="tab-content mt-5">
                                @php $count=0; @endphp
                                @foreach ($languages as $language)
                                    <input type="hidden" name="post[{{ $language['id'] }}]"
                                        value="{{ $language['id'] == $preferredLanguage ? $team->id : $langContent[$language['id']][0]->id ?? '' }}">
                                    <div id="aa-{{ $language['id'] }}"
                                        class="tab-pane fade in {{ $count == 0 ? 'active show' : '' }}">
                                        <div class="form-group">
                                            <label class="control-label">Full Name <span
                                                    class="text-danger">*</span></label>
                                            {!! Form::text('full_name[' . $language['id'] . ']', $language['id'] == $preferredLanguage ? $team->full_name : $langContent[$language['id']][0]->full_name ?? '', ['class' => 'form-control', 'placeholder' => 'Full Name']) !!}
                                        </div>

                                        <div class="form-group">
                                            <label class="control-label">Designation <span
                                                    class="text-danger">*</span></label>
                                            {!! Form::text('designation[' . $language['id'] . ']', $language['id'] == $preferredLanguage ? $team->designation : $langContent[$language['id']][0]->designation ?? '', ['class' => 'form-control', 'placeholder' => 'Designation']) !!}
                                        </div>
                                        <div class="form-group">
                                            <label class="control-label">Description</label>
                                            {!! Form::textarea('description[' . $language['id'] . ']', $language['id'] == $preferredLanguage ? $team->description : $langContent[$language['id']][0]->description ?? '', [
    'class' => 'form-control
                                    editor',
    'id' => '',
    'required' => 'true',
]) !!}
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
                                        <input type="checkbox" name="is_active" value="1"
                                            {{ $team->is_active == 1 ? 'checked' : '' }}>
                                        <span></span>Publish ?</label>
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="checkbox-inline">
                                    <label class="checkbox checkbox-lg">
                                        <input type="checkbox" name="position" value="1"
                                            {{ $team->position == 1 ? 'checked' : '' }}>
                                        <span></span>Chairman ?</label>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="control-label">Category <span class="text-danger">*</span></label>
                                <select name="category_id" class="form-control">
                                    <option value="">Select category</option>
                                    @include('admin.team.recursive_options', [
                                        'parents' => $categories,
                                        'selected_id' => $team->category_id,
                                    ])
                                </select>
                            </div>
                            <div>
                                @if (file_exists('storage/' . $team->photo) && $team->photo != '')
                                    <div class="banner-wrap mb-2">
                                        <div class="bg-gray-300 mb-2 px-3 py-2"><small>Existing Team Preview</small></div>
                                        <div class="card card-custom overlay">
                                            <div class="card-body p-0">
                                                <div class="overlay-wrapper">
                                                    <img src="{{ asset('storage/' . $team->photo) }}"
                                                        class="displayimage" alt="">
                                                </div>
                                                <div class="overlay-layer">
                                                    <a href="{{ route('admin.team.destroy-image', $team->id) }}"
                                                        class="btn btn-icon btn-danger btn-shadow btn-delete"
                                                        data-action="{{ route('admin.team.destroy-image', $team->id) }}"
                                                        data-type="banner"><i class="la la-trash"></i></a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endif
                                <div class="form-group">
                                    <label class="control-label">Photo</label>
                                    {{-- <span class="text-muted float-right">Preferred size: {{
                                    Helper::preferredSize('team', 'image') }}</span> --}}
                                    <br>
                                    <span class="text-muted  small">only jpg, jpeg and png format are allowed.</span>
                                    <br>
                                    <span class="text-muted  small">Image size should not be greater than 2MB and 800 x
                                        800.</span>
                                    <div class="custom-file">
                                        <input type="file" class="custom-file-input" name="photo" id="image-file">
                                        <label class="custom-file-label selected" for="image-file"></label>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="control-label">Phone</label>
                                <input type="text" name="phone" id="" class="form-control"
                                    value="{{ $team->phone }}">
                            </div>

                            <div class="form-group">
                                <label class="control-label">Email</label>
                                <input type="email" name="email" id="" class="form-control"
                                    value="{{ $team->email }}">
                            </div>

                            <div class="form-group">
                                <label class="control-label">Date</label>
                                <input type="date" name="date" id="" class="form-control"
                                    value="{{ $team->date }}">
                            </div>

                            <div class="form-group">
                                <label class="control-label">Tenure</label>
                                <input type="text" name="tenure" id="" class="form-control"
                                    value="{{ $team->tenure }}">
                            </div>
                        </div>
                    </div>
                </div>
                {!! method_field('PATCH') !!}
                {!! Form::close() !!}
            </div>
        </div>
    @endsection
