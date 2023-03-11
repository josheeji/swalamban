<div class="content-block" data-index="{{ $index }}">
    <div class=" bg-light mb-2 p-3">
        <div class="clearfix">
            <h5 class="panel-title">Block<a class="heading-elements-toggle"><i class="icon-more"></i></a>
                <a href="javascript:void(0)" class="btn btn-danger btn-icon btn-sm btn-circle float-right btn-remove-block" data-id="{{ isset($block) ? $block->id : '' }}" data-index="{{ $index }}"><i class="la la-trash"></i></a>
            </h5>
        </div>
        <div class="form-group">
            <label class="control-label">Image</label>
            <small class="text-dark-50 float-right">Preferred size: 1920x738px or 1300x500px</small>
            <input type="file" class="" name="blocks[{{ $index }}][image]" id="image-file-{{ $index }}">
        </div>
        <div class="col-6 text-center img-wrap-{{ $index }}">
            @if(file_exists('storage/'. $block->image) && $block->image != '')
            <img src="{{ asset('storage/'.$block->image) }}">
            <a href="javascript:void(0);" class="btn btn-danger btn-remove-block-image" data-id="{{ $block->id }}" data-index="{{ $index }}">Remove image</a>
            @endif
        </div>
        @php
        $languages = Helper::getLanguages();
        $isMultiLanguage = SettingHelper::setting('multi_language');
        @endphp
        @if($isMultiLanguage)
        <ul class="nav nav-tabs nav-tabs-line nav-tabs-line-2x">
            @php
            $count=0;
            @endphp
            @foreach($languages as $language)
            <li class="nav-item"><a class="nav-link {{ ($count == 0) ? 'active' : '' }}" data-toggle="tab" href="#aa-{{ $index }}-{{ $language['id'] }}">{{ $language['name'] ?? '' }}</a></li>
            @php
            $count++;
            @endphp
            @endforeach
        </ul>
        @endif
        <div class="tab-content mt-5">
            @php $count=0; @endphp
            @foreach($languages as $language)
            @php
            $multiContent = $block->multiBlock($block->id, $language['id']);
            @endphp
            <div id="aa-{{ $index }}-{{ $language['id'] }}" class="tab-pane fade in {{ ($count == 0) ? 'active show' : '' }}">
                <input type="hidden" name="blocks[{{ $index }}][{{ $language['id'] }}][id]" value="{{ isset($multiContent) && $language['id'] == $multiContent->language_id ? $multiContent->id : $block->id }}">
                <div class="form-group">
                    <label class="control-label">Description</label>
                    <textarea name="blocks[{{ $index }}][{{ $language['id'] }}][description]" id="" class="form-control editor" cols="10" rows="5">{{ isset($multiContent) && $language['id'] == $multiContent->language_id ? $multiContent->description : $block->description }}</textarea>
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
    </div>
</div>
<script>
    $('.editor').summernote({
        height: 250,
        toolbar: [
            ['style', ['style']],
            ['font', ['bold', 'underline', 'clear']],
            ['color', ['color']],
            ['para', ['ul', 'ol', 'paragraph']],
            ['table', ['table']],
            ['insert', ['link', 'lfm', 'video']],
            ['view', ['fullscreen', 'codeview', 'help']],
        ],
        buttons: {
            lfm: LFMButton
        }
    });
</script>