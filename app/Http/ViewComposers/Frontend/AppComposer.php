<?php

namespace App\Http\ViewComposers\Frontend;

use App\Helper\Helper;
use App\Helper\LayoutHelper;
use App\Helper\PageHelper;
use App\Repositories\CareerRepository;
use App\Repositories\InterestRatesRepository;
use App\Repositories\InterestBatchesRepository;
use App\Repositories\MenuItemRepository;
use App\Repositories\MenuRepository;
use App\Repositories\PostRepository;
use Carbon\Carbon;
use Illuminate\View\View;

class AppComposer
{
    protected $preferredLanguage, $menu, $menuItem, $post, $careerItem;

    public function __construct(
//        InterestBatchesRepository $interestBatch,
//        InterestRatesRepository $interestRate,
        MenuRepository $menu,
        MenuItemRepository $menuItem,

//        PostRepository $post,
//        CareerRepository $careerItem
    ) {
        $this->menu = $menu;
        $this->menuItem = $menuItem;
//        $this->post = $post;
//        $this->interestRate = $interestRate;
//        $this->interestBatch = $interestBatch;
//        $this->careerItem = $careerItem;

    }

    public function compose(view $view)
    {
        $this->preferredLanguage = Helper::locale();
        $layoutMenu = LayoutHelper::layoutMenu(1);
        $notifications = PageHelper::notifications();
        $topMenuItems = [];
        // dd($topMenuItems);
//        $careerItem = $this->careerItem->where('language_id',$this->preferredLanguage)->where("publish_to", '>=', Carbon::now())->where('is_active', 1)->count();
        $topMenu = LayoutHelper::headerTopMenu($layoutMenu, 'header-top-menu');
        // dd($topMenu);
        $menu = $this->menu->frontendMenu();
        // dd($menu);

        if ($topMenu) {
            // $menu = $this->menu->frontendMenu($topMenu);
            foreach ($menu as $data) {
                if ($data->menu_id == $topMenu) {
                    if ($data->parent_id == null) {
                        $topMenuItems['parent'][$data->id]['id'] = $data->id;
                        $topMenuItems['parent'][$data->id]['title'] = $data->title;
                        $topMenuItems['parent'][$data->id]['slug'] = $data->slug;
                        $url = !empty($data->link_url) ?  url($data->link_url) : '';
                        $topMenuItems['parent'][$data->id]['url'] = $data->is_external == 1 ? ($data->link_url) : $url;
                        $topMenuItems['parent'][$data->id]['relative_url'] = $data->link_url;
                        $topMenuItems['parent'][$data->id]['target'] = $data->link_target == true ? 'target="_blank"' : '';
                        $topMenuItems['parent'][$data->id]['icon'] = isset($data->icon) && !empty($data->icon) ? $data->icon : '';
                        $topMenuItems['parent'][$data->id]['block'] = $data->block;
                    } else {
                        $topMenuItems['child'][$data->parent_id][$data->id]['title'] =  $data->title;
                        $topMenuItems['child'][$data->parent_id][$data->id]['slug'] = $data->slug;
                        $topMenuItems['child'][$data->parent_id][$data->id]['url'] = $data->is_external == 1 ? ($data->link_url) : url($data->link_url);
                        $topMenuItems['child'][$data->parent_id][$data->id]['relative_url'] = $data->link_url;
                        $topMenuItems['child'][$data->parent_id][$data->id]['target'] = $data->link_target == true ? 'target="_blank"' : '';
                        $topMenuItems['child'][$data->parent_id][$data->id]['icon'] = isset($data->icon) && !empty($data->icon) ? $data->icon : '';
                        $topMenuItems['child'][$data->parent_id][$data->id]['block'] = $data->block;
                    }
                }
            }
        }

        $menuItems = [];
        $primaryMenu = LayoutHelper::primaryMenu($layoutMenu, 'primary-menu');
        if ($primaryMenu) {
//             $menu = $this->menu->frontendMenu($primaryMenu);
            foreach ($menu as $data) {
                if ($data->menu_id == $primaryMenu) {
                    if ($data->parent_id == null) {
                        $menuItems['parent'][$data->id]['id'] = $data->id;
                        $menuItems['parent'][$data->id]['title'] = $data->title;
                        $menuItems['parent'][$data->id]['slug'] = $data->slug;
                        $menuItems['parent'][$data->id]['url'] = $data->is_external == 1 ? ($data->link_url) : url($data->link_url);
                        $menuItems['parent'][$data->id]['relative_url'] = $data->link_url;
                        $menuItems['parent'][$data->id]['target'] = $data->link_target == true ? 'target="_blank"' : '';
                        $menuItems['parent'][$data->id]['icon'] = isset($data->icon) && !empty($data->icon) ? $data->icon : '';
                        $menuItems['parent'][$data->id]['image'] = $data->image != '' && file_exists('storage/' . $data->image) ? asset('storage/' . $data->image) : '';
                        $menuItems['parent'][$data->id]['block'] = $data->block;
                    } else {
                        $menuItems['child'][$data->parent_id][$data->id]['title'] =  $data->title;
                        $menuItems['child'][$data->parent_id][$data->id]['slug'] = $data->slug;
                        $menuItems['child'][$data->parent_id][$data->id]['url'] = $data->is_external == 1 ? ($data->link_url) : url($data->link_url);
                        $menuItems['child'][$data->parent_id][$data->id]['relative_url'] = $data->link_url;
                        $menuItems['child'][$data->parent_id][$data->id]['target'] = $data->link_target == true ? 'target="_blank"' : '';
                        $menuItems['child'][$data->parent_id][$data->id]['icon'] = isset($data->icon) && !empty($data->icon) ? $data->icon : '';
                        $menuItems['child'][$data->parent_id][$data->id]['block'] = $data->block;
                    }
                }
            }
        }
        // dd($menuItems);

        // $bannerMenuItems = [];
        // if ($bannerMenu = LayoutHelper::bannerMenu($layoutMenu, 'banner-menu')) {
        //     // if ($menu = $this->menu->frontendMenu($bannerMenu)) {
        //     foreach ($menu as $data) {
        //         if ($data->menu_id == $bannerMenu) {
        //             if ($data->parent_id == null) {
        //                 $bannerMenuItems['parent'][$data->id]['id'] = $data->id;
        //                 $bannerMenuItems['parent'][$data->id]['title'] = $data->title;
        //                 $bannerMenuItems['parent'][$data->id]['slug'] = $data->slug;
        //                 $bannerMenuItems['parent'][$data->id]['url'] = $data->is_external == 1 ? ($data->link_url) : url($data->link_url);
        //                 $bannerMenuItems['parent'][$data->id]['relative_url'] = $data->link_url;
        //                 $bannerMenuItems['parent'][$data->id]['target'] = $data->link_target == true ? 'target="_blank"' : '';
        //                 $bannerMenuItems['parent'][$data->id]['icon'] = isset($data->icon) && !empty($data->icon) ? $data->icon : '';
        //                 $bannerMenuItems['parent'][$data->id]['block'] = $data->block;
        //             } else {
        //                 $bannerMenuItems['child'][$data->parent_id][$data->id]['title'] =  $data->title;
        //                 $bannerMenuItems['child'][$data->parent_id][$data->id]['slug'] = $data->slug;
        //                 $bannerMenuItems['child'][$data->parent_id][$data->id]['url'] = $data->is_external == 1 ? ($data->link_url) : url($data->link_url);
        //                 $bannerMenuItems['child'][$data->parent_id][$data->id]['relative_url'] = $data->link_url;
        //                 $bannerMenuItems['child'][$data->parent_id][$data->id]['target'] = $data->link_target == true ? 'target="_blank"' : '';
        //                 $bannerMenuItems['child'][$data->parent_id][$data->id]['icon'] = isset($data->icon) && !empty($data->icon) ? $data->icon : '';
        //                 $bannerMenuItems['child'][$data->parent_id][$data->id]['block'] = $data->block;
        //             }
        //         }
        //     }
        // }

        $widget1Items = [];
        $widget1 = LayoutHelper::widget1($layoutMenu, 'widget-1');
        if ($widget1) {
            // $menu = $this->menu->frontendMenu($widget1);
            foreach ($menu as $data) {
                if ($data->menu_id == $widget1 && $data->parent_id == null) {
                    $widget1Items['parent'][$data->id]['id'] = $data->id;
                    $widget1Items['parent'][$data->id]['title'] = $data->title;
                    $widget1Items['parent'][$data->id]['slug'] = $data->slug;
                    if(!empty($data->link_url)){
                        $widget1Items['parent'][$data->id]['url'] = $data->is_external == 1 ? ($data->link_url) : url('/').$data->link_url;
                    }else{
                        $widget1Items['parent'][$data->id]['url'] = '#';
                    }
                    $widget1Items['parent'][$data->id]['relative_url'] = $data->link_url;
                    $widget1Items['parent'][$data->id]['target'] = $data->link_target == true ? 'target="_blank"' : '';
                    $widget1Items['parent'][$data->id]['icon'] = isset($data->icon) && !empty($data->icon) ? $data->icon : '';
                }
            }
        }

        $widget2Items = [];
        $widget2 = LayoutHelper::widget2($layoutMenu, 'widget-2');
        if ($widget2) {
            // $menu = $this->menu->frontendMenu($widget2);
            foreach ($menu as $data) {
                if ($data->menu_id == $widget2 && $data->parent_id == null) {
                    $widget2Items['parent'][$data->id]['id'] = $data->id;
                    $widget2Items['parent'][$data->id]['title'] = $data->title;
                    $widget2Items['parent'][$data->id]['slug'] = $data->slug;
                    if(!empty($data->link_url)){
                        $widget2Items['parent'][$data->id]['url'] = $data->is_external == 1 ? ($data->link_url) : url('/').$data->link_url;
                    }else{
                        $widget2Items['parent'][$data->id]['url'] = '#';
                    }
                    $widget2Items['parent'][$data->id]['relative_url'] = $data->link_url;
                    $widget2Items['parent'][$data->id]['target'] = $data->link_target == true ? 'target="_blank"' : '';
                    $widget2Items['parent'][$data->id]['icon'] = isset($data->icon) && !empty($data->icon) ? $data->icon : '';
                }
            }
        }

       $widget3Items = [];
       $widget3 = LayoutHelper::widget3($layoutMenu, 'widget-3');
       if ($widget3) {
           // $menu = $this->menu->frontendMenu($widget3);
           foreach ($menu as $data) {
               if ($data->menu_id == $widget3 && $data->parent_id == null) {
                   $widget3Items['parent'][$data->id]['id'] = $data->id;
                   $widget3Items['parent'][$data->id]['title'] = $data->title;
                   $widget3Items['parent'][$data->id]['slug'] = $data->slug;
                   $widget3Items['parent'][$data->id]['url'] = $data->is_external == 1 ? ($data->link_url) : url($data->link_url);
                   $widget3Items['parent'][$data->id]['relative_url'] = $data->link_url;
                   $widget3Items['parent'][$data->id]['target'] = $data->link_target == true ? 'target="_blank"' : '';
                   $widget3Items['parent'][$data->id]['icon'] = isset($data->icon) && !empty($data->icon) ? $data->icon : '';
               }
           }
       }

//        $searchMenu1Items = [];
//        $searchMenu1 = LayoutHelper::search1($layoutMenu, 'search-menu-1');
//        if ($searchMenu1) {
//            // $menu = $this->menu->frontendMenu($searchMenu1);
//            foreach ($menu as $data) {
//                if ($data->menu_id == $searchMenu1 && $data->parent_id == null) {
//                    $searchMenu1Items['parent'][$data->id]['id'] = $data->id;
//                    $searchMenu1Items['parent'][$data->id]['title'] = $data->title;
//                    $searchMenu1Items['parent'][$data->id]['slug'] = $data->slug;
//                    $searchMenu1Items['parent'][$data->id]['url'] = $data->is_external == 1 ? ($data->link_url) : url($data->link_url);
//                    $searchMenu1Items['parent'][$data->id]['relative_url'] = $data->link_url;
//                    $searchMenu1Items['parent'][$data->id]['target'] = $data->link_target == true ? 'target="_blank"' : '';
//                    $searchMenu1Items['parent'][$data->id]['icon'] = isset($data->icon) && !empty($data->icon) ? $data->icon : '';
//                }
//            }
//        }

//        $searchMenu2Items = [];
//        $searchMenu2 = LayoutHelper::search1($layoutMenu, 'search-menu-2');
//        if ($searchMenu2) {
//            // $menu = $this->menu->frontendMenu($searchMenu2);
//            foreach ($menu as $data) {
//                if ($data->menu_id == $searchMenu2 && $data->parent_id == null) {
//                    $searchMenu2Items['parent'][$data->id]['id'] = $data->id;
//                    $searchMenu2Items['parent'][$data->id]['title'] = $data->title;
//                    $searchMenu2Items['parent'][$data->id]['slug'] = $data->slug;
//                    $searchMenu2Items['parent'][$data->id]['url'] = $data->is_external == 1 ? ($data->link_url) : url($data->link_url);
//                    $searchMenu2Items['parent'][$data->id]['relative_url'] = $data->link_url;
//                    $searchMenu2Items['parent'][$data->id]['target'] = $data->link_target == true ? 'target="_blank"' : '';
//                    $searchMenu2Items['parent'][$data->id]['icon'] = isset($data->icon) && !empty($data->icon) ? $data->icon : '';
//                }
//            }
//        }

//        $openAnAccountItems = [];
//        $openAnAccount = LayoutHelper::openAnAccount($layoutMenu, 'open-an-account');
//        if ($openAnAccount) {
//            foreach ($menu as $data) {
//                if ($data->menu_id == $openAnAccount) {
//                    $openAnAccountItems['parent'][$data->id]['id'] = $data->id;
//                    $openAnAccountItems['parent'][$data->id]['title'] = $data->title;
//                    $openAnAccountItems['parent'][$data->id]['slug'] = $data->slug;
//                    $openAnAccountItems['parent'][$data->id]['url'] = $data->is_external == 1 ? ($data->link_url) : url($data->link_url);
//                    $openAnAccountItems['parent'][$data->id]['relative_url'] = $data->link_url;
//                    $openAnAccountItems['parent'][$data->id]['target'] = $data->link_target == true ? 'target="_blank"' : '';
//                    $openAnAccountItems['parent'][$data->id]['icon'] = isset($data->icon) && !empty($data->icon) ? $data->icon : '';
//                }
//            }
//        }

//        $activeIntBatch = $this->interestBatch->where('active', 1)->first();

        $topInterestRates = [];

//        if($activeIntBatch){
//            $topInterestRates = $activeIntBatch->interestRates()->whereIn('type',['current_saving','fixed_deposits'])->get();
//        }

//        $interestTypes = Helper::getInterestTypes();
        $view
            ->withTopMenuItems($topMenuItems)
            ->withMenuItems($menuItems)
            ->withNotifications($notifications)
            // ->withBannerMenuItems($bannerMenuItems)
            ->withWidget1($widget1Items)
            ->withWidget2($widget2Items)
           ->withWidget3($widget3Items);
//            ->withSearch1($searchMenu1Items)
//            ->withSearch2($searchMenu2Items)
//            ->withTopInterestRates($topInterestRates)
//            ->withInterestTypes($interestTypes)
//            ->withOpenAnAccountItems($openAnAccountItems)
//            ->withCareerItem($careerItem);
    }
}
