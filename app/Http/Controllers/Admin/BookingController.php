<?php

namespace App\Http\Controllers\Admin;

use App\Repositories\BookingRepository;
use App\Repositories\DestinationRepository;
use App\Repositories\PackageRepository;
use App\Repositories\SiteSettingRepository;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class BookingController extends Controller
{

    protected  $booking;


    public function __construct(
        BookingRepository $booking,
        DestinationRepository $destination,
        PackageRepository $package
    ) {
        $this->booking = $booking;
        $this->destination = $destination;
        $this->package = $package;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return redirect()->route('admin.dashboard');
        $this->authorize('master-policy.perform', ['booking', 'view']);
        $perpage = '100';
        return view('admin.booking.index')
            ->withBookings($this->booking->orderBy('display_order', 'asc')->orderBy('created_at', 'desc')->paginate($perpage))
            ->withDestinations($this->destination->all())
            ->withPackages($this->package->all());
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->authorize('master-policy.perform', ['booking', 'add']);
        $data = $request->except('_token');

        foreach ($data as $key => $value) {
            $this->setting->updateByField($key, $value);
        }

        return redirect()->route('admin.setting.index')
            ->with('flash_notice', 'Setting updated successfully');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //      $booking = $this->booking->find($id);
        //      return json_encode($booking);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
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
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, $id)
    {
        $this->authorize('master-policy.perform', ['booking', 'delete']);
        $article = $this->booking->find($request->get('id'));
        if ($this->booking->destroy($article->id)) {
            $message = 'Booking deleted successfully.';
            return response()->json(['status' => 'ok', 'message' => $message], 200);
        }
        return response()->json(['status' => 'error', 'message' => ''], 200);
    }
    public function changeStatus(Request $request)
    {
        $this->authorize('master-policy.perform', ['booking', 'changeStatus']);
        $booking = $this->booking->find($request->get('id'));
        if ($booking->is_active == 0) {
            $status = '1';
            $message = 'Booking is published.';
        } else {
            $status = '0';
            $message = 'activity is unpublished.';
        }
        $this->booking->changeStatus($booking->id, $status);
        $this->booking->update($booking->id, ['is_active' => $status]);
        $updated = $this->booking->find($request->get('id'));
        return response()->json(['status' => 'ok', 'message' => $message, 'response' => $updated], 200);
    }

    public function sort(Request $request)
    {
        $exploded = explode('&', str_replace('item[]=', '', $request->order));
        for ($i = 0; $i < count($exploded); $i++) {
            $this->booking->update($exploded[$i], ['display_order' => $i]);
        }
        return json_encode(['status' => 'success', 'value' => 'Successfully reordered.'], 200);
    }
}
