@extends('layouts.frontend.app')
@section('title', $member->name)
@section('header_js')
@endsection
@section('main')


<!-- .site-header -->

<!-- #main-navigation -->
<div id="custom-header">
    <div class="custom-header-content">
        <div class="container">
            <h1 class="page-title">{{$member->name}}</h1>
            <div id="breadcrumb">
                <div aria-label="Breadcrumbs" class="breadcrumbs breadcrumb-trail">
                    <ul class="trail-items">
                        <li class="trail-item trail-begin"><a href="{{route('home')}}" rel="home"><span>Home</span></a></li>

                        <li class="trail-item trail-end"><span>{{$member->name}}</span></li>
                    </ul>
                </div> <!-- .breadcrumbs -->
            </div> <!-- #breadcrumb -->
        </div> <!-- .container -->
    </div> <!-- .custom-header-content -->
</div>
<div id="content" class="site-content global-layout-right-sidebar">
    <div class="container">
        <div class="inner-wrapper">
            <div id="primary" class="content-area">

                <main id="main" class="site-main">



                    <h3 class="service-item-title" style="font-size: 20px"><a href="{{route('member.show',[$member->id])}}">
                            {{$member->name}}
                        </a></h3>

                    <p>{!! $member->description !!}</p></br>
                    <div class="image" style=" padding-bottom: 15px; width:100px; float:left; margin-right:15px;">
                        @if(file_exists('storage/'.$member->image) && $member->image !== '')
                        <img style=" width:150px; height:100px;" src="{{asset('storage/'.$member->image)}}" alt="{{$member->name}}">
                        @endif
                    </div><span>{{$member->contact}}</span>



                </main> <!-- #main -->

            </div> <!-- #primary -->

            <div id="sidebar-primary" class="sidebar widget-area">
                <div class="sidebar-widget-wrapper">

                    <aside class="widget recent-posts-widget">
                        <h3 class="widget-title"><span class="widget-title-wrapper">Popular News</span></h3>
                        @foreach($news as $new)
                        <div class="recent-post-item">
                            @if(file_exists('storage/'.$new->image) && $new->image !== '')
                            <img class="alignleft" src="{{asset('storage/'.$new->image)}}" alt="{{$new->title}}">
                            @endif
                            <h4><a href="{{route('news.show',[$new->slug])}}">{{ str_limit($new->title,'30','....') }}</a></h4>
                            <p> @php $date = Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $new->created_at)->month @endphp

                                {{ date("F", mktime(0, 0, 0, $date, 10))}} &nbsp;{{Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $new->created_at)->day}},
                                {{Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $new->created_at)->year}}</p>
                        </div>
                        @endforeach

                    </aside>

                </div> <!-- .sidebar-widget-wrapper -->
            </div> <!-- .sidebar -->

        </div> <!-- #inner-wrapper -->
    </div><!-- .container -->
</div><!-- .custom-header -->
<!-- #content-->
@endsection

@section('scripts')


<script type="text/javascript">
    $('.carousel').carousel({
        interval: 2000
    })

    $('#room-id').on('change', function() {
        var roomId = $('#room-id').val();

        $.get("<?php echo url('/') ?>" + "/roomlist/" + roomId, function(data, status) {
            if (data != 'no data') {
                var numberOfRooms = data.number_of_rooms;

                if (numberOfRooms != 0) {
                    var options = '';
                    for (i = 1; i <= numberOfRooms; i++) {
                        options += '<option value="' + i + '">' + i + '</option>';
                    }

                    $('#number-of-rooms option').remove();
                    $('#number-of-rooms').append(options);
                } else {
                    $('#number-of-rooms option').remove();
                    $('#number-of-rooms').append('<option>' + 0 + '<option>');
                }

            }
        });
    });
</script>
@endsection