@php
    $title = $content->title;
    $title = isset($content->parent) ? $content->parent->title : $title;
    $title = isset($content->parent->parent) ? $content->parent->parent->title : $title;
    $title = isset($content->parent->parent->parent) ? $content->parent->parent->parent->title : $title;
@endphp
@php
    $menuList = PageHelper::contentHierarchy($content);
@endphp

@if (isset($content->show_children) && $content->show_children == 1)
    @if (isset($menuList) && !empty($menuList))
        {{-- <div class="side-menu">
            <ul>
                @foreach ($menuList as $item)
                    <li><a href="{{ $item['url'] }}">{{ $item['title'] }}</a></li>
                @endforeach
            </ul>
            <div class="clear"></div>
        </div> --}}
        <div class="cns-title">
            <a href="javascript:void(0);" class="">Other Links
            </a>
            <ul class="list-categories">
                @foreach ($menuList as $item)
                <li><a href="{{ $item['url'] }}">{{ $item['title'] }}</a></li>
            @endforeach
            </ul>
        </div>
    @endif
@endif
