@extends('layouts.backend.app')
@section('title', 'Branches - ' . $branchDirectory->title)
@section('styles')

@endsection
@section('scripts')
<script type="text/javascript">
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

    $('.province').change(function() {
        if ($(this).val() != '') {
            var province = $(this).val();
            $.ajax({
                url: "{{ route('admin.branch-directory.district') }}",
                method: "GET",
                data: {
                    province: province
                },
                success: function(result) {
                    $('.district').html(result);
                }
            })
        }
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
            <h5 class="text-dark font-weight-bold mt-2 mb-2 mr-5">Branches</h5>
            <!--end::Page Title-->
            <!--begin::Actions-->
            <div class="subheader-separator subheader-separator-ver mt-2 mb-2 mr-4 bg-gray-200"></div>
            <span class="text-dark-50 font-weight-bold" id="kt_subheader_total">Edit</span>
            <!--end::Actions-->
        </div>
        <!--end::Info-->

        <!--begin::Toolbar-->
        <div class="d-flex align-items-center">
            <a href="{{ route('admin.branch-directory.index') }}"
                class="btn btn-outline-success font-weight-bolder">Back</a>
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
        {!! Form::open(array('route' => ['admin.branch-directory.update',
        $branchDirectory->id],'class'=>'form-horizontal','id'=>'validator', 'files' => 'true')) !!}
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
                            <li class="nav-item"><a class="nav-link {{ ($count == 0) ? 'active' : '' }}"
                                    data-toggle="tab" href="#aa-{{ $language['id'] }}">{{ $language['name'] ?? '' }}</a>
                            </li>
                            @php
                            $count++;
                            @endphp
                            @endforeach
                        </ul>
                        @endif
                        <div class="tab-content mt-5">
                            @php $count=0; @endphp
                            @foreach($languages as $language)
                            <div id="aa-{{ $language['id'] }}"
                                class="tab-pane fade in {{ ($count == 0) ? 'active show' : '' }}">
                                <input type="hidden" name="post[{{ $language['id'] }}]"
                                    value="{{ ($language['id'] == $preferredLanguage) ? $branchDirectory->id : ($langContent[$language['id']][0]->id ?? "") }}">
                                <div class="form-group {{ $errors->has('title') ? ' has-error' : '' }}">
                                    <label class="control-label">Title <span class="text-danger">*</span></label>
                                    {!! Form::text('title['.$language['id'].']', ($language['id'] == $preferredLanguage)
                                    ? $branchDirectory->title : ($langContent[$language['id']][0]->title ?? ""),
                                    array('class'=>'form-control')) !!}
                                </div>
                                <div class="form-group {{ $errors->has('caption') ? ' has-error' : '' }}">
                                    <label class="control-label">Address</label>
                                    {!! Form::text('address['.$language['id'].']', ($language['id'] ==
                                    $preferredLanguage) ? $branchDirectory->address :
                                    ($langContent[$language['id']][0]->address ?? ""),
                                    array('class'=>'form-control','placeholder'=>'Address')) !!}
                                </div>
                                <div class="form-group d-none {{ $errors->has('link') ? ' has-error' : '' }}">
                                    <label class="control-label">Ward No.</label>
                                    {!! Form::text('ward_no['.$language['id'].']', ($language['id'] ==
                                    $preferredLanguage) ? $branchDirectory->ward_no :
                                    ($langContent[$language['id']][0]->ward_no ?? ""), array('class'=>'form-control'))
                                    !!}
                                </div>

                                <div class="form-group {{ $errors->has('phone') ? ' has-error' : '' }}">
                                    <label class="control-label">Phone</label>
                                    {!! Form::text('phone['.$language['id'].']', ($language['id'] == $preferredLanguage)
                                    ? $branchDirectory->phone : ($langContent[$language['id']][0]->phone ?? ""),
                                    array('class'=>'form-control')) !!}
                                </div>

                                <div class="form-group {{ $errors->has('mobile') ? ' has-error' : '' }}">
                                    <label class="control-label">Mobile</label>
                                    {!! Form::text('mobile['.$language['id'].']', ($language['id'] ==
                                    $preferredLanguage) ? $branchDirectory->mobile :
                                    ($langContent[$language['id']][0]->mobile ?? ""), array('class'=>'form-control'))
                                    !!}
                                </div>

                                <label>Branch Incharge Info / Branch Manager</label>

                                <div class="form-group {{ $errors->has('fullname') ? ' has-error' : '' }}">
                                    <label class="control-label">Fullname</label>
                                    {!! Form::text('fullname['.$language['id'].']', ($language['id'] ==
                                    $preferredLanguage) ? $branchDirectory->fullname :
                                    ($langContent[$language['id']][0]->fullname ?? ""), array('class'=>'form-control'))
                                    !!}
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
                                    <input type="checkbox" name="is_active" value="1" {{ $branchDirectory->is_active ==
                                    1 ? 'checked' : '' }}>
                                    <span></span>Publish ?</label>
                            </div>
                        </div>
                        @if(file_exists('storage/thumbs/' . $branchDirectory->photo) && $branchDirectory->photo != '')
                        <div class="image-wrap mb-2">
                            <div class="bg-gray-300 my-2 px-3 py-2"><small>Existing Image Preview</small></div>
                            <div class="card card-custom overlay">
                                <div class="card-body p-0">
                                    <div class="overlay-wrapper">
                                        <img src="{{ asset('storage/' . $branchDirectory->photo) }}" alt=""
                                            class="w-100 rounded" />
                                    </div>
                                    <div class="overlay-layer">
                                        <a href="#" class="btn btn-icon btn-danger btn-shadow btn-delete"
                                            data-action="{{ route('admin.branch.destroy-image', $branchDirectory->id) }}"
                                            data-type="image"><i class="la la-trash"></i></a>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @endif
                        <div class="form-group">
                            <label class="control-label">Photo</label>
                            <small class="text-dark-50 float-right">Preferred size: {{
                                Helper::preferredSize('network-point', 'image') }}</small>
                            <div class="custom-file">
                                <input type="file" class="custom-file-input" name="photo" id="image-file">
                                <label class="custom-file-label selected" for="image-file"></label>
                            </div>
                        </div>
                        <div class="form-group {{ $errors->has('province_id') ? ' has-error' : '' }}">
                            <label class="control-label">Province <span class="text-danger">*</span></label>
                            <select name="province_id" class="form-control province">
                                <option value="">Select Province</option>
                                @if(isset($provinces) && !empty($provinces))
                                @foreach($provinces as $province)
                                <option value="{{ $province->id }}" {{ $province->id == old('province_id',
                                    $branchDirectory->province_id) ? 'selected' : ''}}>{{ $province->title }}</option>
                                @endforeach
                                @endif
                            </select>
                        </div>
                        <div class="form-group {{ $errors->has('district_id') ? ' has-error' : '' }}">
                            <label class="control-label">District <span class="text-danger">*</span></label>
                            <select name="district_id" class="form-control district">
                                <option value="">Select District</option>
                                @if(isset($districts) && !empty($districts))
                                @foreach($districts as $district)
                                <option value="{{ $district->id }}" {{ $district->id == old('district_id',
                                    $branchDirectory->district_id) ? 'selected' : '' }}>{{ $district->title }}</option>
                                @endforeach
                                @endif
                            </select>
                        </div>
                        <div class="form-group d-none">
                            <label class="col-form-label">Inside Kathmandu Valley</label>
                            <div class="col-form-label">
                                <div class="radio-inline">
                                    <label class="radio radio-outline radio-success">
                                        <input type="radio" name="inside_valley" value="1" {{ old('inside_valley',
                                            $branchDirectory->inside_valley) == 1 ? 'checked' : '' }} />
                                        <span></span>
                                        Yes
                                    </label>
                                    <label class="radio radio-outline radio-success">
                                        <input type="radio" name="inside_valley" value="0" {{ old('inside_valley',
                                            $branchDirectory->inside_valley) == 0 ? 'checked' : '' }} />
                                        <span></span>
                                        No
                                    </label>
                                    <label class="radio radio-outline radio-success d-none">
                                        <input type="radio" name="inside_valley" value="2" {{ old('inside_valley',
                                            $branchDirectory->inside_valley) == 2 ? 'checked' : '' }} />
                                        <span></span>
                                        Extension Counter
                                    </label>
                                </div>
                            </div>
                        </div>
                        {{-- <div class="form-group">
                            <label class="col-form-label">Is Headoffice?</label>
                            <div class="col-form-label">
                                <div class="radio-inline">
                                    <label class="radio radio-outline radio-success">
                                        <input type="radio" name="is_headoffice" value="1" {{ old('is_headoffice',
                                            $branchDirectory->is_headoffice) == 1 ? 'checked' : '' }} />
                                        <span></span>
                                        Yes
                                    </label>
                                    <label class="radio radio-outline radio-success">
                                        <input type="radio" name="is_headoffice" value="0" {{ old('is_headoffice',
                                            $branchDirectory->is_headoffice) == 0 ? 'checked' : '' }} />
                                        <span></span>
                                        No
                                    </label>
                                </div>
                            </div>
                        </div> --}}
                        <div class="form-group">
                            <label class="col-form-label">Office Type</label>
                            <div class="col-form-label">
                                <div class="radio-inline">
                                    <label class="radio radio-outline radio-success">
                                        <input type="radio" name="type" value="1" checked/>
                                        <span></span>
                                        Branch Office
                                    </label>
                                    <label class="radio radio-outline radio-success">
                                        <input type="radio" name="type" value="2"
                                            {{ $branchDirectory->is_headoffice == 2 ? 'checked' : '' }} />
                                        <span></span>
                                        Central Office
                                    </label>
                                    <label class="radio radio-outline radio-success">
                                        <input type="radio" name="type" value="3"
                                            {{ $branchDirectory->is_headoffice == 3 ? 'checked' : '' }} />
                                        <span></span>
                                        Area Office
                                    </label>
                                    <label class="radio radio-outline radio-success">
                                        <input type="radio" name="type" value="4"
                                            {{ $branchDirectory->is_headoffice == 4 ? 'checked' : '' }} />
                                        <span></span>
                                        Information Office
                                    </label>
                                </div>
                            </div>
                        </div>
                        <div class="form-group {{ $errors->has('email') ? ' has-error' : '' }}">
                            <label class="control-label">Email</label>
                            {!! Form::text('email', $branchDirectory->email,
                            array('class'=>'form-control','placeholder'=>'Email')) !!}
                        </div>
                        <div class="form-group {{ $errors->has('fax') ? ' has-error' : '' }}">
                            <label class="control-label">Fax</label>
                            {!! Form::text('fax', $branchDirectory->fax,
                            array('class'=>'form-control','placeholder'=>'Fax')) !!}
                        </div>
                        <div class="form-group">
                            <label for="" class="control-label">Map URL</label>
                            {!! Form::text('url', $branchDirectory->url, array('class' => 'form-control')) !!}
                        </div>
                        <div class="form-group">
                            <label for="" class="control-label">Latitude</label>
                            {!! Form::text('lat', $branchDirectory->lat, array('class' => 'form-control')) !!}
                        </div>
                        <div class="form-group">
                            <label for="" class="control-label">Longitude</label>
                            {!! Form::text('long', $branchDirectory->long, array('class' => 'form-control')) !!}
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
