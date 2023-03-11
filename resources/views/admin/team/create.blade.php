@extends('layouts.backend.app')
@section('title', 'Teams - create')
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
                <span class="text-dark-50 font-weight-bold" id="kt_subheader_total">Create</span>
                <!--end::Actions-->
            </div>
            <!--end::Info-->

            <!--begin::Toolbar-->
            <div class="d-flex align-items-center">
                <a href="{{ route('admin.team.index') }}"
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
            {!! Form::open(['route' => 'admin.team.store', 'class' => 'form-horizontal', 'id' => 'team', 'files' => 'true']) !!}
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
                                    <div id="aa-{{ $language['id'] }}"
                                        class="tab-pane fade in {{ $count == 0 ? 'active show' : '' }}">
                                        <div class="form-group">
                                            <label class="control-label">Full name <span
                                                    class="text-danger">*</span></label>
                                            {!! Form::text('full_name[' . $language['id'] . ']', null, ['class' => 'form-control', 'placeholder' => 'Full name']) !!}
                                        </div>
                                        <div class="form-group">
                                            <label class="control-label">Designation <span
                                                    class="text-danger">*</span></label>
                                            {!! Form::text('designation[' . $language['id'] . ']', null, ['class' => 'form-control', 'placeholder' => 'Designation']) !!}
                                        </div>
                                        <div class="form-group">
                                            <label class="control-label">Description</label>
                                            {!! Form::textarea('description[' . $language['id'] . ']', null, ['class' => 'form-control editor', 'id' => 'editor']) !!}
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
                                <div class="checkbox-inline">
                                    <label class="checkbox checkbox-lg">
                                        <input type="checkbox" name="position">
                                        <span></span>Chairman ?</label>
                                </div>
                            </div>
                            <div class="form-group new">
                                <label class="control-label">Category <span class="text-danger">*</span></label>
                                <select name="category_id" class="form-control">
                                    <option value="">Select category</option>
                                    @include('admin.team.recursive_options', [
                                        'parents' => $categories,
                                        'selected_id' => '',
                                    ])
                                </select>
                            </div>
                            <div class="form-group">
                                <label class="control-label">Photo</label>
                                {{-- <span class="text-muted float-right small">Preferred size: {{
                                Helper::preferredSize('team', 'image') }}</span> --}}
                                <div class="custom-file">
                                    <input type="file" class="custom-file-input" name="photo" id="image-file">
                                    <label class="custom-file-label selected" for="image-file"></label>
                                    <br>
                                    <span class="text-muted  small">only jpg, jpeg and png format are allowed.</span>
                                    <br>
                                    <span class="text-muted  small">Image size should not be greater than 2MB.</span>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="control-label">Phone</label>
                                <input type="text" name="phone" id="" class="form-control">
                            </div>

                            <div class="form-group">
                                <label class="control-label">Email</label>
                                <input type="email" name="email" id="" class="form-control">
                            </div>

                            <div class="form-group">
                                <label class="control-label">Date</label>
                                <input type="date" name="date" id="" class="form-control">
                            </div>

                            <div class="form-group">
                                <label class="control-label">Tenure</label>
                                <input type="text" name="tenure" id="" class="form-control">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            {!! Form::close() !!}
        </div>
    </div>
@endsection
