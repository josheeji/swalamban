@extends('layout.containerlist')

@section('title', 'Api Log')

@section('footer_js')
    <script type="text/javascript">
        $(document).ready(function () {
            $('#sidebar li').removeClass('active');
            $('#sidebar a').removeClass('active');
            $('#sidebar').find('#privilege').addClass('active');
            $('#sidebar').find('#logs').addClass('active');

            $('.date').datepicker({
                format: 'yyyy-mm-dd',
                autoclose: true,
                endDate: '0d'
            });

            $('#tablebody').on('click', '.view-log', function (e) {
                e.preventDefault();
                $object = $(this);
                var id = $object.attr('id').replace(/^\D+/g, '');
                $.ajax({
                    type: "GET",
                    url: "{{ url('api/logs/') }}" + "/" + id,
                    dataType: 'json',
                    success: function (response) {
                        $('#viewLogModal').modal().show();
                        $('#viewLogModal').find(".full_name").text(response.user.full_name);
                        $('#viewLogModal').find(".email_address").text(response.user.email_address);
                        $('#viewLogModal').find(".ip_address").text(response.log.ip_address);
                        $('#viewLogModal').find(".created_at").text(response.log.created_at);
                        $('#viewLogModal').find(".route").text(response.log.route);

                        $('#viewLogModal').find(".description").text(response.log.description);
                    },
                    error: function (e) {
                        Swal.fire('Oops...', 'Something went wrong!', 'error');
                    }
                });
            });
        });
    </script>
@endsection
@section('dynamicdata')

    <div class="row">
        <div class="col-sm-12">
            <section class="panel">
                <header class="panel-heading">
                    Api Logs
                </header>

                <div class="panel-body">
                    <div class="">
                        <form role="form" method="get" action="" autocomplete="off">
                            <div class="col-xs-3 form-group">
                                <label>Period From</label>
                                <input class="form-control date" type="text" name="from_date" placeholder="Start date"
                                       value="{{ (!empty($requestData['from_date'])) ? $requestData['from_date'] : date('Y-m-d') }}"/>
                            </div>
                            <div class="col-xs-3 form-group">
                                <label>Period To</label>
                                <input class="form-control date" type="text" placeholder="end date" name="to_date"
                                       value="{{ (!empty($requestData['to_date'])) ? $requestData['to_date'] : date('Y-m-d') }}"/>
                            </div>
                            <div class="col-xs-3 form-group">
                                <label>User</label>
                                <select name="user_id" class="form-control">
                                    <option value="">All</option>
                                    @foreach($users as $user)
                                        <option value="{{ $user->id }}"
                                                @if(!empty($requestData['user_id'])) @if($user->id == $requestData['user_id']) selected="selected" @endif @endif>{{ $user->full_name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-xs-3 form-group">
                                <label>Description (Like)</label>
                                <input class="form-control" type="text" name="description"
                                       value="{{ (!empty($requestData['description'])) ? $requestData['description'] : '' }}"/>
                            </div>
                            <div class="col-xs-3 form-group">
                                <br/>
                                <button type="submit" class="btn btn-info">Search</button>
                            </div>
                            <div class="clearfix"></div>
                        </form>
                    </div>
                </div>

                <div class="panel-body">
                    <div class="adv-table editable-table ">
                        <div class="btn-group">
                        </div>
                        <table class="display table table-bordered table-striped" id="log-table">
                            <thead>
                            <tr>
                                <th>
                                    S N
                                </th>
                                <th>
                                    User
                                </th>
                                {{--                                <th>--}}
                                {{--                                    Original User--}}
                                {{--                                </th>--}}
                                <th>
                                    IP Address
                                </th>
                                <th width="18%">
                                    Date Time
                                </th>
                                <th>
                                    Description
                                </th>
                            </tr>
                            </thead>
                            <tbody id="tablebody">
                            @forelse($logs as $index=>$log)
                                <tr class="gradeX" id="row_{{ $log->id }}">
                                    <td>
                                        {{ $loop->iteration + $logs->perPage() * ($logs->currentPage()-1) }}
                                    </td>
                                    <td class="user">
                                        {{ $log->getUserFullNameAndEmail() }}
                                    </td>
                                    {{--                                    <td class="user">--}}
                                    {{--                                        {{ $log->getOriginalUserFullNameAndEmail() }}--}}
                                    {{--                                    </td>--}}
                                    <td class="ip_address">
                                        {{ $log->ip_address }}
                                    </td>
                                    <td class="created_at">
                                        {{ $log->getCreatedAt() }}
                                    </td>
                                    <td class="description">
                                        <a href="javascript:;" class="view-log"
                                           id="log_{!! $log->id !!}">{{ $log->description }}</a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6">Record Not Found.</td>
                                </tr>
                            @endforelse
                            </tbody>
                            <tfoot>
                            <tr>
                            <tr>
                                <th>
                                    S N
                                </th>
                                <th>
                                    User
                                </th>
                                <th>
                                    IP Address
                                </th>
                                <th>
                                    Date Time
                                </th>
                                <th>
                                    Description
                                </th>
                            </tr>
                            </tr>
                            </tfoot>
                        </table>

                        {!! $logs->appends($requestData)->links() !!}

                    </div>
                </div>
            </section>
        </div>
    </div>

    <!-- Modal Start -->
    <div class="modal fade" id="viewLogModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel"
         data-keyboard="false" data-backdrop="static">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                                aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title" id="myModalLabel">View Log</h4>
                </div>
                <div class="modal-body">
                    <table class="display table table-bordered table-condensed" id="purchase-order-detail-table">
                        <tr>
                            <td class="gray-bg" width="15%">Full Name</td>
                            <td class="full_name"></td>

                        </tr>
                        <tr>
                            <td class="gray-bg" width="15%">Email Address</td>
                            <td class="email_address"></td>
                        </tr>
                        <tr>
                            <td class="gray-bg" width="15%">IP Address</td>
                            <td class="ip_address"></td>
                        </tr>
                        <tr>
                            <td class="gray-bg" width="15%">Created At</td>
                            <td class="created_at"></td>
                        </tr>

                        <tr>
                            <td class="gray-bg" width="15%">Route</td>
                            <td class="route"></td>
                        </tr>
                        <tr>
                            <td class="gray-bg" width="15%">Description</td>
                            <td class="description" colspan="3"></td>
                        </tr>
                    </table>
                    <div class="clearfix"></div>
                </div>
            </div>
        </div>
    </div>





@stop
