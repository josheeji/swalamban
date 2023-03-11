<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>{!! env('APP_NAME') !!}</title>
</head>
<body>
<div style="font-size:12px; font-family:Arial, Helvetica, sans-serif; width:700px;">
    Dear {!! $requester->full_name !!},
    <br/><br/>
    The grn having items of your purchase request is created.
    <table width="700px" border="1" cellspacing="0" cellpadding="4" style=" margin:15px 0;">
        <tr>
            <td class="gray-bg">PR Number</td>
            <td>
                @foreach($grn->purchaseOrder->sourcing->purchaseRequests as $pr)
                    <a href="{!! route('purchase.request.view', $pr->id) !!}" >{!! $pr->prefix . $pr->number !!}</a> @unless(($loop->last)) , @endunless
                @endforeach
            </td>
            <td class="gray-bg">PR Date</td>
            <td>
                @foreach($grn->purchaseOrder->sourcing->purchaseRequests as $pr)
                    {!! $pr->request_date !!} @unless(($loop->last)) , @endunless
                @endforeach
            </td>
        </tr>
        <tr>
            <td class="gray-bg">Sourcing Number</td>
            <td><a href="{!! route('sourcing.view', $grn->purchaseOrder->id) !!}" target="_blank">{!! $grn->purchaseOrder->sourcing->prefix.$grn->purchaseOrder->sourcing->number  !!}</a></td>
            <td class="gray-bg">Sourcing Type</td>
            <td>{!! $grn->purchaseOrder->sourcing->sourcingType->heading !!}</td>
        </tr>
        <tr>
            <td class="gray-bg">PO Number</td>
            <td><a href="{!! route('purchase.order.view', $grn->purchaseOrder->id) !!}" target="_blank">{!! $grn->purchaseOrder->prefix.$grn->purchaseOrder->number   !!}</a></td>
            <td class="gray-bg">PO Date</td>
            <td>{!! $grn->purchaseOrder->order_date !!}</td>
        </tr>
        <tr>
            <td>GRN Number</td>
            <td>{!! $grn->prefix . $grn->number  !!}</td>
            <td>GRN Date</td>
            <td>{!! $grn->grn_date !!}</td>
        </tr>
        <tr>
            <td class="gray-bg">Received Date</td>
            <td>{!! $grn->received_date !!}</td>
            <td class="gray-bg">Received By</td>
            <td>{!! $grn->receivedBy ? $grn->receivedBy->full_name : '' !!}</td>
        </tr>
        <tr>
            <td class="gray-bg">Technical Review</td>
            <td colspan="3">{!! $grn->technical_review !!}</td>
        </tr>
    </table>

    <table width="700px" border="1" cellspacing="0" cellpadding="4">
        <tr>
            <td align="center" bgcolor="#F2DCDB"><strong>SN</strong></td>
            <td align="center" bgcolor="#F2DCDB"><strong>Office</strong></td>
            <td align="center" bgcolor="#F2DCDB"><strong>Department</strong></td>
            <td align="center" bgcolor="#F2DCDB"><strong>User</strong></td>
            <td align="center" bgcolor="#F2DCDB"><strong>Status</strong></td>
            <td align="center" bgcolor="#F2DCDB"><strong>Remarks</strong></td>
            <td align="center" bgcolor="#F2DCDB"><strong>Date</strong></td>
        </tr>
        @foreach($grn->grnLogs as $index=>$log)
            <tr>
                <td align="center">{!! $index+1 !!}</td>
                <td>{!! $log->user ? $log->user->office->office_name : '' !!}</td>
                <td>{!! $log->user ? $log->user->department->department_name : '' !!}</td>
                <td>{!! $log->user ? $log->user->full_name : '' !!}</td>
                <td>{!! array_key_exists($log->status,config()->get('constant.grn_status')) ? config()->get('constant.grn_status')[$log->status] : '' !!}</td>
                <td>{!! $log->remarks !!}</td>
                <td>{!! $log->created_at->toFormattedDateString() !!}</td>
            </tr>
        @endforeach
    </table>
    <br/><br/>

    <table width="700px" border="1" cellspacing="0" cellpadding="4">
        <tr>
            <td align="center" bgcolor="#F2DCDB"><strong>SN</strong></td>
            <td align="center" bgcolor="#F2DCDB"><strong>Item Name</strong></td>
            <td align="center" bgcolor="#F2DCDB"><strong>Specification</strong></td>
            <td align="center" bgcolor="#F2DCDB"><strong>Unit</strong></td>
            <td align="center" bgcolor="#F2DCDB"><strong>Quantity Ordered</strong></td>
            <td align="center" bgcolor="#F2DCDB"><strong>Received Quantity</strong></td>
            <td align="center" bgcolor="#F2DCDB"><strong>Variance</strong></td>
            <td align="center" bgcolor="#F2DCDB"><strong>Condition</strong></td>
        </tr>
        @foreach($grn->grnItems as $index=>$record)
            <tr>
                <td>{!! $index+1 !!}</td>
                <td>{!! $record->item->item_name !!}</td>
                <td>{!! $record->purchaseOrderItem->specification !!}</td>
                <td>{!! $record->unit ? $record->unit->unit : '' !!}</td>
                <td>{!! $record->ordered_quantity !!}</td>
                <td>{!! $record->purchaseOrderItem->grnItems->sum('received_quantity') !!}</td>
                <td>{!! $record->ordered_quantity - $record->purchaseOrderItem->grnItems->sum('received_quantity') !!}</td>
                <td>{!! $record->conditions !!}</td>
            </tr>
        @endforeach
    </table>

    <br/><br/>

    Kind Regards, <br/>
    {!! $sender ? $sender->full_name : '' !!}<br/>
    {!! $sender->department ? $sender->department->department_name : '' !!} Department<br/>
    {!! $sender->office ? $sender->office->office_name : env('MAIL_FROM_NAME') !!}
</div>
</body>
</html>