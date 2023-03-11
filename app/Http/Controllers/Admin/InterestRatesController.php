<?php

namespace App\Http\Controllers\Admin;

use App\Helper\MediaHelper;
use App\Helper\Helper;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\InterestRate\InterestRateStoreRequest;
use App\Http\Requests\Admin\InterestRate\InterestRateUpdateRequest;
use App\Repositories\InterestRatesRepository;
use App\Repositories\InterestBatchesRepository;
use Illuminate\Http\Request;

class InterestRatesController extends Controller
{
    public $title = 'Interest Rates';

    protected $interestRate;

    public function __construct(
        InterestRatesRepository $interestRate,
        InterestBatchesRepository $interestBatch
    ) {
        $this->interestRate = $interestRate;
        $this->interestBatch = $interestBatch;
        auth()->shouldUse('admin');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {

        $param = $request->all();

        $this->authorize('master-policy.perform', ['interest-rate', 'view']);
        $title = $this->title;
        $interestTypes = Helper::getInterestTypes();
        if(isset($param['batch']) && $param['batch'] != ''){
            $intBatch = $this->interestBatch->find($param['batch']);

            $interestRates = $intBatch->interestRates;
            $param['batch'] = $intBatch->id;

        }else{
            $intBatch = $this->interestBatch->where('active',1)->first();

            if(!$intBatch)
            {
                $intBatch = $this->interestBatch->first();
            }

            if($intBatch)
            {
                $param['batch'] = $intBatch->id;
            }
        }

        $interestBatches = $this->interestBatch->orderBy('interest_date','DESC')->get();

        return view('admin.IntRate.index', compact('title', 'interestTypes', 'interestBatches', 'param', 'intBatch'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $this->authorize('master-policy.perform', ['interest-rate', 'add']);
        $title = $this->title;
        $interestTypes = Helper::getInterestTypes();
        return view('admin.IntRate.create', compact('title', 'interestTypes'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->authorize('master-policy.perform', ['interest-rate', 'add']);
        $data = $request->except(['_token']);
        $is_active = isset($request['is_active']) ? 1 : 0;

        if($is_active)
        {
            // Disable previous rates
            $this->interestRate->where('is_active',1)->update(['is_active'=>0]);
            $this->interestRate->where('date', $data['date'])->delete();

            // Disable previous rates
            $this->interestBatch->where('active',1)->update(['active'=>0]);
            $this->interestBatch->where('interest_date', $data['date'])->delete();
        }

        $intBatch = [
            'title' => $data['title'],
            'interest_date' => $data['date'],
            'active' => $is_active
        ];

        $batch = $this->interestBatch->create($intBatch);

        $rates = [];
        foreach ($data['interestRates'] as $key => $value) {

            if(strlen($value) <= 11){
                $value = null;
            }

            $row = [
                'batch' => $batch->id,
                'type' => $key,
                'content' => $value,
                'is_active' => $is_active,
                'date' => $data['date']
            ];

            $rates[] = $row;
        }

        $ratesAdded = $this->interestRate->createMany($rates);

        if($ratesAdded){
            return redirect()->route('admin.interest-rates.index')->with('flash_notice', 'Interest Rates added.');
        }

        return redirect()->back()->withInput()->with('flash_notice', 'Interest Rate can not be added.');
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
        $this->authorize('master-policy.perform', ['interest-rate', 'edit']);

        $title = $this->title;
        $interestTypes = Helper::getInterestTypes();

        $batch = $this->interestBatch->find($id);

        $interestRates = $batch->interestRates;

        return view('admin.IntRate.edit', compact('title', 'interestRates', 'interestTypes', 'batch'));
    }

    public function update(Request $request, $id)
    {
        $data = $request->all();
        $this->authorize('master-policy.perform', ['interest-rate', 'edit']);
        $is_active = isset($request['is_active']) ? 1 : 0;

        $batch = $this->interestBatch->find($id);

        $batch->title = $data['title'];
        $batch->active = $is_active;
        $batch->interest_date = $data['date'];
        $batch->save();

        foreach ($data['interestRates'] as $key => $value) {
            $rateId = $key;

            foreach ($value as $type => $content) {
                if(strlen($content) <= 11){
                    $content = null;
                }

                $this->interestRate->where('id', $rateId)->where('type', $type)->update(['content' => $content, 'date' => $data['date'], 'is_active' => $is_active]);
            }
        }

        return redirect()->route('admin.interest-rates.index')->with('flash_notice', 'Interest rates updated successfully.');
    }

    public function editActive()
    {
        $this->authorize('master-policy.perform', ['interest-rate', 'edit']);

        $title = $this->title;
        $interestTypes = Helper::getInterestTypes();

        $interestRates = $this->interestRate->where('is_active',1)->get();

        return view('admin.IntRate.edit', compact('title', 'interestRates', 'interestTypes'));
    }

    public function updateActive(Request $request)
    {
        $data = $request->all();
        $this->authorize('master-policy.perform', ['interest-rate', 'edit']);
        $is_active = isset($request['is_active']) ? 1 : 0;

        foreach ($data['interestRates'] as $key => $value) {
            $rateId = $key;

            foreach ($value as $type => $content) {
                if(strlen($content) <= 11){
                    $content = null;
                }

                $this->interestRate->where('id', $rateId)->where('type', $type)->update(['content' => $content, 'date' => $data['date'], 'is_active' => $is_active]);
            }
        }

        return redirect()->route('admin.interest-rates.index')->with('flash_notice', 'Interest rates updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($batchId)
    {
        $this->authorize('master-policy.perform', ['interest-rate', 'delete']);

        $batch = $this->interestBatch->find($batchId);

        // Clean interest rates
        $batch->interestRates()->delete();
        
        // Delete batch
        $deleted = $batch->delete();

        if($deleted){
            return redirect()->route('admin.interest-rates.index')->withInput()->with('flash_notice', 'Interest rates deleted.');
        }
        
        return redirect()->route('admin.interest-rates.index')->with('flash_notice', 'Interest rates cannot be deleted.');
    }

    public function toggleStatus(Request $request, $id)
    {
        $data = $request->all();

        $batch = $this->interestBatch->find($id);

        if($data['checked'] == 1)
        {
            $this->interestBatch->where('active',1)->update(['active'=>0]);
        }

        $batch->active = $data['checked'] == 1 ? 1 : 0;
        $batch->save();

        $message = $data['checked'] == 1 ? 'Interest rate activated.' : 'Interest rate deactivated.';

        $updated = $this->interestBatch->find($id);
        return response()->json(['status' => 'ok', 'message' => $message, 'response' => $updated], 200);
    }
}
