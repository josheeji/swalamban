@inject('helper', App\Helper\Helper)

@extends('layouts.backend.app')
@section('styles')

@endsection
@section('scripts')

@endsection
@section('page-header')
<div class="page-header page-header-default">
    <div class="page-header-content">
        <div class="page-title">
            <h4>
                <i class="icon-arrow-left52 position-left"></i> <span class="text-semibold">{{ $title }}</span> - <span class="small">Edit</span>
                <a href="{{ route('admin.remittance-alliance.index') }}" class="btn btn-default legitRipple pull-right">
                    <i class="icon-undo2 position-left"></i> Back <span class="legitRipple-ripple"></span>
                </a>
            </h4>
        </div>
    </div>
    <div class="breadcrumb-line">
        <ul class="breadcrumb">
            <li><a href="{{ route('admin.dashboard') }}"><i class="icon-home2 position-left"></i> Home</a></li>
            <li><a href="{{ route('admin.remittance-alliance.index') }}">{{ $title }}</a></li>
            <li class="active">{{ $post->title }}</li>
        </ul>
    </div>
</div>
@endsection
@section('content')
<div class="panel panel-flat">
    <div class="panel-body">
        {!! Form::open(array('route' => ['admin.remittance-alliance.update', $post->id],'class'=>'form-horizontal','id'=>'validator', 'files' => 'true')) !!}
        <fieldset class="content-group">
            <div class="form-group">
                <label class="control-label col-lg-2">Image</label>
                <div class="col-lg-4">
                    <div class="custom-file">
                        <input type="file" class="custom-file-input" id="customFile" name="image">
                    </div>
                </div>
                <div class="col-lg-2 text-right">
                    @if(file_exists('storage/thumbs/' . $post->image) && $post->image != '')
                    <img src="{{ asset('storage/thumbs/' . $post->image) }}" class="" alt="">
                    @endif
                </div>
            </div>

            <div class="form-group">
                <label for="" class="control-label col-lg-2">Visible In</label>
                <div class="col-lg-10">
                    {!! Form::radio('visible_in', 1, $post->visible_in == 1 ? true : false, array('id'=> 'local')) !!}
                    <label for="personal">Local</label>&nbsp;
                    {!! Form::radio('visible_in', 2, $post->visible_in == 2 ? true : false, array('id'=> 'overseas')) !!}
                    <label for="business">Overseas</label>&nbsp;
                </div>
            </div>


            <div class="form-group">
                <label class="control-label col-lg-2">URL <span class="text-danger">*</span></label>
                <div class="col-lg-6">
                    {!! Form::text('url', $post->url, array('class'=>'form-control','placeholder'=>'URL')) !!}
                </div>
            </div>

            <div class="form-group">
                <label class="control-label col-lg-2">Publish ?</label>
                <div class="col-lg-10">
                    {!! Form::checkbox('is_active', 1, $post->is_active, array('class' => 'switch','data-on-text'=>'On','data-off-text'=>'Off', 'data-on-color'=>'success','data-off-color'=>'danger' )) !!}
                </div>
            </div>

            <h5 class="panel-title">Content in Multiple Languages</h5>
            <hr>
            @php $languages = Helper::getLanguages(); @endphp

            <ul class="nav nav-tabs">
                @php $count=0; @endphp
                @foreach($languages as $language)
                <li class="{{ ($count == 0) ? 'active' : '' }} {{ ($count !=0 && !isset($langContent[$language['id']])) ? 'emptyContent' : '' }}"><a data-toggle="tab" href="#aa-{{ $language['id'] }}">{{ $language['name'] ?? '' }}</a>
                </li>
                @php $count++; @endphp
                @endforeach
            </ul>

            <div class="tab-content">
                @php $count=0; @endphp

                @foreach($languages as $language)
                <input type="hidden" name="post[{{ $language['id'] }}]" value="{{ ($language['id'] == $preferredLanguage) ? $post->id : ($langContent[$language['id']][0]->id ?? "") }}">
                <div id="aa-{{ $language['id'] }}" class="tab-pane fade in {{ ($count == 0) ? 'active show' : '' }}">
                    <div class="form-group">
                        <label class="control-label col-lg-2">Title <span class="text-danger">*</span></label>
                        <div class="col-lg-6">
                            {!! Form::text('title['.$language['id'].']', ($language['id'] == $preferredLanguage) ? $post->title : ($langContent[$language['id']][0]->title ?? ""), array('class'=>'form-control','placeholder'=>'Remittance title')) !!}
                        </div>
                    </div>
                </div>
                @php $count++; @endphp

                @endforeach
            </div>
        </fieldset>
        <div class="text-left col-lg-offset-2">
            <button type="submit" class="btn btn-primary legitRipple"> Update <i class="icon-arrow-right14 position-right"></i></button>
        </div>
        {!! method_field('PATCH') !!}
        {!! Form::close() !!}
    </div>
</div>
@endsection