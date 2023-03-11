@inject('helper', App\Helper\Helper)
@if(isset($visibleIn) && !empty($visibleIn))
<div class="form-group">
    <label for="" class="control-label col-lg-2">Visible In</label>
    <div class="col-lg-10">
        {!! Form::checkbox('visible_in[]', 1, Helper::visibleIn(1, $visibleIn), array('id'=> 'personal', 'class' => 'view-check')) !!}
        <label for="personal">Personal</label>&nbsp;
        {!! Form::checkbox('visible_in[]', 2, Helper::visibleIn(2, $visibleIn), array('id'=> 'business', 'class' => 'view-check')) !!}
        <label for="business">Business</label>&nbsp;
        {!! Form::checkbox('visible_in[]', 3, Helper::visibleIn(3, $visibleIn), array('id'=> 'trade', 'class' => 'view-check')) !!}
        <label for="trade">Trade</label>&nbsp;
        {!! Form::checkbox('visible_in[]', 4, Helper::visibleIn(4, $visibleIn), array('id'=> 'remittance', 'class' => 'view-check')) !!}
        <label for="remittance">Remittance</label>&nbsp;
        <!-- {!! Form::checkbox('visible_in[]', 5, Helper::visibleIn(5, $visibleIn), array('id'=> 'remittance-local', 'class' => 'view-check')) !!}
        <label for="remittance-local">Remittance Local</label>&nbsp;
        {!! Form::checkbox('visible_in[]', 6, Helper::visibleIn(6, $visibleIn), array('id'=> 'remittance-overseas', 'class' => 'view-check')) !!}
        <label for="remittance-overseas">Remittance Overseas</label>&nbsp;
        {!! Form::checkbox('visible_in[]', 7, Helper::visibleIn(7, $visibleIn), array('id'=> 'remittance-kumari', 'class' => 'view-check')) !!}
        <label for="remittance-kumari">Remittance Kumari</label>&nbsp; -->
        {!! Form::checkbox('visible_in[]', 8, Helper::visibleIn(8, $visibleIn), array('id'=> 'news', 'class' => 'view-check')) !!}
        <label for="news">News</label>&nbsp;
        {!! Form::checkbox('visible_in[]', 9, Helper::visibleIn(9, $visibleIn), array('id'=> 'csr', 'class' => 'view-check')) !!}
        <label for="csr">CSR</label>&nbsp;
        {!! Form::checkbox('visible_in[]', 10, Helper::visibleIn(10, $visibleIn), array('id'=> 'content', 'class' => 'view-check')) !!}
        <label for="content">Content</label>
    </div>
</div>
@else
<div class="form-group">
    <label for="" class="control-label col-lg-2">Visible In</label>
    <div class="col-lg-10">
        {!! Form::checkbox('visible_in[]', 1, true, array('id'=> 'personal', 'class' => 'view-check')) !!}
        <label for="personal">Personal</label>&nbsp;
        {!! Form::checkbox('visible_in[]', 2, '', array('id'=> 'business', 'class' => 'view-check')) !!}
        <label for="business">Business</label>&nbsp;
        {!! Form::checkbox('visible_in[]', 3, '', array('id'=> 'trade', 'class' => 'view-check')) !!}
        <label for="trade">Trade</label>&nbsp;
        {!! Form::checkbox('visible_in[]', 4, '', array('id'=> 'remittance', 'class' => 'view-check')) !!}
        <label for="remittance">Remittance</label>&nbsp;
        <!-- {!! Form::checkbox('visible_in[]', 5, '', array('id'=> 'remittance-local', 'class' => 'view-check')) !!}
        <label for="remittance-local">Remittance Local</label>&nbsp;
        {!! Form::checkbox('visible_in[]', 6, '', array('id'=> 'remittance-overseas', 'class' => 'view-check')) !!}
        <label for="remittance-overseas">Remittance Overseas</label>&nbsp;
        {!! Form::checkbox('visible_in[]', 7, '', array('id'=> 'remittance-kumari', 'class' => 'view-check')) !!}
        <label for="remittance-kumari">Remittance Kumari</label>&nbsp; -->
        {!! Form::checkbox('visible_in[]', 8, '', array('id'=> 'news', 'class' => 'view-check')) !!}
        <label for="news">News</label>&nbsp;
        {!! Form::checkbox('visible_in[]', 9, '', array('id'=> 'csr', 'class' => 'view-check')) !!}
        <label for="csr">CSR</label>&nbsp;
        {!! Form::checkbox('visible_in[]', 10, '', array('id'=> 'content', 'class' => 'view-check')) !!}
        <label for="content">Content</label>
    </div>
</div>
@endif