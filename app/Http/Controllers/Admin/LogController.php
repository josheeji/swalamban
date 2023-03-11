<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Repositories\LogRepository;
use App\Repositories\UserRepository;

class LogController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @param  LogRepository  $logs
     * @param  UserRepository  $users
     * @return void
     */
    public function __construct(
        LogRepository $logs,
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
        $requestData = $request->all();
        $query = $this->logs->with(['user'])
            ->selectRaw('*, DATE_FORMAT(created_at, "%D %b, %Y  %h:%i %p") as created_on');
        if (!empty($requestData['from_date']))
            $query->whereDate("created_at", '>=', $requestData['from_date']);
        if (!empty($requestData['to_date']))
            $query->whereDate("created_at", '<=', $requestData['to_date']);
        if (!empty($requestData['user_id']))
            $query->where("user_id", $requestData['user_id']);
        if (!empty($requestData['description']))
            $query->where("description", 'like', '%' . $requestData['description'] . '%');
        if (!empty($requestData['model_id']))
            $query->where("model_id", $requestData['model_id']);
        if (!empty($requestData['model']))
            $query->where("model", 'like', '%' . $requestData['model'] . '%');
        $logs = $query->orderBy('created_at', 'desc')->paginate(100);

        return view('admin.logs.audit', compact('logs'))->withRequestData($requestData);
    }

    /**
     * Display the specified log.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $log = $this->logs->find($id);
        if ($log) {
            return response()->json(['type' => 'ok', 'log' => $log, 'user' => $log->user], 200);
        }
        return response()->json(['message' => 'Log not found'], 404);
    }
}
