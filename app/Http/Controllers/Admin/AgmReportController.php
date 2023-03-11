<?php

namespace App\Http\Controllers\Admin;

use App\Helper\SettingHelper;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\AgmReportRequest;
use App\Repositories\AgmReportCategoryRepository;
use App\Repositories\AgmReportRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class AgmReportController extends Controller
{

    public $title = 'AGM Report';

    protected $report;
    protected $category;

    public function __construct(AgmReportRepository $report, AgmReportCategoryRepository $category)
    {
        $this->report = $report;
        $this->category = $category;
        $this->preferredLanguage = SettingHelper::setting('preferred_language') == null ? 1 : SettingHelper::setting('preferred_language');

        auth()->shouldUse('admin');
    }

    protected function multiCategory($category_id, $language_id)
    {
        if ($category = $this->category->where('existing_record_id', $category_id)->where('language_id', $language_id)->first()) {
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
        $this->authorize('master-policy.perform', ['agm-report', 'view']);

        $title = $this->title;
        $reports = $this->report->where('language_id', $this->preferredLanguage)
            ->orderBy('display_order', 'asc')
            ->orderBy('created_at', 'desc')
            ->get();

        return view('admin.agmReport.index', compact('title'))->withReports($reports);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $this->authorize('master-policy.perform', ['agm-report', 'add']);

        $title = 'Add AGM Report';
        $categories = $this->category->with('allChild')
            ->whereNull('parent_id')
            ->whereLanguageId($this->preferredLanguage)
            ->get();

        return view('admin.agmReport.create', compact('title'))
            ->withCategories($categories);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(AgmReportRequest $request)
    {
        $this->authorize('master-policy.perform', ['agm-report', 'add']);

        $data = $request->except(['file', '_token']);
        if ($request->file) {
            ini_set('memory_limit', '256M');
            ini_set('post_max_size', '32M');
            ini_set('upload_max_filesize', '32M');
            ini_set('max_execution_time', 1800);
            $file = $request->file;
            $filename  = time() . "_" . $file->getClientOriginalName();
            $data['file'] = 'agm-report/' . $filename;
            Storage::put('agm-report/' . $filename, file_get_contents($file->getRealPath()));
        }
        $data['is_active'] = isset($data['is_active']) ? 1 : 0;
        $preferred_language = $this->preferredLanguage;
        /**
         * Insert Preferred Language Item First.
         */
        $preferred_language_item = $data;
        $preferred_language_item['language_id'] = $preferred_language;
        $preferred_language_item['title'] = $data['title'][$preferred_language];
        $preferred_insert = $this->report->create($preferred_language_item);
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
                        $lang_items[$count]['category_id'] = $this->multiCategory($data['category_id'], $language_id);
                        $lang_items[$count]['display_order'] = $preferred_insert->display_order == null ? 0 : $preferred_insert->display_order;
                        $lang_items[$count]['created_by'] = $preferred_insert->created_by;
                        $lang_items[$count]['created_at'] = $preferred_insert->created_at;
                        $count++;
                    }
                }
            }
            if (!empty($lang_items)) {
                $this->report->model()->insert($lang_items);
            }

            return redirect()->route('admin.agm-report.index')
                ->with('flash_notice', 'AGM Report Created Successfully.');
        }

        return redirect()->back()->withInput()
            ->with('flash_notice', 'AGM Report can not be Create');
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
        $this->authorize('master-policy.perform', ['agm-report', 'edit']);

        $title = 'Edit AGM Report';
        $preferredLanguage = $this->preferredLanguage;
        $report = $this->report->find($id);
        $lang_content = $this->report->where('existing_record_id', $report->id)->where('language_id', '!=', $this->preferredLanguage)->get();
        $langContent = $lang_content->groupBy('language_id');
        $categories = $this->category->with('allChild')
            ->whereNull('parent_id')
            ->whereLanguageId($this->preferredLanguage)
            ->get();

        return view('admin.agmReport.edit', compact('title', 'report', 'langContent', 'categories', 'preferredLanguage'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(AgmReportRequest $request, $id)
    {
        $this->authorize('master-policy.perform', ['agm-report', 'edit']);

        $report = $this->report->find($id);
        $data = $request->except(['file', '_token', '_method']);
        if ($request->type == 'Link') {
            $data['file'] = $request->file;
        } else {
            if ($request->file && $request->hasFile('file')) {
                ini_set('memory_limit', '256M');
                ini_set('post_max_size', '32M');
                ini_set('upload_max_filesize', '32M');
                ini_set('max_execution_time', 1800);
                $file = $request->file;
                $filename  = time() . "_" . $file->getClientOriginalName();
                $data['file'] = 'agm-report/' . $filename;
                Storage::put('agm-report/' . $filename, file_get_contents($file->getRealPath()));
                if (Storage::exists($report->file)) {
                    Storage::delete($report->file);
                }
            }
        }
        $data['is_active'] = isset($data['is_active']) ? 1 : 0;
        $preferred_language = $this->preferredLanguage;
        $existing_record_id = $this->report->find($data['post'][$preferred_language]) ?? 0;
        if ($existing_record_id) {
            foreach ($data['title'] as $language_id => $value) {
                if ($data['title'][$language_id] != NULL) {
                    $lang_items = $data;
                    $lang_items['language_id'] = $language_id;
                    $lang_items['existing_record_id'] = ($language_id != $preferred_language) ? $existing_record_id->id : null;
                    $lang_items['file'] = isset($data['file']) && !empty($data['file']) ? $data['file'] : $existing_record_id->file;
                    $lang_items['title'] = $data['title'][$language_id];
                    $lang_items['category_id'] = $this->multiCategory($data['category_id'], $language_id);
                    $lang_items['display_order'] = $existing_record_id->display_order == null ? 0 : $existing_record_id->display_order;
                    $lang_items['created_by'] = $existing_record_id->created_by;
                    $lang_items['updated_by'] = $existing_record_id->updated_by;
                    unset($lang_items['post']);
                    $lang_items['id'] = $data['post'][$language_id];
                    $this->report->model()->updateOrInsert(['id' => $data['post'][$language_id]], $lang_items);
                }
            }

            return redirect()->route('admin.agm-report.index')
                ->with('flash_notice', 'Download updated successfully');
        }

        return redirect()->back()->withInput()
            ->with('flash_notice', 'Download can not be Updated.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, $id)
    {
        $report = $this->report->find($request->get('id'));
        $this->report->where('existing_record_id', $id)->delete();
        $this->report->update($report->id, array('deleted_by' => Auth::user()->id));
        if ($this->report->destroy($report->id)) {
            $message = 'AGM Report item deleted successfully.';

            return response()->json(['status' => 'ok', 'message' => $message], 200);
        }

        return response()->json(['status' => 'error', 'message' => ''], 422);
    }

    public function changeStatus(Request $request)
    {
        $report = $this->report->find($request->get('id'));
        $status = $report->is_active == 0 ? 1 : 0;
        $message = $report->is_active == 0 ? 'AGM Report with title "' . $report->title . '" is published.' : 'AGM Report with title "' . $report->title . '" is unpublished.';
        $this->report->changeStatus($report->id, $status);
        $this->report->update($report->id, array('updated_by' => Auth::user()->id));
        $updated = $this->report->find($request->get('id'));
        if ($multiContent = $this->report->where('existing_record_id', $report->id)->first()) {
            $this->report->changeStatus($multiContent->id, $status);
        }

        return response()->json(['status' => 'ok', 'message' => $message, 'response' => $updated], 200);
    }

    public function sort(Request $request)
    {
        $preferred_language = $this->preferredLanguage;
        $exploded = explode('&', str_replace('item[]=', '', $request->order));
        for ($i = 0; $i < count($exploded); $i++) {
            $this->report->update($exploded[$i], ['display_order' => $i]);
        }
        $other_posts = $this->report->whereIn('existing_record_id', $exploded)->get();
        $other_posts_grouped_language = $other_posts->groupBy('language_id');
        $english_sort = $this->report->where('language_id', $preferred_language)->pluck('display_order', 'id')->toArray();
        if ($other_posts_grouped_language) {
            foreach ($other_posts_grouped_language as $language => $records) {
                foreach ($records as $record) {
                    $this->report->update($record->id, ['display_order' => $english_sort[$record->existing_record_id]]);
                }
            }
        }

        return json_encode(['status' => 'success', 'value' => 'Successfully reordered.'], 200);
    }
}
