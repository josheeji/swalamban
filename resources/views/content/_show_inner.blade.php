@php
    $title = $content->title;
    $title = isset($content->parent) ? $content->parent->title : $title;
    $title = isset($content->parent->parent) ? $content->parent->parent->title : $title;
    $title = isset($content->parent->parent->parent) ? $content->parent->parent->parent->title : $title;
@endphp
@php
    $menuList = PageHelper::contentHierarchy($content);
@endphp
<div class="row">
    @if (isset($content->show_children) && $content->show_children == 1)
        @if (isset($menuList) && !empty($menuList))
            @foreach ($menuList as $item)
                <div class="col-md-4  mb-4">
                    <div class="box-border">
                        <a href="{{ $item['url'] }}">{{ $item['title'] }}</a>
                    </div>
                </div>
            @endforeach
        @endif
    @endif
</div>
