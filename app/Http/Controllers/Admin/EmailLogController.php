<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Repositories\EmailLogRepository;
use App\Repositories\UserRepository;

class EmailLogController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @param  EmailLogRepository  $logs
     * @param  AdminRepository  $users
     * @return void
     */
    public function __construct(
        EmailLogRepository $logs,
        UserRepository $users
    ) {
        $this->logs = $logs;
        $this->users = $users;
    }

    /**
     * Display a listing of the log.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        return view('admin.logs.email')
            ->withLogs($this->logs->searchAndPaginate($request->all(), 100))
            ->withUsers($this->users->orderby('full_name', 'asc')->pluck('full_name', 'id'))
            ->withRequestData($request->all());
    }

    /**
     * Display the specified log.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $emailLog = $this->logs->find($id);
        if ($emailLog) {
            return response()->json([
                'type' => 'ok',
                'emailLog' => $emailLog,
            ], 200);
        }
        return response()->json(['message' => 'Email log not found'], 404);
    }
}
