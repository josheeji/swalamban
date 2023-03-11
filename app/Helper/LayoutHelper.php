<?php

namespace App\Helper;

use App\Models\Content;
use App\Models\Layout;
use App\Models\LayoutOption;
use App\Models\News;
use Carbon\Carbon;

class LayoutHelper
{
    protected static function preferredLanguage()
    {
        return Helper::locale();
    }

    public static function layoutMenu($layout)
    {
        $option = [
            'header-top-menu',
            'primary-menu',
            'banner-menu',
            'aside-menu',
            'calculator-menu',
            'widget-1',
            'widget-2',
            'widget-3',
            'widget-4',
            'widget-5',
            'widget-6',
            'widget-7',
            'widget-8',
            'search-menu-1',
            'search-menu-2',
            'open-an-account'
        ];
        return LayoutOption::whereIn('slug', $option)->where('layout_id', $layout)->pluck('menu_id', 'slug');
    }

    public static function headerTopMenu($layoutMenu, $slug)
    {
        return $layoutMenu[$slug];
    }

    public static function primaryMenu($layoutMenu, $slug)
    {
        return $layoutMenu[$slug];
    }

    public static function bannerMenu($layoutMenu, $slug)
    {
        return isset($layoutMenu[$slug]) ? $layoutMenu[$slug] : '';
    }

    public static function openAnAccount($layoutMenu, $slug)
    {
        return (isset($layoutMenu[$slug])) ? $layoutMenu[$slug] : '';
    }

    public static function widget1($layoutMenu, $slug)
    {
        return $layoutMenu[$slug];
    }

    public static function widget2($layoutMenu, $slug)
    {
        return $layoutMenu[$slug];
    }

    public static function widget3($layoutMenu, $slug)
    {
        return $layoutMenu[$slug];
    }

    public static function widget4($layoutMenu, $slug)
    {
        return $layoutMenu[$slug];
    }

    public static function search1($layoutMenu, $slug)
    {
        return $layoutMenu[$slug];
    }

    public static function search2($layoutMenu, $slug)
    {
        return $layoutMenu[$slug];
    }

    public static function widget5($layout)
    {
        return News::where('is_active', 1)->where('published_date', '<=', Carbon::now())
            ->where('language_id', Helper::locale())
            ->where(function ($query) {
                $query->where('type', 'like', '%' . ConstantHelper::NEWS_TYPE_NEWS)
                    ->orWhere('type', 'like', '%' . ConstantHelper::NEWS_TYPE_ALL);
            })
            ->orderBy('display_order', 'desc')->limit(3)->get();
    }

    public static function widget6($layout)
    {
        return LayoutOption::where('slug', 'widget-6')->where('layout_id', $layout)->first();
    }

    public static function widget7($layout)
    {
        return LayoutOption::where('slug', 'widget-7')->where('layout_id', $layout)->first();
    }

    public static function widget8($layout)
    {
        return LayoutOption::where('slug', 'widget-8')->where('layout_id', $layout)->first();
    }

    public static function newsUpdate()
    {
        return News::where('is_active', 1)->where('published_date', '<=', Carbon::now())->orderBy('display_order', 'desc')->limit(5)->get();
    }

    public static function blocks()
    {
        $data = [];
        if ($layout = Layout::where('is_active', 1)->first()) {
            if ($blocks = LayoutOption::where('layout_id', $layout->id)->where('language_id', Helper::locale())->where('type', 2)->get()) {
                foreach ($blocks as $block) {
                    $data[$block->slug]['title'] = $block->block_title;
                    $data[$block->slug]['subtitle'] = $block->subtitle;
                    $data[$block->slug]['value'] = $block->value;
                    $data[$block->slug]['content_id'] = $block->content_id;
                    $data[$block->slug]['image'] = $block->image;
                    if (empty($block->link)) {
                        $data[$block->slug]['link'] = '#!';
                    } else {
                        $data[$block->slug]['link'] = $block->external_link == 1 ? $block->link : url($block->link);
                    }
                    $data[$block->slug]['link_text'] = $block->link_text;
                    $data[$block->slug]['link_target'] = $block->link_target;
                }
            }
        }
        return $data;
    }

    public static function blockContent($id)
    {
        $content = '';
        if ($content = Content::with('child', 'child.child')->find($id)) {
            if ($content->language_id != Helper::locale()) {
                $content = Content::with('child', 'child.child')->where('existing_record_id', $content->id)->where('language_id', Helper::locale())->first();
            }
        }
        return $content;
    }

    public static function topBlock($index)
    {
        $topBlock = '';
        if ($topBlock = LayoutOption::where('slug', 'top-block-' . $index)->first()) {
            $topBlock = self::blockContent($topBlock->content_id);
        }
        return $topBlock;
    }
}
