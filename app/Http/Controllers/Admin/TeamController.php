<?php

namespace App\Http\Controllers\Admin;

use App\Helper\MediaHelper;
use App\Helper\SettingHelper;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\TeamEditRequest;
use App\Http\Requests\Admin\TeamRequest;
use App\Repositories\TeamCategoryRepository;
use App\Repositories\TeamRepository;
use Illuminate\Http\Request;

class TeamController extends Controller
{
    public $title = 'Management Team';

    protected $preferredLanguage, $team, $teamCategory;

    public function __construct(TeamRepository $team, TeamCategoryRepository $teamCategory)
    {
        $this->team = $team;
        $this->teamCategory = $teamCategory;
        $this->preferredLanguage = SettingHelper::setting('preferred_language') == null ? 1 : SettingHelper::setting('preferred_language');

        auth()->shouldUse('admin');
    }

    protected function teamMultiCategory($category_id)
    {
        if ($category = $this->teamCategory->where('existing_record_id', $category_id)->first()) {
            return $category->id;
        }
        return $category_id;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $this->authorize('master-policy.perform', ['team', 'view']);
        $title = $this->title;
        $teams = $this->team->with('category')->where('language_id', $this->preferredLanguage)
            ->orderBy('display_order', 'asc')
            ->get();

        return view('admin.team.index')->withTitle($title)->withTeams($teams);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $this->authorize('master-policy.perform', ['team', 'add']);

        $title = 'Add Management Team';
        $categories = $this->teamCategory->with('allChild')->whereNull('parent_id')->whereLanguageId($this->preferredLanguage)->get();

        return view('admin.team.create')
            ->withTitle($title)
            ->withCategories($categories);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(TeamRequest $request)
    {
        $this->authorize('master-policy.perform', ['team', 'add']);

        $data = $request->except(['file']);
        if ($request->hasFile('photo')) {
            $filelocation = MediaHelper::upload($request->file('photo'), 'team', true, true);
            $data['photo'] = $filelocation['storage'];
        }
        $data['is_active'] = isset($data['is_active']) ? 1 : 0;
        $data['position'] = isset($data['position']) ? 1 : 0;
        $data['category_id'] = isset($data['category_id']) ? $data['category_id'] : 1;

        $preferred_language = $this->preferredLanguage;
        /**
         * Insert Preferred Language Item First.
         */
        $preferred_language_item = $data;
        $preferred_language_item['language_id'] = $preferred_language;
        $preferred_language_item['full_name'] = $data['full_name'][$preferred_language];
        $preferred_language_item['designation'] = $data['designation'][$preferred_language];
        $preferred_language_item['description'] = $data['description'][$preferred_language];
        $preferred_insert = $this->team->create($preferred_language_item);
        if ($preferred_insert) {
            $lang_items = [];
            $count = 0;
            unset($data['_token']);
            foreach ($data['full_name'] as $language_id => $value) {
                if ($language_id != $preferred_language) {
                    if ($data['full_name'][$language_id] != NULL) {
                        $lang_items[$count] = $data;
                        $lang_items[$count]['existing_record_id'] = $preferred_insert->id;
                        $lang_items[$count]['language_id'] = $language_id;
                        $lang_items[$count]['full_name'] = $data['full_name'][$language_id];
                        $lang_items[$count]['designation'] = $data['designation'][$language_id];
                        $lang_items[$count]['description'] = $data['description'][$language_id];
                        $lang_items[$count]['category_id'] = $this->teamMultiCategory($preferred_insert->category_id);
                        $lang_items[$count]['display_order'] = $preferred_insert->display_order == null ? 999 : $preferred_insert->display_orders;
                        $lang_items[$count]['photo'] = $preferred_insert->photo;
                        $lang_items[$count]['created_by'] = $preferred_insert->created_by;
                        $lang_items[$count]['created_at'] = $preferred_insert->created_at;
                        if ($lang_items[$count]['existing_record_id'] != NULL) {
                            $category = $this->teamCategory->where('existing_record_id', $data['category_id'])->first();
                            $lang_items[$count]['category_id'] = isset($category) ? $category->id : $lang_items[$count]['category_id'];
                        }
                        $count++;
                    }
                }
            }
            if (!empty($lang_items)) {
                $this->team->model()->insert($lang_items);
            }

            return redirect()->route('admin.team.index')->with('flash_notice', 'Team Created Successfully.');
        }

        return redirect()->back()->withInput()->with('flash_notice', 'Team can not be Create');
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $this->authorize('master-policy.perform', ['team', 'edit']);
        $title = 'Edit Management Team';
        $preferredLanguage = $this->preferredLanguage;
        $team = $this->team->find($id);
        $lang_content = $this->team->where('existing_record_id', $team->id)->where('language_id', '!=', $this->preferredLanguage)->get();
        $langContent = $lang_content->groupBy('language_id');
        $categories = $this->teamCategory->with('allChild')
            ->whereNull('parent_id')
            ->whereLanguageId($this->preferredLanguage)
            ->get();

        return view('admin.team.edit', compact('title', 'team', 'langContent', 'categories', 'preferredLanguage'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function update(TeamEditRequest $request, $id)
    {
        $this->authorize('master-policy.perform', ['team', 'edit']);
        $team = $this->team->find($id);
        $data = $request->except(['_token', '_method']);
        if ($request->hasFile('photo')) {
            if (file_exists('storage/' . $team->photo) && !empty($team->photo)) {
                MediaHelper::destroy($team->photo);
            }
            $filelocation = MediaHelper::upload($request->file('photo'), 'team', true, true);
            $data['photo'] = $filelocation['storage'];
        } else {
            if (empty($team->photo)) {
                $this->validate($request, [
                    'photo' => 'required'
                ]);
            }
        }
        $data['is_active'] = isset($data['is_active']) ? 1 : 0;
        $data['position'] = isset($data['position']) ? 1 : 0;
        $preferred_language = $this->preferredLanguage;
        $existing_record_id = $this->team->find($data['post'][$preferred_language]) ?? 0;
        if ($existing_record_id) {
            foreach ($data['full_name'] as $language_id => $value) {
                if ($data['full_name'][$language_id] != NULL) {
                    $lang_items = $data;
                    $lang_items['language_id'] = $language_id;
                    $lang_items['existing_record_id'] = ($language_id != $preferred_language) ? $existing_record_id->id : null;
                    $lang_items['full_name'] = $data['full_name'][$language_id];
                    $lang_items['designation'] = $data['designation'][$language_id];
                    $lang_items['description'] = $data['description'][$language_id];
                    $lang_items['id'] = $data['post'][$language_id];
                    $lang_items['photo'] = (isset($data['photo']) && !empty($data['photo'])) ? $data['photo'] : $existing_record_id->photo;
                    $lang_items['created_by'] = $existing_record_id->created_by;
                    $lang_items['created_at'] = $existing_record_id->created_at;
                    unset($lang_items['post']);
                    if ($lang_items['existing_record_id'] != NULL) {
                        $category = $this->teamCategory->where('existing_record_id', $data['category_id'])->first();
                        $lang_items['category_id'] = isset($category) ? $category->id : $lang_items['category_id'];
                    }
                    unset($lang_items['q']);
                    // dd($lang_items);
                    $this->team->model()->updateOrInsert(['id' => $data['post'][$language_id]], $lang_items);
                }
            }

            return redirect()->route('admin.team.index')->with('flash_notice', 'Team updated successfully');
        }

        return redirect()->back()->withInput()->with('flash_notice', 'Team can not be Updated.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $this->authorize('master-policy.perform', ['team', 'delete']);

        $team = $this->team->find($id);
        $this->team->where('existing_record_id', $id)->delete();
        if ($this->team->destroy($team->id)) {
            $message = 'Team item deleted successfully.';

            return response()->json(['status' => 'ok', 'message' => $message], 200);
        }

        return response()->json(['status' => 'error', 'message' => ''], 422);
    }

    public function changeStatus(Request $request)
    {
        $team = $this->team->find($request->get('id'));
        $status = $team->is_active == 0 ? 1 : 0;
        $message = $team->is_active == 0 ? 'Team with title "' . $team->title . '" is published.' : 'Team with title "' . $team->title . '" is unpublished.';
        $this->team->changeStatus($team->id, $status);
        $updated = $this->team->find($request->get('id'));
        if ($multiContenct = $this->team->where('existing_record_id', $team->id)->first()) {
            $this->team->changeStatus($multiContenct->id, $status);
        }

        return response()->json(['status' => 'ok', 'message' => $message, 'response' => $updated], 200);
    }

    public function sort(Request $request)
    {
        $preferred_language = $this->preferredLanguage;
        $exploded = explode('&', str_replace('item[]=', '', $request->order));
        for ($i = 0; $i < count($exploded); $i++) {
            $this->team->update($exploded[$i], ['display_order' => $i]);
        }
        $other_posts = $this->team->whereIn('existing_record_id', $exploded)->get();
        $other_posts_grouped_language = $other_posts->groupBy('language_id');
        $english_sort = $this->team->where('language_id', $preferred_language)->pluck('display_order', 'id')->toArray();
        if ($other_posts_grouped_language) {
            foreach ($other_posts_grouped_language as $language => $records) {
                foreach ($records as $record) {
                    $this->team->update($record->id, ['display_order' => $english_sort[$record->existing_record_id]]);
                }
            }
        }

        return json_encode(['status' => 'success', 'value' => 'Successfully reordered.'], 200);
    }

    public function destroyImage($id)
    {
        $team = $this->team->find($id);
        if ($team) {
            MediaHelper::destroy($team->photo);
            $team->photo = null;
            $team->save();
            $message = 'Photo Deleted successfully.';

            return back()->with(['success' => $message]);
        }

        return back()->with(['success' => 'Invalid Request']);
    }
}