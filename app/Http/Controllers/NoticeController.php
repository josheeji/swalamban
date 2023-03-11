<?php

namespace App\Http\Controllers;

use App\Helper\ConstantHelper;
use App\Helper\Helper;
use App\Helper\SettingHelper;
use App\Models\MenuItems;
use App\Repositories\NoticeRepository;
use Artesaos\SEOTools\Facades\OpenGraph;
use Artesaos\SEOTools\Facades\SEOMeta;
use Carbon\Carbon;
use Illuminate\Http\Request;

class NoticeController extends Controller
{

    protected $notice;

    public function __construct(
        NoticeRepository $notice
    ) {
        $this->notice = $notice;
    }

    protected function locale()
    {
        return Helper::locale();
    }

    public function pressRelease()
    {
        $notices = $this->notice->where('type', ConstantHelper::NOTICE_TYPE_PRESS_RELEASE)
            ->where('language_id', $this->locale())
            ->where('start_date', '<=', Carbon::now())
            ->orderBy('display_order')->orderBy('start_date','desc')->where('is_active', 1)->paginate('15');
        $menu = MenuItems::where('link_url',request()->path())->first();
        SEOMeta::setDescription($menu->title ??  'Swabalamban Laghubitta Bittiya Sanstha Ltd.');

        OpenGraph::setDescription($menu->title ??   'Swabalamban Laghubitta Bittiya Sanstha Ltd.');
        OpenGraph::setTitle($menu->title ?? SettingHelper::setting('site_title'));
        OpenGraph::addImages([isset($menu->image) ? asset('storage/' . @$menu->image) : asset('swabalamban/images/titlebg.jpg')]);
        OpenGraph::setUrl(route('press-release'));

        return view('notice.press-release', compact('notices','menu'));
    }

    public function tenderNotice()
    {
        $notices = $this->notice->where('type', ConstantHelper::NOTICE_TYPE_TENDER)
            ->where('is_active', 1)->where('language_id', $this->locale())
            ->where('start_date', '<=', Carbon::now())
            // ->where('end_date', '>=', Carbon::now())
            ->orderBy('display_order', 'asc')->where('is_active', 1)->paginate(30);
        return view('notice.tender-notice', compact('notices'));
    }

    public function show($slug)
    {
        $notice = $this->notice->where('slug', $slug)->where('language_id', $this->locale())
            ->where('is_active', 1)->first();
        if (!$notice) {
            abort('404');
        }
        $prev = $this->notice->where('id', $notice->id - 1)->where('language_id', $this->locale())
            ->where('is_active', 1)->where('start_date', '<=', Carbon::now())
            ->where(function ($query) use ($notice) {
                $query->where('type', 'like', '%' . $notice->type . '%');
                if ($notice->type == ConstantHelper::NOTICE_TYPE_TENDER) {
                    // $query->where('end_date', '>=', Carbon::now());
                }
            })->first();
        $next = $this->notice->where('id', $notice->id + 1)->where('language_id', $this->locale())
            ->where('is_active', 1)->where('start_date', '<=', Carbon::now())
            ->where(function ($query) use ($notice) {
                $query->where('type', 'like', '%' . $notice->type . '%');
                if ($notice->type == ConstantHelper::NOTICE_TYPE_TENDER) {
                    // $query->where('end_date', '>=', Carbon::now());
                }
            })->first();
        $latest = $this->notice->where('is_active', 1)->whereNotIn('id', [$notice->id])
            ->where('language_id', $this->locale())->where('start_date', '<=', Carbon::now())
            ->orderBy('start_date','desc')
            ->orderBy('id','desc')
            ->where(function ($query) use ($notice) {
                $query->where('type', 'like', '%' . $notice->type . '%');
                if ($notice->type == ConstantHelper::NOTICE_TYPE_TENDER) {
                    // $query->where('end_date', '>=', Carbon::now());
                }
            })
            ->limit(5)->get();
        $view = $notice->type == ConstantHelper::NOTICE_TYPE_PRESS_RELEASE ? 'notice.press-release-show' : 'notice.tender-notice-show';
        return view($view, compact('notice', 'prev', 'next', 'latest'));
    }
}
