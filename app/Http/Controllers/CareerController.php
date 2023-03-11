<?php

namespace App\Http\Controllers;

use App\Helper\ConstantHelper;
use App\Helper\Helper;
use App\Helper\MediaHelper;
use App\Http\Requests\Applicant\ApplicantStoreRequest;
use App\Mail\MailApplicant;
use App\Mail\MailCareerAdmin;
use App\Models\DownloadCategory;
use App\Models\MenuItems;
use App\Models\Notice;
use App\Repositories\ApplicantRepository;
use App\Repositories\CareerRepository;
use App\Rules\CaptchaRule;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class CareerController extends Controller
{

    protected $career, $applicant;

    public function __construct(CareerRepository $career, ApplicantRepository $applicant)
    {
        $this->career = $career;
        $this->applicant = $applicant;
    }

    protected function locale()
    {
        return Helper::locale();
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $careers = $this->career->where('language_id', $this->locale())
            ->where('publish_from', '<=', Carbon::now())
            ->where("publish_to", '>=', Carbon::now())
            ->where('is_active', 1)
            ->orderBy('display_order', 'asc')
            ->orderBy('publish_from', 'desc')
            ->paginate(10);

        $notices = Notice::where('type', ConstantHelper::NOTICE_TYPE_PRESS_RELEASE)
            ->where('language_id', Helper::locale())
            ->where('start_date', '<=', Carbon::now())
            ->orderBy('display_order')->orderBy('start_date', 'desc')->where('is_active', 1)->limit(3)->get();

        $categories = DownloadCategory::with('downloads')->whereNull('parent_id')->where('is_active', 1)
            ->where('language_id', Helper::locale())
            ->orderBy('display_order', 'asc')
            ->get();
        $menu = MenuItems::where('link_url', request()->path())
            ->orWhere('link_url', '/' . request()->path())->first();
        return view('career.index', compact('careers', 'menu','notices','categories'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create($slug)
    {
        $career = $this->career->where('slug', $slug)->where('language_id', $this->locale())->first();
        $careers = $this->career->where('language_id', $this->locale())
            ->where('publish_from', '<=', Carbon::now())
            ->where("publish_to", '>=', Carbon::now())
            ->where("id", "!=", $career->id)
            ->where('is_active', 1)
            ->get();
        return view('career.create', compact('career', 'careers'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'career_id' => 'required',
            'full_name' => 'required',
            'email' => 'required|email',
            'contact_no' => 'required',
            'p_address' => 'required',
            't_address' => 'nullable',
            'message' => 'required|max:250',
            'resume' => 'required|max:6048|mimes:jpeg,jpg,png,pdf,doc,docx',
            'cover_letter' => 'required|max:6048|mimes:jpeg,jpg,png,pdf,doc,docx',
            'captcha' => ['required', new CaptchaRule]
        ]);
        $data = $request->except(['_token', 'captcha']);
        $career = $this->career->where('id', $request->career_id)->where('language_id', session('site_settings')['preferred_language'])->first();
        $data['address'] = $request->p_address;
        if ($request->hasFile('resume')) {
            $resume = $request->file('resume');
            $filename = 'resume_' . time() . rand(10, 100) . '.' . $resume->getClientOriginalExtension();
            $path = MediaHelper::uploadDocument($resume, 'applicants', $filename);
            $data['resume'] = $path;
        }
        if ($request->hasFile('cover_letter')) {
            $coverLetter = $request->file('cover_letter');
            $filename = 'cover_letter_' . time() . rand(10, 100) . '.' . $coverLetter->getClientOriginalExtension();
            $path = MediaHelper::uploadDocument($coverLetter, 'applicants', $filename);
            $data['cover_letter'] = $path;
        }
        if ($applicant = $this->applicant->create($data)) {
            //            $hr_email = Helper::get_hr_email();
            //            Mail::to($applicant->email)->send(new MailApplicant($applicant));
            //            if ($hr_email)
            //                Mail::to($hr_email)->send(new MailCareerAdmin($applicant));
            return redirect()->back()->with('flash_success', trans('career.success'));
        }

        return redirect()->back()->withInput();
    }

    public function storeCareer(ApplicantStoreRequest $request)
    {
        $data = $request->except(['_token', 'captcha']);
        if ($request->hasFile('resume')) {
            $resume = $request->file('resume');
            $filename = 'resume_' . time() . rand(10, 100) . '.' . $resume->getClientOriginalExtension();
            // $resume->move(public_path('/applicants/'), $filename);
            $path = MediaHelper::uploadDocument($resume, 'applicants', $filename);
            $data['resume'] = $path;
        }
        if ($request->hasFile('cover_letter')) {
            $coverLetter = $request->file('cover_letter');
            $filename = 'cover_letter_' . time() . rand(10, 100) . '.' . $coverLetter->getClientOriginalExtension();
            // $coverLetter->move(public_path('/applicants/'), $filename);
            $path = MediaHelper::uploadDocument($coverLetter, 'applicants', $filename);
            $data['cover_letter'] = $path;
        }

        if ($applicant = $this->applicant->create($data)) {
            $hr_email = Helper::get_hr_email();
            Mail::to($applicant->email)->send(new MailApplicant($applicant));
            if ($hr_email)
                Mail::to($hr_email)->send(new MailCareerAdmin($applicant));
            return redirect()->back()->with('flash_success', trans('career.success'));
        }

        return redirect()->back()->withInput();
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($slug)
    {
        $career = $this->career->where('slug', $slug)->where('language_id', $this->locale())
            ->where('is_active', 1)->first();

        $careers = $this->career->where('language_id', $this->locale())
            ->where('publish_from', '<=', Carbon::now())
            ->where("publish_to", '>=', Carbon::now());
        if (isset($career) && !empty($career)) {
            $careers = $careers->where("id", "!=", $career->id);
        }
        $careers = $careers->where('is_active', 1)
            ->get();
            $notices = Notice::where('type', ConstantHelper::NOTICE_TYPE_PRESS_RELEASE)
            ->where('language_id', Helper::locale())
            ->where('start_date', '<=', Carbon::now())
            ->orderBy('display_order')->orderBy('start_date', 'desc')->where('is_active', 1)->limit(3)->get();

        $categories = DownloadCategory::with('downloads')->whereNull('parent_id')->where('is_active', 1)
            ->where('language_id', Helper::locale())
            ->orderBy('display_order', 'asc')
            ->get();
        return view('career.show', compact('career', 'careers','notices','categories'));
    }
}
