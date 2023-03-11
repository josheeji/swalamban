@inject('helper', App\Helper\Helper)
@extends('layouts.backend.app')
@section('styles')
<link href="{{ asset('backend/plugins/datatables/datatables.bundle.css') }}" rel="stylesheet" type="text/css" />
@endsection
@section('scripts')
@endsection
@section('page-header')
<div class="page-header page-header-default">
    <div class="page-header-content">
        <div class="page-title">
            <h4>
                <i class="icon-arrow-left52 position-left"></i> <span class="text-semibold">{{ $title }}</span> - <span class="small">view</span>
                <a href="{{ route('admin.remittance-alliance-request.index') }}" class="btn btn-default legitRipple pull-right">
                    <i class="icon-undo2 position-left"></i> Back <span class="legitRipple-ripple">
                </a>
            </h4>
        </div>
    </div>
    <div class="breadcrumb-line">
        <ul class="breadcrumb">
            <li><a href="{{ route('admin.dashboard') }}"><i class="icon-home2 position-left"></i> Home</a></li>
            <li><a href="{{ route('admin.remittance-alliance-request.index') }}">{{ $title }}</a></li>
            <li class="active">{{ $data->subject }}</li>
        </ul>
    </div>
</div>
@endsection
@section('content')
<div class="panel panel-flat">
    <div class="panel-body">
        <ul class="media-list row">
            <li class="col-md-12"></li>
            <li class="media col-md-6">
                <div class="media-body media-middle text-semibold">
                    <div class="media-annotation">Name</div>
                    {{ $data->name }}
                </div>
            </li>
            <li class="media col-md-6">
                <div class="media-body media-middle text-semibold">
                    <div class="media-annotation">Subject</div>
                    {{ $data->subject }}
                </div>
            </li>
            <li class="media col-md-6">
                <div class="media-body media-middle text-semibold">
                    <div class="media-annotation">Contact</div>
                    {!! $data->phone !!}
                </div>
            </li>
            <li class="media col-md-6">
                <div class="media-body media-middle text-semibold">
                    <div class="media-annotation">Email</div>
                {!! $data->email !!}
                </div>
            </li>
            <li class="media col-md-12">
                <div class="media-body media-middle text-semibold">
                    <div class="media-annotation">Message</div>
                    {!! $data->message !!}
                </div>
            </li>
        </ul>
    </div>
</div>
@endsection