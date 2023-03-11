{!! Form::open(array('route' => ['admin.menu-item.store', $menu->id ],'class'=>'form-horizontal','id'=>'validator', 'files' => 'true')) !!}
<fieldset class="content-group" style="max-height: 500px; overflow-y: scroll; overflow-x:hidden">
    <input type="hidden" name="type" value="1" />
    <input type="hidden" name="module_id" value="{{ $module->id }}" />
    @if(!empty($dataProvider))
    @foreach($dataProvider as $data)
    <div class="checkbox">
        <label>
            <input type="checkbox" name="existing_record_id[]" value="{{ $data->id }}" {{ old('link_target') == 1 ? 'checked': '' }}>
            {{ $data->title }}
        </label>
    </div>
    @endforeach
    @endif
</fieldset>
<div class="">
    <button type="submit" class="btn btn-primary btn-custom">Submit</button>
</div>
{!! Form::close() !!}