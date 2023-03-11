<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Forex\ImportForexRequest;
use App\Http\Requests\Admin\Forex\StoreForexRequest;
use App\Http\Requests\Admin\Forex\UpdateForexRequest;
use App\Imports\ForexImport;
use App\Repositories\ForexOrderRepository;
use App\Repositories\ForexRepository;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class ForexController extends Controller
{
    public function __construct(ForexOrderRepository $forexOrder, ForexRepository $forex)
    {
        $this->forexOrder = $forexOrder;
        $this->forex = $forex;
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $this->authorize('master-policy.perform', ['forex', 'view']);
        $this->permanentDestory();
        $date = $request->has('date') ? $request->get('date') : date('Y-m-d');
        $forexes = $this->forex->where('RTLIST_DATE', $date)->get();
        $forexOrders = $this->forexOrder->orderBy('order', 'asc')->pluck('name', 'code')->toArray();
        return view('admin.forex.index', ['forexOrders' => $forexOrders, 'forexes' => $forexes, 'date' => $date]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $this->authorize('master-policy.perform', ['forex', 'add']);
        $forexOrders = $this->forexOrder->orderBy('order', 'asc')->get();
        return view('admin.forex.create', ['forexOrders' => $forexOrders]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreForexRequest $request)
    {
        $this->authorize('master-policy.perform', ['forex', 'add']);
        $data = $request->except(['_token', 'Forex']);
        $commonData['RTLIST_DATE'] = $data['RTLIST_DATE'];
        $forexes =  $request->only(['Forex']);
        if ($this->forex->where('RTLIST_DATE', $commonData['RTLIST_DATE'])->first()) {
            return redirect()->route('admin.forex.index')->with('flash_success', 'Forex rates already added for ' . $commonData['RTLIST_DATE']);
        }
        foreach ($forexes['Forex'] as $forex) {
            $input = array_merge($commonData, $forex);
            $this->forex->create($input);
        }
        return redirect()->route('admin.forex.index')->with('flash_success', 'Forex rates added successfully');;
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
        $this->authorize('master-policy.perform', ['forex', 'edit']);
        $forexes = $this->forex->where('RTLIST_DATE', $id)->get();
        $forexOrders = $this->forexOrder->orderBy('order', 'asc')->get();
        $data = [];
        foreach ($forexOrders as $order) {
            $data[$order->code]['unit'] = $order->unit;
        }
        return view('admin.forex.edit', ['forexOrders' => $data, 'forexes' => $forexes, 'date' => $id]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateForexRequest $request, $id)
    {
        $this->authorize('master-policy.perform', ['forex', 'edit']);
        $data = $request->except(['_token']);
        foreach ($data['Forex'] as $key =>  $forex) {
            if (is_numeric($key)) {
                $this->forex->update($key, $forex);
            } else {
                $forex['RTLIST_DATE'] = $id;
                $this->forex->create($forex);
            }
        }
        return redirect()->route('admin.forex.index', ['date' => $id])->with('flash_success', 'Forex updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $this->authorize('master-policy.perform', ['forex', 'delete']);
        $message = 'Forex cannot be deleted.';
        if ($forexes = $this->forex->where('RTLIST_DATE', $id)->get()) {
            foreach ($forexes as $forex) {
                $forex->forceDelete();
                $message = 'Forex deleted successfully.';
            }
            return response()->json(['status' => 'ok', 'message' => $message], 200);
        }
        return response()->json(['status' => 'ok', 'message' => $message], 200);
    }

    public function permanentDestory()
    {
        $date = date('Y-m-d', strtotime("-30 days"));
        $forexes = $this->forex->where('RTLIST_DATE', '<', $date)->get();
        foreach ($forexes as $forex) {
            $forex->forceDelete();
        }
    }

    public function import()
    {
        $this->authorize('master-policy.perform', ['forex', 'add']);
        return view('admin.forex.import');
    }

    public function storeImport(ImportForexRequest $request)
    {
        $this->authorize('master-policy.perform', ['forex', 'add']);
        $path = $request->file('file');
        $date = $request->get('RTLIST_DATE');
        if ($this->forex->where('RTLIST_DATE', $date)->first()) {
            return redirect()->route('admin.forex.index')->with('flash_success', 'Forex rates already added for ' . $date . '.');
        }
        $rows = Excel::toArray(new ForexImport, $path);
        static $i = 0;
        $data = [];
        foreach ($rows as $k => $v) {
            foreach ($v as $key => $row) {
                if (is_numeric($row[0])) {
                    $data[$i]['RTLIST_DATE'] = $date;
                    $data[$i]['FXD_CRNCY_CODE'] = $row[1];
                    $data[$i]['VAR_CRNCY_CODE'] = 'NPR';
                    $data[$i]['FXD_CRNCY_UNITS'] = $row[2];
                    $data[$i]['BUY_RATE'] = $row[3];
                    $data[$i]['BUY_RATE_ABOVE'] = $row[4];
                    $data[$i]['SELL_RATE'] = $row[5];
                    $data[$i]['created_at'] = Carbon::now();
                    $i++;
                }
            }
        }
        $chunk_data = array_chunk($data, 1000);
        if (isset($chunk_data) && !empty($chunk_data)) {
            foreach ($chunk_data as $chunk_data_val) {
                $this->forex->model()->insert($chunk_data_val);
            }
        }
        return redirect()->route('admin.forex.index')->with('flash_success', 'Forex imported successfully.');
    }
}
