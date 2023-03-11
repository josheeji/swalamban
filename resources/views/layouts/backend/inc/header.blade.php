<div id="kt_header" class="header  header-fixed ">
    <!--begin::Container-->
    <div class=" container-fluid  d-flex align-items-stretch justify-content-between">
        <!--begin::Header Menu Wrapper-->
        <div class="header-menu-wrapper header-menu-wrapper-left" id="kt_header_menu_wrapper">
            <!--begin::Header Menu-->
            <div id="kt_header_menu" class="header-menu header-menu-mobile  header-menu-layout-default ">
                <!--begin::Header Nav-->
                <!--end::Header Nav-->
            </div>
            <!--end::Header Menu-->
        </div>
        <!--end::Header Menu Wrapper-->
        <!--begin::Topbar-->
        <div class="dropdown">

            <!--begin::User-->
            <div class="topbar-item mt-6" data-toggle="dropdown" data-offset="10px,0px">
                <div class="btn btn-icon w-auto btn-clean btn-dropdown d-flex align-items-center btn-lg px-2"
                     id="kt_quick_user_toggle">
                    <span
                            class="text-muted font-weight-bold font-size-base d-none d-md-inline mr-1">Welcome,</span>
                    <span class="text-dark-50 font-weight-bolder font-size-base d-none d-md-inline mr-3">{{ auth()->user()->first_name }} {{ auth()->user()->last_name }}</span>
{{--                    <span class="symbol symbol-35 symbol-light-success">--}}
{{--		                <span class="symbol-label font-size-h5 font-weight-bold">{{ (auth()->user()->first_name) }}</span>--}}
{{--		            </span>--}}
                </div>
            </div>
            <!--end::User-->
            <!--begin::Dropdown-->
            <div class="dropdown-menu p-0 m-0 dropdown-menu-anim-up dropdown-menu-md dropdown-menu-right">
                <!--begin::Nav-->
                <ul class="navi navi-hover py-5">
                    <!--begin::Item-->
                    <li class="navi-item">
                        <a  href="#" id="change_password" class="navi-link">
                            <span class="symbol symbol-20 mr-3"><i class="fa fa-key"></i></span>
                            <span class="navi-text">Change Password</span>
                        </a>
                    </li>
                    <li class="navi-item">
                        <a class="navi-link" href="{{route('admin.logout')}}">
                            <span class="symbol symbol-20 mr-3"><i class="fa fa-power-off"></i></span>
                            <span class="navi-text">Logout</span>
                        </a>
                    </li>

                    <!--end::Item-->
                </ul>
                <!--end::Nav-->
            </div>
            <!--end::Dropdown-->
    </div>
    </div>
    <!--end::Container-->
</div>

<div class="modal fade" id="myModal" role="dialog">
    <div class="modal-dialog">
        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Change Password</h4>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            <div class="modal-body">
                <div class="alert alert-success" style="display:none"></div>
                <div class="alert alert-danger" style="display:none"></div>
                {!! Form::open(array('route' => 'admin.reset_password','class'=>'form-horizontal','id'=>'Password', 'files' => 'true')) !!}
                <fieldset class="content-group">
                    <div class="form-group {{ $errors->has('title') ? ' has-error' : '' }}">
                        <label class="control-label">old Password <span class="text-danger">*</span></label>
                        {!! Form::password('oldpassword', array('class'=>'form-control')) !!}
                    </div>
                    <div class="form-group {{ $errors->has('newpassword') ? ' has-error' : '' }}">
                        <label class="control-label">New Password <span class="text-danger">*</span></label>
                        {!! Form::password('newpassword', array('class'=>'form-control')) !!}
                    </div>
                    <div class="form-group {{ $errors->has('description') ? 'has-error' :'' }}">
                        <label class="control-label">Confirm Password <span class="text-danger">*</span></label>
                        {!! Form::password('confirmpassword', array('class'=>'form-control')) !!}
                    </div>
                </fieldset>
            </div>
            <div class="modal-footer">
                <button type="submit" class="btn btn-primary">Submit</button>
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            </div>
            {!! Form::close() !!}
        </div>
    </div>
</div>