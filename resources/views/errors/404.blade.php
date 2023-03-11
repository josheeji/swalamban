@extends('layouts.frontend.app')
@section('content')
<section id="inner-content">
    <div class="container">
        <div class="row">
            <div class="col-12 text-center" style="padding: 40px 0;">
            
            <img class="no-page" src="{{ asset('swabalamban/images/404.png') }}" alt="">

                <p class="nopage-txt">We are sorry, the page you requested could not be found. Please go back to the homepage.</p>

                <a href="{{ url('/') }}" class="btn"><u>Go To Homepage</u></a>
            </div>
        </div>

    </div>
</section>
@endsection