@extends('layout.containerlist')

@section('title', 'Email Log')

@section('footer_js')
<script type="text/javascript">
    $(document).ready(function() {
        $('#sidebar li').removeClass('active');
        $('#sidebar a').removeClass('active');
        $('#sidebar').find('#privilege').addClass('active');
//        $('#sidebar').find('#logs').addClass('active');

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
                url: "{{ url('/email/logs/') }}" + "/" + id,
                dataType: 'json',
                success: function (response) {
                    $('#viewLogModal').modal().show();
                    $('#viewLogModal').find(".subject").text(response.emailLog.subject);
                    $('#viewLogModal').find(".to_email").text(response.emailLog.to_email);
                    $('#viewLogModal').find(".from_email").text(response.emailLog.from_email);
                    $('#viewLogModal').find(".created_at").text(response.emailLog.created_at);
                    $('#viewLogModal').find(".email_content").html('');
                    $('#viewLogModal').find(".email_content").append(response.emailLog.email_content);
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
                Email Logs
            </header>

            <div class="panel-body">
                <div class="">
                    <form role="form" method="get" action="">
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
                            <br />
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
                                    Subject
                                </th>
                                <th>
                                    To Address
                                </th>
                                <th>
                                    Type
                                </th>
                                <th width="18%">Date Time</th>
                            </tr>
                        </thead>
                        <tbody id="tablebody">
                            @forelse($logs as $index=>$log)
                            <tr class="gradeX" id="row_{{ $log->id }}" >
                                <td>
                                    {{ $loop->iteration + $logs->perPage() * ($logs->currentPage()-1) }}
                                </td>
                                <td class="subject">
                                    {{ $log->subject }}
                                </td>
                                <td class="to_email">
                                    {{ $log->to_email }}
                                </td>
                                <td class="type">
                                    {{ $log->type == 2 ? "Email Sent" : "Email Sending" }}
                                </td>

                                <td class="created_at">
                                    <a href="javascript:;" class="view-log"
                                       id="log_{!! $log->id !!}"> {{ $log->created_at->format('M d, Y h:i A') }}</a>

                                </td>
                            </tr>
                                @empty
                                <tr>
                                    <td colspan="7">Record Not Found.</td>
                                </tr>
                            @endforelse
                        </tbody>
                        <tfoot>
                            <tr>
                                <th>
                                    S N
                                </th>
                                <th>
                                    Subject
                                </th>
                                <th>
                                    To Address
                                </th>
                                <th>
                                    Type
                                </th>
                                <th>Date Time</th>
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
                <h4 class="modal-title" id="myModalLabel">View Email Log</h4>
            </div>
            <div class="modal-body">
                <table class="display table table-bordered table-condensed" id="purchase-order-detail-table">
                    <tr>
                        <td class="gray-bg" width="15%">Subject</td>
                        <td class="subject"></td>
                        <td class="gray-bg" width="15%">Created At</td>
                        <td class="created_at"></td>
                    </tr>
                    <tr>
                        <td class="gray-bg" width="15%">To Email Address</td>
                        <td class="to_email"></td>
                        <td class="gray-bg" width="15%">From Email Address</td>
                        <td class="from_email"></td>
                    </tr>
                    <tr>
                        <td class="gray-bg" width="15%">Email Content</td>
                        <td class="email_content" colspan="3"></td>
                    </tr>
                </table>
                <div class="clearfix"></div>
            </div>
        </div>
    </div>
</div>



@stop
