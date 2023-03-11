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
            <h5 class="text-dark font-weight-bold mt-2 mb-2 mr-5">Stock Watch</h5>
            <!--end::Page Title-->
            <!--begin::Actions-->
            <div class="subheader-separator subheader-separator-ver mt-2 mb-2 mr-4 bg-gray-200"></div>
            <span class="text-dark-50 font-weight-bold" id="kt_subheader_total">Edit</span>
            <!--end::Actions-->
        </div>
        <!--end::Info-->

        <!--begin::Toolbar-->
        <div class="d-flex align-items-center">
            <a href="{{ route('admin.stock-watch.index') }}" class="btn btn-default btn-outline-success font-weight-bolder">Back</span></a>
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
        {!! Form::open(array('route' => ['admin.stock-watch.update', $stockInfo->id],'class'=>'form-horizontal','id'=>'validator', 'files' => 'true','novalidate')) !!}
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
                            <input type="hidden" name="post[{{ $language['id'] }}]" value="{{ ($language['id'] == $preferredLanguage) ? $stockInfo->id : ($langContent[$language['id']][0]->id ?? "") }}">
                            <div id="aa-{{ $language['id'] }}" class="tab-pane fade in {{ ($count == 0) ? 'active show' : '' }}">
                                <div class="form-group">
                                    <label class="control-label">Paidup Value<span class="text-danger">*</span></label>
                                    {!! Form::text('paidup_value['.$language['id'].']', ($language['id'] == $preferredLanguage) ? $stockInfo->paidup_value : ($langContent[$language['id']][0]->paidup_value ?? ""), array('class'=>'form-control')) !!}
                                </div>
                                <div class="form-group">
                                    <label class="control-label">Maximum<span class="text-danger">*</span></label>
                                    {!! Form::text('maximum['.$language['id'].']', ($language['id'] == $preferredLanguage) ? $stockInfo->maximum : ($langContent[$language['id']][0]->maximum ?? ""), array('class'=>'form-control')) !!}
                                </div>
                                <div class="form-group">
                                    <label class="control-label">Minimum<span class="text-danger">*</span></label>
                                    {!! Form::text('minimum['.$language['id'].']', ($language['id'] == $preferredLanguage) ? $stockInfo->minimum : ($langContent[$language['id']][0]->minimum ?? ""), array('class'=>'form-control')) !!}
                                </div>
                                <div class="form-group">
                                    <label class="control-label">Closing<span class="text-danger">*</span></label>
                                    {!! Form::text('closing['.$language['id'].']', ($language['id'] == $preferredLanguage) ? $stockInfo->closing : ($langContent[$language['id']][0]->closing ?? ""), array('class'=>'form-control')) !!}
                                </div>
                                <div class="form-group">
                                    <label class="control-label">No. of Traded Shares<span class="text-danger">*</span></label>
                                    {!! Form::text('traded_share['.$language['id'].']', ($language['id'] == $preferredLanguage) ? $stockInfo->traded_share : ($langContent[$language['id']][0]->traded_share ?? ""), array('class'=>'form-control')) !!}
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
                            <label class="control-label">Publish Date</label>
                            <input type="date" class="form-control" name="published_at" value="{{ old('published_at', $stockInfo->published_at) }}">
                        </div>
                        <div class="form-group">
                            <div class="checkbox-inline">
                                <label class="checkbox checkbox-lg">
                                    <input type="checkbox" name="is_active" value="1" {{ $stockInfo->is_active == 1 ? 'checked' : '' }}>
                                    <span></span>Publish ?</label>
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