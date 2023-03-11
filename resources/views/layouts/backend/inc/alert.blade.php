@if (Session::has('flash_info'))
<div class="alert alert-custom alert-info no-border">
    <div class="alert-icon"><i class="icon fa fa-info"></i></div>
    <div class="alert-text">{!! Session::get('flash_info') !!}</div>
    <div class="alert-close">
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true"><i class="ki ki-close"></i></span>
        </button>
    </div>
</div>
@endif

@if(Session::has('flash_success'))
<div class="alert alert-custom alert-success no-border">
    <div class="alert-icon"><i class="icon fa fa-check"></i></div>
    <div class="alert-text">{!! Session::get('flash_success') !!}</div>
    <div class="alert-close">
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true"><i class="ki ki-close"></i></span>
        </button>
    </div>
</div>
@endif

@if(Session::has('flash_notice'))
<div class="alert alert-custom alert-warning no-border">
    <div class="alert-icon"><i class="icon fa fa-check"></i></div>
    <div class="alert-text">{!! Session::get('flash_notice') !!}</div>
    <div class="alert-close">
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true"><i class="ki ki-close"></i></span>
        </button>
    </div>
</div>
@endif

@if (Session::has('flash_error'))
<div class="alert alert-custom alert-danger no-border">
    <div class="alert-icon"><i class="icon fa fa-warning"></i></div>
    <div class="alert-text">{!! Session::get('flash_error') !!}</div>
    <div class="alert-close">
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true"><i class="ki ki-close"></i></span>
        </button>
    </div>
</div>
@endif

@if (Session::has('flash_custom'))
<div class="alert alert-custom alert-white alert-shadow fade show" role="alert">
    <div class="alert-icon"><i class="icon fa fa-warning"></i></div>
    <div class="alert-text">{!! Session::get('flash_custom') !!}</div>
    <div class="alert-close">
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true"><i class="ki ki-close"></i></span>
        </button>
    </div>
</div>
@endif

@if (session('status'))
<div class="alert alert-custom alert-info no-border">
    <div class="alert-icon"><i class="icon fa fa-check"></i></div>
    <div class="alert-text">{!! session('status') !!}</div>
    <div class="alert-close">
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true"><i class="ki ki-close"></i></span>
        </button>
    </div>
</div>
@endif

@if ($errors->any())
<div class="alert alert-custom alert-danger no-border">
    <div class="alert-icon"><i class="icon fa fa-ban"></i></div>
    <div class="alert-text">
        <ul>
            @foreach ($errors->all() as $error)
            <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
    <div class="alert-close">
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true"><i class="ki ki-close"></i></span>
        </button>
    </div>
</div>
@endif