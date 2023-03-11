<?php

namespace App\Http\Controllers\Admin;

use App\Helper\Helper;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StatisticsRequest;
use App\Repositories\StatisticsRepository;
use Illuminate\Http\Request;

class StatisticsController extends Controller
{
    protected $preferredLanguage, $statistics;

    public function __construct(StatisticsRepository $statistics)
    {
        $this->statistics = $statistics;
        $this->preferredLanguage = Helper::locale();

        auth()->shouldUse('admin');
    }


    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $this->authorize('master-policy.perform', ['statistics', 'view']);
        $dataProvider = $this->statistics->where('language_id', $this->preferredLanguage)->orderBy('display_order', 'asc')->get();

        return view('admin.statistics.index', ['dataProvider' => $dataProvider]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $this->authorize('master-policy.perform', ['statistics', 'add']);

        return view('admin.statistics.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StatisticsRequest $request)
    {
        $this->authorize('master-policy.perform', ['statistics', 'add']);

        $data = $request->except([]);
        $data['is_active'] = isset($data['is_active']) ? 1 : 0;
        $preferred_language = $this->preferredLanguage;
        /**
         * Insert Preferred Language Item First.
         */
        $preferred_language_item = $data;
        $preferred_language_item['language_id'] = $preferred_language;
        $preferred_language_item['year'] = $data['year'][$preferred_language];
        $preferred_language_item['earning'] = $data['earning'][$preferred_language];
        $preferred_language_item['expenses'] = $data['expenses'][$preferred_language];
        $preferred_insert = $this->statistics->create($preferred_language_item);
        if ($preferred_insert) {
            $lang_items = [];
            $count = 0;
            unset($data['_token']);
            foreach ($data['year'] as $language_id => $value) {
                if ($language_id != $preferred_language) {
                    if ($data['year'][$language_id] != NULL) {
                        $lang_items[$count] = $data;
                        $lang_items[$count]['existing_record_id'] = $preferred_insert->id;
                        $lang_items[$count]['language_id'] = $language_id;
                        $lang_items[$count]['year'] = $data['year'][$language_id];
                        $lang_items[$count]['earning'] = $data['earning'][$language_id];
                        $lang_items[$count]['expenses'] = $data['expenses'][$language_id];
                        $lang_items[$count]['display_order'] = $preferred_insert->display_order == null ? 999 : $preferred_insert->display_orders;
                        $lang_items[$count]['created_by'] = $preferred_insert->created_by;
                        $lang_items[$count]['created_at'] = $preferred_insert->created_at;

                        $count++;
                    }
                }
            }
            if (!empty($lang_items)) {
                $this->statistics->model()->insert($lang_items);
            }
            return redirect()->route('admin.statistics.index')->with('flash_success', 'Statistics added successfully.');
        }

        return redirect()->back()->withInput()->with('flash_error', 'Statistics cannot be added.');
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
        $this->authorize('master-policy.perform', ['statistics', 'edit']);
        $statistics = $this->statistics->find($id);
        $lang_content = $this->statistics->where('existing_record_id', $statistics->id)->where('language_id', '!=', $this->preferredLanguage)->get();
        $langContent = $lang_content->groupBy('language_id');

        return view('admin.statistics.edit', ['statistics' => $statistics, 'langContent' => $langContent, 'preferredLanguage' => $this->preferredLanguage]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(StatisticsRequest $request, $id)
    {
        $this->authorize('master-policy.perform', ['statistics', 'edit']);
        $statistics = $this->statistics->find($id);
        $data = $request->except(['_token', '_method']);

        $data['is_active'] = isset($data['is_active']) ? 1 : 0;
        $preferred_language = $this->preferredLanguage;
        $existing_record_id = $this->statistics->find($data['post'][$preferred_language]) ?? 0;
        if ($existing_record_id) {
            foreach ($data['year'] as $language_id => $value) {
                if ($data['year'][$language_id] != NULL) {
                    $lang_items = $data;
                    $lang_items['language_id'] = $language_id;
                    $lang_items['existing_record_id'] = ($language_id != $preferred_language) ? $existing_record_id->id : null;
                    $lang_items['year'] = $data['year'][$language_id];
                    $lang_items['earning'] = $data['earning'][$language_id];
                    $lang_items['expenses'] = $data['expenses'][$language_id];
                    $lang_items['id'] = $data['post'][$language_id];
                    $lang_items['created_by'] = $existing_record_id->created_by;
                    $lang_items['created_at'] = $existing_record_id->created_at;
                    unset($lang_items['post']);
                    unset($lang_items['q']);

                    $this->statistics->model()->updateOrInsert(['id' => $data['post'][$language_id]], $lang_items);
                }
            }

            return redirect()->route('admin.statistics.index')->with('flash_statistics', 'Statistics updated successfully');
        }

        return redirect()->back()->withInput()->with('flash_error', 'Statistics can not be updated.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $this->authorize('master-policy.perform', ['statistics', 'delete']);

        $team = $this->statistics->find($id);
        $this->statistics->where('existing_record_id', $id)->delete();
        if ($this->statistics->destroy($team->id)) {
            $message = 'Statistics deleted successfully.';

            return response()->json(['status' => 'ok', 'message' => $message], 200);
        }

        return response()->json(['status' => 'error', 'message' => ''], 422);
    }

    public function changeStatus(Request $request)
    {
        $statistics = $this->statistics->find($request->get('id'));
        $status = $statistics->is_active == 0 ? 1 : 0;
        $message = $statistics->is_active == 0 ? 'Statistics with year "' . $statistics->year . '" is published.' : 'Statistics with year "' . $statistics->year . '" is unpublished.';
        $this->statistics->changeStatus($statistics->id, $status);
        $updated = $this->statistics->find($request->get('id'));
        if ($multiContenct = $this->statistics->where('existing_record_id', $statistics->id)->first()) {
            $this->statistics->changeStatus($multiContenct->id, $status);
        }

        return response()->json(['status' => 'ok', 'message' => $message, 'response' => $updated], 200);
    }

    public function sort(Request $request)
    {
        $preferred_language = $this->preferredLanguage;
        $exploded = explode('&', str_replace('item[]=', '', $request->order));
        for ($i = 0; $i < count($exploded); $i++) {
            $this->statistics->update($exploded[$i], ['display_order' => $i]);
        }
        $other_posts = $this->statistics->whereIn('existing_record_id', $exploded)->get();
        $other_posts_grouped_language = $other_posts->groupBy('language_id');
        $english_sort = $this->statistics->where('language_id', $preferred_language)->pluck('display_order', 'id')->toArray();
        if ($other_posts_grouped_language) {
            foreach ($other_posts_grouped_language as $language => $records) {
                foreach ($records as $record) {
                    $this->statistics->update($record->id, ['display_order' => $english_sort[$record->existing_record_id]]);
                }
            }
        }

        return json_encode(['status' => 'success', 'value' => 'Successfully reordered.'], 200);
    }
}
