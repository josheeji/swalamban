<?php

namespace App\Http\Controllers;

use App\Helper\Helper;
use App\Http\Requests\GrievanceStoreRequest;
use App\Mail\MailGrievance;
use App\Models\Grievance;
use App\Repositories\BranchDirectoryRepository;
use App\Repositories\DepartmentRepository;
use App\Repositories\SettingRepository;
use Illuminate\Http\Request;
use Mail;

class GrievanceController extends Controller
{
    protected $branchDirectory, $department, $grievance, $site_setting;

    public function __construct(BranchDirectoryRepository $branchDirectory, DepartmentRepository $department, Grievance $grievance, SettingRepository $site_setting)
    {
        $this->branchDirectory = $branchDirectory;
        $this->department = $department;
        $this->grievance = $grievance;
        $this->site_setting = $site_setting;
    }

    protected function locale()
    {
        return Helper::locale();
    }
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $branch = $this->branchDirectory->where('is_active', 1)
            ->where('language_id', $this->locale())
            ->orderBy('title', 'asc')->get();
        $department = $this->department->where('is_active', 1)
            ->where('language_id', $this->locale())
            ->orderBy('display_order', 'asc')->get();
        return view('grievance.create', compact('branch', 'department'));
    }

    public function departments(Request $request)
    {
        $id = $request->post('branch');
        $departments = $this->department->where('branch_id', $id)->orderBy('display_order', 'asc')->get();

        $option = '<option value="">Select department</option>';
        if ($departments) {
            foreach ($departments as $department) {
                $option .= "<option value='{$department->id}'>{$department->title}</option>";
            }
        }
        return response()->json($option);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(GrievanceStoreRequest $request)
    {
        $data = $request->except(['captcha']);
        if ($grievance = $this->grievance->create($data)) {
            try {


                $admin_email = $this->site_setting->where('key', 'admin_email')->value('value')->first();
                Mail::to($grievance->email)->send(new MailGrievance($grievance, 0));
                if ($$admin_email) {
                    Mail::to($admin_email)->send(new MailGrievance($grievance, 1));
                }
                return redirect()->back()->with('flash_success', trans('contact.success'));
            } catch (\Exception $e) {
                return redirect()->back()->with('flash_success', trans('contact.success'));
            }
        }

        return redirect()->back()->withInput();
    }
}