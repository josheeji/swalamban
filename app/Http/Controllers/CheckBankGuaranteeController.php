<?php

namespace App\Http\Controllers;

use App\Repositories\CheckBankGuaranteeRepository;
use Illuminate\Http\Request;
class CheckBankGuaranteeController extends Controller
{

    protected $check_bank_guarantee;

    public function __construct(CheckBankGuaranteeRepository $check_bank_guarantee)
    {
        $this->check_bank_guarantee = $check_bank_guarantee;
    }

    public function index()
    {
        return view('checkBankGuarantee.show');
    }

    public function result(Request $request)
    {
        $data = $this->check_bank_guarantee->where('ref_no', trim($request->ref_number))->first();
        return view('checkBankGuarantee.show')->with(['data' => $data, 'post' => true]);
    }
}
