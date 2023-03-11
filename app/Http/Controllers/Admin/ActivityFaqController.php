<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\Admin\ActivityFaqStoreRequest;
use App\Http\Controllers\Controller;
use App\Repositories\ActivityRepository;
use App\Repositories\ActivityFaqRepository;
use Illuminate\Http\Request;

class ActivityFaqController extends Controller
{

    protected $faq;

    protected $activity;

    public function __construct(ActivityFaqRepository $faq, ActivityRepository $activity)
    {
        $this->faq = $faq;
        $this->activity = $activity;

    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index($activity_id)
    {
        $activity = $this->activity->find($activity_id);
        $faq = $this->faq->where('faq_activity_id', $activity_id)->get();
        return view('admin.activity.faqListIndex')
            ->withActivity($activity)
            ->withFaq($faq);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create($activity_id)
    {
        return view('admin.activity.faqCreate')
            ->withActivity($this->activity->find($activity_id));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(ActivityFaqStoreRequest $request,$activity_id)
    {
                $data['faq_activity_id'] = $activity_id;
                $data['question'] = $request->question;
                $data['answer'] = $request->answer;
                $data['is_active'] = isset($request->is_active) ? 1:0;
           if($this->faq->create($data)){
            return redirect()->route('admin.activityfaq.index',[$activity_id])
                ->with('flash_notice', 'Faq  Added Successfully.');
        }else{
            return redirect()->back()->with('flash_error', 'Something went wrong during the operation.');
        }
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
    public function edit($activity_id, $id)
    {
        $data['activity_id'] = $activity_id;
        $data['faq'] = $this->faq->find($id);
        return view('admin.activity.faqEdit', $data);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(ActivityFaqStoreRequest $request, $activity_id, $id)
    {
        $data = $request->all();
        $data['is_active'] = (isset($data['is_active']) && $data['is_active'] != 0) ? 1 : 0;
        $type = $this->faq->update($id, $data);

        return redirect()->route('admin.activityfaq.index', [$activity_id])
            ->with('flash_notice', 'FAQ updated successfully');
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
            'id' => 'required|exists:activity_faqs,id',
        ]);
        $faq = $this->faq->find($request->get('id'));
        $this->faq->destroy($faq->id);
        $message = 'FAQ deleted successfully.';
        return response()->json(['status' => 'ok', 'message' => $message], 200);
    }

    public function changeStatus(Request $request)
    {
        $faq = $this->faq->find($request->get('id'));
        if ($faq->is_active == 0) {
            $status = 1;
            $message = 'FAQ with question "' . $faq->question . '" is published.';
        } else {
            $status = 0;
            $message = 'FAQ with question "' . $faq->question . '" is unpublished.';
        }

        $this->faq->changeStatus($faq->id, $status);
        $updated = $this->faq->find($request->get('id'));
        return response()->json(['status' => 'ok', 'message' => $message, 'response' => $updated], 200);
    }
}
