<?php

namespace App\Http\Controllers;

use App\Helper\Helper;
use App\Repositories\AgmReportCategoryRepository;
use App\Repositories\AgmReportRepository;
use App\Repositories\CompanyRepository;
use App\Repositories\FinancialReportCategoryRepository;
use App\Repositories\FinancialReportRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ReportController extends Controller
{
    protected $report;
    protected $category;
    protected $agmReport;
    protected $agmCategory;
    protected $company;


    public function __construct(FinancialReportRepository $report, FinancialReportCategoryRepository $category, AgmReportRepository $agmReport, AgmReportCategoryRepository $agmCategory, CompanyRepository $company)
    {
        $this->report = $report;
        $this->category = $category;
        $this->agmReport = $agmReport;
        $this->agmCategory = $agmCategory;
        $this->company = $company;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $categories = $this->category->whereNull('parent_id')->where('is_active', 1)
            ->where('language_id', Helper::locale())
            ->orderBy('display_order', 'asc')
            ->get();
        $category = ($request->slug != null) ? $this->category->where('slug', $request->slug)->where('language_id', Helper::locale())->first() : '';
        // $reports = $this->report->where('is_active', 1)->where('language_id', Helper::locale());
        // if (!empty($category)) {
        //     $reports = $reports->where('category_id', $category->id);
        // }

        // if ($request->slug == 'quarterly-report') {
        //     $reports = $reports->select('*', DB::raw("SUBSTR(title,1,7) as main_title"));
        // }

        // $reports = $reports->orderBy('display_order', 'asc')->orderBy('published_date', 'desc')->paginate(500);
        $company = $this->company->get();
        return view('report.index')->withCategories($categories)->withCategory($category)->withCompany($company);
    }

    public function agm()
    {
        $categories = $this->agmCategory->whereNull('parent_id')->where('is_active', 1)
            ->where('language_id', Helper::locale())
            ->get();

        $reports = $this->agmReport->where('is_active', 1)->orderBy('display_order', 'asc')->orderBy('id', 'desc')->get();

        return view('report.agm-index')->withCategories($categories)->withReports($reports);
    }
}