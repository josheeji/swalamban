@extends('layouts.frontend.app')
@section('title', 'Document Validation')
@section('script')
<script type="text/javascript">
    $('.bonus-form').on('submit', function(e) {
        e.preventDefault();
        $('.bonus-form button').attr('disabled', true);
        $.ajax({
            type: 'POST',
            data: $('.bonus-form').serialize(),
            url: "{{ route('bonus.search') }}",
            success: function(data) {
                $(".result-wrap").html(data);
                $('.bonus-form button').removeAttr('disabled');
            }
        });
    });
</script>
@endsection
@section('style')
<style>
    .hero-body::before {
        background: url("{{ asset('kumari/images/page-title/buildingvec.jpg') }}") no-repeat center top;
        background-size: cover
    }
</style>
@endsection
@section('content')
<!-- #header-wrap -->
<div id="header-bottom-wrap" class="is-clearfix">
    <div id="header-bottom" class="site-header-bottom">
        <div id="header-bottom-inner" class="site-header-bottom-inner ">
            <section class="hero page-title is-medium has-text-centered blog">
                <div class="hero-body">
                    <div class="container">
                        <h1>{{ trans('general.Tax for Bonus Share of former Dev Bikash Bank') }}</h1>
                        <nav class="breadcrumb has-arrow-separator" aria-label="breadcrumbs">
                            <ul>
                                <li><a href="{{ url('/') }}">{{ trans('general.home') }}</a> </li>

                                <li class="is-active"><a href="#" aria-current="page">{{ trans('general.Tax for Bonus Share of former Dev Bikash Bank') }}</a></li>
                            </ul>
                        </nav>
                    </div>
                    <!-- .hero-body -->
                </div>
                <!-- .container -->
            </section>
            <!-- .page-title -->
        </div>
        <!-- #header-bottom-inner -->
    </div>
    <!-- #header-bottom -->
    <!-- import content layouts and modules -->
    <!-- import content layouts and modules -->
    <div id="content-main-wrap" class="is-clearfix">
        <div id="content-area" class="site-content-area">
            <div id="content-area-inner" class="site-content-area-inner">
                <section class="section  has-background-primary-light is-clearfix">
                    <div class="container">
                        <div class="columns is-variable is-5 ">
                            <div class="column is-12 ">
                                <div class="box" style="background: #fff;">
                                    <p><strong>Please deposit bonus tax of former Dev Bikash Bank Limited, to account number : 1291502001020</strong></p>
                                    @include('layouts.kumari.inc.alert')
                                    <form class="default-form bonus-form" method="post" action="{{ route('bonus.search') }}">
                                        <div class="columns is-variable is-5 ">
                                            <div class="column is-6">
                                                <div class="field">
                                                    <div class="control is-expanded">
                                                        <label for="">Select</label>
                                                        <div class="select">
                                                            <select name="category_id" id="" required>
                                                                @if(isset($categories) && !empty($categories))
                                                                @foreach($categories as $category)
                                                                <option value="{{ $category->id }}">{{ $category->title }}</option>
                                                                @endforeach
                                                                @endif
                                                            </select>
                                                        </div>
                                                    </div>
                                                </div>
                                                <!-- .field -->
                                            </div>
                                            <div class="column is-3">
                                                <div class="field">
                                                    <div class="control is-expanded">
                                                        <label for="">{{ trans('contact.full-name') }}</label>
                                                        <input class="input" type="text" name="name" placeholder="" required>
                                                    </div>
                                                </div>
                                                <!-- .field -->
                                            </div>
                                            <div class="column is-3">
                                                <div class="field">
                                                    <div class="control is-expanded">
                                                        <label for="">Share holder/BOID</label>
                                                        <input class="input" type="tel" name="boid" placeholder="" required>
                                                    </div>
                                                </div>
                                                <!-- .field -->
                                            </div>
                                        </div>
                                        <div class="field ">
                                            <div class="control ">
                                                <button class="button is-rounded" type="submit">Search</button>
                                            </div>
                                        </div>
                                        <!-- .field -->
                                    </form>
                                </div>
                            </div>
                        </div>
                        <div class="columns is-variable is-5 ">
                            <div class="column is-12  result-wrap">
                                @include('bonus.search')
                            </div>
                        </div>
                        <div class="clear"></div>
                    </div>
                </section>
            </div>
        </div>
    </div>

</div>

<!-- #content-main-wrap -->

@endsection