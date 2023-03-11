<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\CheckBankGuaranteeRequest;
use App\Imports\CheckBankGuaranteeImport;
use App\Models\CheckBankGuarantee;
use App\Repositories\CheckBankGuaranteeRepository;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Excel;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Validator;

class CheckBankGuaranteeController extends Controller
{
    protected $check_bank_guarantee;
    public function __construct(CheckBankGuaranteeRepository $check_bank_guarantee)
    {
        $this->check_bank_guarantee = $check_bank_guarantee;
    }

    /**
     * Display search form
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $data = $request->all();
        $result = $this->check_bank_guarantee->orderBy('id', 'desc')->paginate(30);
        return view('admin.checkBankGuarantee.index', ['data' => $result]);
    }

    public function import()
    {
        return view('admin.checkBankGuarantee.import');
    }

    public function saveImport(CheckBankGuaranteeRequest $request)
    {
        DB::table('check_bank_guarantee')->truncate();

        $path = $request->file('file');
        $rows = Excel::toArray(new CheckBankGuaranteeImport, $path);
        static $i = 0;
        $data = [];
        foreach ($rows as $k => $v) {
            foreach ($v as $key => $row) {
                // if (is_numeric($row[0])) {
                    $data[$i]['branch_code'] = isset($row[0]) ? $row[0] : '';
                    $data[$i]['branch_name'] = isset($row[1]) ? $row[1] : '';
                    $data[$i]['ref_no'] = isset($row[2]) ? $row[2] : '';
                    $data[$i]['applicant'] = isset($row[3]) ? $row[3] : '';
                    $data[$i]['beneficiary'] = isset($row[4]) ? $row[4] : '';
                    $data[$i]['purpose'] = isset($row[5]) ? $row[5] : '';
                    $data[$i]['lcy_amount'] = isset($row[6]) ? $row[6] : '';
                    //$data[$i]['issued_date'] = Carbon::parse(isset($row[7]) ? $row[7] : '')->format('Y-m-d');
                    //$data[$i]['expiary_date'] = Carbon::parse(isset($row[8]) ? $row[8] : '')->format('Y-m-d');
                
                    $data[$i]['issued_date'] = ($row[7]) ? Carbon::parse($row[7])->format('Y-m-d') : '';
                    $data[$i]['expiary_date'] = ($row[8]) ? Carbon::parse($row[8])->format('Y-m-d') : '';

                    $i++;
                // }
            }
        }

        $chunk_data = array_chunk($data, 1000);
        if (isset($chunk_data) && !empty($chunk_data)) {
            foreach ($chunk_data as $chunk_data_val) {
                CheckBankGuarantee::insert($chunk_data_val);
            }
        }

        return redirect()->route('admin.check-bank-guarantee.index')
            ->with('flash_notice', 'Bank Guarantee Records imported successfully.');
    }

    public function view($id)
    {
        $item = $this->check_bank_guarantee->find($id);
        return view('admin.checkBankGuarantee.view', ['data' => $item]);
    }

    public function edit($id)
    {
        $item = $this->check_bank_guarantee->find($id);
        return view('admin.checkBankGuarantee.edit', ['data' => $item]);
    }

    public function update(Request $request, $id)
    {
        $rules = array(
            'branch_code' => 'required',
            'branch_name' => 'required',
            'ref_no' => 'required',
            'applicant' => 'required',
            'beneficiary' => 'required',
            'purpose' => 'required',
            'lcy_amount' => 'required',
            'issued_date' => 'required',
            'expiary_date' => 'required',
        );
        $validator = Validator::make($request->all(), $rules);
        
        if ($validator->fails()) {
            return redirect()->route('admin.check-bank-guarantee.index')
                ->withErrors($validator);
        } else {
            $data = CheckBankGuarantee::find($id);
            $data->branch_code  = $request->input('branch_code');
            $data->branch_name  = $request->input('branch_name');
            $data->ref_no = $request->input('ref_no');
            $data->applicant  = $request->input('applicant');
            $data->beneficiary  = $request->input('beneficiary');
            $data->purpose  = $request->input('purpose');
            $data->lcy_amount  = $request->input('lcy_amount');
            $data->issued_date  = $request->input('issued_date');
            $data->expiary_date  = $request->input('expiary_date');


            $data->save();
            
            return redirect()->route('admin.check-bank-guarantee.index')
            ->with('flash_notice', 'Records Updated successfully.');
        }
    }

    public function truncate(Request $request)
    {
        $this->authorize('master-policy.perform', ['check-bank-guarantee', 'edit']);
        DB::table('check_bank_guarantee')->truncate();
        return redirect()->route('admin.check-bank-guarantee.index')
            ->with('flash_notice', 'Table truncated successfully.');
    }

    public function destroy($id)
    {
        $this->authorize('master-policy.perform', ['check-bank-guarantee', 'delete']);
        $item = $this->check_bank_guarantee->find($id);
        if ($this->check_bank_guarantee->destroy($item->id)) {
            $message = 'Bank Guarantee details deleted successfully.';
            return response()->json(['status' => 'ok', 'message' => $message], 200);
        }
        return response()->json(['status' => 'error', 'message' => ''], 422);
    }
}
