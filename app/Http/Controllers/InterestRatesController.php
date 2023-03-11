<?php

namespace App\Http\Controllers;

use App\Helper\MediaHelper;
use App\Helper\Helper;
use App\Http\Controllers\Controller;
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
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $param = $request->all();

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

            if($intBatch){
                $param['batch'] = $intBatch->id;
            }
            
        }


        $interestBatches = $this->interestBatch->orderBy('interest_date','DESC')->get();

        return view('interestRates.index', compact('title', 'interestTypes', 'interestBatches', 'param', 'intBatch'));
    }

}
