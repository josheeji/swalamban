<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\Admin\BannerStoreRequest;
use App\Http\Requests\Admin\BannerUpdateRequest;
use App\Repositories\PackageBannerRepository;
use App\Repositories\PackageRepository;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class PackageBannerController extends Controller
{

    public $title = 'Banner';

    protected $banner;

    public function __construct(PackageBannerRepository $banner,
                                PackageRepository $package)
    {
        $this->banner = $banner;
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
        $this->authorize('master-policy.perform', ['package_banner', 'view']);
        $title = $this->title;
        $banners = $this->banner->where('package_id',$id)->orderBy('display_order', 'asc')->orderBy('created_at', 'desc')->paginate('10');
        $package = $this->package->find($id);
        return view('admin.packagebanner.index')
        ->withBanners($banners)
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
        $title = 'Add Banner';
        $this->authorize('master-policy.perform', ['package_banner', 'add']);
        return view('admin.packagebanner.create')
        ->withTitle($title)
        ->withPackage($this->package->find($id));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(BannerStoreRequest $request)
    {
        $this->authorize('master-policy.perform', ['package_banner', 'add']);
        $data = $request->except(['image']);
        $packageid = $request->package_id;
        if($request->get('image')){
            $saveName = sha1(date('YmdHis') . Str::random(3));
            $image = $request->get('image');
            $image = str_replace('data:image/png;base64,', '', $image);
            $image = str_replace(' ', '+', $image);
            $imageData = base64_decode($image);
            $data['image'] = 'packagebanner/'. $saveName . '.png';

            Storage::put($data['image'], $imageData);
        }
        $data['is_active'] = isset($data['is_active']) ? 1 : 0;
        $data['created_by'] = Auth::user()->id;
        if($this->banner->create($data)){
            return redirect()->route('admin.packagebanner.index',$packageid)
                ->with('flash_notice', 'Package Banner Created Successfully.');
        }

        return redirect()->back()->withInput()
            ->with('flash_notice', 'Package Banner can not be created.');
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
        $this->authorize('master-policy.perform', ['package_banner', 'edit']);
        $title = 'Edit Banner';
        $banner = $this->banner->find($id);
        return view('admin.packagebanner.edit')
        ->withBanner($banner)
        ->withTitle($title);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function update(BannerUpdateRequest $request,$package_id, $packagebanner_id)
    {
        $this->authorize('master-policy.perform', ['package_banner', 'edit']);
        $data = $request->except(['image','_token','_method']);
        $banner = $this->banner->find($packagebanner_id);
        if($request->get('image')){
            $saveName = sha1(date('YmdHis') . Str::random(3));
            $image = $request->get('image');
            $image = str_replace('data:image/png;base64,', '', $image);
            $image = str_replace(' ', '+', $image);
            $imageData = base64_decode($image);
            $data['image'] = 'packagebanner/'. $saveName . '.png';
            Storage::put($data['image'], $imageData);
            if(Storage::exists($banner->image)){
                Storage::delete($banner->image);
            }
        }
        $data['is_active'] = isset($data['is_active']) ? 1 : 0;
        $data['updated_by'] = Auth::user()->id;
        if($this->banner->update($packagebanner_id,$data)){
            return redirect()->route('admin.packagebanner.index',[$package_id])
                ->with('flash_notice', 'Package Banner Created Successfully.');
        }

        return redirect()->back()->withInput()
            ->with('flash_notice', 'Package Banner can not be created.');

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request,$package_id, $packagebanner_id)
    {
        $this->authorize('master-policy.perform', ['package_banner', 'delete']);
        $this->validate($request, [
            'id' => 'required|exists:package_banners,id',
        ]);
        $banner = $this->banner->find($packagebanner_id);
        $this->banner->destroy($banner->id);
        $message = 'Item deleted successfully.';
        return response()->json(['status' => 'ok', 'message' => $message], 200);

    }

    public function changeStatus(Request $request)
    {
        $this->authorize('master-policy.perform', ['package_banner', 'changeStatus']);

         $banner = $this->banner->find($request->get('id'));
        if ($banner->is_active == 0) {
            $status = '1';
            $message = 'banner with title "' . $banner->title . '" is published.';
        } else {
            $status = '0';
            $message = 'banner with title "' . $banner->title . '" is unpublished.';
        }
        $this->banner->changeStatus($banner->id, $status);
        $this->banner->update($banner->id, array('is_active' => $status));
        $updated = $this->banner->find($request->get('id'));
        return response()->json(['status' => 'ok', 'message' => $message, 'response' => $updated], 200);
    }

    public function sort(Request $request)
    {
        $exploded = explode('&', str_replace('item[]=', '', $request->order));
        for ($i = 0; $i < count($exploded); $i++) {
            $this->banner->update($exploded[$i], ['display_order' => $i]);
        }
        return json_encode(['status' => 'success', 'value' => 'Successfully reordered.'], 200);
    }

}
