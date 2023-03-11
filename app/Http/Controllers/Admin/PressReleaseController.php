<?php

namespace App\Http\Controllers\Admin;

use App\Helper\ConstantHelper;
use App\Helper\MediaHelper;
use App\Helper\SettingHelper;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\PressRelease\PressReleaseStoreRequest;
use App\Http\Requests\Admin\PressRelease\PressReleaseUpdateRequest;
use App\Repositories\NoticeRepository;
use Illuminate\Http\Request;

class PressReleaseController extends Controller
{
    public $title = 'Notice';
    protected $notice, $preferredLanguage;

    public function __construct(
        NoticeRepository $notice
    ) {
        $this->notice = $notice;
        $this->preferredLanguage = SettingHelper::setting('preferred_language') == null ? 1 : SettingHelper::setting('preferred_language');

        auth()->shouldUse('admin');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $this->authorize('master-policy.perform', ['press-release', 'view']);
        $title = $this->title;

        $contents = $this->notice->where('type', ConstantHelper::NOTICE_TYPE_PRESS_RELEASE)->where('language_id', $this->preferredLanguage)
            ->orderBy('display_order')
            ->orderBy('start_date', 'desc')
            ->get();
        return view('admin.pressRelease.index')
            ->withContents($contents)
            ->withTitle($title);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $this->authorize('master-policy.perform', ['press-release', 'add']);
        $title = 'Add Notice';
        return view('admin.pressRelease.create')
            ->withTitle($title);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(PressReleaseStoreRequest $request)
    {
        $this->authorize('master-policy.perform', ['press-release', 'add']);
        $data = $request->except(['image']);
        if ($request->hasFile('image')) {
            $filelocation = MediaHelper::upload($request->file('image'), 'notice');
            $data['image'] = $filelocation['storage'];
        }

        if ($request->hasFile('file')) {
            $fileName = rand(0, 9999) . '-' . $request->file('file')->getClientOriginalName();
            $filelocation = MediaHelper::uploadDocument($request->file('file'), 'notices',  $fileName);
            $data['link'] = $filelocation;
        }
        $data['type'] = ConstantHelper::NOTICE_TYPE_PRESS_RELEASE;

        $preferred_language = $this->preferredLanguage;
        /*
         *
         * Insert Preferred Language Item First.
         */

        $preferred_language_item = $data;
        $preferred_language_item['language_id'] = $preferred_language;
        $preferred_language_item['title'] = $data['title'][$preferred_language];
        $preferred_language_item['excerpt'] = $data['excerpt'][$preferred_language];
        $preferred_language_item['description'] = $data['description'][$preferred_language];

        $preferred_insert = $this->notice->create($preferred_language_item);

        if ($preferred_insert) {
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
                        $lang_items[$count]['slug'] = $preferred_insert->slug;
                        $lang_items[$count]['excerpt'] = $data['excerpt'][$language_id];
                        $lang_items[$count]['description'] = $data['description'][$language_id];
                        $lang_items[$count]['display_order'] = $preferred_insert->press_release == null ? 1 : $preferred_insert->press_release;
                        $lang_items[$count]['created_by'] = $preferred_insert->created_by;
                        $lang_items[$count]['created_at'] = $preferred_insert->created_at;

                        $count++;
                    }
                }
            }

            if (!empty($lang_items)) {
                $this->notice->model()->insert($lang_items);
            }
            return redirect()->route('admin.notice.index')
                ->with('flash_notice', 'Notice Created Successfully.');
        }

        return redirect()->back()->withInput()
            ->with('flash_notice', 'Notice can not be created.');
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
    public function edit($id)
    {
        $this->authorize('master-policy.perform', ['press-release', 'edit']);
        $title = 'Edit notice';
        $notice = $this->notice->find($id);


        $lang_content = $this->notice->where('existing_record_id', $notice->id)->where('language_id', '!=', $this->preferredLanguage)->get();
        $lang_content = $lang_content->groupBy('language_id');


        return view('admin.pressRelease.edit')
            ->withContent($notice)
            ->withLangContent($lang_content)
            ->withPreferredLanguage($this->preferredLanguage)
            ->withTitle($title);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(PressReleaseUpdateRequest $request, $id)
    {
        $this->authorize('master-policy.perform', ['press-release', 'edit']);
        $notice = $this->notice->find($id);
        $data = $request->except(['image']);
        if ($request->hasFile('image')) {
            if (file_exists('storage/' . $notice->image) && !empty($notice->image)) {
                MediaHelper::destroy($notice->image);
            }
            $filelocation = MediaHelper::upload($request->file('image'), 'notice');
            $data['image'] = $filelocation['storage'];
        }
        if ($request->hasFile('file')) {
            $fileName = rand(0, 9999) . '-' . $request->file('file')->getClientOriginalName();
            $filelocation = MediaHelper::uploadDocument($request->file('file'), 'notices',  $fileName);
            $data['link'] = $filelocation;
        }
        $data['type'] = ConstantHelper::NOTICE_TYPE_PRESS_RELEASE;
        $data['is_active'] = isset($request['is_active']) ? 1 : 0;

        $preferred_language = $this->preferredLanguage;

        $existing_record_id = $this->notice->find($data['post'][$preferred_language]) ?? 0;

        if ($existing_record_id) {
            foreach ($data['title'] as $language_id => $value) {

                if ($data['title'][$language_id] != NULL) {
                    $lang_items = $data;
                    $lang_items['image'] = isset($data['image']) && !empty($data['image']) ? $data['image'] : $existing_record_id->image;
                    $lang_items['language_id'] = $language_id;
                    $lang_items['existing_record_id'] = ($language_id != $preferred_language) ? $existing_record_id->id : null;
                    $lang_items['title'] = $data['title'][$language_id];
                    $lang_items['slug'] =  $notice->slug;
                    $lang_items['excerpt'] = $data['excerpt'][$language_id];
                    $lang_items['description'] = $data['description'][$language_id];
                    $lang_items['display_order'] = $existing_record_id->display_order == null ? 1 : $existing_record_id->display_order;

                    $this->notice->model()->updateOrCreate(['id' => $data['post'][$language_id]], $lang_items);
                }
            }
            return redirect()->route('admin.notice.index')
                ->with('flash_notice', 'Notice updated successfully');
        } else {
            return redirect()->back()->withInput()
                ->with('flash_notice', 'Notice can not be updated.');
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $this->authorize('master-policy.perform', ['press-release', 'delete']);
        $this->notice->where('existing_record_id', $id)->delete();
        $notice = $this->notice->find($id);
        if ($this->notice->destroy($notice->id)) {
            // if (!empty($notice->image)) {
            //     MediaHelper::destroy($notice->image);
            // }
            $message = 'Notice deleted successfully.';
            return response()->json(['status' => 'ok', 'message' => $message], 200);
        }
        return response()->json(['status' => 'error', 'message' => ''], 422);
    }

    public function changeStatus(Request $request)
    {
        $this->authorize('master-policy.perform', ['press-release', 'changeStatus']);
        $notice = $this->notice->find($request->get('id'));
        if ($notice->is_active == 0) {
            $status = 1;
            $message = 'Notice is published.';
        } else {
            $status = 0;
            $message = 'Notice is unpublished.';
        }

        $this->notice->changeStatus($notice->id, $status);
        $this->notice->update($notice->id, array('updated_by' => auth()->id()));
        $updated = $this->notice->find($request->get('id'));
        if ($multiContent = $this->notice->where('existing_record_id', $notice->id)->first()) {
            $this->notice->changeStatus($multiContent->id, $status);
        }
        return response()->json(['status' => 'ok', 'message' => $message, 'response' => $updated], 200);
    }

    public function sort(Request $request)
    {

        $exploded = explode('&', str_replace('item[]=', '', $request->order));
        $language_id = $request->language_id ?? 1;

        for ($i = 0; $i < count($exploded); $i++) {
            $this->notice->update($exploded[$i], ['display_order' => $i]);
        }

        $preferred_language = $this->preferredLanguage;

        $other_posts = $this->notice->whereIn('existing_record_id', $exploded)->get();
        $other_posts_grouped_language = $other_posts->groupBy('language_id');

        $english_sort = $this->notice->where('language_id', $preferred_language)->pluck('display_order', 'id')->toArray();

        if ($other_posts_grouped_language) {
            foreach ($other_posts_grouped_language as $language => $records) {
                foreach ($records as $record) {
                    $this->notice->update($record->id, ['display_order' => $english_sort[$record->existing_record_id]]);
                }
            }
        }
        return json_encode(['status' => 'success', 'value' => 'Successfully reordered.'], 200);
    }

    public function destroyImage(Request $request, $id)
    {
        $content = $this->notice->find($id);
        if ($content) {
            switch ($request->post('type')) {
                case 'banner':
                    MediaHelper::destroy($content->banner);
                    $content->banner = null;
                    break;
                case 'link':
                    MediaHelper::destroy($content->link);
                    $content->link = null;
                    break;
                case 'image':
                    MediaHelper::destroy($content->image);
                    $content->image = null;
                    break;
            }
            $content->save();
            $message = 'Content deleted successfully.';

            return response()->json(['status' => 'ok', 'message' => $message], 200);
        }

        return response()->json(['status' => 'error', 'message' => ''], 422);
    }
}
