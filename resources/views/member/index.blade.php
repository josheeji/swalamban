@extends('layouts.frontend.app')
@section('title', 'Members')
@section('content')
    <section class="bannertop">
        <div class="container">
            <div class="bannerimg parallax">
                <h1>Members</h1>
            </div>
        </div>
    </section>
    <section class="bredcrum-inner">
        <div class="container">
            <div class="titleblock-inner">
                <ul>
                    <li>
                        <a href="{{ route('home.index') }}"><i class="fas fa-home"></i> Home</a> <i
                            class="fas fa-chevron-right"></i>
                    </li>
                    <li>Members</li>
                </ul>
            </div>
        </div>
    </section>
    <section class="maininner-container">
        <div class="container">
            <div class="row">
                <div class="col-xs-12 col-sm-12 col-md-12 maintxt">
                    <div class="row">
                        @if (isset($members) && !$members->isEmpty())
                            @foreach ($members as $member)
                                <div class="col-xs-12 col-sm-6 col-md-3 col-ld-3 ">
                                    <div class="member-block">
                                        <div class="member-pic">
                                            @if (file_exists('storage/' . $member->photo) && $member->photo !== '')
                                                <img src="{{ asset('storage/' . $member->photo) }}" alt="{{ $member->name }}">
                                            @endif
                                        </div>
                                        <div class="member-names">
                                            <div>{{ $member->full_name }}</div>
                                            <span>{{ $member->designation }} </span>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        @else
                            <div class="col-xs-12">
                                No record found.
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
