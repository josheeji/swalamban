@inject('helper', App\Helper\Helper)
@php
$type = request()->get('type');
@endphp
@if(isset($visibleIn) && !empty($visibleIn))
<div class="form-group" style="display: none;">
    <label for="" class="control-label col-lg-2">Visible In</label>
    <div class="col-lg-10">
        {!! Form::checkbox('visible_in[]', 1, Helper::visibleIn(1, $visibleIn), array('id'=> 'local', 'class' => 'view-check')) !!}
        <label for="local">Remit Service</label>&nbsp;
        {!! Form::checkbox('visible_in[]', 2, Helper::visibleIn(2, $visibleIn), array('id'=> 'overseas', 'class' => 'view-check')) !!}
        <label for="overseas">Overseas Alliance</label>&nbsp;
        {!! Form::checkbox('visible_in[]', 3, Helper::visibleIn(3, $visibleIn), array('id'=> 'kumari', 'class' => 'view-check-all')) !!}
        <label for="kumari">Kumari Paying Alliance</label>
    </div>
</div>
@else
<div class="form-group" style="display: none;">
    <label for="" class="control-label col-lg-2">Visible In</label>
    <div class="col-lg-10">
        @if($type == 'remit-service')
        {!! Form::checkbox('visible_in[]', 1, true, array('id'=> 'local', 'class' => 'view-check')) !!}
        <label for="local">Remit Service</label>&nbsp;
        @endif
        @if($type == 'overseas')
        {!! Form::checkbox('visible_in[]', 2, true, array('id'=> 'overseas', 'class' => 'view-check')) !!}
        <label for="overseas">Overseas Alliance</label>&nbsp;
        @endif
        @if($type == 'kumari-paying-alliance')
        {!! Form::checkbox('visible_in[]', 3, true, array('id'=> 'kumari', 'class' => 'view-check-all')) !!}
        <label for="kumari">Kumari Paying Alliance</label>
        @endif
    </div>
</div>
@endif