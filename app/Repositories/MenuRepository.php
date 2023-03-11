<?php


namespace App\Repositories;

use App\Helper\ConstantHelper;
use App\Helper\Helper;
use App\Models\Menu;

class MenuRepository extends Repository
{
    protected $preferredLanguage,
        $menu,
        $menuItem,
        $content,
        $blog,
        $news,
        $post,
        $teamCategory,
        $accountType;

    public function __construct(
        Menu $menu,
        MenuItemRepository $menuItem,
        ContentRepository $content,
        BlogRepository $blog,
        NewsRepository $news,
        PostRepository $post,
        AccountTypeRepository $accountType,
        TeamCategoryRepository $teamCategory
    ) {
        $this->preferredLanguage = Helper::locale();
        $this->model =  $menu;
        $this->menuItem = $menuItem;
        $this->content = $content;
        $this->blog = $blog;
        $this->news = $news;
        $this->post = $post;
        $this->accountType = $accountType;
        $this->teamCategory = $teamCategory;
    }

    public function customLinks($id)
    {
        return $this->menuItem->where('type', 2)->where('menu_id', $id)
            ->where('language_id', $this->preferredLanguage)->get();
    }

    public function moduleContents($module, $existing_record_id = null)
    {
        switch ($module) {
            case 'content':
                return $this->content->where('is_active', 1)
                    ->where(function ($query) use ($existing_record_id) {
                        if ($existing_record_id) {
                            $query->where('existing_record_id', '=', $existing_record_id);
                        } else {
                            $query->where('language_id', $this->preferredLanguage);
                        }
                    })
                    ->orderBy('title', 'asc')->get();
            case 'blog':
                return $this->blog->where('is_active', 1)->where('language_id', $this->preferredLanguage)
                    ->orderBy('title', 'asc')->get();
            case 'news':
                return $this->news->where('is_active', 1)
                    ->where(function ($query) use ($existing_record_id) {
                        if ($existing_record_id) {
                            $query->where('existing_record_id', '=', $existing_record_id);
                        } else {
                            $query->where('language_id', $this->preferredLanguage);
                        }
                    })
                    ->orderBy('title', 'asc')->get();
            case 'service':
                return $this->post->where('is_active', 1)
                    ->where(function ($query) use ($existing_record_id) {
                        if ($existing_record_id) {
                            $query->where('existing_record_id', '=', $existing_record_id);
                        } else {
                            $query->where('language_id', $this->preferredLanguage);
                        }
                    })
                    ->where('type', ConstantHelper::POST_TYPE_SERVICE)->orderBy('title', 'asc')->get();
            case 'offer':
                return $this->post->where('is_active', 1)
                    ->where(function ($query) use ($existing_record_id) {
                        if ($existing_record_id) {
                            $query->where('existing_record_id', '=', $existing_record_id);
                        } else {
                            $query->where('language_id', $this->preferredLanguage);
                        }
                    })
                    ->where('type', ConstantHelper::POST_TYPE_OFFER)->orderBy('title', 'asc')->get();
            case 'account-type':
                return $this->accountType->where('is_active', 1)
                    ->where(function ($query) use ($existing_record_id) {
                        if ($existing_record_id) {
                            $query->where('existing_record_id', '=', $existing_record_id);
                        } else {
                            $query->where('language_id', $this->preferredLanguage);
                        }
                    })->orderBy('title', 'asc')->get();
            case 'team-category':
                return $this->teamCategory->where('is_active', 1)
                    ->where(function ($query) use ($existing_record_id) {
                        if ($existing_record_id) {
                            $query->where('existing_record_id', '=', $existing_record_id);
                        } else {
                            $query->where('language_id', $this->preferredLanguage);
                        }
                    })->orderBy('title', 'asc')->get();
        }
    }

    public function getContent($module, $reference_id)
    {
        switch ($module) {
            case 'content':
                return $this->content->where('id', $reference_id)->where('is_active', 1)->first();
            case 'blog':
                return $this->blog->where('id', $reference_id)->where('is_active', 1)->first();
            case 'news':
                return $this->news->where('id', $reference_id)->where('is_active', 1)->first();
            case 'service':
                return  $this->post->where('id', $reference_id)->where('is_active', 1)
                    ->where('type', ConstantHelper::POST_TYPE_SERVICE)->first();
            case 'offer':
                return $this->post->where('id', $reference_id)->where('is_active', 1)
                    ->where('type', ConstantHelper::POST_TYPE_OFFER)->first();
            case 'account-type':
                return $this->accountType->where('id', $reference_id)->where('is_active', 1)->first();
            case 'team-category':
                return $this->teamCategory->where('id', $reference_id)->where('is_active', 1)->first();
        }
    }

    public function content($menuItem)
    {
        $language_id = Helper::locale();
        $data['title'] = '';
        $data['slug'] = '';
        $data['url'] = '';
        $data['relative_url'] = '';
        $data['target'] = '';
        $data['icon'] = '';
        // $menuItem = $this->menuItem->find($menuItem);
        if ($menuItem) {
            $data['target'] = $menuItem->link_target == true ? '_blank' : '';
            $data['icon'] = !empty($menuItem->icon) ? $menuItem->icon : '';
            if ($menuItem->type == 1) {
                switch ($menuItem->module->alias) {
                    case 'content':
                        $id = $language_id == 1 ? 'id' : 'existing_record_id';
                        $content = $this->content->where($id, $menuItem->reference_id)
                            ->where('language_id', $language_id)->where('is_active', 1)->first();
                        if ($content) {
                            $data['title'] = $content->title;
                            $data['slug'] = $content->slug;
                            $data['url'] = url('/' . $content->slug);
                            $data['relative_url'] = '/' . $content->slug;
                        }
                        break;
                    case 'blog':
                        $id = $language_id == 1 ? 'id' : 'existing_record_id';
                        $blog = $this->blog->where($id, $menuItem->reference_id)
                            ->where('language_id', $language_id)->where('is_active', 1)->first();
                        if ($blog) {
                            $data['title'] = $blog->title;
                            $data['slug'] = $blog->slug;
                            $data['url'] = url('/blog' . '/' . $blog->slug);
                            $data['relative_url'] = '/blog' . '/' . $blog->slug;
                        }
                        break;
                    case 'team-category':
                        $id = $language_id == 1 ? 'id' : 'existing_record_id';
                        $team = $this->teamCategory->where($id, $menuItem->reference_id)
                            ->where('language_id', $language_id)->where('is_active', 1)->first();
                        if ($team) {
                            $data['title'] = $team->title;
                            $data['slug'] = $team->slug;
                            $data['url'] = url('/board-management'.'/' . $team->slug);
                            $data['relative_url'] = '/board-management'.'/' . $team->slug;
                        }
                        break;
                    case 'news':
                        $id = $language_id == 1 ? 'id' : 'existing_record_id';
                        $news = $this->news->where($id, $menuItem->reference_id)
                            ->where('language_id', $language_id)->where('is_active', 1)->first();
                        if ($news) {
                            $data['title'] = $news->title;
                            $data['slug'] = $news->slug;
                            $data['url'] = url('/news' . '/' . $news->slug);
                            $data['relative_url'] = '/news' . '/' . $news->slug;
                        }
                        break;
                    case 'service':
                        $id = $language_id == 1 ? 'id' : 'existing_record_id';
                        $service =  $this->post->where($id, $menuItem->reference_id)
                            ->where('language_id', $language_id)->where('is_active', 1)
                            ->where('type', ConstantHelper::POST_TYPE_SERVICE)->first();
                        if ($service) {
                            $data['title'] = $service->title;
                            $data['slug'] = $service->slug;
                            $data['url'] = isset($service->url) && !empty($service->url) ? $service->url : url('/services' . '/' . $service->slug);
                            $data['relative_url'] = isset($service->url) && !empty($service->url) ? $service->url : '/services' . '/' . $service->slug;
                            $data['target'] = isset($service->link_target) && !empty($service->link_target) ? '_blank' : $data['target'];
                        }
                        break;
                    case 'offer':
                        $id = $language_id == 1 ? 'id' : 'existing_record_id';
                        $service =  $this->post->where($id, $menuItem->reference_id)
                            ->where('language_id', $language_id)->where('is_active', 1)
                            ->where('type', ConstantHelper::POST_TYPE_OFFER)->first();
                        if ($service) {
                            $data['title'] = $service->title;
                            $data['slug'] = $service->slug;
                            $data['url'] = isset($service->url) && !empty($service->url) ? $service->url : url('/services' . '/' . $service->slug);
                            $data['relative_url'] = isset($service->url) && !empty($service->url) ? $service->url : '/services' . '/' . $service->slug;
                            $data['target'] = isset($service->link_target) && !empty($service->link_target) ? '_blank' : $data['target'];
                        }
                        break;
                    case 'account-type':
                        $id = $language_id == 1 ? 'id' : 'existing_record_id';
                        $service =  $this->accountType->where($id, $menuItem->reference_id)
                            ->where('language_id', $language_id)->where('is_active', 1)->first();
                        if ($service) {
                            $data['title'] = $service->title;
                            $data['slug'] = $service->slug;
                            // $data['url'] = isset($service->url) && !empty($service->url) ? $service->url : url('/products' . '/' . $service->Category?->slug . '/' . $service->slug);
                            $data['url'] = isset($service->url) && !empty($service->url) ? $service->url : url('/products' . '/' . $service->slug);
                            // $data['relative_url'] = isset($service->url) && !empty($service->url) ? $service->url : '/products' . '/' . $service->Category?->slug .  '/' . $service->slug;

                            $data['relative_url'] = isset($service->url) && !empty($service->url) ? $service->url : '/products' . '/' . $service->slug;
                            $data['target'] = isset($service->link_target) && !empty($service->link_target) ? '_blank' : $data['target'];
                        }
                        break;
                    case 'account-type':
                        $id = $language_id == 1 ? 'id' : 'existing_record_id';
                        $service =  $this->accountType->where($id, $menuItem->reference_id)
                            ->where('language_id', $language_id)->where('is_active', 1)->first();
                        if ($service) {
                            $data['title'] = $service->title;
                            $data['slug'] = $service->slug;
                            $data['url'] = isset($service->url) && !empty($service->url) ? $service->url : url('/products' . '/' . $service->Category?->slug . '/' . $service->slug);
                            $data['relative_url'] = isset($service->url) && !empty($service->url) ? $service->url : '/products' . '/' . $service->Category?->slug .  '/' . $service->slug;
                            $data['target'] = isset($service->link_target) && !empty($service->link_target) ? '_blank' : $data['target'];
                        }
                        break;
                    default:
                        $data['title'] = $menuItem->title;
                        $dat['slug'] = $menuItem->slug;
                        $data['url'] = $menuItem->link_url;
                        $data['relative_url'] = $menuItem->slug;
                        break;
                }
            } else {
                if ($menuItem->language_id != $language_id) {
                    return $data;
                }
                $data['title'] = $menuItem->title;
                $data['slug'] = $menuItem->slug;
                $data['url'] = $menuItem->link_url;
            }
        }
        return $data;
    }

    public function getChild($menu_id, $parent_id)
    {
        $language_id = $this->preferredLanguage;
        return $this->menuItem->where('menu_id', $menu_id)
            ->where('parent_id', $parent_id)
            ->where('language_id', $language_id)
            ->where('is_active', 1)->orderBy('display_order', 'asc')->get();
    }

    public function hasChild($menu_id, $parent_id)
    {
        if ($this->menuItem->where('menu_id', $menu_id)->where('parent_id', $parent_id)->first()) {
            return true;
        }
        return false;
    }

    public function getMenu($id)
    {
        return $this->menu->find($id);
    }

    public function getMenuItem($id)
    {
        return $this->menuItem->where('menu_id', $id)->orderBy('display_order', 'asc')->get();
    }

    public function getParentMenu($id)
    {
        return $this->menuItem->where('menu_id', $id)
            ->where('language_id', Helper::locale())
            ->whereNull('parent_id')->orderBy('display_order', 'asc')->get();
    }

    public function frontendMenu($id = null)
    {
        $items =  $this->menuItem;
        if ($id) {
            $items = $items->where('menu_id', $id);
        }
        return $items->where('language_id', Helper::locale())
            ->where('is_active', 1)
            ->orderBy('parent_id', 'asc')
            ->orderBy('display_order', 'asc')->get();
    }
}
