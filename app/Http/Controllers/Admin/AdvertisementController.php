<?php

namespace App\Http\Controllers\Admin;

use App\Helper\MediaHelper;
use App\Repositories\AdvertisementRepository;
use App\Repositories\PlacementRepository;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Advertisement\AdvertisementStoreRequest;
use App\Http\Requests\Admin\Advertisement\AdvertisementUpdateRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;

class AdvertisementController extends Controller
{
    protected $placement;
    protected $advertisement;

    public function __construct(PlacementRepository $placement, AdvertisementRepository $advertisement)
    {
        $this->placement = $placement;
        $this->advertisement = $advertisement;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        abort_if(Gate::denies('master-policy.perform', ['advertisement', 'view']), 403);
        $ads = $this->advertisement->allWith('placement');

        return view('admin.ads.index', compact('ads'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        abort_if(Gate::denies('master-policy.perform', ['advertisement', 'add']), 403);
        $placement = $this->placement->all();

        return view('admin.ads.create', compact('placement'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(AdvertisementStoreRequest $request)
    {
        $data = $request->except(['image']);
        if ($request->hasFile('image')) {
            $filelocation = MediaHelper::upload($request->file('image'), 'advertisement', true, true);
            $data['image'] = $filelocation['storage'];
        }
        $data['is_active'] = (isset($data['is_active']) && $data['is_active'] != 0) ? 1 : 0;
        $ads = $this->advertisement->create($data);

        return redirect()->route('admin.ads.index')
            ->with('flash_notice', 'Advertisement Created Successfully.');
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
        abort_if(Gate::denies('master-policy.perform', ['advertisement', 'edit']), 403);
        $ad = $this->advertisement->find($id);
        $placement = $this->placement->all();

        return view('admin.ads.edit', compact('ad', 'placement'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(AdvertisementUpdateRequest $request, $id)
    {
        $data = $request->except(['image']);
        $advertisement = $this->advertisement->find($id);
        if ($request->hasFile('image')) {
            MediaHelper::destroy($advertisement->image);
            $filelocation = MediaHelper::upload($request->file('image'), 'advertisement', true, true);
            $data['image'] = $filelocation['storage'];
        }
        $data['is_active'] = (isset($data['is_active']) && $data['is_active'] != 0) ? 1 : 0;
        $ads = $this->advertisement->update($id, $data);

        return redirect()->route('admin.ads.index')
            ->with('flash_notice', 'Advertisement updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */

    public function destroy(Request $request, $id)
    {
        abort_if(Gate::denies('master-policy.perform', ['advertisement', 'delete']), 403);
        $this->validate($request, [
            'id' => 'required|exists:advertisements,id',
        ]);
        $advertisement = $this->advertisement->find($request->get('id'));
        $this->advertisement->update($advertisement->id, array('deleted_by' => Auth::user()->id));
        $this->advertisement->destroy($advertisement->id);
        $message = 'Advertisement deleted successfully.';

        return response()->json(['status' => 'ok', 'message' => $message], 200);
    }

    public function changeStatus(Request $request)
    {
        abort_if(Gate::denies('master-policy.perform', ['advertisement', 'changeStatus']), 403);
        $advertisement = $this->advertisement->find($request->get('id'));
        $status = $advertisement->is_active == 0 ? 1 : 0;
        $message = $advertisement->is_active == 0 ? 'Advertisement published.' : 'Advertisement unpublished.';
        $this->advertisement->changeStatus($advertisement->id, $status);
        $this->advertisement->update($advertisement->id, array('status_by' => Auth::user()->id));
        $updated = $this->advertisement->find($request->get('id'));

        return response()->json(['status' => 'ok', 'message' => $message, 'response' => $updated], 200);
    }

    public function checkAdvertisement($data, $id = null)
    {
        $response = false;
        foreach ($data['visible_in'] as $key => $value) {
            if (
                $this->advertisement->where('placement_id', $data['placement_id'])->whereNotIn('id', [$id])
                ->where('visible_in', 'like', '%' . $value . '%')->where('is_active', 1)->count() > 0
            ) {

                return true;
            }
        }

        return $response;
    }
}
