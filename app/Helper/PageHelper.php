<?php

namespace App\Helper;

use App\Models\AccountType;
use App\Models\AccountTypeCategory;
use App\Models\AdminType;
use App\Models\Advertisement;
use App\Models\AtmLocation;
use App\Models\Banner;
use App\Models\Content;
use App\Models\GalleryVideo;
use App\Models\Gallery;
use App\Models\MenuItems;
use App\Models\Nav;
use App\Models\News;
use App\Models\NewsCategory;
use App\Models\Notice;
use App\Models\Popup;
use App\Models\Post;
use App\Models\Blog;
use App\Models\BranchDirectory;
use App\Models\Career;
use App\Models\StockInfo;
use App\Models\Testimonials;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class PageHelper
{
    protected static function preferredLanguage()
    {
        return Helper::locale();
    }

    public static function visibleInList()
    {
        return [
            // ConstantHelper::VISIBLE_IN_ALL => 'All',
            ConstantHelper::VISIBLE_IN_PERSONAL => 'Personal',
            ConstantHelper::VISIBLE_IN_BUSINESS => 'Business',
            ConstantHelper::VISIBLE_IN_TRADE => 'Trade',
            ConstantHelper::VISIBLE_IN_REMITTANCE => 'Remittance'
        ];
    }

    public static function visibleInLabel($visibleIn)
    {
        $data = '';
        if (self::isVisibleIn(ConstantHelper::VISIBLE_IN_ALL, $visibleIn)) {
            // $data .= '<span class="label bg-purple">All</span> ';
        }
        if (self::isVisibleIn(ConstantHelper::VISIBLE_IN_PERSONAL, $visibleIn)) {
            $data .= '<span class="label label-primary label-pill label-inline mr-1">Personal</span> ';
        }
        if (self::isVisibleIn(ConstantHelper::VISIBLE_IN_BUSINESS, $visibleIn)) {
            $data .= '<span class="label label-warning label-pill label-inline mr-1">Business</span>';
        }
        if (self::isVisibleIn(ConstantHelper::VISIBLE_IN_TRADE, $visibleIn)) {
            $data .= '<span class="label label-success label-pill label-inline mr-1">Trade</span> ';
        }
        if (self::isVisibleIn(ConstantHelper::VISIBLE_IN_REMITTANCE, $visibleIn)) {
            $data .= '<span class="label label-info label-pill label-inline mr-1">Remittance</span>';
        }
        return $data;
    }

    public static function isVisibleIn($value, $data)
    {
        return (in_array($value, explode(',', $data))) ? true : false;
    }

    public static function visibleIn($page)
    {
        switch ($page) {
            case 'business-banking':
                return ConstantHelper::VISIBLE_IN_BUSINESS;
            case 'trade-finance-treasury':
                return ConstantHelper::VISIBLE_IN_TRADE;
            case 'remittance':
                return ConstantHelper::VISIBLE_IN_REMITTANCE;
            default:
                return ConstantHelper::VISIBLE_IN_PERSONAL;
        }
    }

    public static function pageLayoutOptionList()
    {
        return [
            1 => 'Full Width Layout',
            2 => 'Left Column Layout',
            3 => 'Right Column Layout',
            // 4 => 'Iframe Layout'

        ];
    }
    public static function year()
    {
        return range(2060, 2080);
    }
    public static function month()
    {
        return [
            1 => 'Baisakha',
            2 => 'Jestha',
            3 => 'Ashara',
            4 => 'Shrawan',
            5 => 'Bhadra',
            6 => 'Ashwin',
            7 => 'Kartika',
            8 => 'Mangshir',
            9 => 'Poush',
            10 => 'Magha',
            11 => 'Falguna',
            12 => 'Chaita'
        ];
        // return [
        //     1 => 'बैसाख (Baisakha)',
        //     2 =>'जेठ (Jeth)',
        //     3 =>'असार (Ashara)',
        //     4 =>'साउन (Sauna)',
        //     5 =>'भदौ (Bhadau)',
        //     6 =>'असोज (Ashoj)',
        //     7 =>'कार्तिक (Kartika)',
        //     8 =>'मंसिर (Mangshir)',
        //     9 =>'पुष (Poush)',
        //     10 =>'माघ (Magha)',
        //     11 =>'फाल्गुन (Falguna)',
        //     12 => 'चैत (Chaita)'
        // ];
    }
    public static function getMonthName($month_id)
    {
        $months = PageHelper::month();
        return $months[$month_id];
    }

    public static function banner($page = null)
    {
        $banners = Banner::where('is_active', 1)
            ->where('language_id', self::preferredLanguage())
            ->orderBy('display_order', 'ASC')
            ->orderBy('created_at', 'desc')
            ->get();

        // if (!empty($page)) {
        //     $banners->where(function ($query) use ($page) {
        //         $query->where('visible_in', 'like', '%' . self::visibleIn($page) . '%')
        //             ->orWhere('visible_in', 'like', '%' . ConstantHelper::VISIBLE_IN_ALL . '%');
        //     });
        // }
        return $banners;
    }

    public static function offer($page = null)
    {
        return Post::where('is_active', 1)->where('language_id', self::preferredLanguage())
            ->where('type', ConstantHelper::POST_TYPE_OFFER)
            ->orderBy('display_order', 'ASC')->get();
    }

    public static function product($page = null)
    {
        return AccountType::where('is_active', 1)->where('language_id', self::preferredLanguage())->orderBy('display_order', 'ASC')->get();
    }

    public static function productCategory($page = null)
    {
        return AccountTypeCategory::where('is_active', 1)->where('language_id', self::preferredLanguage())->orderBy('display_order', 'ASC')->get();
    }

    public static function service($page = null)
    {
        return Post::where('is_active', 1)->where('language_id', self::preferredLanguage())
            ->where('type', ConstantHelper::POST_TYPE_SERVICE)
            ->orderBy('display_order', 'ASC')->get();
    }

    public static function news($id = '', $page = null, $order = 'desc', $paginate = false, $limit = 6)
    {
        $news = News::where('news.is_active', 1)->where('news.language_id', self::preferredLanguage())
            ->join('news_categories', 'category_id', '=', 'news_categories.id')->where('news_categories.language_id', Helper::locale())
            ->where('published_date', '<=', Carbon::now())
            ->whereNotIn('news.id', [$id])
            ->select('news.*', 'news_categories.title as cat_title', 'news_categories.slug as cat_slug')
            // ->where(function ($query) {
            //     $query->where('type', 'like', '%' . ConstantHelper::NEWS_TYPE_NEWS . '%');
            // })
            ->whereIn('news.category_id', [1, 2, 3, 4]);
        $news->orderBy('published_date', 'desc');
        if ($paginate) {
            return $news->paginate($limit);
        }
        $news->limit($limit);
        return $news->orderBy('news.display_order', 'asc')->get();
    }
    public static function csr($id = '', $page = null, $order = 'desc', $paginate = false, $limit = 1)
    {
        $news = News::where('news.is_active', 1)->where('news.language_id', self::preferredLanguage())
            ->join('news_categories', 'category_id', '=', 'news_categories.id')->where('news_categories.language_id', Helper::locale())
            ->where('published_date', '<=', Carbon::now())
            ->whereNotIn('news.id', [$id])
            ->select('news.*', 'news_categories.title as cat_title', 'news_categories.slug as cat_slug')
            // ->where(function ($query) {
            //     $query->where('type', 'like', '%' . ConstantHelper::NEWS_TYPE_CSR . '%');
            // })
            ->whereIn('news.category_id', [5, 6]);
        $news->orderBy('published_date', 'desc');
        if ($paginate) {
            return $news->paginate($limit);
        }
        $news->limit($limit);
        return $news->orderBy('news.display_order', 'asc')->get();
    }

    public static function popup($page = null)
    {
        return Popup::where('is_active', 1)->orderBy('created_at', 'asc')->get();

        //        if (!empty($page) && !isset($_COOKIE[$page])) {
        //            setcookie($page, true, time() + (60 * 60), '/');
        //            return Popup::where('is_active', 1)->get();
        //        }
        // return Popup::where('is_active', 1)->where(function ($query) use ($page) {
        //     $query->where('visible_in', 'like', '%' . self::visibleIn($page) . '%')
        //         ->orWhere('visible_in', 'like', '%' . ConstantHelper::VISIBLE_IN_ALL . '%');
        // })->get();
    }

    public static function testimonial()
    {
        return Testimonials::where('is_active', 1)->where('language_id', self::preferredLanguage())->orderBy('created_at', 'asc')->get();
    }

    public static function videoLinks()
    {
        return GalleryVideo::where('is_active', 1)->orderBy('created_at', 'asc')->get();
    }

    public static function bannerMenu()
    {
        $layoutMenu = LayoutHelper::layoutMenu(1);
        $bannerItems = [];
        $bannerMenu = LayoutHelper::bannerMenu($layoutMenu, 'banner-menu');
        if ($bannerMenu) {
            $menu = MenuItems::where('menu_id', $bannerMenu)
                ->where('language_id', Helper::locale())
                ->where('parent_id', null)
                ->orderBy('parent_id', 'asc')
                ->orderBy('display_order', 'asc')->get();
            foreach ($menu as $data) {
                $bannerItems['parent'][$data->id]['id'] = $data->id;
                $bannerItems['parent'][$data->id]['title'] = $data->title;
                $bannerItems['parent'][$data->id]['slug'] = $data->slug;
                $bannerItems['parent'][$data->id]['url'] = $data->is_external == 1 ? $data->link_url : url($data->link_url);
                $bannerItems['parent'][$data->id]['relative_url'] = $data->link_url;
                $bannerItems['parent'][$data->id]['target'] = $data->link_target == true ? 'target="_blank"' : '';
                $bannerItems['parent'][$data->id]['icon'] = isset($data->icon) && !empty($data->icon) ? $data->icon : '';
                $bannerItems['parent'][$data->id]['image'] = isset($data->image) && !empty($data->image) ? $data->image : '';
            }
        }
        return $bannerItems;
    }

    public static function advertisements($page = null, $placement = null)
    {
        $ads = [];

        if (!empty($page) || !empty($placement)) {
            $advertisements = Advertisement::where('is_active', 1);
            if (!empty($page) && self::adsVisibleIn($page) != false) {
                $advertisements = $advertisements->where('visible_in', 'like', '%' . self::adsVisibleIn($page) . '%');
            }
            if (!empty($placement)) {
                $advertisements = $advertisements->where('placement_id', $placement);
            }
            // $advertisements = $advertisements->orWhere('visible_in', 'like', '%' . ConstantHelper::AD_VISIBLE_IN_ALL . '%');
            $advertisements = $advertisements->get();
            if ($advertisements) {
                foreach ($advertisements as $advertisement) {
                    $ads[$advertisement->id]['image'] = $advertisement->image;
                    $ads[$advertisement->id]['link'] = $advertisement->link;
                }
            }
        }
        return $ads;
    }

    public static function adsVisibleIn($page)
    {
        switch ($page) {
            case 'business-banking':
                return ConstantHelper::AD_VISIBLE_IN_BUSINESS;
            case 'trade-finance-treasury':
                return ConstantHelper::AD_VISIBLE_IN_TRADE;
            case 'remittance':
                return ConstantHelper::AD_VISIBLE_IN_REMITTANCE;
            case 'news':
                return ConstantHelper::AD_VISIBLE_IN_NEWS;
            case 'csr':
                return ConstantHelper::AD_VISIBLE_IN_CSR;
            case 'content':
                return ConstantHelper::AD_VISIBLE_IN_CONTENT;
            case 'personal-banking':
                return ConstantHelper::AD_VISIBLE_IN_PERSONAL;
            default:
                return false;
        }
    }

    public static function asideMenu($content)
    {
        $items = [];
        $menuItem = MenuItems::where('slug', $content->slug)
            ->where('language_id', Helper::locale())
            ->orderBy('display_order', 'asc')
            ->first();
        if ($menuItem) {
            $itemList = MenuItems::where('parent_id', $menuItem->parent_id)
                ->where('language_id', Helper::locale())
                ->where('is_active', 1)
                ->orderBy('display_order', 'asc')->get();
            if ($itemList) {
                foreach ($itemList as $data) {
                    $items[$data->id]['id'] = $data->id;
                    $items[$data->id]['title'] = $data->title;
                    $items[$data->id]['slug'] = $data->slug;
                    $items[$data->id]['url'] = url($data->link_url);
                    $items[$data->id]['relative_url'] = $data->link_url;
                    $items[$data->id]['target'] = $data->link_target == true ? 'target="_blank"' : '';
                    $items[$data->id]['icon'] = isset($data->icon) && !empty($data->icon) ? $data->icon : '';
                }
            }
        }
        return $items;
    }

    public static function contentHierarchy($content)
    {
        $items = [];
        $parentId = $content->parent_id != null && !empty($content->parent_id) ? $content->parent_id : $content->id;

        $contentList = Content::where('parent_id', $parentId)
            ->where('id', '!=', $content->id)
            ->where('language_id', Helper::locale())
            ->where('is_active', 1)
            ->orderBy('display_order', 'asc')->get();

        if ($contentList) {
            foreach ($contentList as $data) {
                $items[$data->id]['id'] = $data->id;
                $items[$data->id]['title'] = $data->title;
                $items[$data->id]['slug'] = $data->slug;
                $items[$data->id]['url'] = !empty($data->link) ? $data->link : url($data->slug);
                $items[$data->id]['relative_url'] = $data->slug;
                $items[$data->id]['target'] = $data->link_target == true ? 'target="_blank"' : '';
            }
        }
        return $items;
    }

    public static function notifications()
    {
        $notification = [];
        $notification['count'] = 0;

        $list = News::where('show_in_notification', 1)->where('news.language_id', Helper::locale())
            ->join('news_categories', 'category_id', '=', 'news_categories.id')->where('news_categories.language_id', Helper::locale())
            ->with('category')
            ->where('news.is_active', 1)
            ->orderBy('news.display_order', 'asc')
            ->select('news.*')
            ->limit(4)
            ->get();

        if ($list) {
            $notification['count'] = count($list);
            foreach ($list as $item) {
                $visibleIn = explode(',', $item->type);
                $url = $visibleIn[0] == ConstantHelper::NEWS_TYPE_CSR ? url("/csr/{$item->slug}") : url("/news/{$item->category->slug}/{$item->slug}");
                $notification['item'][$item->title]['url'] = $url;
                $notification['item'][$item->title]['ago'] = self::getTimeAgo($item->created_at);
            }
        }

        $offer = Post::where('show_in_notification', 1)->where('language_id', Helper::locale())
            ->where('type', ConstantHelper::POST_TYPE_OFFER)
            ->where('is_active', 1)
            ->orderBy('display_order', 'asc')
            ->limit(4)
            ->get();

        if ($offer) {
            $notification['count'] = $notification['count'] + count($offer);

            foreach ($offer as $item) {
                $url = url("/offers/{$item->slug}");
                $notification['item'][$item->title]['url'] = $url;
                $notification['item'][$item->title]['ago'] = self::getTimeAgo($item->created_at);
            }
        }

        if ($popup = Popup::where('show_in_notification', 1)->where('is_active', 1)->limit(4)->get()) {
            $notification['count'] += count($popup);
            foreach ($popup as $item) {
                $notification['item'][$item->title]['url'] = route('popup.view', $item->slug);
                $notification['item'][$item->title]['ago'] = self::getTimeAgo($item->created_at);
            }
        }
        return $notification;
    }

    public static function getTimeAgo($carbonObject)
    {
        return str_ireplace(
            [' seconds', ' second', ' minutes', ' minute', ' hours', ' hour', ' days', ' day', ' weeks', ' week'],
            ['s', 's', 'm', 'm', 'h', 'h', 'd', 'd', 'w', 'w'],
            $carbonObject->diffForHumans()
        );
    }

    public static function videos($limit = 3)
    {
        return GalleryVideo::where('is_active', 1)->orderBy('display_order', 'asc')->limit($limit)->get();
    }

    public static function pressReleases($paginate = false, $limit = 3)
    {
        $notice = Notice::where('type', ConstantHelper::NOTICE_TYPE_PRESS_RELEASE)
            ->where('is_active', 1)->orderBy('display_order', 'asc')
            ->where('language_id', self::preferredLanguage())
            ->orderBy('start_date', 'desc');
        if ($paginate) {
            return $notice->paginate($limit);
        }
        return $notice->limit($limit)->get();
    }

    public static function galleries($limit = 5)
    {
        return Gallery::where('is_active', 1)->orderBy('display_order', 'asc')
            ->where('language_id', self::preferredLanguage())
            ->limit($limit)->get();
    }

    public static function successStories($paginate = false, $limit = 3)
    {
        $parent = Content::where('slug', 'success-stories')->first();
        if ($parent) {
            $child = Content::where('parent_id', $parent->id)->where('is_active', 1);
            if ($paginate) {
                return $child->paginate($limit);
            }
            return $child->limit($limit)->get();
        }
    }

    public static function allChildContent($id, $paginate = false, $limit = 12)
    {
        $content = Content::where('parent_id', $id)->with('child')->where('is_active', 1)->where('language_id', Helper::locale());
        $content->orderBy('display_order', 'asc');
        if ($paginate) {
            return $content->paginate($limit);
        }
        return $content->limit($limit)->get();
    }

    public static function breadcrumb($title)
    {
        dd(session()->get('menuItems'));
    }

    public static function getMenu($slug)
    {
        $layoutMenu = LayoutHelper::layoutMenu(1);
        $items = [];
        $menu = LayoutHelper::bannerMenu($layoutMenu, $slug);
        if ($menu) {
            $menu = MenuItems::where('menu_id', $menu)
                ->where('language_id', Helper::locale())
                ->where('parent_id', null)
                ->orderBy('parent_id', 'asc')
                ->orderBy('display_order', 'asc')->get();
            foreach ($menu as $data) {
                $items['parent'][$data->id]['id'] = $data->id;
                $items['parent'][$data->id]['title'] = $data->title;
                $items['parent'][$data->id]['slug'] = $data->slug;
                $items['parent'][$data->id]['url'] = !empty($data->link_url) ? url($data->link_url) : '#!';
                $items['parent'][$data->id]['relative_url'] = $data->link_url;
                $items['parent'][$data->id]['target'] = $data->link_target;
                $items['parent'][$data->id]['icon'] = isset($data->icon) && !empty($data->icon) ? $data->icon : '';
            }
        }
        return $items;
    }

    public static function newsCategories()
    {
        return NewsCategory::with('newsHomepage')->where('is_active', 1)->where('language_id', Helper::locale())->orderBy('display_order', 'asc')->get();
    }

    public static function latestNav()
    {
        return Nav::where('is_active', 1)->orderBy('publish_at', 'desc')->first();
    }

    public static function partners()
    {
        return Post::where('type', ConstantHelper::POST_TYPE_PARTNER)->where('is_active', 1)->orderby('display_order', 'asc')->where('language_id', Helper::locale())->get();
    }

    public static function blogs($notIn = [])
    {
        return Blog::with('category')->where('is_active', '1')->whereNotIn('id', $notIn)->where('language_id', Helper::locale())->orderBy('id', 'desc')->take(5)->get();
    }

    public static function careers()
    {
        return Career::where('language_id', Helper::locale())
            ->whereDate('publish_from', '<=', Carbon::now())
            ->whereDate("publish_to", '>=', Carbon::now())->where('is_active', 1)->take(4)->get();
    }

    public static function locations()
    {
        $response = [];
        $response['atmInsideValley'] = AtmLocation::where('is_active', 1)->where('language_id', Helper::locale())->where('inside_valley', 1)->orderBy('display_order', 'asc')->take(5)->get();
        $response['atmOutsideValley'] = AtmLocation::where('is_active', 1)->where('language_id', Helper::locale())->where('inside_valley', 0)->orderBy('display_order', 'asc')->take(5)->get();
        $response['branchInsideValley'] = BranchDirectory::where('is_active', 1)->where('inside_valley', 1)->where('language_id', Helper::locale())->orderBy('title', 'asc')->take(5)->get();
        $response['branchOutsideValley'] = BranchDirectory::where('is_active', 1)->where('inside_valley', 0)->where('language_id', Helper::locale())->orderBy('title', 'asc')->take(5)->get();
        return $response;
    }

    public static function topSearchKeywords()
    {
        return DB::table('searches')
            ->select('keyword', DB::raw('count(*) as total'))
            ->groupBy('keyword')
            ->limit(5)
            ->orderBy('total', 'desc')
            ->get();
    }

    public static function stockWatch()
    {
        return StockInfo::where('language_id', Helper::locale())->where('published_at', date('Y-m-d'))->where('is_active', 1)->first();
    }

    public static function checkDirectiveCat()
    {

        $type = Auth()->user()->admin_type_id;
        $role = AdminType::where('name', 'Download Category')->first();
        if ($role != '' && $role->id == $type) {
            return true;
        } else {
            return false;
        }
    }
}
