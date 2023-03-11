<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\Admin\PriceRangeStoreRequest;
use App\Http\Requests\Admin\PriceRangeUpdateRequest;
use App\Repositories\PackagePriceRangeRepository;
use App\Repositories\PackageRepository;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class PackagePriceRangeController extends Controller
{

    public $title = 'pricerange';

    protected $pricerange;

    public function __construct(PackagePriceRangeRepository $pricerange,
                                PackageRepository $package)
    {
        $this->pricerange = $pricerange;
        $this->package = $package;
        auth()->shouldUse('admin');

    }


    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index($id)
    {
        $this->authorize('master-policy.perform', ['package_pricerange', 'view']);
        $title = $this->title;
        $priceranges = $this->pricerange->where('package_id',$id)->orderBy('created_at', 'desc')->paginate('100');
        $package = $this->package->find($id);
        return view('admin.packagepricerange.index')
            ->withPriceranges($priceranges)
            ->withPackage($package)
            ->withTitle($title);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create($id)
    {
        $title = 'Add Pricerange';
        $this->authorize('master-policy.perform', ['package_pricerange', 'add']);
        return view('admin.packagepricerange.create')
            ->withTitle($title)
            ->withPackage($this->package->find($id));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->authorize('master-policy.perform', ['package_pricerange', 'add']);
        $packageid = $request->package_id;
        $data['package_id'] = $packageid;
        $data['traveller_range'] = $request->traveller_range;
        $data['amount'] = $request->amount;
        $data['is_active'] = isset($data['is_active']) ? 0 : 1;
        if($this->pricerange->create($data)){
            return redirect()->route('admin.packagepricerange.index',$packageid)
                ->with('flash_notice', 'Package pricerange Created Successfully.');
        }

        return redirect()->back()->withInput()
            ->with('flash_notice', 'Package pricerange can not be created.');
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
        $this->authorize('master-policy.perform', ['package_pricerange', 'edit']);
        $title = 'Edit pricerange';
        $pricerange = $this->pricerange->find($id);
        return view('admin.packagepricerange.edit')
            ->withPricerange($pricerange)
            ->withPackage($this->package->find($pricerange->package_id))
            ->withTitle($title);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request,$package_id, $packagepricerange_id)
    {
        $this->authorize('master-policy.perform', ['package_pricerange', 'edit']);
        $data = $request->except(['image','_token','_method']);
        $pricerange = $this->pricerange->find($packagepricerange_id);

        $data['is_active'] = isset($data['is_active']) ? 1 : 0;
        if($this->pricerange->update($packagepricerange_id,$data)){
            return redirect()->route('admin.packagepricerange.index',[$package_id])
                ->with('flash_notice', 'Package pricerange updated successfully');
        }

        return redirect()->back()->withInput()
            ->with('flash_notice', 'Package pricerange can not be created.');

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request,$package_id, $packagepricerange_id)
    {
        $this->authorize('master-policy.perform', ['package_pricerange', 'delete']);
        $this->validate($request, [
            'id' => 'required|exists:price_ranges,id',
        ]);
        $pricerange = $this->pricerange->find($packagepricerange_id);
        $this->pricerange->destroy($pricerange->id);
        $message = 'Item deleted successfully.';
        return response()->json(['status' => 'ok', 'message' => $message], 200);

    }

    public function changeStatus(Request $request)
    {
        $this->authorize('master-policy.perform', ['package_pricerange', 'changeStatus']);

        $pricerange = $this->pricerange->find($request->get('id'));
        if ($pricerange->is_active == 0) {
            $status = '1';
            $message = 'pricerange with title "' . $pricerange->title . '" is published.';
        } else {
            $status = '0';
            $message = 'pricerange with title "' . $pricerange->title . '" is unpublished.';
        }
        $this->pricerange->changeStatus($pricerange->id, $status);
        $this->pricerange->update($pricerange->id, array('is_active' => $status));
        $updated = $this->pricerange->find($request->get('id'));
        return response()->json(['status' => 'ok', 'message' => $message, 'response' => $updated], 200);
    }

    public function sort(Request $request)
    {
        $exploded = explode('&', str_replace('item[]=', '', $request->order));
        for ($i = 0; $i < count($exploded); $i++) {
            $this->pricerange->update($exploded[$i], ['display_order' => $i]);
        }
        return json_encode(['status' => 'success', 'value' => 'Successfully reordered.'], 200);
    }

}
