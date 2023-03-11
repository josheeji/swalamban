<?php

namespace App\Http\Controllers\Admin;

use App\Repositories\EmailSubscriptionRepository;
use App\Repositories\SendEmailSubscriptionRepository;
use App\Mail\EmailSendToSubscriberForwarded;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Mail;

class EmailSubscribeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    protected $emailsubscribe;
    public function __construct(
        EmailSubscriptionRepository $emailsubscribe,
        SendEmailSubscriptionRepository $send_email_subscribe
    ) {
        $this->emailsubscribe = $emailsubscribe;
        $this->send_email_subscribe = $send_email_subscribe;
        auth()->shouldUse('admin');
    }

    public function index()
    {
        $this->authorize('master-policy.perform', ['email-subscribe', 'view']);
        $perpage = '100';
        $emailsubscribes = $this->emailsubscribe->orderBy('created_at', 'asc')->paginate($perpage);
        return view('admin.emailSubscribe.index')->withEmailsubscribes($emailsubscribes);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.emailSubscribe.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $data = $request->all();
        $mail = $this->send_email_subscribe->create($data);
        if ($mail) {
            $suscribers = $this->emailsubscribe->where('is_active', '=', 1)->pluck('email');
            foreach ($suscribers as $email) {
                Mail::to($email)
                    ->send(new EmailSendToSubscriberForwarded($mail));
            }
            return redirect()->route('admin.email-subscribe.index')
                ->with('flash_notice', 'Email Send  successfully.');
        }
        return redirect()->back()->withInput()
            ->with('flash_notice', 'Email Cannot be Send');
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
        //
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
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, $id)
    {
        $this->authorize('master-policy.perform', ['email-subscribe', 'delete']);
        $email = $this->emailsubscribe->find($request->get('id'));
        if ($this->emailsubscribe->destroy($email->id)) {
            $message = 'Email deleted successfully.';
            return response()->json(['status' => 'ok', 'message' => $message], 200);
        }

        return response()->json(['status' => 'error', 'message' => ''], 422);
    }

    public function changeStatus(Request $request)
    {
        $this->authorize('master-policy.perform', ['email-subscribe', 'changeStatus']);
        $email = $this->emailsubscribe->find($request->get('id'));
        if ($email->is_active == 0) {
            $status = 1;
            $message = 'EmailSubscribe with title "' . $email->email . '" is published.';
        } else {
            $status = 0;
            $message = 'EmailSubscribe with title "' . $email->email . '" is unpublished.';
        }

        $this->emailsubscribe->changeStatus($email->id, $status);
        $this->emailsubscribe->update($email->id, ['is_active' => $status]);
        $updated = $this->emailsubscribe->find($request->get('id'));
        return response()->json(['status' => 'ok', 'message' => $message, 'response' => $updated], 200);
    }
}