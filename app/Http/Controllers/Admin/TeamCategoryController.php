<?php

namespace App\Http\Controllers\Admin;

use App\Helper\SettingHelper;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\TeamCategoryRequest;
use App\Repositories\TeamCategoryRepository;
use Illuminate\Http\Request;

class TeamCategoryController extends Controller
{

    protected $teamCategory;

    public $title = 'Team Category';

    public function __construct(TeamCategoryRepository $teamCategory)
    {
        $this->teamCategory = $teamCategory;
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
        $this->authorize('master-policy.perform', ['team-category', 'view']);
        $title = $this->title;
        $teamCategories = $this->teamCategory->where('language_id', $this->preferredLanguage)->orderBy('display_order')->get();

        return view('admin.teamCategory.index', compact('title'))
            ->withTeamCategories($teamCategories);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $this->authorize('master-policy.perform', ['team-category', 'add']);
        $title = 'Add Team Category';
        $parents = $this->teamCategory->with('allChild')->whereNull('parent_id')->whereLanguageId($this->preferredLanguage)->get();

        return view('admin.teamCategory.create', compact('title'))->withParents($parents);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(TeamCategoryRequest $request)
    {
        $this->authorize('master-policy.perform', ['team-category', 'add']);
        $data = $request->except(['_token']);
        $preferred_language = $this->preferredLanguage;
        /**
         * Insert Preferred Language Item First.
         */
        $preferred_language_item = $data;
        $preferred_language_item['language_id'] = $preferred_language;
        $preferred_language_item['title'] = $data['title'][$preferred_language];
        $preferred_insert = $this->teamCategory->create($preferred_language_item);
        if ($preferred_insert) {
            $lang_items = [];
            $count = 0;
            foreach ($data['title'] as $language_id => $value) {
                if ($language_id != $preferred_language) {
                    if ($data['title'][$language_id] != NULL) {
                        $lang_items[$count] = $data;
                        $lang_items[$count]['existing_record_id'] = $preferred_insert->id;
                        $lang_items[$count]['language_id'] = $language_id;
                        $lang_items[$count]['title'] = $data['title'][$language_id];
                        $lang_items[$count]['slug'] = $preferred_insert->slug;
                        $lang_items[$count]['display_order'] = $preferred_insert->display_order == null ? 0 : $preferred_insert->display_orders;
                        $lang_items[$count]['created_by'] = $preferred_insert->created_by;
                        $lang_items[$count]['created_at'] = $preferred_insert->created_at;
                        $count++;
                    }
                }
            }
            if (!empty($lang_items)) {
                $this->teamCategory->model()->insert($lang_items);
            }

            return redirect()->route('admin.team-category.index')->with('flash_notice', 'Team Category Created Successfully.');
        }

        return redirect()->back()->withInput()->with('flash_notice', 'Team Category can not be created.');
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
        $this->authorize('master-policy.perform', ['team-category', 'edit']);
        $title = 'Edit Team Cateogry';
        $teamCategory = $this->teamCategory->find($id);
        $lang_content = $this->teamCategory->where('existing_record_id', $teamCategory->id)->where('language_id', '!=', $this->preferredLanguage)->get();
        $lang_content = $lang_content->groupBy('language_id');
        $patenr = $this->teamCategory->where('id', '!=', $id)->with('allchild')->whereNull('parent_id')->whereLanguageId($this->preferredLanguage)->get();
        $parents = $patenr->where('parent_id', '!=', $id)->all();

        return view('admin.teamCategory.edit', compact('title'))
            ->withTeamCategory($teamCategory)
            ->withLangContent($lang_content)
            ->withParents($parents)
            ->withPreferredLanguage($this->preferredLanguage);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(TeamCategoryRequest $request, $id)
    {
        $this->authorize('master-policy.perform', ['team-category', 'edit']);
        $content = $this->teamCategory->find($id);
        $data = $request->except(['_token', '_method']);
        $data['link_target'] = isset($request['link_target']) ? 1 : 0;
        $data['is_active'] = isset($request['is_active']) ? 1 : 0;
        $data['edit'] = isset($request['edit']) ? 1 : 0;
        $preferred_language = $this->preferredLanguage;
        $existing_record_id = $this->teamCategory->find($data['post'][$preferred_language]) ?? 0;
        if ($existing_record_id) {
            foreach ($data['title'] as $language_id => $value) {
                if ($data['title'][$language_id] != NULL) {
                    $lang_items = $data;
                    $lang_items['language_id'] = $language_id;
                    $lang_items['existing_record_id'] = ($language_id != $preferred_language) ? $existing_record_id->id : null;
                    $lang_items['title'] = $data['title'][$language_id];
                    $lang_items['slug'] = $existing_record_id->slug;
                    unset($lang_items['post']);
                    $this->teamCategory->model()->updateOrCreate(['id' => $data['post'][$language_id]], $lang_items);
                }
            }

            return redirect()->route('admin.team-category.index')->with('flash_notice', 'Team Category updated successfully');
        } else {

            return redirect()->back()->withInput()->with('flash_notice', 'Team Category can not be updated.');
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
        $this->authorize('master-policy.perform', ['team-category', 'delete']);
        $this->teamCategory->where('existing_record_id', $id)->delete();
        $content = $this->teamCategory->find($id);
        if ($this->teamCategory->destroy($content->id)) {
            $message = 'Team Category deleted successfully.';

            return response()->json(['status' => 'ok', 'message' => $message], 200);
        }

        return response()->json(['status' => 'error', 'message' => ''], 422);
    }

    public function changeStatus(Request $request)
    {
        $this->authorize('master-policy.perform', ['team-category', 'changeStatus']);

        $content = $this->teamCategory->find($request->get('id'));
        $status = $content->is_active == 0 ? 1 : 0;
        $message = $content->is_active == 0 ? 'Team category is published.' : 'Team category is unpublished.';

        $this->teamCategory->changeStatus($content->id, $status);
        $this->teamCategory->update($content->id, array('updated_by' => auth()->id()));
        $updated = $this->teamCategory->find($request->get('id'));
        if ($multiContenct = $this->teamCategory->where('existing_record_id', $content->id)->first()) {
            $this->teamCategory->changeStatus($multiContenct->id, $status);
        }

        return response()->json(['status' => 'ok', 'message' => $message, 'response' => $updated], 200);
    }

    public function sort(Request $request)
    {
        $exploded = explode('&', str_replace('item[]=', '', $request->order));
        $language_id = $request->language_id ?? 1;
        for ($i = 0; $i < count($exploded); $i++) {
            $this->teamCategory->update($exploded[$i], ['display_order' => $i]);
        }
        $preferred_language = $this->preferredLanguage;
        $other_posts = $this->teamCategory->whereIn('existing_record_id', $exploded)->get();
        $other_posts_grouped_language = $other_posts->groupBy('language_id');
        $english_sort = $this->teamCategory->where('language_id', $preferred_language)->pluck('display_order', 'id')->toArray();
        if ($other_posts_grouped_language) {
            foreach ($other_posts_grouped_language as $language => $records) {
                foreach ($records as $record) {
                    $this->teamCategory->update($record->id, ['display_order' => $english_sort[$record->existing_record_id]]);
                }
            }
        }

        return json_encode(['status' => 'success', 'value' => 'Successfully reordered.'], 200);
    }
}
