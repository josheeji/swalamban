@php
$lists = PageHelper::visibleinList();
@endphp
<div class="separator separator-dashed separator-border-2 mb-5"></div>
<div class="form-group">
    <label for="">Visible In</label>
    <div class="checkbox-list">
        @foreach($lists as $key => $title)
        <label class="checkbox checkbox-lg">
            <input type="checkbox" name="visible_in[]" value="{{ $key }}" {{ (isset($visibleIn) && PageHelper::isVisibleIn($key, $visibleIn) == true) ? 'checked' : '' }}><span></span>{{ $title }}
        </label>
        @endforeach
    </div>
</div>
<div class="separator separator-dashed separator-border-2 mb-5"></div>