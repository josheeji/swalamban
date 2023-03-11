<?php

namespace App\Http\Controllers\Admin;

use App\Helper\MediaHelper;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\PackageRequest;
use App\Repositories\PackageRepository;
use App\Repositories\DestinationRepository;
use App\Repositories\ActivityRepository;
use App\Repositories\PackageCategoryRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class PackageController extends Controller
{
    public $title = 'Packages';

    protected $package;
    protected $destination;
    protected $activity;
    protected $package_category;

    public function __construct(
        PackageRepository $package,
        DestinationRepository $destination,
        ActivityRepository $activity,
        PackageCategoryRepository $package_category
    ) {
        $this->package = $package;
        $this->destination = $destination;
        $this->activity = $activity;
        $this->package_category = $package_category;
        auth()->shouldUse('admin');
    }


    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        abort_if(Gate::denies('master-policy.perform', ['packages', 'view']), 403);
        $title = $this->title;
        $packages = $this->package->orderBy('display_order', 'asc')->orderBy('created_at', 'desc')->with('creator', 'updator')->paginate(100);
        return view('admin.package.index')
            ->withTitle($title)
            ->withPackages($packages);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        abort_if(Gate::denies('master-policy.perform', ['packages', 'add']), 403);
        $title = 'Add Package';
        $categories = $this->package_category->categoryList();
        return view('admin.package.create', compact('title', 'categories'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(PackageRequest $request)
    {
        $this->authorize('master-policy.perform', ['packages', 'add']);
        $data = $request->except(['image']);
        if ($request->hasFile('image')) {
            $filelocation = MediaHelper::upload($request->file('image'), 'package-category');
            $data['image'] = $filelocation['storage'];
        }
        $data['is_active'] = isset($request['is_active']) ? 1 : 0;
        $data['best_sale'] = isset($request['best_sale']) ? 1 : 0;
        if ($this->package->create($data)) {
            return redirect()->route('admin.packages.index')->with('flash_notice', 'Package is created Successfully');
        }
        return redirect()->back()->withInput()->with('flash_notice', 'Package can not be created ');
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
        abort_if(Gate::denies('master-policy.perform', ['packages', 'edit']), 403);
        $title = 'Edit Package';
        $package = $this->package->find($id);
        $categories = $this->package_category->categoryList();
        return view('admin.package.edit', compact('title', 'package', 'categories'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(PackageRequest $request, $id)
    {
        $this->authorize('master-policy.perform', ['packages', 'edit']);
        $package = $this->package->find($id);
        $data = $request->only(['title', 'category_id', 'description', 'cost', 'tac', 'trip_overview', 'itinerary_details', 'includes_excludes', 'duration', 'food', 'difficulty', 'accommodation', 'start_end', 'trip_code', 'max_altitude', 'transportation', 'best_season']);
        if ($request->hasFile('image')) {
            $filelocation = MediaHelper::upload($request->file('image'), 'package-category');
            $data['image'] = $filelocation['storage'];
        }
        $data['is_active'] = isset($request['is_active']) ? 1 : 0;
        $data['best_sale'] = isset($request['best_sale']) ? 1 : 0;
        if ($this->package->update($package->id, $data)) {
            return redirect()->route('admin.packages.index')->with('flash_notice', 'Package is Update Successfully');
        }
        return redirect()->back()->withInput()->with('flash_notice', 'Package can not be Update ');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, $id)
    {
        $this->validate($request, [
            'id' => 'required|exists:packages,id',
        ]);
        $package = $this->package->find($request->get('id'));
        $this->package->update($package->id, array('deleted_by' => Auth::user()->id));
        $this->package->destroy($package->id);
        $message = 'Package deleted successfully.';
        return response()->json(['status' => 'ok', 'message' => $message], 200);
    }

    public function changeStatus(Request $request)
    {
        abort_if(Gate::denies('master-policy.perform', ['packages', 'changeStatus']), 403);
        $package = $this->package->find($request->get('id'));
        if ($package->is_active == 0) {
            $status = 1;
            $message = 'Package with title "' . $package->title . '" is published.';
        } else {
            $status = 0;
            $message = 'Package with title "' . $package->title . '" is unpublished.';
        }

        $this->package->changeStatus($package->id, $status);
        $this->package->update($package->id, ['is_active' => $status]);
        $updated = $this->package->find($request->get('id'));
        return response()->json(['status' => 'ok', 'message' => $message, 'response' => $updated], 200);
    }

    public function sort(Request $request)
    {
        $exploded = explode('&', str_replace('item[]=', '', $request->order));
        for ($i = 0; $i < count($exploded); $i++) {
            $this->package->update($exploded[$i], ['display_order' => $i]);
        }
        return json_encode(['status' => 'success', 'value' => 'Successfully reordered.'], 200);
    }
}
