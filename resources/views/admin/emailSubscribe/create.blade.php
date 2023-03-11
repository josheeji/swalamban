@extends('layouts.backend.app')
@section('scripts')
  <script type="text/javascript">
      $(document).ready(function () {
          $('.sidebar-category').find('#travel').addClass('nav-item nav-item-submenu nav-item-expanded nav-item-open');
          $('.sidebar-category').find('#destination').addClass('active');
          $('.sidebar-category').find('#travel').find('ul').show();
      });
  </script>
  <script>
      $(document).ready(function () {
          $('#validator')
              .formValidation({
                  framework: 'bootstrap',
                  icon: {
                      valid: 'glyphicon glyphicon-ok',
                      invalid: 'glyphicon glyphicon-remove',
                      validating: 'glyphicon glyphicon-refresh'
                  },
                  fields: {
                      title: {
                          validators: {
                              notEmpty: {
                                  message: 'The title is required'
                              }
                          }
                      },
                      description: {
                          validators: {
                              notEmpty: {
                                  message: 'The description is required'
                              }
                          }
                      },

                  }
              })


      });
  </script>
@endsection
@section('page-header')
  <div class="page-header page-header-default">
    <div class="page-header-content">
      <div class="page-title">
        <h4><i class="icon-arrow-left52 position-left"></i> <span class="text-semibold">Home</span> -
          Email Subscription</h4>
      </div>

    </div>
    <div class="breadcrumb-line">
      <ul class="breadcrumb">
        <li><a href="{{ route('admin.dashboard') }}"><i class="icon-home2 position-left"></i> Home</a>
        </li>
        <li><a href="{{ route('admin.email-subscribe.index') }}"> Email Subscription</a>
        </li>
        <li class="active"> Compose Mail</li>
      </ul>
    </div>
  </div>
@endsection
@section('content')

  <div class="panel panel-flat">
    <div class="panel-heading">
      <h5 class="panel-title"><i class="icon-file-plus position-left"></i>Compose Mail</h5>
      <div class="heading-elements">
        <a href="{{ route('admin.email-subscribe.index') }}" class="btn btn-default legitRipple pull-right">
          <i class="icon-undo2 position-left"></i>
          Back
          <span class="legitRipple-ripple"></span>
        </a>
      </div>
    </div>

    <div class="panel-body">


      {!! Form::open(array('route' => 'admin.email-subscribe.store','class'=>'form-horizontal','id'=>'validator', 'files' => 'true')) !!}
      <fieldset class="content-group">


        <div class="form-group">
          <label class="control-label col-lg-2">Compose Mail <span class="text-danger">*</span></label>

          <div class="col-lg-10">
            {!! Form::textarea('message', null, array('class'=>'form-control editor')) !!}
          </div>
        </div>
        <div class="clearfix"></div>

      </fieldset>


      <div class="text-left col-lg-offset-2">
        <button type="submit" class="btn btn-primary legitRipple">
          Submit <i class="icon-arrow-right14 position-right"></i></button>
      </div>
      {!! Form::close() !!}


    </div>
  </div>
@endsection