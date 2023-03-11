@extends('layouts.backend.app')
@section('title', 'Bonus Shares - edit')
@section('styles')

@endsection
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
            <h5 class="text-dark font-weight-bold mt-2 mb-2 mr-5">Bonus Shares</h5>
            <!--end::Page Title-->
            <!--begin::Actions-->
            <div class="subheader-separator subheader-separator-ver mt-2 mb-2 mr-4 bg-gray-200"></div>
            <span class="text-dark-50 font-weight-bold" id="kt_subheader_total">Edit</span>
            <!--end::Actions-->
        </div>
        <!--end::Info-->

        <!--begin::Toolbar-->
        <div class="d-flex align-items-center">
            <a href="{{ route('admin.bonus.index') }}" class="btn btn-outline-success font-weight-bolder"><i class="icon-undo2 position-left"></i> Back</span></a>
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
        {!! Form::open(array('route' => ['admin.bonus.update', $bonus->id],'class'=>'form-horizontal','id'=>'validator', 'files' => 'true')) !!}
        <div class="row">
            <div class="col-12 col-md-8">
                <div class="card card-custom gutter-b">
                    <div class="card-body">
                        <fieldset class="content-group">
                            <div class="form-group">
                                <label for="" class="control-label">Category <span class="text-danger">*</span></label>
                                <select name="category_id" class="form-control">
                                    @if(isset($categories) && !empty($categories))
                                    @foreach($categories as $category)
                                    <option value="{{ $category->id }}" {{ $bonus->category_id == $category->id ? 'selected': '' }}>{!! $category->title !!}</option>
                                    @endforeach
                                    @endif
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="" class="control-label">Name <span class="text-danger">*</span></label>
                                {!! Form::text('name', $bonus->name, array('class' => 'form-control')) !!}
                            </div>
                            <div class="form-group">
                                <label for="" class="control-label">Fathers Name <span class="text-danger">*</span></label>
                                {!! Form::text('fathers_name', $bonus->fathers_name, array('class' => 'form-control')) !!}
                            </div>
                            <div class="form-group">
                                <label for="" class="control-label">Grandfathers Name <span class="text-danger">*</span></label>
                                {!! Form::text('grandfathers_name', $bonus->grandfathers_name, array('class' => 'form-control')) !!}
                            </div>
                            <div class="form-group">
                                <label for="" class="control-label">Address <span class="text-danger">*</span></label>
                                {!! Form::text('address', $bonus->address, array('class' => 'form-control')) !!}
                            </div>
                            <div class="form-group">
                                <label for="" class="control-label">Shareholder No. <span class="text-danger">*</span></label>
                                {!! Form::text('shareholder_no', $bonus->shareholder_no, array('class' => 'form-control')) !!}
                            </div>
                            <div class="form-group">
                                <label for="" class="control-label">BOID <span class="text-danger">*</span></label>
                                {!! Form::text('boid', $bonus->boid, array('class' => 'form-control')) !!}
                            </div>
                            <div class="form-group">
                                <label for="" class="control-label">Actual Bonus <span class="text-danger">*</span></label>
                                {!! Form::text('actual_bonus', $bonus->actual_bonus, array('class' => 'form-control')) !!}
                            </div>
                            <div class="form-group">
                                <label for="" class="control-label">Total Kitta <span class="text-danger">*</span></label>
                                {!! Form::text('tax_amount', $bonus->total, array('class' => 'form-control')) !!}
                            </div>

                            <div class="form-group">
                                <label class="control-label col-lg-2">Publish ?</label>
                                <div class="col-lg-6">
                                    {!! Form::checkbox('is_active', 1, $bonus->is_active, array('class' => 'switch','data-on-text'=>'On','data-off-text'=>'Off', 'data-on-color'=>'success','data-off-color'=>'danger' )) !!}
                                </div>
                            </div>
                        </fieldset>
                        <div class="text-left col-lg-offset-2">
                            <button type="submit" class="btn btn-primary legitRipple">Submit <i class="icon-arrow-right14 position-right"></i></button>
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