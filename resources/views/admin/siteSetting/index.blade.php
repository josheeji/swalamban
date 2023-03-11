@extends('layouts.backend.app')
@section('title', 'Settings')
@section('styles')
<style>
    .preview {
        position: relative;
    }

    .preview .remove {
        position: absolute;
        left: 41%;
        top: 40%;
        display: none;
    }

    .preview:hover .remove {
        display: block;
    }
</style>
@endsection
@section('scripts')
<script>
    $(document).ready(function() {
        $(".preview").on("click", ".remove", function() {
            $object = $(this);
            var id = $object.attr('data-id');
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
                        type: "POST",
                        url: baseUrl + "/admin/setting" + "/" + id,
                        data: {
                            id: id,
                            _method: 'DELETE'
                        },
                        success: function(response) {
                            Swal.fire("Deleted!", response.message, "success");
                            $('.preview').html('');
                        },
                        error: function(e) {
                            if (e.responseJSON.message) {
                                Swal.fire('Error', e.responseJSON.message, 'error');
                            } else {
                                Swal.fire('Error', 'Something went wrong while processing your request.', 'error')
                            }
                        }
                    });
                }
            })
        });
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
            <h5 class="text-dark font-weight-bold mt-2 mb-2 mr-5">Settings</h5>
            <!--end::Page Title-->
            <!--begin::Actions-->
            <div class="subheader-separator subheader-separator-ver mt-2 mb-2 mr-4 bg-gray-200"></div>
            <span class="text-dark-50 font-weight-bold" id="kt_subheader_total">Manage</span>
            <!--end::Actions-->
        </div>
        <!--end::Info-->

        <!--begin::Toolbar-->
        <div class="d-flex align-items-center">

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
        {!! Form::open(array('route' =>
        'admin.setting.store','class'=>'form-horizontal','id'=>'validator', 'files' => 'true')) !!}
        <ul class="nav nav-tabs" id="myTab" role="tablist">
            <li class="nav-item">
                <a class="nav-link active" id="english-tab" data-toggle="tab" href="#english" role="tab"
                    aria-controls="english" aria-selected="true">English</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" id="nepali-tab" data-toggle="tab" href="#nepali" role="tab" aria-controls="nepali"
                    aria-selected="false">Nepali</a>
            </li>
        </ul>
        <div class="tab-content" id="myTabContent">
            <div class="tab-pane fade show active" id="english" role="tabpanel" aria-labelledby="english-tab">
                <div class="row">
                    <div class="col-2">
                        <ul class="nav flex-column nav-pills">
                            <li class="nav-item">
                                <a class="nav-link active" href="#left-tab1" data-toggle="tab" aria-expanded="true">
                                    <span class="nav-icon"><i class="la la-cog"></i></span>
                                    <span class="nav-text">General</span>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="#left-tab2" data-toggle="tab" aria-expanded="false">
                                    <span class="nav-icon"><i class="la la-id-card"></i></span>
                                    <span class="nav-text">Contact</span>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="#left-tab3" data-toggle="tab" aria-expanded="false">
                                    <span class="nav-icon"><i class="la la-share-alt-square"></i></span>
                                    <span class="nav-text">Social</span>
                                </a>
                            </li>
                            <li class="d-none">
                                <a class="nav-link" href="#left-tab4" data-toggle="tab" aria-expanded="false">
                                    <i class="icon-credit-card2 position-left"></i> Remittance
                                </a>
                            </li>
                            <li class="">
                                <a class="nav-link" href="#left-tab6" data-toggle="tab" aria-expanded="false">
                                    <span class="nav-icon"><i class="la la-envelope"></i></span><span
                                        class="nav-text">Grievance</span>
                                </a>
                            </li>
                            <li class="d-none">
                                <a class="nav-link" href="#left-tab7" data-toggle="tab" aria-expanded="false">
                                    <i class=" icon-make-group position-left"></i> Schema
                                </a>
                            </li>
                            <li class="d-none">
                                <a class="nav-link" href="#left-tab8" data-toggle="tab" aria-expanded="false">
                                    <i class="icon-make-group position-left"></i> Schema Home
                                </a>
                            </li>

                            <li class="d-none">
                                <a class="nav-link" href="#left-tab9" data-toggle="tab" aria-expanded="false">
                                    <i class="icon-images3 position-left"></i> Banners
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="#left-tab10" data-toggle="tab" aria-expanded="true">
                                    <span class="nav-icon"><i class="la la-cog"></i></span>
                                    <span class="nav-text">Information Officer</span>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="#left-tab5" data-toggle="tab" aria-expanded="false">
                                    <span class="nav-icon"><i class="la la-thumbtack"></i></span>
                                    <span class="nav-text">Others</span>
                                </a>
                            </li>
                        </ul>
                    </div>
                    <div class="col-12 col-md-10">
                        <div class="card card-custom gutter-b">
                            <div class="card-body">

                                <div class="tab-content">
                                    <div class="tab-pane has-padding active" id="left-tab1">
                                        <fieldset class="">
                                            @foreach($general as $item)
                                            @if($item->language_id == "1")
                                            @if(in_array($item->key, ['multi_language']))
                                            <div class="form-group">
                                                <div class="form-group">
                                                    <div class="checkbox-inline">
                                                        <label class="checkbox checkbox-lg">
                                                            <input type="checkbox" name="{{ $item->key }}" value="1" {{
                                                                $item->value ==
                                                            1 ? 'checked' : ''}}>
                                                            <span></span>Check to enable language option on
                                                            backend.</label>
                                                    </div>
                                                </div>
                                            </div>
                                            @elseif(in_array($item->key, ['multi_language_front']))
                                            <div class="form-group">
                                                <div class="form-group">
                                                    <div class="checkbox-inline">
                                                        <label class="checkbox checkbox-lg">
                                                            <input type="checkbox" name="{{ $item->key }}" value="1" {{
                                                                $item->value ==
                                                            1 ? 'checked' : ''}}>
                                                            <span></span>Check to enable language option on
                                                            frontend.</label>
                                                    </div>
                                                </div>
                                            </div>
                                            @elseif(in_array($item->key, ['product_popup']))
                                            <div class="form-group">
                                                <div class="form-group">
                                                    <div class="checkbox-inline">
                                                        <label class="checkbox checkbox-lg">
                                                            <input type="checkbox" name="{{ $item->key }}" value="1" {{
                                                                $item->value ==
                                                            1 ? 'checked' : ''}}>
                                                            <span></span>Check to enable popup modal on product.</label>
                                                    </div>
                                                </div>
                                            </div>
                                            @elseif(in_array($item->key, ['landing_pages', 'tagline']))
                                            @if(!in_array($item->key, ['landing_pages']))
                                            <div class="form-group">
                                                <label class="control-label">{{ $item->title }}</label>
                                                {!! Form::textarea($item->key, $item->value,
                                                array('class'=>'form-control','rows' => 3)) !!}
                                            </div>
                                            @endif
                                            @elseif(in_array($item->key, ['site_updated_date']))
                                            <div class="form-group">
                                                <label class="control-label">{{ $item->title }}</label>
                                                {!! Form::date($item->key, $item->value,
                                                array('class'=>'form-control','rows' => 3)) !!}
                                            </div>
                                            @elseif(!in_array($item->key, ['header_logo', 'footer_logo', 'fav_icon',
                                            'site_url', 'preferred_language']))
                                            <div class="form-group">
                                                <label class="control-label">{{ $item->title }}</label>
                                                {!! Form::text($item->key, $item->value, array('class'=>'form-control'))
                                                !!}
                                            </div>
                                            @endif
                                            @endif
                                            @endforeach
                                        </fieldset>
                                    </div>

                                    <div class="tab-pane has-padding" id="left-tab2">
                                        <fieldset class="">
                                            @foreach($contact as $item)
                                            @if($item->language_id == "1")
                                            @if(in_array($item->key, ['address']))
                                            <div class="form-group">
                                                <label class="control-label">{{ $item->title }}</label>
                                                {!! Form::textarea($item->key, $item->value,
                                                array('class'=>'form-control','rows' => 3)) !!}
                                            </div>
                                            @else
                                            <div class="form-group">
                                                <label class="control-label">{{ $item->title }}</label>
                                                {!! Form::text($item->key, $item->value, array('class'=>'form-control'))
                                                !!}
                                            </div>
                                            @endif
                                            <div class="clearfix"></div>
                                            @endif
                                            @endforeach
                                        </fieldset>
                                    </div>

                                    <div class="tab-pane has-padding" id="left-tab3">
                                        <fieldset class="s">
                                            @foreach($social as $item)
                                            @if($item->language_id == "1")
                                            <div class="form-group">
                                                <label class="control-label">{{ $item->title }}</label>
                                                {!! Form::text($item->key, $item->value, array('class'=>'form-control'))
                                                !!}
                                            </div>
                                            <div class="clearfix"></div>
                                            @endif
                                            @endforeach
                                        </fieldset>
                                    </div>

                                    <div class="tab-pane has-padding" id="left-tab4">
                                        <fieldset class="s">
                                            @foreach($remittance as $item)
                                            @if($item->language_id == "1")
                                            @if(in_array($item->key, ['remit_map']))
                                            @elseif(in_array($item->key, ['remit_contact']))
                                            <div class="form-group">
                                                <label class="control-label">{{ $item->title }}</label>
                                                {!! Form::textarea($item->key, $item->value,
                                                array('class'=>'form-control',
                                                'rows' => 3)) !!}
                                            </div>
                                            @elseif(in_array($item->key, ['remit_banner']))
                                            <div class="form-group">
                                                <label class="control-label">{{ $item->title }}</label>
                                                <div class="col-lg-9">
                                                    <input type="file" name="remit_banner">
                                                </div>
                                                <div class="col-lg-12"><br></div>
                                                <div class="col-lg-3"></div>
                                                <div class="col-lg-9">
                                                    <div class="label label-striped border-left-info">Preferred size:
                                                        1900px /
                                                        600px</div>
                                                    @if(isset($item->value) && !empty($item->value))
                                                    <div class="preview">
                                                        <div class="remove btn btn-danger btn-icon btn-circle legitRipple"
                                                            data-id="{{ $item->id }}"><i class="la la-trash"></i></div>
                                                        <img src="{{ asset('storage/thumbs/'.$item->value) }}" />
                                                    </div>
                                                    @endif
                                                </div>
                                            </div>
                                            @else
                                            <div class="form-group">
                                                <label class="control-label">{{ $item->title }}</label>
                                                {!! Form::text($item->key, $item->value, array('class'=>'form-control'))
                                                !!}
                                            </div>
                                            @endif
                                            <div class="clearfix"></div>
                                            @endif
                                            @endforeach
                                        </fieldset>
                                    </div>

                                    <div class="tab-pane has-padding" id="left-tab5">
                                        <fieldset class="s">
                                            @foreach($others as $item)
                                            @if($item->language_id == "1")
                                            <div class="form-group">
                                                <label class="control-label">{{ $item->title }}</label>
                                                @if(in_array($item->key, ['custom_css']))
                                                {!! Form::textarea($item->key, json_decode($item->value),
                                                array('class'=>'form-control', 'rows' => 12)) !!}
                                                @else
                                                {!! Form::textarea($item->key, $item->value,
                                                array('class'=>'form-control',
                                                'rows' => 3)) !!}
                                                @endif
                                            </div>
                                            <div class="clearfix"></div>
                                            @endif
                                            @endforeach
                                        </fieldset>
                                    </div>

                                    <div class="tab-pane has-padding" id="left-tab6">
                                        <fieldset>
                                            @foreach($grievance as $item)
                                            @if($item->language_id == "1")
                                            <div class="form-group">
                                                <label for="" class="control-label">{{ $item->title }}</label>
                                                @if(in_array($item->key, ['grievance_handling_officer']))
                                                {!! Form::textarea($item->key, $item->value,
                                                array('class'=>'form-control',
                                                'rows' => 3)) !!}
                                                @elseif(in_array($item->key, ['grievance_image']))
                                                <input type="file" name="grievance_image">
                                                @if(isset($item->value) && !empty($item->value))
                                                <div class="row">
                                                    <div class="col-4">
                                                        <div class="preview">
                                                            <div class="remove btn btn-danger btn-icon btn-circle legitRipple"
                                                                data-id="{{ $item->id }}"><i class="la la-trash"></i>
                                                            </div>
                                                            <img src="{{ asset('storage/thumbs/'.$item->value) }}" />
                                                        </div>
                                                    </div>
                                                </div>
                                                @endif
                                                @else
                                                {!! Form::text($item->key, $item->value, array('class' =>
                                                'form-control',
                                                'placeholder' => $item->title)) !!}
                                                @endif
                                            </div>
                                            @endif
                                            @endforeach
                                        </fieldset>
                                    </div>

                                    <div class="tab-pane has-padding" id="left-tab7">
                                        <fieldset class="s">
                                            @foreach($schema as $item)
                                            @if($item->language_id == "1")
                                            <div class="form-group">
                                                <label class="control-label">{{ $item->title }}</label>
                                                @if(in_array($item->key, ['schema_home_page']))
                                                {!! Form::textarea($item->key, $item->value,
                                                array('class'=>'form-control',
                                                'rows' => 6)) !!}
                                                @else
                                                {!! Form::text($item->key, $item->value, array('class'=>'form-control'))
                                                !!}
                                                @endif
                                            </div>
                                            <div class="clearfix"></div>
                                            @endif
                                            @endforeach
                                        </fieldset>
                                    </div>
                                    <div class="tab-pane has-padding" id="left-tab8">
                                        <fieldset class="s">
                                            @foreach($schemaHome as $item)
                                            @if($item->language_id == "1")
                                            <div class="form-group">
                                                <label class="control-label">{{ $item->title }}</label>
                                                @if(in_array($item->key, ['schema_home_description']))
                                                {!! Form::textarea($item->key, $item->value,
                                                array('class'=>'form-control',
                                                'rows' => 6)) !!}
                                                @else
                                                {!! Form::text($item->key, $item->value, array('class'=>'form-control'))
                                                !!}
                                                @endif
                                            </div>
                                            <div class="clearfix"></div>
                                            @endif
                                            @endforeach
                                        </fieldset>
                                    </div>

                                    <div class="tab-pane has-padding" id="left-tab9">
                                        <fieldset class="s">
                                            @foreach($banners as $item)
                                            @if($item->language_id == "1")
                                            <div class="form-group">
                                                <label class="control-label">{{ $item->title }}</label>
                                                <div class="col-lg-9">
                                                    <div class="col-lg-9">
                                                        <input type="file" name="{{ $item->key }}">
                                                    </div>
                                                    <div class="col-lg-3">
                                                        <div class="label label-striped border-left-info">Preferred
                                                            size: 1900px
                                                            / 600px</div>
                                                        @if(isset($item->value) && !empty($item->value))
                                                        <div class="preview">
                                                            <div class="remove btn btn-danger btn-icon btn-circle legitRipple"
                                                                data-id="{{ $item->id }}"><i class="la la-trash"></i>
                                                            </div>
                                                            <img src="{{ asset('storage/thumbs/'.$item->value) }}" />
                                                        </div>
                                                        @endif
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="clearfix"></div>
                                            @endif
                                            @endforeach
                                        </fieldset>
                                    </div>

                                    <div class="tab-pane has-padding" id="left-tab10">
                                        <fieldset>
                                            @foreach($informationOfficer as $item)
                                            <div class="form-group">
                                                <label for="" class="control-label">{{ $item->title }}</label>
                                                @if(in_array($item->key, ['information_designation']))
                                                {!! Form::text($item->key, $item->value, array('class'=>'form-control'))
                                                !!}
                                                @elseif(in_array($item->key, ['information_image',
                                                'information_reinsurer_image']))
                                                <input type="file" name="{{$item->key}}">
                                                @if(isset($item->value) && !empty($item->value))
                                                <div class="row">
                                                    <div class="col-4">
                                                        <div class="preview">
                                                            <div class="remove btn btn-danger btn-icon btn-circle legitRipple"
                                                                data-id="{{ $item->id }}"><i class="la la-trash"></i>
                                                            </div>
                                                            <img src="{{ asset('storage/thumbs/'.$item->value) }}" />
                                                        </div>
                                                    </div>
                                                </div>
                                                @endif
                                                @else
                                                {!! Form::text($item->key, $item->value, array('class' =>
                                                'form-control',
                                                'placeholder' => $item->title))
                                                !!}
                                                @endif
                                            </div>
                                            @endforeach
                                        </fieldset>
                                    </div>
                                </div>
                            </div>
                            <div class="card-footer">
                                <div class="text-left col-lg-offset-10 pull-right">
                                    <button type="submit" class="btn btn-primary legitRipple"> Submit <i
                                            class="icon-arrow-right14 position-right"></i></button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="tab-pane fade" id="nepali" role="tabpanel" aria-labelledby="nepali-tab">
                <div class="col-12 col-md-10">
                    <div class="card card-custom gutter-b">
                        <div class="card-body">
                            <div class="form-group">
                                <label for="exampleFormControlInput1"> Tagline</label>
                                @foreach($general as $item)
                                @if($item->key == "tagline" && $item->language_id == "2")
                                <input type="text" name="setting[2][tagline]" value="{{$item->value}}"
                                    class="form-control" id="exampleFormControlInput1">
                                @endif
                                @endforeach
                            </div>
                            <div class="form-group">
                                <label for="exampleFormControlInput1">Address</label>
                                @foreach($contact as $item)
                                @if($item->key == "address" && $item->language_id == "2")
                                <input type="text" name="setting[2][address]" value="{{$item->value}}"
                                    class="form-control" id="exampleFormControlInput1">
                                @endif
                                @endforeach
                            </div>
                            <div class="form-group">
                                <label for="exampleFormControlInput1">Contact</label>
                                @foreach($contact as $item)
                                @if($item->key == "contact" && $item->language_id == "2")
                                <input type="text" name="setting[2][contact]" value="{{$item->value}}"
                                    class="form-control" id="exampleFormControlInput1">
                                @endif
                                @endforeach
                            </div>
                            <div class="form-group">
                                <label for="exampleFormControlInput1">Information Officer Name</label>
                                @foreach($informationOfficer as $item)
                                @if($item->key == "information_name" && $item->language_id == "2")
                                <input type="text" name="setting[2][information_name]" value="{{$item->value}}"
                                    class="form-control" id="exampleFormControlInput1">
                                @endif
                                @endforeach
                            </div>
                            <div class="form-group">
                                <label for="exampleFormControlInput1">Information Officer Designation</label>
                                @foreach($informationOfficer as $item)
                                @if($item->key == "information_designation" && $item->language_id == "2")
                                <input type="text" name="setting[2][information_designation]" value="{{$item->value}}" class="form-control"
                                    id="exampleFormControlInput1">
                                @endif
                                @endforeach
                            </div>
                            <div class="form-group">
                                <label for="exampleFormControlInput1">Information Officer Address</label>
                                @foreach($informationOfficer as $item)
                                @if($item->key == "information_address" && $item->language_id == "2")
                                <input type="text" name="setting[2][information_address]" value="{{$item->value}}" class="form-control"
                                    id="exampleFormControlInput1">
                                @endif
                                @endforeach
                            </div>
                            <div class="form-group">
                                <label for="exampleFormControlInput1">Information Officer Phone</label>
                                @foreach($informationOfficer as $item)
                                @if($item->key == "information_phone" && $item->language_id == "2")
                                <input type="text" name="setting[2][information_phone]" value="{{$item->value}}" class="form-control"
                                    id="exampleFormControlInput1">
                                @endif
                                @endforeach
                            </div>
                            <div class="form-group">
                                <label for="exampleFormControlInput1">Information Officer Mobile</label>
                                @foreach($informationOfficer as $item)
                                @if($item->key == "information_mobile" && $item->language_id == "2")
                                <input type="text" name="setting[2][information_mobile]" value="{{$item->value}}" class="form-control"
                                    id="exampleFormControlInput1">
                                @endif
                                @endforeach
                            </div>



                            <div class="form-group">
                                <label for="exampleFormControlInput1">Copyright</label>
                                @foreach($general as $item)
                                @if($item->key == "copyright" && $item->language_id == "2")
                                <input type="text" name="setting[2][copyright]" value="{{$item->value}}"
                                    class="form-control" id="exampleFormControlInput1">
                                @endif
                                @endforeach
                            </div>
                            <div class="card-footer">
                                <div class="text-left col-lg-offset-10 pull-right">
                                    <button type="submit" class="btn btn-primary legitRipple"> Submit <i
                                            class="icon-arrow-right14 position-right"></i></button>
                                </div>
                            </div>
                        </div>
                    </div>
                    {!! Form::close() !!}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection