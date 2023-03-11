@extends('layouts.backend.app')
@section('scripts')
<script>
    $(document).ready(function() {
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

        function readURL(input) {

            if (input.files && input.files[0]) {
                var reader = new FileReader();
                reader.onload = function(e) {
                    $("#my-image").attr('src', e.target.result);
                    var resize = new Croppie($("#my-image")[0], {
                        viewport: {
                            width: 600,
                            height: 300
                        },
                        boundary: {
                            width: 800,
                            height: 400
                        },
                        showZoomer: true,
                        enableResize: false,
                        enableOrentation: true,
                        enableExif: false,
                    });
                    $('.use').fadeIn();
                    $('.use').click(function() {
                        resize.result({
                            type: 'canvas',
                            size: {
                                width: 800,
                                height: 400
                            }
                        }).then(function(dataImg) {

                            var data = [{
                                image: dataImg
                            }, {
                                name: 'myimgage.jpg'
                            }];

                            // use ajax to send data to php

                            $('.result').empty();
                            $('.result').append('<img src="' + dataImg + '" style="width:200px; height:200px">');
                            $('.fileimage').val(dataImg);
                            $('.displayimage').css('display', 'none');
                        });
                    });
                }
                reader.readAsDataURL(input.files[0]);

            }

        }

        $("#imgInp").change(function() {

            readURL(this)
        });

    });

    $('form').submit(function() {
        $(this).find("button[type='submit']").prop('disabled', true);
    });
</script>
@endsection
@section('page-header')
<div class="page-header page-header-default">
    <div class="page-header-content">
        <div class="page-title">
            <h4><i class="icon-arrow-left52 position-left"></i> <span class="text-semibold">Home</span> -
                Notice</h4>
        </div>
    </div>
    <div class="breadcrumb-line">
        <ul class="breadcrumb">
            <li><a href="{{ route('admin.dashboard') }}"><i class="icon-home2 position-left"></i> Home</a>
            </li>
            <li class="active">Notice</li>
        </ul>
    </div>
</div>
@endsection
@section('content')
<div class="panel panel-flat">
    <div class="panel-heading">
        <h5 class="panel-title"><i class="icon-file-plus position-left"></i>Edit Notice</h5>
        <div class="heading-elements">
            <a href="{{ route('admin.notice.index') }}" class="btn btn-default legitRipple pull-right">
                <i class="icon-undo2 position-left"></i>
                Back
                <span class="legitRipple-ripple"></span>
            </a>
        </div>
    </div>
    <div class="panel-body">
        {!! Form::open(array('route' => ['admin.notice.update', $notice->id],'class'=>'form-horizontal','id'=>'validator', 'files' => 'true')) !!}
        <fieldset class="content-group">

            <div class="clearfix"></div>
            <div class="form-group">
                <label class="control-label col-lg-2">Title</label>

                <div class="col-lg-6">
                    {!! Form::text('title',$notice->title, array('class'=>'form-control','placeholder'=>'Title')) !!}
                </div>
            </div>
            <div class="form-group">
                <label class="control-label col-lg-2">Image</label>
                <div class="col-lg-10">
                    @if(file_exists('storage/'.$notice->image) && $notice->image !== '')
                    <img src="{{ asset('storage/'.$notice->image)}}" class="displayimage" style="width:100px; height:100px; margin-bottom: 15px;" alt=""></br>

                    @endif
                    <input name="image" type="hidden" class="fileimage">
                    <div id="form1" runat="server">
                        <input type='file' id="imgInp" /></br> </br>
                        <img id="my-image" src="#" />
                    </div>
                    {{--<button class="use">Upload</button>--}}
                    <input type="button" class="use" value="Crop"></br> </br>
                    <div class="result"></div>
                </div>
            </div>
            <div class="form-group">
                <div class="checkbox-inline">
                    <label class="checkbox checkbox-lg">
                        <input type="checkbox" name="show_image" value="1" {{ $notice->show_image == 1 ? 'checked' : '' }}>
                        <span></span>Show Image on detail view.</label>
                </div>
            </div>
            <div class="clearfix"></div>

            <div class="form-group">
                <label class="control-label col-lg-2">Position<span class="text-danger">*</span></label>
                <div class="col-lg-6">

                    @php $positions = ['T' => 'Top', 'B' => 'Bottom', 'C' => 'Center', 'R' => 'Right', 'L' => 'Left']; @endphp

                    <select name="position" class="form-control">
                        <option value="">Select an option</option>
                        @foreach($positions as $key => $value)
                        <option value="{{ $key }}" {{ ($notice->position == $key) ? "selected" : "" }}>{{ $value }}</option>
                        @endforeach
                    </select>

                </div>
            </div>

            <div class="clearfix"></div>

            <div class="form-group">
                <label class="control-label col-lg-2">Timer</label>

                <div class="col-lg-6">
                    {!! Form::text('timer', $notice->timer, array('class'=>'form-control','placeholder'=>'Timer')) !!}
                </div>
            </div>

            <div class="clearfix"></div>

            <div class="form-group">
                <label class="control-label col-lg-2">Start Date <span class="text-danger">*</span></label>

                <div class="col-lg-6">
                    {!! Form::date('start_date', $notice->start_date, array('class'=>'form-control','placeholder'=>'Start Date')) !!}
                </div>
            </div>
            <div class="clearfix"></div>

            <div class="form-group">
                <label class="control-label col-lg-2">End Date <span class="text-danger">*</span></label>

                <div class="col-lg-6">
                    {!! Form::date('end_date', $notice->end_date, array('class'=>'form-control','placeholder'=>'End Date')) !!}
                </div>
            </div>
            <div class="clearfix"></div>

            <div class="form-group">
                <label class="control-label col-lg-2">Link</label>

                <div class="col-lg-6">
                    {!! Form::url('link', $notice->link ?? "", array('class'=>'form-control','placeholder'=>'Link')) !!}
                </div>
            </div>
            <div class="clearfix"></div>

            <div class="form-group">
                <label class="control-label col-lg-2">Publish ?</label>
                <div class="col-lg-10">
                    {!! Form::checkbox('is_active', null, $notice->is_active, array('class' => 'switch','data-on-text'=>'On','data-off-text'=>'Off', 'data-on-color'=>'success','data-off-color'=>'danger' )) !!}
                </div>
            </div>
            <div class="clearfix"></div>
        </fieldset>
        <div class="text-left col-lg-offset-2">
            <button type="submit" class="btn btn-primary legitRipple">
                Submit <i class="icon-arrow-right14 position-right"></i></button>
        </div>
        {!! method_field('PATCH') !!}
        {!! Form::hidden('id', $notice->id)!!}
        {!! Form::close() !!}
    </div>
</div>
@endsection
