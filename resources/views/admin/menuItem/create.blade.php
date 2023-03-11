{!! Form::open(array('route' => ['admin.menu-item.store', $menu->id ],'class'=>'form-horizontal','id'=>'validator', 'files' => 'true')) !!}
<fieldset class="content-group">
    @php
    $languages = Helper::getLanguages();
    $isMultiLanguage = SettingHelper::setting('multi_language');
    @endphp
    @if($isMultiLanguage)
    <ul class="nav nav-tabs nav-tabs-line nav-tabs-line-2x">
        @php $count=0; @endphp
        @foreach($languages as $language)
        <li class="nav-item">
            <a class="nav-link {{ ($count == 0) ? 'active' : '' }}" data-toggle="tab" href="#aa-{{ $language['id'] }}">{{ $language['name'] ?? '' }}</a>
        </li>
        @php $count++; @endphp
        @endforeach
    </ul>
    @endif
    <div class="tab-content mt-5">
        @php $count=0; @endphp

        @foreach($languages as $language)
        <div id="aa-{{ $language['id'] }}" class="tab-pane fade in {{ ($count == 0) ? 'active show' : '' }}">
            <div class="form-group">
                <label class="control-label">Title <span class="text-danger">*</span></label>
                {!! Form::text('title['.$language['id'].']', null, array('class'=>'form-control')) !!}
            </div>
        </div>
        @php
        $count++;
        if(!$isMultiLanguage){
        break;
        }
        @endphp
        @endforeach
    </div>
    <input type="hidden" name="type" value="2" />
    <div class="clearfix"></div>

    <div class="form-group">
        <label class="control-label">URL <span class="text-danger">*</span></label>

        {!! Form::text('link_url', null, array('class'=>'form-control','placeholder'=>'URL')) !!}
    </div>

    <div class="form-group d-none">
        <label class="control-label">Icon </label>
        {!! Form::text('icon', $menuItem->icon ?? "", array('class'=>'form-control','placeholder'=>'Icon class')) !!}
    </div>

    <div class="form-group">
        <div class="checkbox-inline">
            <label class="checkbox checkbox-lg">
                <input type="checkbox" name="is_external" value="1" {{ old('is_external') == 1 ? 'checked': '' }}>
                <span></span>Is External Link</label>
        </div>
    </div>

    <div class="form-group">
        <div class="checkbox-inline">
            <label class="checkbox checkbox-lg">
                <input type="checkbox" name="link_target" value="1" {{ old('link_target') == 1 ? 'checked': '' }}>
                <span></span>Open In New tab</label>
        </div>
    </div>
    <div class="clearfix"></div>

</fieldset>
<div class="">
    <button type="submit" class="btn btn-primary btn-custom">Submit</button>
</div>
{!! Form::close() !!}