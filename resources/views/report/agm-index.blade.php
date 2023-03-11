@inject('helper', 'App\Helper\Helper')
@inject('layout', 'App\Helper\LayoutHelper')
@inject('menuRepo', 'App\Repositories\MenuRepository')
@inject('helper', 'App\Helper\Helper')
@inject('layout', 'App\Helper\LayoutHelper')
@inject('menuRepo', 'App\Repositories\MenuRepository')
@extends('layouts.frontend.app')
@section('title', 'AGM Reports')
@section('style')

<style>
    .hero-body::before {
        background: url("{{ asset('kumari/images/page-title/buildingvec.jpg') }}") no-repeat center top;
        background-size: cover
    }

    .allMaps {
        height: 800px;
        width: 400px;
    }

    .heading-title {
        text-align: left;
        margin-bottom: 1.5rem !important
    }

    hr {
        margin-bottom: 60px;
        margin-top: 60px;
    }

    .alliance-sep {
        height: 140px;
        border-left: 1px solid #ddd;
        position: absolute;
        left: 50%;
        width: 1px;
    }

    .faq-section .tab-block .widget-title {
        display: none;
    }

    @media (max-width:767px) {

        .faq-section .tab-block {
            display: block !important;
        }

        .faq-section .tab-block .widget-title {
            display: block;
        }
    }
</style>
@endsection

@section('script')

@endsection
@section('content')

<section class="bannertop">
  <div class="container">
    <div class="bannerimg parallax">
      <h1>{{ trans('general.agm-reports') }}</h1>
    </div>
  </div>
</section>
<section class="bredcrum-inner">
  <div class="container">
    <div class="titleblock-inner">
      <ul>
        <li>
          <a href="{{ route('home.index') }}"><i class="fas fa-home"></i> {{ trans('general.home') }}</a> <i class="fas fa-chevron-right"></i>
        </li>
        <li><a href="#!">Notice & Publications <i class="fas fa-chevron-right"></i></a></li>
        <li>{{ trans('general.agm-reports') }}</li>
        
      </ul>
    </div>
  </div>
</section>

<section class="maininner-container">
    <div class="container">
        <div class="row">
            <div class="col-xs-12 col-sm-12 col-md-9 maintxt">
                
                <div class="table-responsive">
                    <table class="tablestyle">
                        @foreach($reports as $index => $item)
                        <tr>
                            <td width="6%">{{ $index + 1 }}</td>
                            <td width="79%">
                                @if(file_exists('storage/'.$item->file) && $item->file != '')
                                <a href="{{ asset('storage/'.$item->file) }}" target="_blank">{{ $item->title }}</a>
                                @else
                                <a href="#!">{{ $item->title }}</a>
                                @endif
                            </td>
                            <td width="15%">
                                @if(file_exists('storage/'.$item->file) && $item->file != '')
                                <a href="{{ asset('storage/'.$item->file) }}" target="_blank">Download</a>
                                @else
                                <a href="#!">Download</a>
                                @endif
                            </td>
                        </tr>
                        @endforeach
                    </table>
                </div>
                
            </div>

            <div class="col-xs-12 col-sm-12 col-md-3 mainsidewrapper">
                <div class="row">
                    <div class="col-xs-12 col-sm-6 col-md-12">
                        <div class="sidebox">
                            <h2>
                                {{ trans('general.press-release') }} <a href="{{ route('press-release') }}" class="customview">View All <i class="fas fa-chevron-right"></i></a>
                            </h2>
                            @php
                            $pressReleases = PageHelper::pressReleases();
                            @endphp
                            @if(isset($pressReleases))
                            <ul class="noticesection">
                                @foreach($pressReleases as $pressRelease)
                                <li>
                                    <div class="noticedate">{{ Helper::formatDate($pressRelease->start_date, 13) }}<span>{{ Helper::formatDate($pressRelease->start_date, 14) }}</span></div>
                                    <a href="{{ route('press-release.show', $pressRelease->slug) }}">{{ $pressRelease->title }}</a>
                                </li>
                                @endforeach
                            </ul>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

@endsection