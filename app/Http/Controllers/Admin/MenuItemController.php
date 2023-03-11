<?php

namespace App\Http\Controllers\Admin;

use App\Helper\ConstantHelper;
use App\Helper\MediaHelper;
use App\Helper\SettingHelper;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\MenuItem\MenuItemStoreRequest;
use App\Models\Menu;
use App\Models\MenuItems;
use App\Repositories\AccountTypeRepository;
use App\Repositories\BlogRepository;
use App\Repositories\ContentRepository;
use App\Repositories\MenuItemRepository;
use App\Repositories\MenuRepository;
use App\Repositories\ModuleRepository;
use App\Repositories\NewsRepository;
use App\Repositories\PostRepository;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Spatie\SchemaOrg\MenuItem;
use App\Repositories\TeamCategoryRepository;

class MenuItemController extends Controller
{
    public $title = 'Menu';

    protected $menu, $menuItem, $module, $preferredLanguage;

    public function __construct(
        MenuRepository $menu,
        MenuItemRepository $menuItem,
        ModuleRepository $module,
        ContentRepository $content,
        BlogRepository $blog,
        NewsRepository $news,
        PostRepository $post,
        AccountTypeRepository $accountType,
        TeamCategoryRepository $teamCategory
    ) {
        $this->menu = $menu;
        $this->menuItem = $menuItem;
        $this->module = $module;
        $this->content = $content;
        $this->blog = $blog;
        $this->news = $news;
        $this->post = $post;
        $this->teamCategory = $teamCategory;
        $this->accountType = $accountType;
        $this->preferredLanguage = SettingHelper::setting('preferred_language') == null ? 1 : SettingHelper::setting('preferred_language');
        auth()->shouldUse('admin');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index($id)
    {
        $title = $this->title;
        $menu = $this->menu->find($id);
        $modules = $this->module->whereIn('alias', ['content', 'account-type', 'team-category'])->get();
        $customLinks = $this->menu->customLinks($id);
        $menuItems = '';
        $items = $this->menuItem->items($id);
        return view('admin.menuItem.index', compact('title', 'menu', 'modules', 'customLinks', 'items'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create($id)
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(MenuItemStoreRequest $request, $id)
    {
        $data = $request->except(['_token']);
        $data['menu_id'] = $id;

        if ($data['type'] == ConstantHelper::MENU_TYPE_CUSTOM) {
            $preferred_language = $this->preferredLanguage;
            $preferred_language_item = $data;
            $preferred_language_item['language_id'] = $preferred_language;
            $preferred_language_item['title'] = $data['title'][$preferred_language];
            $preferred_language_item['link_url'] = isset($data['link_url']) ? $data['link_url'] : '#';
            $preferred_language_item['link_target'] = isset($data['link_target']) ? $data['link_target'] : 0;

            if ($preferred_insert = $this->menuItem->create($preferred_language_item)) {
                $lang_items = [];
                $count = 0;

                unset($data['_token']);
                foreach ($data['title'] as $language_id => $value) {
                    if ($language_id != $preferred_language) {
                        if ($data['title'][$language_id] != NULL) {
                            $lang_items[$count] = $data;
                            $lang_items[$count]['existing_record_id'] = $preferred_insert->id;
                            $lang_items[$count]['language_id'] = $language_id;
                            $lang_items[$count]['parent_id'] = $preferred_insert->parent_id;
                            $lang_items[$count]['title'] = $data['title'][$language_id];
                            $lang_items[$count]['slug'] = $preferred_insert->slug;
                            $lang_items[$count]['link_url'] = isset($data['link_url']) && !empty($data['link_url']) ? $data['link_url'] : 0;
                            $lang_items[$count]['link_target'] = isset($data['link_target']) ? $data['link_target'] : 0;
                            $count++;
                        }
                    }
                }
                if ($this->menuItem->model()->insert($lang_items)) {
                    return redirect()->route('admin.menu-item.index', $id)->with('flash_notice', 'Menu item(s) created successfully.');
                }
            }
        } else {
            $module = $this->module->find($data['module_id']);
            foreach ($data['existing_record_id'] as $key => $value) {
                if (!$this->menuItem->where('menu_id', $id)->where('module_id', $data['module_id'])->where('existing_record_id', $value)->first()) {
                    $menuItem = $this->menu->getContent($module->alias, $value);
                    $url = '';
                    switch ($module->alias) {
                        case 'content':
                            $url = '';
                            break;
                        case 'offer':
                            $url = 'offers';
                            break;
                        case 'account-type':
                            // $url = 'products/' . $menuItem->Category?->slug;
                            $url = 'products';
                            break;
                        case 'team-category':
                            $url = 'board-management';
                            break;
                    }
                    $menuItemData['module_id'] = $data['module_id'];
                    $menuItemData['menu_id'] = $id;
                    $menuItemData['language_id'] = isset($menuItem->language_id) ? $menuItem->language_id : 1;
                    $menuItemData['reference_id'] = $value;
                    $menuItemData['title'] = isset($menuItem->title) ? $menuItem->title : '';
                    $menuItemData['slug'] = isset($menuItem->slug) ? $menuItem->slug : '';
                    $menuItemData['link_url'] = isset($menuItem->slug) ? $url . '/' . $menuItem->slug : '';
                    $menuItemData['type'] = 1;
                    $this->menuItem->model()->insert($menuItemData);
                    $data = $this->menuItem->where('menu_id', $id)->where('module_id', $data['module_id'])->where('reference_id', $value)->first();
                    $langContent = $this->menu->moduleContents($module->alias, $value);
                    if ($langContent) {
                        foreach ($langContent as $content) {
                            $multiContent['module_id'] = $data['module_id'];
                            $multiContent['menu_id'] = $id;
                            $multiContent['language_id'] = $content->language_id;
                            $multiContent['reference_id'] = $data->reference_id;
                            $multiContent['title'] = $content->title;
                            $multiContent['slug'] = $content->slug;
                            $multiContent['link_url'] = isset($menuItem->slug) ? $url . '/' . $menuItem->slug : '';
                            $multiContent['type'] = 1;
                            $multiContent['parent_id'] = $data->id;
                            $multiContent['existing_record_id'] = $data->id;
                            $this->menuItem->model()->insert($multiContent);
                        }
                    }
                }
            }
            return redirect()->route('admin.menu-item.index', $id)->with('flash_notice', 'Menu item(s) created successfully.');
        }

        return redirect()->back()->withInput()->with('flash_notice', 'Menu can not be created.');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id, $itemId)
    {
        $title = $this->title;
        $menuItem = $this->menuItem->find($itemId);
        $lang_content = $this->menuItem->where('existing_record_id', $menuItem->id)
            ->where('language_id', '!=', $this->preferredLanguage)->get();
        $langContent = $lang_content->groupBy('language_id');

        return view('admin.menuItem.edit', compact('title', 'menuItem', 'langContent'))
            ->withPreferredLanguage($this->preferredLanguage);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id, $itemId)
    {
        $data = $request->except(['_token', '_method']);
        $data['is_external'] = isset($request['is_external']) ? 1 : 0;
        $data['link_target'] = isset($request['link_target']) ? 1 : 0;
        $data['block'] = isset($request['block']) ? 1 : 0;
        if ($request->hasFile('image')) {
            $filelocation = MediaHelper::upload($request->file('image'), 'menu', false);
            $data['image'] = $filelocation['storage'];
        }

        $menuItem = $this->menuItem->find($itemId);
        if ($menuItem->type == ConstantHelper::MENU_TYPE_CUSTOM) {
            $preferred_language = $this->preferredLanguage;
            $existing_record_id = $this->menuItem->find($data['post'][$preferred_language]) ?? 0;
            if ($existing_record_id) {
                foreach ($data['title'] as $language_id => $value) {
                    if ($data['title'][$language_id] != NULL) {
                        $lang_items = $data;
                        $lang_items['language_id'] = $language_id;
                        $lang_items['existing_record_id'] = ($language_id != $preferred_language) ? $existing_record_id->id : null;
                        $lang_items['title'] = $data['title'][$language_id];
                        $lang_items['slug'] = $existing_record_id->slug;
                        $lang_items['menu_id'] = $existing_record_id->menu_id;
                        $lang_items['type'] = $existing_record_id->type;
                        $lang_items['display_order'] = $existing_record_id->display_order;
                        unset($lang_items['post']);
                        $this->menuItem->model()->updateOrCreate(['id' => $data['post'][$language_id]], $lang_items);
                    }
                }
            }
            return redirect()->route('admin.menu-item.index', $id)
                ->with('flash_notice', 'Menu Item updated successfully');
        } else {
            if ($this->menuItem->update($itemId, $data)) {
                $item = $this->menu->getChild($id, $itemId);
                if ($item->isEmpty()) {
                    $langContent = $this->menu->moduleContents($menuItem->module->alias, $menuItem->reference_id);
                    if ($langContent) {
                        foreach ($langContent as $content) {
                            $multiContent['module_id'] = $menuItem->module_id;
                            $multiContent['menu_id'] = $id;
                            $multiContent['language_id'] = $content->language_id;
                            $multiContent['reference_id'] = $content->id;
                            $multiContent['title'] = $content->title;
                            $multiContent['slug'] = $content->slug;
                            $multiContent['link_url'] = isset($menuItem->slug) ? '/' . $menuItem->slug : '';
                            $multiContent['type'] = 1;
                            $multiContent['parent_id'] = $menuItem->id;
                            $this->menuItem->model()->updateOrInsert($multiContent);
                        }
                    }
                }

                return redirect()->route('admin.menu-item.index', $id)
                    ->with('flash_notice', 'Menu Item updated successfully');
            }
        }

        return redirect()->back()->withInput()->with('flash_notice', 'Menu can not be updated.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, $menu_id, $id)
    {
        $this->menuItem->where('parent_id', $id)->delete();
        if ($this->menuItem->destroy($id)) {
            return response()->json(['status' => 'ok', 'message' => 'Menu deleted successfully.'], 200);
        }
        return response()->json(['status' => 'error', 'message' => ''], 422);
    }

    public function sort(Request $request, $id)
    {
        $list = $request->list;
        if (isset($list)) {
            // $this->deleteDulicateItem(); // Enable to auto delete the duplicate menu items.
            // dd($list);
            foreach ($list as $lvl1 => $data1) {
                $item = $this->menuItem->update($data1['id'], ['display_order' => $lvl1, 'parent_id' => null]);
                $this->updateUrl($item, 1);
                $this->sortMenu($item, $lvl1);
                $this->sortMultiMenu($item, $lvl1, $data1);
                if (isset($data1['children'])) {
                    foreach ($data1['children'] as $lvl2 => $data2) {
                        $item = $this->menuItem->update($data2['id'], ['display_order' => $lvl2, 'parent_id' => $data1['id']]);
                        $this->updateUrl($item, 2);
                        $this->sortMenu($item, $lvl2, $data1['id']);
                        $this->sortMultiMenu($item, $lvl2, $data2, $data1['npl']);
                        if (isset($data2['children'])) {
                            foreach ($data2['children'] as $lvl3 => $data3) {
                                $item = $this->menuItem->update($data3['id'], ['display_order' => $lvl3, 'parent_id' => $data2['id']]);
                                $this->updateUrl($item, 3);
                                $this->sortMenu($item, $lvl3, $data2['id']);
                                $this->sortMultiMenu($item, $lvl3, $data3, $data2['npl']);
                                if (isset($data3['children'])) {
                                    foreach ($data3['children'] as $lvl4 => $data4) {
                                        $item = $this->menuItem->update($data4['id'], ['display_order' => $lvl4, 'parent_id' => $data3['id']]);
                                        $this->updateUrl($item, 4);
                                        $this->sortMenu($item, $lvl4, $data3['id']);
                                        $this->sortMultiMenu($item, $lvl4, $data4, $data3['npl']);
                                    }
                                }
                            }
                        }
                    }
                }
            }
            MenuItems::updateMenuItem();
            return 'success';
        }
    }

    protected function sortMultiMenu($item, $lvl, $data, $multiParentId = null)
    {
        $items = $this->menuItem->where('existing_record_id', $item->id)->where('is_active', 1)->get();
        if ($items) {
            $i = 0;
            foreach ($items as $menuItem) {
                if ($i == 0) {
                    $this->menuItem->update($menuItem->id, ['display_order' => $lvl, 'parent_id' => $multiParentId, 'is_active' => 1]);
                    $i++;
                } else {
                    $this->menuItem->update($menuItem->id, ['is_active' => 0]);
                }
            }
        }
        if (isset($data['new']) && $data['new'] == true && isset($data['multiTitle'])) {
            $this->menuItem->create([
                'title' => $data['multiTitle'],
                'slug' => $item->slug,
                'module' => $item->module,
                'menu_id' => $item->menu_id,
                'existing_record_id' => $item->id,
                'reference_id' => $data['multiId'],
                'parent_id' => $multiParentId,
                'type' => $item->type,
                'is_external' => $item->is_external,
                'link_url' => $item->link_url,
                'link_active' => $item->link_active,
                'block' => $item->block,
                'icon' => $item->icon,
                'link_target' => $item->link_target,
                'display_order' => $item->display_order,
                'is_active' => $item->is_active
            ]);
        }
    }

    protected function deleteDulicateItem()
    {
        $customItems = $this->menuItem->where('language_id', 2)->where('type', 2)->get();
        if ($customItems) {
            foreach ($customItems as $item) {
                $this->menuItem->update($item->id, ['parent_id' => null]);
            }
        }
        $items = $this->menuItem->where('language_id', 2)->where('type', 1)->get();
        if ($items) {
            foreach ($items as $item) {
                $this->menuItem->destroy($item->id);
            }
        }
        $itemLists = $this->menuItem->where('language_id', 1)->where('type', 1)->get();
        if ($itemLists) {
            foreach ($itemLists as $item) {
                $multidata = $this->getContent($item);
                if (!$multidata) {
                    $multidata = $this->getContentBySlug($item);
                }
                if ($multidata) {
                    $data['language_id'] = 2;
                    $data['title'] = $multidata->title;
                    $data['slug'] = $multidata->slug;
                    $data['module_id'] = $item->module_id;
                    $data['menu_id'] = $item->menu_id;
                    $data['existing_record_id'] = $item->id;
                    $data['reference_id'] = $multidata->id;
                    $data['type'] = $item->type;
                    $data['is_external'] = $item->is_external;
                    $data['link_url'] = $item->link_url;
                    $data['link_active'] = $item->link_active;
                    $data['block'] = $item->block;
                    $data['icon'] = $item->icon;
                    $data['link_target'] = $item->link_target;
                    $data['display_order'] = $item->display_order;
                    $data['is_active'] = $item->is_active;
                    $data['created_at'] = Carbon::now();
                    $this->menuItem->model()->insert($data);
                }
            }
        }
    }

    protected function getContent($item)
    {
        switch ($item->module->alias) {
            case 'content':
                return $this->content->where('existing_record_id', $item->id)->where('language_id', 2)->where('is_active', 1)->first();
                break;
            case 'blog':
                return  $this->blog->where('existing_record_id', $item->id)->where('language_id', 2)->where('is_active', 1)->first();
                break;
            case 'news':
                return $this->news->where('existing_record_id', $item->id)->where('language_id', 2)->where('is_active', 1)->first();
                break;
            case 'service':
                return $this->post->where('existing_record_id', $item->id)->where('language_id', 2)->where('is_active', 1)
                    ->where('type', ConstantHelper::POST_TYPE_SERVICE)->first();
                break;
            case 'offer':
                return $this->post->where('existing_record_id', $item->id)->where('language_id', 2)->where('is_active', 1)
                    ->where('type', ConstantHelper::POST_TYPE_OFFER)->first();
                break;
            case 'account-type':
                return $this->accountType->where('existing_record_id', $item->reference_id)->where('language_id', 2)->where('is_active', 1)->first();
                break;
        }
    }

    protected function getContentBySlug($item)
    {
        switch ($item->module->alias) {
            case 'content':
                return $this->content->where('slug', $item->slug)->where('language_id', 2)->where('is_active', 1)->first();
                break;
            case 'blog':
                return  $this->blog->where('slug', $item->slug)->where('language_id', 2)->where('is_active', 1)->first();
                break;
            case 'news':
                return $this->news->where('slug', $item->slug)->where('language_id', 2)->where('is_active', 1)->first();
                break;
            case 'service':
                return $this->post->where('slug', $item->slug)->where('language_id', 2)->where('is_active', 1)
                    ->where('type', ConstantHelper::POST_TYPE_SERVICE)->first();
                break;
            case 'offer':
                return $this->post->where('slug', $item->slug)->where('language_id', 2)->where('is_active', 1)
                    ->where('type', ConstantHelper::POST_TYPE_OFFER)->first();
                break;
            case 'account-type':
                return $this->accountType->where('slug', $item->slug)->where('language_id', 2)->where('is_active', 1)->first();
                break;
        }
    }

    protected function sortMenu($item, $lvl, $parentId = null)
    {
        $items = $this->menuItem->where('existing_record_id', $item->id)->get();
        if ($items) {
            foreach ($items as $list) {
                if ($item->parent_id == null) {
                    $parentId = null;
                } else {
                    $parentLangItem = $this->menuItem->where('existing_record_id', $item->parent_id)->first();

                    if ($parentLangItem) {
                        $parentId = $parentLangItem->id;
                    } else {
                        $parentId = $item->parent_id;
                    }
                }
                $this->menuItem->update($list->id, ['display_order' => $lvl, 'parent_id' => $parentId]);
            }
        }
    }

    protected function updateUrl($item, $lvl)
    {
        switch ($lvl) {
            case 4:
                $item->link_url = $item->parent->parent->parent->slug;
                break;
            case 3:
                $item->link_url = $item->parent->parent->slug;
                break;
            case 2:
                $item->link_url = $item->parent->slug;
                break;
            default:
                $item->link_url = '';
                break;
        }
        if ($item->type == ConstantHelper::MENU_TYPE_CONTENT) {

            $url = $this->menu->content($item);
            // if (!strpos($url['relative_url'], 'products')) {
            //     $item->link_url =  $item->link_url . $url['relative_url'];
            // } else {
            //     $item->link_url = $url['relative_url'];
            // }
            $item->link_url = $url['relative_url'];
            $item->save();
            $langContent = $this->menuItem->where('existing_record_id', $item->id)->get();
            if ($langContent) {
                foreach ($langContent as $content) {
                    $content->link_url = $item->link_url;
                    $content->save();
                }
            }
        }
    }

    public function destroyImage(Request $request, $menuId, $id)
    {
        $content = $this->menuItem->find($id);
        if ($content) {
            switch ($request->post('type')) {
                case 'banner':
                    MediaHelper::destroy($content->banner);
                    $content->banner = null;
                    break;
                case 'image':
                    MediaHelper::destroy($content->image);
                    $content->image = null;
                    break;
            }
            if ($content->save()) {
                $preferred_language = $this->preferredLanguage;
                $other_posts = $this->menuItem->where('existing_record_id', $content->id)->get();
                $other_posts_grouped_language = $other_posts->groupBy('language_id');
                $english_sort = $this->menuItem->where('language_id', $preferred_language)->pluck('display_order', 'id')->toArray();
                if ($other_posts_grouped_language) {
                    foreach ($other_posts_grouped_language as $language => $records) {
                        foreach ($records as $record) {
                            $this->menuItem->update($record->id, ['banner' => $content->banner, 'image' => $content->image]);
                        }
                    }
                }
            }
            $message = 'Image deleted successfully.';
            return response()->json(['status' => 'ok', 'message' => $message], 200);
        }
        return response()->json(['status' => 'error', 'message' => ''], 422);
    }
}
