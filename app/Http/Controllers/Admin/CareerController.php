<?php

namespace App\Http\Controllers\Admin;

use App\Helper\MediaHelper;
use App\Helper\SettingHelper;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Career\CareerStoreRequest;
use App\Http\Requests\Admin\Career\CareerUpdateRequest;
use App\Repositories\CareerRepository;
use Illuminate\Http\Request;

class CareerController extends Controller
{

    protected $career, $preferredLanguage;
    public $title = 'Career';

    public function __construct(CareerRepository $career)
    {
        $this->career = $career;
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
        $this->authorize('master-policy.perform', ['career', 'view']);
        $title = $this->title;
        $careers = $this->career->where('language_id', $this->preferredLanguage)
            ->orderBy('display_order', 'asc')
            ->orderBy('publish_from', 'desc')->get();

        return view('admin.career.index', compact('title', 'careers'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $this->authorize('master-policy.perform', ['career', 'add']);
        $title = $this->title;

        return view('admin.career.create', compact('title'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(CareerStoreRequest $request)
    {
        $preferred_language = $this->preferredLanguage;
        $this->authorize('master-policy.perform', ['career', 'add']);
        $data = $request->except(['_token', 'file']);
        if ($request->hasFile('file')) {
            $fileName = preg_replace("/[\s_]/", "-", $data['title'][1]);
            $fileExtention = $request->file('file')->extension();
            $fileName = $fileName . '.' . $fileExtention;
            $filelocation = MediaHelper::uploadDocument($request->file('file'), 'career',  $fileName);
            $data['file'] = $filelocation;
        }
        $data['is_active'] = isset($data['is_active']) ? 1 : 0;
        /*
         *
         * Insert Preferred Language Item First.
         */
        $preferred_language_item = $data;
        $preferred_language_item['language_id'] = $preferred_language;
        $preferred_language_item['title'] = $data['title'][$preferred_language];
        $preferred_language_item['description'] = $data['description'][$preferred_language]??'';

        $preferred_insert = $this->career->create($preferred_language_item);
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
                        $lang_items[$count]['description'] = $data['description'][$language_id]??'';
                        $lang_items[$count]['display_order'] = $preferred_insert->display_order == null ? 0 : $preferred_insert->display_order;
                        $lang_items[$count]['created_by'] = $preferred_insert->created_by;
                        $lang_items[$count]['created_at'] = $preferred_insert->created_at;
                        $count++;
                    }
                }
            }
            if (!empty($lang_items)) {
                $this->career->model()->insert($lang_items);
            }

            return redirect()->route('admin.career.index')
                ->with('flash_notice', 'Career created successfully.');
        }

        return redirect()->back()->withInput()
            ->with('flash_notice', 'Career can not be created.');
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
        $this->authorize('master-policy.perform', ['career', 'edit']);
        $title = $this->title;
        $career = $this->career->find($id);
        $lang_content = $this->career->where('existing_record_id', $career->id)
            ->where('language_id', '!=', $this->preferredLanguage)->get();
        $lang_content = $lang_content->groupBy('language_id');

        return view('admin.career.edit')
            ->withCareer($career)
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
    public function update(CareerUpdateRequest $request, $id)
    {
        $this->authorize('master-policy.perform', ['career', 'edit']);
        $career = $this->career->find($id);
        $data = $request->except(['_token', 'file']);
        if ($request->hasFile('file')) {
            MediaHelper::destroy($career->file);
            $fileName = preg_replace("/[\s_]/", "-", $data['title'][1]);
            $fileExtention = $request->file('file')->extension();
            $fileName = $fileName . '.' . $fileExtention;
            $filelocation = MediaHelper::uploadDocument($request->file('file'), 'career',  $fileName);
            $data['file'] = $filelocation;
        } else {
            $data['file'] = $career->file ?? '';
        }
        $data['is_active'] = isset($request['is_active']) ? 1 : 0;
        $preferred_language = $this->preferredLanguage;
        $existing_record_id = $this->career->find($data['post'][$preferred_language]) ?? 0;
        if ($existing_record_id) {
            foreach ($data['title'] as $language_id => $value) {
                if ($data['title'][$language_id] != NULL) {
                    $lang_items = $data;
                    $lang_items['language_id'] = $language_id;
                    $lang_items['existing_record_id'] = ($language_id != $preferred_language) ? $existing_record_id->id : null;
                    $lang_items['title'] = $data['title'][$language_id];
                    $lang_items['description'] = $data['description'][$language_id]??'';
                    $lang_items['slug'] = $existing_record_id->slug;
                    $lang_items['display_order'] = $existing_record_id->display_order == null ? 0 : $existing_record_id->display_order;
                    $this->career->model()->updateOrCreate(['id' => $data['post'][$language_id]], $lang_items);
                }
            }

            return redirect()->route('admin.career.index')
                ->with('flash_notice', 'Career updated successfully');
        } else {

            return redirect()->back()->withInput()
                ->with('flash_notice', 'Career can not be updated.');
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
        $this->authorize('master-policy.perform', ['career', 'delete']);
        $this->career->where('existing_record_id', $id)->delete();
        $career = $this->career->find($id);
        if ($this->career->destroy($career->id)) {
            $message = 'Career deleted successfully.';

            return response()->json(['status' => 'ok', 'message' => $message], 200);
        }

        return response()->json(['status' => 'error', 'message' => ''], 422);
    }

    public function changeStatus(Request $request)
    {
        $this->authorize('master-policy.perform', ['career', 'changeStatus']);
        $career = $this->career->find($request->get('id'));
        $status = $career->is_active == 0 ? 1 : 0;
        $message = $career->is_active == 0 ? 'Career is published.' : 'Career is unpublished.';
        $this->career->changeStatus($career->id, $status);
        $this->career->update($career->id, array('updated_by' => auth()->id()));
        $updated = $this->career->find($request->get('id'));

        return response()->json(['status' => 'ok', 'message' => $message, 'response' => $updated], 200);
    }

    public function sort(Request $request)
    {
        $exploded = explode('&', str_replace('item[]=', '', $request->order));
        $language_id = $request->language_id ?? 1;
        for ($i = 0; $i < count($exploded); $i++) {
            $this->career->update($exploded[$i], ['display_order' => $i]);
        }
        $preferred_language = $this->preferredLanguage;
        $other_posts = $this->career->whereIn('existing_record_id', $exploded)->get();
        $other_posts_grouped_language = $other_posts->groupBy('language_id');
        $english_sort = $this->career->where('language_id', $preferred_language)->pluck('display_order', 'id')->toArray();
        if ($other_posts_grouped_language) {
            foreach ($other_posts_grouped_language as $language => $records) {
                foreach ($records as $record) {
                    $this->career->update($record->id, ['display_order' => $english_sort[$record->existing_record_id]]);
                }
            }
        }

        return json_encode(['status' => 'success', 'value' => 'Successfully reordered.'], 200);
    }
}
