<?php

namespace App\Http\Controllers;

use App\Repositories\EmailSubscriptionRepository;
use Illuminate\Http\Request;
use App\Mail\SubscriptionMailForwarded;
use Illuminate\Support\Facades\Validator;
use Mail;

class EmailSubscriptionController extends Controller
{

    protected $email;

    public function __construct(EmailSubscriptionRepository $email)
    {
        $this->email = $email;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validation = Validator::make($request->all(), [
            'email' => 'required|email'
        ]);
        if ($validation->fails()) {
            // dd($validation->errors()->all());
            return $request->wantsJson() ?
                response()->json([
                    'message' => $validation->errors()->all()[0],
                    'status' => false
                ], 500)
                : redirect()->back()->with('flash_error', 'You have already subscribed.');
        }

        $data['email'] = $request->email;
        $data['full_name'] = $request->full_name;


        if ($this->email->where('email', $request->email)->count() > 0)
            return $request->wantsJson() ?
                response()->json([
                    'message' => 'You have already subscribed',
                    'status' => false
                ], 500)
                : redirect()->back()->with('flash_error', 'You have already subscribed.');

        if ($this->email->create($data)) {

            Mail::to($request->email)
                ->send(new SubscriptionMailForwarded($request->email));
            return $request->wantsJson() ?
                response()->json([
                    'message' => 'Thank you for subscription',
                    'status' => true
                ], 200)
                : redirect()->back()->with('flash_success', 'Subscription Successful !');
        } else
            return $request->wantsJson() ?
                response()->json([
                    'message' => 'Subscription failed, try again',
                    'status' => false
                ], 500)
                : redirect()->back()->withInput();
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
    public function destroy($id)
    {
        //
    }
}
