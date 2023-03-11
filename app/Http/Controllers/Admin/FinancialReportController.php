<?php

namespace App\Http\Controllers\Admin;

use App\Helper\MediaHelper;
use App\Helper\SettingHelper;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\FinancialReportRequest;
use App\Repositories\FinancialReportCategoryRepository;
use App\Repositories\FinancialReportRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class FinancialReportController extends Controller
{
    public $title = 'Financial Report';

    protected $report;
    protected $category;

    public function __construct(FinancialReportRepository $report, FinancialReportCategoryRepository $category)
    {
        $this->report = $report;
        $this->category = $category;
        $this->preferredLanguage = SettingHelper::setting('preferred_language') == null ? 1 : SettingHelper::setting('preferred_language');

        auth()->shouldUse('admin');
    }

    public function multiCategory($category_id, $language_id)
    {
        if ($category_id == null) {
            return $category_id;
        }
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
        $this->authorize('master-policy.perform', ['financial-report', 'view']);
        $title = $this->title;
        $reports = $this->report->where('language_id', $this->preferredLanguage)
            ->orderBy('display_order', 'asc')
            ->orderBy('created_at', 'desc')
            ->get();
        return view('admin.financialReport.index', compact('title'))->withReports($reports);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $this->authorize('master-policy.perform', ['financial-report', 'add']);
        $title = 'Add Financial Report';
        $categories = $this->category->with('allChild')
            ->whereNull('parent_id')
            ->whereLanguageId($this->preferredLanguage)
            ->get();
        return view('admin.financialReport.create', compact('title'))
            ->withCategories($categories);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(FinancialReportRequest $request)
    {
        $request->validate([
            'file' => ['required'],
        ]);

        $this->authorize('master-policy.perform', ['financial-report', 'add']);

        $data = $request->except(['file', '_token']);
        if ($request->file) {
            ini_set('memory_limit', '256M');
            ini_set('post_max_size', '32M');
            ini_set('upload_max_filesize', '32M');
            ini_set('max_execution_time', 1800);
            $file = $request->file;
            // $filename  = time() . "_" . $file->getClientOriginalName();
            // $data['file'] = 'financial-report/' . $filename;
            // Storage::put('financial-report/' . $filename, file_get_contents($file->getRealPath()));
            $fileName = rand(0, 9999) . '-' . $request->file('file')->getClientOriginalName();
            $filelocation = MediaHelper::uploadDocument($request->file('file'), 'financial-report',  $fileName);
            $data['file'] = $filelocation;
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
                        $lang_items[$count]['created_by'] = $preferred_insert->created_by;
                        $lang_items[$count]['created_at'] = $preferred_insert->created_at;
                        $lang_items[$count]['published_date'] = $preferred_insert->published_date;

                        $lang_items[$count]['category_id'] = $this->multiCategory($data['category_id'], $language_id);
                        $lang_items[$count]['display_order'] = $preferred_insert->display_order == null ? 1 : $preferred_insert->display_order;
                        $count++;
                    }
                }
            }

            if (!empty($lang_items)) {
                $this->report->model()->insert($lang_items);
            }
            return redirect()->route('admin.financial-report.index')
                ->with('flash_notice', 'Financial Report Created Successfully.');
        }
        return redirect()->back()->withInput()
            ->with('flash_notice', 'FinancialReport can not be Create');
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
        $this->authorize('master-policy.perform', ['financial-report', 'edit']);
        $title = 'Edit Financial Report';
        $preferredLanguage = $this->preferredLanguage;
        $report = $this->report->find($id);

        $lang_content = $this->report->where('existing_record_id', $report->id)->where('language_id', '!=', $this->preferredLanguage)->get();
        $langContent = $lang_content->groupBy('language_id');

        $categories = $this->category->with('allChild')
            ->whereNull('parent_id')
            ->whereLanguageId($this->preferredLanguage)
            ->get();

        return view('admin.financialReport.edit', compact('title', 'report', 'langContent', 'categories', 'preferredLanguage'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(FinancialReportRequest $request, $id)
    {
        $this->authorize('master-policy.perform', ['financial-report', 'edit']);
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

                if (Storage::exists($report->file)) {
                    Storage::delete($report->file);
                }
                $fileName = rand(0, 9999) . '-' . $request->file('file')->getClientOriginalName();
                $filelocation = MediaHelper::uploadDocument($request->file('file'), 'financial-report',  $fileName);
                $data['file'] = $filelocation;

                // $filename  = time() . "_" . $file->getClientOriginalName();
                // $data['file'] = 'financial-report/' . $filename;
                // Storage::put('financial-report/' . $filename, file_get_contents($file->getRealPath()));
                // if (Storage::exists($report->file)) {
                //     Storage::delete($report->file);
                // }
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
                    $lang_items['created_by'] = $existing_record_id->created_by;
                    $lang_items['updated_by'] = $existing_record_id->updated_by;
                    $lang_items['published_date'] = $data['published_date'];
                    unset($lang_items['post']);
                    unset($lang_items['q']);
                    $lang_items['id'] = $data['post'][$language_id];

                    $lang_items['category_id'] = $this->multiCategory($data['category_id'], $language_id);
                    $lang_items['display_order'] = $existing_record_id->display_order == null ? 1 : $existing_record_id->display_order;
                    $this->report->model()->updateOrInsert(['id' => $data['post'][$language_id]], $lang_items);
                }
            }
            return redirect()->route('admin.financial-report.index')
                ->with('flash_notice', 'Financial Report updated successfully');
        }

        return redirect()->back()->withInput()
            ->with('flash_notice', 'Financial Report can not be Updated.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, $id)
    {
        $this->authorize('master-policy.perform', ['financial-report', 'delete']);
        $report = $this->report->find($request->get('id'));
        $this->report->where('existing_record_id', $id)->delete();
        $this->report->update($report->id, array('deleted_by' => Auth::user()->id));
        if ($this->report->destroy($report->id)) {
            $message = 'Financial Report item deleted successfully.';
            return response()->json(['status' => 'ok', 'message' => $message], 200);
        }
        return response()->json(['status' => 'error', 'message' => ''], 422);
    }

    public function changeStatus(Request $request)
    {
        $this->authorize('master-policy.perform', ['financial-report', 'changeStatus']);
        $report = $this->report->find($request->get('id'));
        $status = $report->is_active == 0 ? 1 : 0;
        $message = $report->is_active == 0 ? 'Financial Report with title "' . $report->title . '" is published.' : 'Financial Report with title "' . $report->title . '" is unpublished.';
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