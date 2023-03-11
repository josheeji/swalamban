<?php

namespace App\Http\Controllers\Admin;

use App\Helper\Helper;
use App\Http\Controllers\Controller;
use App\Repositories\StockInfoRepository;
use Illuminate\Http\Request;

class StockInfoController extends Controller
{
    public function __construct(StockInfoRepository $stockinfo)
    {
        $this->stockInfo = $stockinfo;
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
        $this->authorize('master-policy.perform', ['stock-info', 'view']);
        $stockInfos = $this->stockInfo->where('language_id', $this->preferredLanguage)->orderBy('id', 'desc')->get();

        return view('admin.stockInfo.index', ['stockInfos' => $stockInfos]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $this->authorize('master-policy.perform', ['stock-info', 'add']);

        return view('admin.stockInfo.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->authorize('master-policy.perform', ['stock-info', 'add']);

        $data = $request->except(['_token']);
        $data['is_active'] = isset($data['is_active']) ? $data['is_active'] : 0;
        $preferred_language = $this->preferredLanguage;
        /**
         * Insert Preferred Language Item First.
         */
        $preferred_language_item = $data;
        $preferred_language_item['language_id'] = $preferred_language;
        $preferred_language_item['paidup_value'] = $data['paidup_value'][$preferred_language];
        $preferred_language_item['maximum'] = $data['maximum'][$preferred_language];
        $preferred_language_item['minimum'] = $data['minimum'][$preferred_language];
        $preferred_language_item['closing'] = $data['closing'][$preferred_language];
        $preferred_language_item['traded_share'] = $data['traded_share'][$preferred_language];
        $preferred_insert = $this->stockInfo->create($preferred_language_item);
        if ($preferred_insert) {
            $lang_items = [];
            $count = 0;
            foreach ($data['paidup_value'] as $language_id => $value) {
                if ($language_id != $preferred_language) {
                    if ($data['paidup_value'][$language_id] != NULL) {
                        $lang_items[$count] = $data;
                        $lang_items[$count]['existing_record_id'] = $preferred_insert->id;
                        $lang_items[$count]['language_id'] = $language_id;
                        $lang_items[$count]['paidup_value'] = $data['paidup_value'][$language_id];
                        $lang_items[$count]['maximum'] = $data['maximum'][$language_id];
                        $lang_items[$count]['minimum'] = $data['minimum'][$language_id];
                        $lang_items[$count]['closing'] = $data['closing'][$language_id];
                        $lang_items[$count]['traded_share'] = $data['traded_share'][$language_id];
                        $lang_items[$count]['created_by'] = $preferred_insert->created_by;
                        $lang_items[$count]['created_at'] = $preferred_insert->created_at;
                        $count++;
                    }
                }
            }
            if (!empty($lang_items)) {
                $this->stockInfo->model()->insert($lang_items);
            }

            return redirect()->route('admin.stock-watch.index')->with('flash_success', 'Stock Watch Created Successfully.');
        }

        return redirect()->back()->withInput()->with('flash_error', 'Stock Watch can not be Created');
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
        $this->authorize('master-policy.perform', ['stock-info', 'edit']);

        $preferredLanguage = $this->preferredLanguage;
        $stockInfo = $this->stockInfo->find($id);
        $lang_content = $this->stockInfo->where('existing_record_id', $stockInfo->id)->where('language_id', '!=', $this->preferredLanguage)->get();
        $langContent = $lang_content->groupBy('language_id');
        return view('admin.stockInfo.edit', ['stockInfo' => $stockInfo, 'langContent' => $langContent, 'preferredLanguage' => $preferredLanguage]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $this->authorize('master-policy.perform', ['stock-info', 'edit']);
        $stockInfo = $this->stockInfo->find($id);
        $data = $request->except(['_token', '_method']);
        // dd($data);
        $data['is_active'] = isset($data['is_active']) ? 1 : 0;
        $preferred_language = $this->preferredLanguage;
        $existing_record_id = $this->stockInfo->find($data['post'][$preferred_language]) ?? 0;
        if ($existing_record_id) {
            foreach ($data['paidup_value'] as $language_id => $value) {
                if ($data['paidup_value'][$language_id] != NULL) {
                    $lang_items = $data;
                    $lang_items['language_id'] = $language_id;
                    $lang_items['existing_record_id'] = ($language_id != $preferred_language) ? $existing_record_id->id : null;
                    $lang_items['paidup_value'] = $data['paidup_value'][$language_id];
                    $lang_items['maximum'] = $data['maximum'][$language_id];
                    $lang_items['minimum'] = $data['minimum'][$language_id];
                    $lang_items['closing'] = $data['closing'][$language_id];
                    $lang_items['traded_share'] = $data['traded_share'][$language_id];
                    $lang_items['id'] = $data['post'][$language_id];
                    $lang_items['created_by'] = $existing_record_id->created_by;
                    $lang_items['created_at'] = $existing_record_id->created_at;
                    $lang_items['published_at'] = $data['published_at'];
                    unset($lang_items['post']);
                    $this->stockInfo->model()->updateOrInsert(['id' => $data['post'][$language_id]], $lang_items);
                }
            }

            return redirect()->route('admin.stock-watch.index')->with('flash_success', 'Stock watch updated successfully');
        }

        return redirect()->back()->withInput()->with('flash_error', 'Stock watch can not be Updated.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $this->authorize('master-policy.perform', ['stock-info', 'delete']);

        $stockWatch = $this->stockInfo->find($id);
        $this->stockInfo->where('existing_record_id', $id)->delete();
        if ($this->stockInfo->destroy($stockWatch->id)) {
            $message = 'Stock watch info deleted successfully.';
            return response()->json(['status' => 'ok', 'message' => $message], 200);
        }

        return response()->json(['status' => 'error', 'message' => ''], 422);
    }

    public function changeStatus(Request $request)
    {
        $stockWatch = $this->stockInfo->find($request->get('id'));
        $status = $stockWatch->is_active == 0 ? 1 : 0;
        $message = $stockWatch->is_active == 0 ? 'Stock watch with title "' . $stockWatch->title . '" is published.' : 'Stock watch with title "' . $stockWatch->title . '" is unpublished.';
        $this->stockInfo->changeStatus($stockWatch->id, $status);
        $updated = $this->stockInfo->find($request->get('id'));
        if ($multiContenct = $this->stockInfo->where('existing_record_id', $stockWatch->id)->first()) {
            $this->stockInfo->changeStatus($multiContenct->id, $status);
        }

        return response()->json(['status' => 'ok', 'message' => $message, 'response' => $updated], 200);
    }
}
