@extends('layouts.frontend.app')
@section('title', 'Check Bank Guarantee')
@section('style')
@endsection
@section('script')
@endsection
@section('page-banner')
    <section class="bannertop">
        <div class="container">
            <div class="bannerimg parallax">
                <h1>{{ trans('general.bank-guarantee') }}</h1>
                <div class="banner-txt"></div>
                <ul class="header-bottom-navi">
                    <li>
                        <a href="{{ route('home.index') }}">{{ trans('general.home') }}</a><i
                            class="fas fa-chevron-right"></i>
                    </li>
                    <li><a href="javascript:void(0);">{{ trans('general.bank-guarantee') }}</a></li>
                </ul>
            </div>
        </div>
    </section>
@endsection
@section('content')
    {{-- <section class="maininner-container ">
        <div class="container">
            <div class="row">
                <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 padding-right maintxt">
                    <h3 class="widget-title ">{{ trans('general.bank-guarantee') }}</h3>

                    <div class="card-body">
                        <div class="table-responsive"
                            id="kt_datatable">
                            <table id="check-bank-guarantee" class="table table-striped table-bordered" style="width:1200px">
                                <thead>
                                    <tr>
                                        <th width="50px">S.No.</th>
                                        <th>Branch Code</th>
                                        <th>Branch Name</th>
                                        <th>Ref. No.</th>
                                        <th>Applicant</th>
                                        <th>Beneficiary</th>
                                        <th>Purpose</th>
                                        <th>LCY Amount</th>
                                        <th>Issue Date</th>
                                        <th>Expiry Date</th>

                                    </tr>
                                </thead>
                                <tfoot>
                                    <tr>
                                        <th width="50px">S.No.</th>
                                        <th>Branch Code</th>
                                        <th>Branch Name</th>
                                        <th>Ref. No.</th>
                                        <th>Applicant</th>
                                        <th>Beneficiary</th>
                                        <th>Purpose</th>
                                        <th>LCY Amount</th>
                                        <th>Issue Date</th>
                                        <th>Expiry Date</th>
                                    </tr>
                                </tfoot>
                                <tbody>
                                    @if (isset($data) && $data->isEmpty())
                                        <tr>
                                            <td colspan="9">No record found.</td>
                                        </tr>
                                    @else
                                        @php
                                            $index = $data->hasPages() ? $data->firstItem() : 1;
                                        @endphp
                                        @foreach ($data as $key => $item)
                                            <tr>
                                                <td>{{ $index++ }}</td>
                                                <td>{{ $item->branch_code }}</td>
                                                <td>{{ $item->branch_name }}</td>
                                                <td>{{ $item->ref_no }}</td>
                                                <td>{{ $item->applicant }}</td>
                                                <td>{{ $item->beneficiary }}</td>
                                                <td>{{ $item->purpose }}</td>
                                                <td>{{ $item->lcy_amount }}</td>
                                                <td>{{ $item->issued_date }}</td>
                                                <td>{{ $item->expiary_date }}</td>
                                            </tr>
                                        @endforeach
                                    @endif
                                </tbody>
                            </table>
                            @if (isset($data) && !$data->isEmpty())
                                {!! $data->appends(request()->query())->links('admin.inc.pagination') !!}
                            @endif
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </section> --}}

    <section class="maininner-container">
        <div class="container">
            <div class="row">
                <div class="col-xs-12 col-sm-12 col-md-12 maintxt">
                    <form class="default-form bonus-form" method="post"
                        action="{{ route('check-bank-guarantee.result') }}">
                        <div class="row">
                            <div class="col-xs-12 col-sm-12 col-md-6 col-lg-6 form-group">
                                <label for="ref"><strong> Reference Number </strong> </label>
                                <input class="form-control" id="ref" type="text" name="ref_number"
                                    placeholder="Enter your reference number" required>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-xs-12 col-sm-12 col-md-6 col-lg-6 form-group">
                                <input id="submit-btn" type="submit" class="btn mr10">
                                <button class="btn" type="reset">Clear</button>
                            </div>

                        </div>
                    </form>
                    <div class="result-wrap">
                        @if (isset($data))

                            @if (!empty($data))
                                <p></p>
                            @else

                            @endif
                        @elseif(isset($post) && $post)
                            <p style="color: rgb(224, 4, 4);">Sorry, Entered Reference Number does not exist!</p>
                        @endif

                        <div class="table-responsive">
                            <table class="table table-stripped" id="card-table">
                                <thead>
                                    <tr>
                                        <th valign="top">Branch Code</th>
                                        <th valign="top">Branch Name</th>
                                        <th valign="top">Ref. Num.</th>
                                        <th valign="top">Applicant</th>
                                        <th valign="top">Beneficary</th>
                                        <th valign="top">Purpose</th>
                                        <th valign="top">LCY Amount</th>
                                        <th valign="top">Issued Date</th>
                                        <th valign="top">Expiry Date</th>

                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        @if (isset($data))
                                            <td valign="top" style="height:50px;"><strong>{!! isset($data) && !empty($data) ? $data->branch_code : '' !!}</strong>
                                            </td>
                                            <td valign="top" style="height:50px;"><strong>{!! isset($data) && !empty($data) ? $data->branch_name : '' !!}</strong>
                                            </td>
                                            <td valign="top"><strong>{!! isset($data) && !empty($data) ? $data->ref_no : '' !!}</strong></td>
                                            <td valign="top"><strong>{!! isset($data) && !empty($data) ? $data->applicant : '' !!}</strong></td>
                                            <td valign="top"><strong>{!! isset($data) && !empty($data) ? $data->beneficiary : '' !!}</strong></td>
                                            <td valign="top"><strong>{!! isset($data) && !empty($data) ? $data->purpose : '' !!}</strong></td>
                                            <td valign="top"><strong>{!! isset($data) && !empty($data) ? $data->lcy_amount : '' !!}</strong></td>
                                            <td valign="top"><strong>{!! isset($data) && !empty($data) ? $data->issued_date : '' !!}</strong></td>
                                            <td valign="top"><strong>{!! isset($data) && !empty($data) ? $data->expiary_date : '' !!}</strong></td>
                                        @else
                                            <td colspan="6">No records found.</td>
                                        @endif
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
