<?php

namespace App\Http\Controllers;

use App\Helper\Helper;
use App\Repositories\FaqCategoryRepository;
use Illuminate\Http\Request;
use App\Repositories\FaqRepository;

class FaqController extends Controller
{
    protected $faqCategory, $faq;

    public function __construct(FaqCategoryRepository $faqCategory, FaqRepository $faq)
    {
        $this->faqCategory = $faqCategory;
        $this->faq = $faq;
    }

    public function index()
    {
        $faqCategories = $this->faqCategory->with('activeFaq')->where('is_active', '1')->where('language_id', Helper::locale())->get();
        $category = $this->faqCategory->with('activeFaq')->where('is_active', '1')->where('language_id', Helper::locale())->orderBy('id', 'desc')->first();
        return view('faq.index', ['faqCategories' => $faqCategories, 'category' => $category]);
    }
    public function category($slug)
    {
        $faqCategories = $this->faqCategory->with('activeFaq')->where('is_active', '1')->where('language_id', Helper::locale())->get();
        $category = $this->faqCategory->with('activeFaq')->where('is_active', '1')->where('language_id', Helper::locale())->where('slug', $slug)->first();
        return view('faq.index', ['faqCategories' => $faqCategories, 'category' => $category]);
    }
}