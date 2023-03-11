@inject('helper', App\Helper\Helper)
@extends('layouts.backend.app')
@section('styles')

@endsection

@section('scripts')
<script type="text/javascript">
    $('.province').change(function() {
        if ($(this).val() != '') {
            var province = $(this).val();
            var index = $(this).data('language');
            $.ajax({
                url: "{{ route('admin.atm-location.district') }}",
                method: "GET",
                data: {
                    province: province
                },
                success: function(result) {
                    $('.district-' + index).html(result);
                }
            })
        }
    });
</script>

@endsection
@section('page-header')
<div class="page-header page-header-default">
    <div class="page-header-content">
        <div class="page-title">
            <h4>
                <i class="icon-arrow-left52 position-left"></i> <span class="text-semibold">{{ $title }}</span> - <span class="small">Edit</span>
                <a href="{{ route('admin.remittance.index') }}" class="btn btn-default legitRipple pull-right">
                    <i class="icon-undo2 position-left"></i> Back <span class="legitRipple-ripple"></span>
                </a>
            </h4>
        </div>
    </div>
    <div class="breadcrumb-line">
        <ul class="breadcrumb">
            <li><a href="{{ route('admin.dashboard') }}"><i class="icon-home2 position-left"></i> Home</a></li>
            <li><a href="{{ route('admin.remittance.index') }}">{{ $title }}</a></li>
            <li class="active">{{ $remittance->title }}</li>
        </ul>
    </div>
</div>
@endsection
@section('content')
<div class="panel panel-flat">
    <div class="panel-body">
        {!! Form::open(array('route' => ['admin.remittance.update', $remittance->id],'class'=>'form-horizontal','id'=>'validator', 'files' => 'true')) !!}
        <fieldset class="content-group">
            @include('admin.inc.visible-remittance', ['visibleIn' => $remittance->visible_in])

            <div class="form-group" style="display: {{ Helper::visibleIn(2, $remittance->visible_in) == true ? 'block': 'none' }};">
                <label for="" class="control-label col-lg-2">Parent ID</label>
                <div class="col-lg-6">
                    <select name="parent_id" class="form-control" id="">
                        <option value="">Self Parent</option>
                        @if(isset($parent))
                        @foreach($parent as $p)
                        <option value="{{ $p->id }}" {{ $p->id == $remittance->parent_id ? 'selected': ''}}>{{ $p->title }}</option>
                        @endforeach
                        @endif
                    </select>
                </div>
            </div>

            <div class="form-group {{ $errors->has('country_id') ? ' has-error' : '' }}">
                <label class="control-label col-lg-2">Country <span class="text-danger">*</span></label>
                <div class="col-lg-6 ">
                    <select name="country_id" class="form-control country" data-language="">
                        <option value="">Select Country</option>
                        @if(isset($countries) && !empty($countries))
                        @foreach($countries as $country)
                        <option value="{{ $country->id }}" {{ $remittance->country_id == $country->id ? 'selected' : '' }}>{{ $country->country_name }}</option>
                        @endforeach
                        @endif
                    </select>
                </div>
            </div>

            <div class="form-group">
                <label class="control-label col-lg-2">Publish ?</label>
                <div class="col-lg-6">
                    {!! Form::checkbox('is_active', 1, true, array('class' => 'switch','data-on-text'=>'On','data-off-text'=>'Off', 'data-on-color'=>'success','data-off-color'=>'danger' )) !!}
                </div>
            </div>

            <h5 class="panel-title"><i class="icon-file-plus position-left"></i>Content in Multiple Languages</h5>
            <hr>

            @php $languages = Helper::getLanguages(); @endphp

            <ul class="nav nav-tabs">
                @php $count=0; @endphp
                @foreach($languages as $language)
                <li class="{{ ($count == 0) ? 'active' : '' }} {{ ($count !=0 && !isset($langContent[$language['id']])) ? 'emptyContent' : '' }}"><a data-toggle="tab" href="#aa-{{ $language['id'] }}">{{ $language['name'] ?? '' }}</a></li>
                @php $count++; @endphp
                @endforeach
            </ul>

            <div class="tab-content">
                @php $count=0; @endphp

                @foreach($languages as $language)
                <input type="hidden" name="post[{{ $language['id'] }}]" value="{{ ($language['id'] == $preferredLanguage) ? $remittance->id : ($langContent[$language['id']][0]->id ?? "") }}">
                <div id="aa-{{ $language['id'] }}" class="tab-pane fade in {{ ($count == 0) ? 'active show' : '' }}">
                    <div class="form-group {{ $errors->has('title') ? ' has-error' : '' }}">
                        <label class="control-label col-lg-2">Title <span class="text-danger">*</span></label>
                        <div class="col-lg-6 ">
                            {!! Form::text('title['.$language['id'].']', ($language['id'] == $preferredLanguage) ? $remittance->title : ($langContent[$language['id']][0]->title ?? ""), array('class'=>'form-control','placeholder'=>'Remittance title')) !!}
                        </div>
                    </div>

                    <div class="form-group {{ $errors->has('country_id') ? ' has-error' : '' }}" style="display: none;">
                        <label class="control-label col-lg-2">Country <span class="text-danger">*</span></label>
                        <div class="col-lg-6 ">
                            <select name="country_ids[{{ $language['id'] }}]" class="form-control country_id-{{ $language['id'] }} country" data-language="{{ $language['id'] }}">
                                <option value="">Select Country</option>
                                @if(isset($countries) && !empty($countries))
                                @foreach($countries as $country)
                                <option value="{{ $country->id }}" {{ $remittance->country_id == $country->id && $remittance->language_id == $language['id'] ? 'selected' : '' }}>{{ $country->country_name }}</option>
                                @endforeach
                                @endif
                            </select>
                        </div>
                    </div>

                    <div class="form-group {{ $errors->has('province_id') ? ' has-error' : '' }}">
                        <label class="control-label col-lg-2">Province <span class="text-danger">*</span></label>
                        <div class="col-lg-6 ">
                            <select name="province_id[{{ $language['id'] }}]" class="form-control province-{{ $language['id'] }} province" data-language="{{ $language['id'] }}">
                                <option value="">Select Province</option>
                                @if(isset($provinces) && !empty($provinces))
                                @foreach($provinces as $province)
                                <option value="{{ $province->id }}" {{ $remittance->province_id == $province->id && $remittance->language_id == $language['id'] ? 'selected' : '' }}>{{ $province->title }}</option>
                                @endforeach
                                @endif
                            </select>
                        </div>
                    </div>

                    <div class="form-group {{ $errors->has('district_id') ? ' has-error' : '' }}">
                        <label class="control-label col-lg-2">District <span class="text-danger">*</span></label>
                        <div class="col-lg-6 ">
                            <select name="district_id[{{ $language['id'] }}]" class="form-control district-{{ $language['id'] }} district">
                                <option value="">Select District</option>
                                @if(isset($districts) && !empty($districts))
                                @foreach($districts as $district)
                                <option value="{{ $district->id }}" {{ $remittance->district_id == $district->id && $remittance->language_id == $language['id'] ? 'selected' : '' }}>{{ $district->title }}</option>
                                @endforeach
                                @endif
                            </select>
                        </div>
                    </div>

                    <div class="form-group {{ $errors->has('caption') ? ' has-error' : '' }}">
                        <label class="control-label col-lg-2">Address</label>
                        <div class="col-lg-6 ">
                            {!! Form::text('address['.$language['id'].']', ($language['id'] == $preferredLanguage) ? $remittance->address : ($langContent[$language['id']][0]->address ?? ""), array('class'=>'form-control','placeholder'=>'Address')) !!}
                        </div>
                    </div>

                    <div class="form-group {{ $errors->has('link') ? ' has-error' : '' }}">
                        <label class="control-label col-lg-2">Contact No.</label>

                        <div class="col-lg-6 ">
                            {!! Form::text('contact_no['.$language['id'].']', ($language['id'] == $preferredLanguage) ? $remittance->contact_no : ($langContent[$language['id']][0]->contact_no ?? ""), array('class'=>'form-control','placeholder'=>'Contact No.')) !!}
                        </div>
                    </div>

                    <div class="form-group {{ $errors->has('link') ? ' has-error' : '' }}">
                        <label class="control-label col-lg-2">Relationship Officer.</label>

                        <div class="col-lg-6 ">
                            {!! Form::textarea('relationship_officer['.$language['id'].']', 'relationship_officer['.($language['id'] == $preferredLanguage) ? $remittance->relationship_officer : ($langContent[$language['id']][0]->relationship_officer ?? ""), array('class'=>'form-control','placeholder'=>'Relationship Officer')) !!}
                        </div>
                    </div>
                </div>
                @php $count++; @endphp

                @endforeach
            </div>
        </fieldset>
        <div class="text-left col-lg-offset-2">
            <button type="submit" class="btn btn-primary legitRipple">Submit <i class="icon-arrow-right14 position-right"></i></button>
        </div>
        {!! method_field('PATCH') !!}
        {!! Form::hidden('id', $remittance->id)!!}
        {!! Form::close() !!}
    </div>
</div>
@endsection