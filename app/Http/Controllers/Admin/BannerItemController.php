<?php

namespace App\Http\Controllers\Admin;

use App\Helper\MediaHelper;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\BannerItemRequest;
use App\Repositories\BannerItemRepository;
use App\Repositories\BannerRepository;
use Illuminate\Http\Request;

class BannerItemController extends Controller
{
    protected $banner, $preferredLanguage;

    public function __construct(
        BannerRepository $banner,
        BannerItemRepository $bannerItem
    ) {
        $this->banner = $banner;
        $this->bannerItem = $bannerItem;
        $this->preferredLanguage = session('site_settings')['preferred_language'];

        auth()->shouldUse('admin');
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index($id)
    {
        $this->authorize('master-policy.perform', ['banner', 'view']);
        $banner = $this->banner->find($id);
        $bannerItems = $this->bannerItem->where('language_id', $this->preferredLanguage)->where('banner_id', $id)->orderBy('display_order', 'asc')->get();
        return view('admin.bannerItem.index', ['banner' => $banner, 'bannerItems' => $bannerItems]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create($id)
    {
        $this->authorize('master-policy.perform', ['banner', 'add']);
        $banner = $this->banner->find($id);
        return view('admin.bannerItem.create', ['banner' => $banner]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store($id, BannerItemRequest $request)
    {
        $this->authorize('master-policy.perform', ['banner', 'add']);
        $data = $request->except(['image']);

        if ($request->hasFile('image')) {
            $filelocation = MediaHelper::upload($request->file('image'), 'banner', true, true);
            $data['image'] = $filelocation['storage'];
        }
        $data['show_info'] = isset($data['show_info']) ? 1 : 0;
        $data['is_active'] = isset($data['is_active']) ? 1 : 0;
        $preferred_language = $this->preferredLanguage;
        /*
         *
         * Insert Preferred Language Item First.
         */
        $preferred_language_item = $data;
        $preferred_language_item['language_id'] = $preferred_language;
        $preferred_language_item['banner_id'] = $id;
        $preferred_language_item['title'] = $data['title'][$preferred_language];
        $preferred_language_item['description'] = $data['description'][$preferred_language];

        $preferred_insert = $this->bannerItem->create($preferred_language_item);

        if ($preferred_insert) {
            $lang_items = [];
            $count = 0;

            unset($data['_token']);
            foreach ($data['title'] as $language_id => $value) {

                if ($language_id != $preferred_language) {
                    if ($data['title'][$language_id] != NULL && !empty($value)) {
                        $banner = $this->banner->where('language_id', $language_id)->where('existing_record_id', $id)->first();
                        $lang_items[$count] = $data;
                        $lang_items[$count]['image'] = isset($data['image']) && !empty($data['image']) ? $data['image'] : $preferred_insert->image;
                        $lang_items[$count]['existing_record_id'] = $preferred_insert->id;
                        $lang_items[$count]['banner_id'] = $banner ? $banner->id : $id;
                        $lang_items[$count]['language_id'] = $language_id;
                        $lang_items[$count]['title'] = $data['title'][$language_id];
                        $lang_items[$count]['description'] = $data['description'][$language_id];
                        $lang_items[$count]['created_by'] = $preferred_insert->created_by;
                        $lang_items[$count]['created_at'] = $preferred_insert->created_at;

                        $count++;
                    }
                }
            }

            if (!empty($lang_items)) {
                $this->bannerItem->model()->insert($lang_items);
            }
            return redirect()->route('admin.banner-item.index', $id)
                ->with('flash_notice', 'Banner item Created Successfully.');
        }

        return redirect()->back()->withInput()
            ->with('flash_notice', 'Banner item can not be created.');
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
        $this->authorize('master-policy.perform', ['banner', 'edit']);
        $banner = $this->banner->find($id);
        $bannerItem = $this->bannerItem->find($itemId);
        $lang_content = $this->bannerItem->where('existing_record_id', $bannerItem->id)->where('language_id', '!=', $this->preferredLanguage)->get();
        $lang_content = $lang_content->groupBy('language_id');
        return view('admin.bannerItem.edit', ['preferredLanguage' => $this->preferredLanguage, 'banner' => $banner, 'bannerItem' => $bannerItem, 'langContent' => $lang_content]);
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
        $this->authorize('master-policy.perform', ['banner', 'edit']);
        $banner = $this->banner->find($id);
        $bannerItem = $this->bannerItem->find($itemId);
        $data = $request->except(['image', '_token', '_method', 'honeycomb']);

        if ($request->hasFile('image')) {
            MediaHelper::destroy($bannerItem->image);
            $filelocation = MediaHelper::upload($request->file('image'), 'banner', true, true);
            $data['image'] = $filelocation['storage'];
        }
        $data['link_target'] = isset($data['link_target']) ? $data['link_target'] : 0;
        $data['show_info'] = isset($data['show_info']) ? $data['show_info'] : 0;
        $data['is_active'] = isset($data['is_active']) ? 1 : 0;

        $preferred_language = $this->preferredLanguage;

        $existing_record_id = $this->bannerItem->find($data['post'][$preferred_language]) ?? 0;

        if ($existing_record_id) {
            foreach ($data['title'] as $language_id => $value) {

                if ($data['title'][$language_id] != NULL) {
                    $lang_items = $data;
                    $lang_items['image'] = isset($data['image']) && !empty($data['image']) ? $data['image'] : $existing_record_id->image;
                    $lang_items['language_id'] = $language_id;
                    $lang_items['existing_record_id'] = ($language_id != $preferred_language) ? $existing_record_id->id : null;
                    if ($language_id != $preferred_language) {
                        $banner = $this->banner->where('language_id', $language_id)->where('existing_record_id', $id)->first();
                    }
                    $lang_items['banner_id'] = $banner ? $banner->id : $id;
                    $lang_items['title'] = $data['title'][$language_id];
                    $lang_items['description'] = $data['description'][$language_id];

                    $this->bannerItem->model()->updateOrCreate(['id' => $data['post'][$language_id]], $lang_items);
                }
            }
            return redirect()->route('admin.banner-item.index', $id)
                ->with('flash_notice', 'Banner item updated successfully');
        } else {
            return redirect()->back()->withInput()
                ->with('flash_notice', 'Banner item can not be updated.');
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id, $itemId)
    {
        $this->authorize('master-policy.perform', ['banner', 'delete']);
        $banner = $this->bannerItem->find($itemId);
        if ($this->bannerItem->destroy($banner->id)) {
            MediaHelper::destroy($banner->image);
            $message = 'Banner deleted successfully.';
            return response()->json(['status' => 'ok', 'message' => $message], 200);
        }

        return response()->json(['status' => 'error', 'message' => ''], 422); //
    }

    public function changeStatus(Request $request)
    {
        $this->authorize('master-policy.perform', ['banner', 'changeStatus']);
        $banner = $this->bannerItem->find($request->get('id'));
        if ($banner->is_active == 0) {
            $status = 1;
            $message = 'Banner item is published.';
        } else {
            $status = 0;
            $message = 'Banner item is unpublished.';
        }

        $this->bannerItem->changeStatus($banner->id, $status);
        if ($multiContenct = $this->bannerItem->where('existing_record_id', $banner->id)->first()) {
            $this->bannerItem->changeStatus($multiContenct->id, $status);
        }
        $updated = $this->bannerItem->find($request->get('id'));
        return response()->json(['status' => 'ok', 'message' => $message, 'response' => $updated], 200);
    }

    public function sort(Request $request)
    {
        $exploded = explode('&', str_replace('item[]=', '', $request->order));
        $language_id = $request->language_id ?? 1;

        for ($i = 0; $i < count($exploded); $i++) {
            $this->bannerItem->update($exploded[$i], ['display_order' => $i]);
        }

        $preferred_language = $this->preferredLanguage;

        $other_posts = $this->bannerItem->whereIn('existing_record_id', $exploded)->get();
        $other_posts_grouped_language = $other_posts->groupBy('language_id');

        $english_sort = $this->bannerItem->where('language_id', $preferred_language)->pluck('display_order', 'id')->toArray();

        if ($other_posts_grouped_language) {
            foreach ($other_posts_grouped_language as $language => $records) {
                foreach ($records as $record) {
                    $this->bannerItem->update($record->id, ['display_order' => $english_sort[$record->existing_record_id]]);
                }
            }
        }
        return json_encode(['status' => 'success', 'value' => 'Successfully reordered.'], 200);
    }

    public function deleteImage($id)
    {
        if ($bannerItem = $this->bannerItem->find($id)) {
            MediaHelper::destroy($bannerItem->image);
            $bannerItem->image = '';
            $bannerItem->save();
            if ($langItems = $this->bannerItem->where('existing_record_id', $id)->get()) {
                foreach ($langItems as $record) {
                    $this->bannerItem->update($record->id, ['image' => '']);
                }
            }
            return 'success';
        }
        return 'error';
    }
}
