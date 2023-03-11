@extends('layouts.frontend.app')
@section('title', 'Careers')
@section('style')

@endsection
@section('script')
    <script type="text/javascript">
        $('#refresh').click(function() {
            $.ajax({
                type: 'GET',
                url: "{{ url('refreshcaptcha') }}",
                success: function(data) {
                    $(".captcha span").html(data.captcha);
                }
            });
        });
    </script>
@endsection
@section('page-banner')

@endsection
@section('content')

    <section id="pagetitle"
        style="background-image:url({{ isset($menu->image) ? asset('storage/' . @$menu->image) : asset('swabalamban/images/titlebg.jpg') }});">
        <div class="container">
            <h1>{{ @$menu->title ?? 'Career' }}
            </h1>
            <ul>
                <li>
                    <a href="{{ route('home.index') }}">Home
                    </a>
                    <i class="fas fa-chevron-right">
                    </i>
                </li>
                <li>{{ @$menu->title ?? 'Career' }}
                </li>
            </ul>
        </div>
    </section>
    <section id="inner-contanier" class="section-padding">
        <div class="container">
            <div class="row">
                @include('layouts.frontend.inc.socialmedia')
                @include('layouts.frontend.inc.comments')
                <div class="col-xs-12 col-sm-12 col-md-12 col-lg-8 col-xl-8">
                    <div class="leftsection">
                        <div class="table-responsive">
                            <table class="table table-striped downloadtable">
                                <thead class="table-heading">
                                    <tr>
                                        <th class="heading text-center">SN
                                        </th>
                                        <th class="heading">Title
                                        </th>
                                        <th class="heading">Posted Date
                                        </th>
                                        <th class="heading">Deadline
                                        </th>
                                        <th class="heading text-center">Opening
                                        </th>
                                        <th class="heading">
                                            File
                                        </th>
                                        <th></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @if (isset($careers) && !empty($careers) && !$careers->isEmpty())

                                        @foreach ($careers as $key => $career)
                                            <tr>
                                                <td class="text-center">{{++$key}}
                                                </td>
                                                <td>
                                                    <a href="" class="d-title">{!! $career->title !!}
                                                    </a>
                                                </td>
                                                <td>{!! Helper::formatDate($career->publish_from) !!}
                                                </td>
                                                <td>{!! Helper::formatDate($career->publish_to) !!}
                                                </td>
                                                <td class="text-center">
                                                    {!! $career->opening ?? '-' !!}
                                                </td>
                                                <td>
                                                    <a target="_blank" href="{{ asset('storage/' . $career->file) }}"
                                                         target="_blank" download><i
                                                            class="fa fa-download "></i>
                                                    </a>
                                                </td>
                                                <td class="text-center">
                                                    <a href="{{ route('career.show',$career->slug) }}" class="loding">Apply
                                                    </a>
                                                </td>
                                            </tr>
                                        @endforeach
                                    @else
                                    <tr>
                                        <td colspan="7">No career opportunities available at the momemt.</td>
                                    </tr>
                                    @endif
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                <div class="col-xs-12 col-sm-12 col-md-12 col-lg-4 col-xl-4">
                    <div class="latest-notice">
                        <div class="cns-title">
                            <a href="javascript:void(0);" class="">{{trans('general.latest-notice')}}
                            </a>
                        </div>
                        @foreach ($notices as $item)
                            <div class="notice-1">

                                <div class="notice-date">
                                    @isset($item->end_date)
                                    <span>Expires On {{ date('d M, Y', strtotime($item->end_date)) }}
                                    </span>
                                    @endisset
                                    {{-- <a href="{{ asset('storage/' . $item->link) }}" class="time-update"> --}}
                                        {{ $item->start_date->diffForHumans() }}
                                    {{-- </a> --}}
                                </div>
                                <div class="notice">
                                    <a href="{{ asset('storage/' . $item->link) }}" class="">{{ $item->title }}
                                    </a>
                                    @if (now()->subDays(7)->format('y-m-d') <= $item->start_date->format('y-m-d'))
                                        <span>New
                                        </span>
                                    @endif

                                </div>
                                <div class="dotted">
                                </div>
                            </div>
                        @endforeach
                    </div>
                    <div class="cns-title">
                        <a href="javascript:void(0);" class="">Download Categories
                        </a>
                        <ul class="list-categories">
                            @foreach ($categories as $item)
                                <li>
                                    <a href="{{ route('download.show', $item->slug) }}">{{ $item->title }}
                                    </a>
                                </li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            </div>
        </div>
        </div>
        </div>
    </section>
@endsection
