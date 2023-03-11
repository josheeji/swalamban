<?php

namespace App\Http\Controllers\Admin;

use App\Helper\SettingHelper;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Menu\MenuStoreRequest;
use App\Http\Requests\Admin\Menu\MenuUpdateRequest;
use App\Repositories\MenuRepository;
use Illuminate\Http\Request;

class MenuController extends Controller
{
    protected $menu, $preferredLanguage;

    public function __construct(
        MenuRepository $menu
    ) {
        $this->menu = $menu;
        $this->preferredLanguage = SettingHelper::setting('preferred_language') == null ? 1 : SettingHelper::setting('preferred_language');
        auth()->shouldUse('admin');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $this->authorize('master-policy.perform', ['menu', 'view']);
        $menus = $this->menu->where('language_id', $this->preferredLanguage)->orderBy('display_order', 'asc')->get();
        return view('admin.menu.index', ['menus' => $menus]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $this->authorize('master-policy.perform', ['menu', 'add']);
        return view('admin.menu.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(MenuStoreRequest $request)
    {
        $this->authorize('master-policy.perform', ['menu', 'add']);
        $data = $request->except(['_token']);
        $preferred_language = $this->preferredLanguage;
        $preferred_language_item = $data;
        $preferred_language_item['language_id'] = $preferred_language;
        $preferred_language_item['title'] = $data['title'][$preferred_language];
        $preferred_language_item['location_main'] = isset($data['location_main']) ? $data['location_main'] : 0;
        $preferred_language_item['location_footer'] = isset($data['location_footer']) ? $data['location_footer'] : 0;
        $preferred_language_item['location_aside'] = isset($data['location_aside']) ? $data['location_aside'] : 0;
        if ($preferred_insert = $this->menu->create($preferred_language_item)) {
            $lang_items = [];
            $count = 0;
            unset($data['_token']);
            foreach ($data['title'] as $language_id => $value) {
                if ($language_id != $preferred_language) {
                    if ($data['title'][$language_id] != NULL) {
                        $lang_items[$count] = $data;
                        $lang_items[$count]['existing_record_id'] = $preferred_insert->id;
                        $lang_items[$count]['language_id'] = $language_id;
                        $lang_items[$count]['title'] = $data['title'][$language_id];
                        $lang_items[$count]['location_main'] = $preferred_insert->location_main;
                        $lang_items[$count]['location_footer'] = $preferred_insert->location_footer;
                        $lang_items[$count]['location_aside'] = $preferred_insert->location_aside;
                        $lang_items[$count]['created_by'] = $preferred_insert->created_by;
                        $lang_items[$count]['created_at'] = $preferred_insert->created_at;
                        $count++;
                    }
                }
            }
            if (!empty($lang_items)) {
                $this->menu->model()->insert($lang_items);
            }
            return redirect()->route('admin.menu.index')->with('flash_notice', 'Menu created successfully.');
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
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $this->authorize('master-policy.perform', ['menu', 'edit']);
        $menu = $this->menu->find($id);
        $lang_content = $this->menu->where('existing_record_id', $menu->id)->where('language_id', '!=', $this->preferredLanguage)->get();
        $langContent = $lang_content->groupBy('language_id');
        return view('admin.menu.edit', ['menu' => $menu, 'lang_content' => $lang_content, 'preferredLanguage' => $this->preferredLanguage]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(MenuUpdateRequest $request, $id)
    {
        $this->authorize('master-policy.perform', ['menu', 'edit']);
        $data = $request->all();
        $preferred_language = $this->preferredLanguage;
        $existing_record_id = $this->menu->find($data['post'][$preferred_language]) ?? 0;
        if ($existing_record_id) {
            foreach ($data['title'] as $language_id => $value) {
                if ($data['title'][$language_id] != NULL) {
                    $lang_items = $data;
                    $lang_items['language_id'] = $language_id;
                    $lang_items['existing_record_id'] = ($language_id != $preferred_language) ? $existing_record_id->id : null;
                    $lang_items['title'] = $data['title'][$language_id];
                    $lang_items['location_aside'] = isset($data['location_main']) && $data['location_main'] == 1 ? 1 : 0;
                    $lang_items['location_aside'] = isset($data['location_aside']) && $data['location_aside'] == 1 ? 1 : 0;
                    $lang_items['location_footer'] = isset($data['location_footer']) && $data['location_footer'] == 1 ? 1 : 0;
                    $lang_items['is_active'] = isset($data['is_active']) && $data['is_active'] == 1 ? 1 : 0;

                    $this->menu->model()->updateOrCreate(['id' => $data['post'][$language_id]], $lang_items);
                }
            }
            return redirect()->route('admin.menu.index')->with('flash_success', 'Menu updated successfully');
        }
        return redirect()->back()->withInput()->with('flash_notice', 'Menu can not be updated.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        if ($this->menu->destroy($id)) {
            return response()->json(['status' => 'ok', 'message' => 'Menu deleted successfully.'], 200);
        }
        return response()->json(['status' => 'error', 'message' => ''], 422);
    }

    public function sort(Request $request)
    {
        $exploded = explode('&', str_replace('item[]=', '', $request->order));
        for ($i = 0; $i < count($exploded); $i++) {
            $this->menu->update($exploded[$i], ['display_order' => $i]);
        }

        return json_encode(['status' => 'success', 'value' => 'Successfully reordered.'], 200);
    }

    public function changeStatus(Request $request)
    {
        $this->authorize('master-policy.perform', ['menu', 'changeStatus']);
        $menu = $this->menu->find($request->get('id'));
        $status = $menu->is_active == 0 ? 1 : 0;
        $message = $menu->is_active == 0 ? 'Menu published.' : 'Menu unpublished.';
        $this->menu->changeStatus($menu->id, $status);
        $updated = $this->menu->find($request->get('id'));
        return response()->json(['status' => 'ok', 'message' => $message, 'response' => $updated], 200);
    }
}
