@extends('layouts.backend.app')

@section('page-header')
    <div class="page-header page-header-default">
        <div class="page-header-content">
            <div class="page-title">
                <h4>
                    <i class="icon-arrow-left52 position-left"></i>
                    <span class="text-semibold">Home</span> -
                    Error!
                </h4>
            </div>

        </div>
        <div class="breadcrumb-line">
            <ul class="breadcrumb">
                <li><a href="{{ route('admin.dashboard') }}"><i class="icon-home2 position-left"></i> Home</a>
                </li>
                <li class="active">Access Denied</li>
            </ul>
        </div>
    </div>
@endsection

@section('content')

    <!-- Main content -->
    <div class="content body">
        <section id="introduction">
            <div class="row">
                <div class="col-md-12">
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            <div class="panel-body">
                                <p><strong> An error has occurred while processing your request.</strong></p>
                                <p>This may occurred because there was an attempt to manipulate this software or
                                    you have not enough permission to process this request.</p>
                                <p>If you have not enough permission, you can request to your system administrator to
                                    get
                                    additional access.</p>
                                <p>Users are prohibited from taking unauthorized actions to intentionally modify the
                                    system.</p>
                            </div>
                        </div>
                    </div>
                </div>
        </section>
    </div>

@endsection