<?php

namespace App\Http\Controllers;

use App\Helper\ConstantHelper;
use App\Helper\Helper;
use App\Http\Requests\ProductEnquiryRequest;
use App\Models\DownloadCategory;
use App\Models\MenuItems;
use App\Models\Notice;
use App\Repositories\AccountTypeCategoryRepository;
use App\Repositories\AccountTypeRepository;
use App\Repositories\ContentRepository;
use App\Repositories\ProductEnquiryRepository;
use Artesaos\SEOTools\Facades\OpenGraph;
use Artesaos\SEOTools\Facades\SEOMeta;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Spatie\SchemaOrg\Schema;

class ProductController extends Controller
{
    protected $preferredLanguage, $accountType, $content, $accountTypeCategory;

    public function __construct(AccountTypeRepository $accountType, AccountTypeCategoryRepository $accountTypeCategory, ContentRepository $content, ProductEnquiryRepository $productEnquiry)
    {
        $this->accountType = $accountType;
        $this->accountTypeCategory = $accountTypeCategory;
        $this->content = $content;
        $this->productEnquiry = $productEnquiry;
        $this->preferredLanguage = session()->get('locale_id');
    }

    protected function locale()
    {
        return Helper::locale();
    }

    public function index($page = null)
    {
        $products = $this->accountType->where('is_active', 1)->where('language_id', $this->locale())->orderBy('display_order', 'asc')->paginate(20);
        $menu = MenuItems::where('link_url', request()->path())->first();

        SEOMeta::setDescription($menu->title ??  'Swabalamban Laghubitta Bittiya Sanstha Ltd.');

        OpenGraph::setDescription($menu->title ??   'Swabalamban Laghubitta Bittiya Sanstha Ltd.');
        OpenGraph::setTitle($menu->title ?? 'Products List');
        OpenGraph::addImages([isset($menu->image) ? asset('storage/' . @$menu->image) : asset('swabalamban/images/titlebg.jpg')]);
        OpenGraph::setUrl(route('news.index'));

        return view('product.index', compact('products'));
    }
    public function show($category, $slug = null)
    {
        // dd($category);
        $slug = empty($slug) ? $category : $slug;

        $product = $this->accountType->where('slug', $slug)->where('language_id', $this->locale())
            ->where('is_active', 1)->first();
        if (!$product) {
            abort('404');
        }
        if (!empty($product->category_id)) {
            $products = $this->accountType->where('category_id', $product->category_id)->where('language_id', $this->locale())->where('is_active', 1)->get();
        } else {
            $products = [];
        }
        SEOMeta::setDescription(strip_tags(str_limit($product->description,200)));

        OpenGraph::setDescription(strip_tags(str_limit($product->description,200)));
        OpenGraph::setTitle($product->title);
        OpenGraph::addImages([isset($product->image) ? asset('storage/' . @$product->image) : asset('swabalamban/images/logo.svg')]);
        OpenGraph::setUrl(url()->current());

        $other = $this->accountType->where('language_id', $this->locale())
            ->whereNot('id', $product->id)
            ->where('is_active', 1)->get();
        // $download_categories = DownloadCategory::with('downloads')->whereNull('parent_id')->where('is_active', 1)
        //     ->where('language_id', Helper::locale())
        //     ->orderBy('display_order', 'asc')
        //     ->get();
        $notices = Notice::where('type', ConstantHelper::NOTICE_TYPE_PRESS_RELEASE)
            ->where('language_id', Helper::locale())
            ->where('start_date', '<=', Carbon::now())
            ->orderBy('display_order')->orderBy('start_date', 'desc')->where('is_active', 1)->limit(3)->get();
        return view('product.show', compact('product', 'products', 'notices', 'other'));
    }
    public function featured()
    {
        $page = $this->content->where('slug', 'feature-product')->where('language_id', Helper::locale())->first();
        $products = $this->accountType->where('is_active', 1)
            ->where('language_id', $this->locale())
            ->where('is_featured', ConstantHelper::IS_FEATURED)
            ->orderBy('display_order', 'asc')
            ->get();

        return view('product.featured', compact('products', 'page'));
    }

    public function compare(Request $request)
    {
        $products = $this->accountType->where('is_active', 1)->where('language_id', $this->locale())->orderBy('display_order', 'asc')->get();
        $product1 = '';
        $product2 = '';
        $product3 = '';
        if ($request->has('p1') && $request->get('p1') != '') {
            $product1 = $this->accountType->where('is_active', 1)->where('language_id', $this->locale())->where('slug', $request->get('p1'))->first();
        }
        if ($request->has('p2') && $request->get('p2') != '') {
            $product2 = $this->accountType->where('is_active', 1)->where('language_id', $this->locale())->where('slug', $request->get('p2'))->first();
        }
        if ($request->has('p3') && $request->get('p3') != '') {
            $product3 = $this->accountType->where('is_active', 1)->where('language_id', $this->locale())->where('slug', $request->get('p3'))->first();
        }
        return view('product.compare', [
            'products' => $products,
            'product1' => $product1,
            'product2' => $product2,
            'product3' => $product3
        ]);
    }

    public function enquiry($slug, ProductEnquiryRequest $request)
    {
        $product = $this->accountType->where('is_active', 1)->where('slug', $slug)->firstOrFail();
        $data = $request->except(['_token']);
        $data['account_type_id'] = $product->id;
        if ($this->productEnquiry->create($data)) {
            return redirect()->back()->with('success', 1)->with('flash_success', 'Enquiry submitted successfully.');
        } else {
            redirect()->back()->withInput()->with('flash_error', 'Please! try again later. Enquiry cannot be submitted.');
        }
    }

    public function category(Request $request, $category)
    {
        // dd($category);
        $category = $this->accountTypeCategory->where('slug', $category)->where('language_id', Helper::locale())->firstOrFail();
        $products = $this->accountType->where('is_active', 1)
            ->where('category_id', $category->id)
            ->where('language_id', Helper::locale())
            ->orderBy('display_order', 'asc')->paginate('100');
        return view('product.category')->withProducts($products)->withCategory($category);
    }
}
