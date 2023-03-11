@inject('helper', App\Helper\Helper)
@extends('layouts.backend.app')
@section('scripts')
@endsection
@section('page-header')
<div class="page-header page-header-default">
    <div class="page-header-content">
        <div class="page-title">
            <h4>
                <i class="icon-arrow-left52 position-left"></i> <span class="text-semibold">Import</span> - <span class="small">Branch</span>
            </h4>
        </div>
    </div>
    <div class="breadcrumb-line">
        <ul class="breadcrumb">
            <li><a href="{{ route('admin.dashboard') }}"><i class="icon-home2 position-left"></i> Home</a></li>
            <li class="active">Import</li>
        </ul>
    </div>
</div>
@endsection
@section('content')
<div class="panel panel-flat">
    <div class="panel-body">
        {!! Form::open(array('route' => 'admin.import.store-download','class'=>'form-horizontal','id'=>'download', 'files' => 'true')) !!}
        <fieldset class="content-group">
            <div class="form-group" id="file">
                <label class="control-label col-lg-2">File <span class="text-danger">*</span></label>
                <div class="col-lg-10">
                    <input name="file" type="file">
                </div>
            </div>
        </fieldset>
        <div class="text-left col-lg-offset-2">
            <button type="submit" class="btn btn-primary legitRipple"> Submit <i class="icon-arrow-right14 position-right"></i></button>
        </div>
        {!! Form::close() !!}
    </div>
</div>
@endsection