<?php

namespace App\Http\Controllers\Admin;

use App\Helper\MediaHelper;
use App\Helper\SettingHelper;
use App\Http\Requests\Admin\DownloadRequest;
use App\Http\Requests\Admin\SyllabusRequest;
use App\Repositories\DownloadRepository;
use App\Repositories\SyllabusRepository;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Repositories\DownloadCategoryRepository;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class SyllabusController extends Controller
{
    public $title = 'Syllabus';

    protected $syllabus, $preferredLanguage;

    public function __construct(SyllabusRepository $syllabus)
    {
        $this->syllabus = $syllabus;
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
        $this->authorize('master-policy.perform', ['syllabus', 'view']);
        $title = $this->title;
        $syllabus = $this->syllabus->where('language_id', $this->preferredLanguage)
            ->orderBy('display_order', 'asc')
            ->orderBy('created_at', 'desc')
            ->get();

        return view('admin.syllabus.index')->withTitle($title)->withSyllabus($syllabus);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $this->authorize('master-policy.perform', ['syllabus', 'add']);
        $title = 'Add Syllabus';

        return view('admin.syllabus.create')->withTitle($title);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(SyllabusRequest $request)
    {
        $request->validate([
            'file' => ['required'],
        ]);
        $this->authorize('master-policy.perform', ['syllabus', 'add']);
        $data = $request->except(['file']);
        if ($request->hasFile('file')) {
            $fileName = rand(0, 9999) . '-' . $request->file('file')->getClientOriginalName();
            $filelocation = MediaHelper::uploadDocument($request->file('file'), 'syllabus',  $fileName);
            $data['file'] = $filelocation;
        }

        $data['is_active'] = isset($data['is_active']) ? 1 : 0;

        $preferred_language = $this->preferredLanguage;
        /**
         * Insert Preferred Language Item First.
         */
        $preferred_language_item = $data;
        $preferred_language_item['language_id'] = $preferred_language;
        $preferred_language_item['category'] = $data['category'][$preferred_language];
        $preferred_language_item['designation'] = $data['designation'][$preferred_language];
        $preferred_insert = $this->syllabus->create($preferred_language_item);
        if ($preferred_insert) {
            $lang_items = [];
            $count = 0;
            unset($data['_token']);
            foreach ($data['designation'] as $language_id => $value) {
                if ($language_id != $preferred_language) {
                    if ($data['designation'][$language_id] != NULL) {
                        $lang_items[$count] = $data;
                        $lang_items[$count]['existing_record_id'] = $preferred_insert->id;
                        $lang_items[$count]['language_id'] = $language_id;
                        $lang_items[$count]['category'] = $data['category'][$language_id];
                        $lang_items[$count]['designation'] = $data['designation'][$language_id];
                        $lang_items[$count]['created_by'] = $preferred_insert->created_by;
                        $lang_items[$count]['created_at'] = $preferred_insert->created_at;
                        $lang_items[$count]['type'] = $preferred_insert->type;
                        $lang_items[$count]['display_order'] = $preferred_insert->display_order == null ? 1 :  $preferred_insert->display_order;
                        $count++;
                    }
                }
            }
            if (!empty($lang_items)) {
                $this->syllabus->model()->insert($lang_items);
            }

            return redirect()->route('admin.syllabus.index')
                ->with('flash_notice', 'Syllabus Created Successfully.');
        }

        return redirect()->back()->withInput()
            ->with('flash_notice', 'Syllabus can not be Create');
    }

    /**
     * Display the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $this->authorize('master-policy.perform', ['syllabus', 'edit']);
        $title = 'Edit Syllabus';
        $preferredLanguage = $this->preferredLanguage;
        $syllabus = $this->syllabus->find($id);
        $lang_content = $this->syllabus->where('existing_record_id', $syllabus->id)->where('language_id', '!=', $this->preferredLanguage)->get();
        $langContent = $lang_content->groupBy('language_id');

        return view('admin.syllabus.edit', compact('title', 'langContent', 'preferredLanguage','syllabus'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function update(SyllabusRequest $request, $id)
    {
        $this->authorize('master-policy.perform', ['syllabus', 'edit']);
        $download = $this->syllabus->find($id);
        $data = $request->except(['file', '_token', '_method']);

        if ($request->hasFile('file')) {
            MediaHelper::destroy($download->file);
            $fileName = rand(0, 9999) . '-' . $request->file('file')->getClientOriginalName();
            $filelocation = MediaHelper::uploadDocument($request->file('file'), 'syllabus',  $fileName);
            $data['file'] = $filelocation;
        }

        $data['is_active'] = isset($data['is_active']) ? 1 : 0;
        $preferred_language = $this->preferredLanguage;
        $existing_record_id = $this->syllabus->find($data['post'][$preferred_language]) ?? 0;
        if ($existing_record_id) {
            foreach ($data['designation'] as $language_id => $value) {
                if ($data['designation'][$language_id] != NULL) {
                    $lang_items = $data;
                    $lang_items['language_id'] = $language_id;
                    $lang_items['existing_record_id'] = ($language_id != $preferred_language) ? $existing_record_id->id : null;
                    $lang_items['file'] = isset($data['file']) && !empty($data['file']) ? $data['file'] : $existing_record_id->file;
                    $lang_items['category'] = $data['category'][$language_id];
                    $lang_items['designation'] = $data['designation'][$language_id];
                    $lang_items['id'] = $data['post'][$language_id];
                    unset($lang_items['post']);
                    unset($lang_items['q']);
                    $lang_items['type'] = $data['type'];
                    $lang_items['display_order'] = $existing_record_id->display_order == null ? 1 : $existing_record_id->display_order;
                    $this->syllabus->model()->updateOrInsert(['id' => $data['post'][$language_id]], $lang_items);
                }
            }

            return redirect()->route('admin.syllabus.index')
                ->with('flash_notice', 'Syllabus updated successfully');
        }

        return redirect()->back()->withInput()
            ->with('flash_notice', 'Syllabus can not be Updated.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, $id)
    {
        $syllabus = $this->syllabus->find($request->get('id'));
        $this->syllabus->where('existing_record_id', $id)->delete();
        $this->syllabus->update($syllabus->id, array('deleted_by' => Auth::user()->id));
        if ($this->syllabus->destroy($syllabus->id)) {
            $message = 'Syllabus item deleted successfully.';

            return response()->json(['status' => 'ok', 'message' => $message], 200);
        }

        return response()->json(['status' => 'error', 'message' => ''], 422);
    }

    public function changeStatus(Request $request)
    {
        $syllabus = $this->syllabus->find($request->get('id'));
        $status = $syllabus->is_active == 0 ? 1 : 0;
        $message = $syllabus->is_active == 0 ? 'Syllabus with designation "' . $syllabus->designation . '" is published.' : 'Syllabus with designation "' . $syllabus->designation . '" is unpublished.';
        $this->syllabus->changeStatus($syllabus->id, $status);
        $this->syllabus->update($syllabus->id, array('updated_by' => Auth::user()->id));
        $updated = $this->syllabus->find($request->get('id'));

        return response()->json(['status' => 'ok', 'message' => $message, 'response' => $updated], 200);
    }

    public function sort(Request $request)
    {
        $preferred_language = $this->preferredLanguage;
        $exploded = explode('&', str_replace('item[]=', '', $request->order));
        for ($i = 0; $i < count($exploded); $i++) {
            $this->syllabus->update($exploded[$i], ['display_order' => $i]);
        }
        $other_posts = $this->syllabus->whereIn('existing_record_id', $exploded)->get();
        $other_posts_grouped_language = $other_posts->groupBy('language_id');
        $english_sort = $this->syllabus->where('language_id', $preferred_language)->pluck('display_order', 'id')->toArray();
        if ($other_posts_grouped_language) {
            foreach ($other_posts_grouped_language as $language => $records) {
                foreach ($records as $record) {
                    $this->syllabus->update($record->id, ['display_order' => $english_sort[$record->existing_record_id]]);
                }
            }
        }

        return json_encode(['status' => 'success', 'value' => 'Successfully reordered.'], 200);
    }
}
