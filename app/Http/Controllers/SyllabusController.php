<?php

namespace App\Http\Controllers;

use App\Helper\ConstantHelper;
use App\Helper\Helper;
use App\Repositories\DownloadCategoryRepository;
use App\Repositories\DownloadRepository;
use App\Repositories\NewsRepository;
use App\Repositories\SyllabusRepository;
use Illuminate\Http\Request;

class SyllabusController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    protected $syllabus;

    public function __construct(SyllabusRepository $syllabus)
    {
        $this->syllabus = $syllabus;
    }

    public function index()
    {
        $syllabus = $this->syllabus->where('is_active', 1)
            ->where('language_id', Helper::locale())
            ->orderBy('display_order', 'desc')
            ->orderBy('created_at', 'desc')
            ->paginate(10);
        return view('syllabus.index', ['syllabus' => $syllabus]);
    }
}
