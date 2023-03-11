@if (Session::has('flash_info'))
<div class="alert alert-info alert-dismissible" role="alert">
    <strong><i class="icon fa fa-info mr-2"></i></strong> {!! Session::get('flash_success') !!}
    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
</div>
@endif

@if(Session::has('flash_success'))
<div class="alert alert-success alert-dismissible" role="alert">
    <strong><i class="icon fa fa-check mr-2"></i></strong> {!! Session::get('flash_success') !!}
    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
</div>
@endif

@if(Session::has('flash_notice'))
<div class="alert alert-info alert-dismissible" role="alert">
    <strong><i class="icon fa fa-check mr-2"></i></strong> {!! Session::get('flash_notice') !!}
    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
</div>
@endif

@if (Session::has('flash_error'))
<div class="alert alert-danger alert-dismissible" role="alert">
    <strong><i class="icon fa fa-warning mr-2"></i></strong> {!! Session::get('flash_error') !!}
    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
</div>
@endif

@if (Session::has('flash_custom'))
<div class="alert alert-secondary alert-dismissible" role="alert">
    <strong><i class="icon fa fa-warning mr-2"></i></strong> {!! Session::get('flash_custom') !!}
    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
</div>
@endif

@if (session('status'))
<div class="alert alert-info alert-dismissible" role="alert">
    <strong><i class="icon fa fa-check mr-2"></i></strong> {!! session('status') !!}
    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
</div>
@endif

@if ($errors->any())
<div class="alert alert-danger alert-dismissible" role="alert">
    <strong><i class="icon fa fa-ban mr-2"></i></strong> You should check in on some of these field(s) below.
    <hr>
    <ul class="mb-0">
        @foreach ($errors->all() as $error)
        <li>{{ $error }}</li>
        @endforeach
    </ul>
    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
</div>
@endif