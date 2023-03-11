<?php

namespace App\Http\Controllers;

use App\Helper\ConstantHelper;
use App\Helper\Helper;
use App\Helper\PageHelper;
use App\Repositories\PostRepository;
use Illuminate\Http\Request;

class OfferController extends Controller
{
    protected $preferredLanguage, $post;

    public  function __construct(PostRepository $post)
    {
        $this->post = $post;
        $this->preferredLanguage = session()->get('locale_id');
    }

    protected function locale()
    {
        return Helper::locale();
    }

    public function index($page = null)
    {
        $offers = $this->post->where('is_active', 1)
            ->where('type', ConstantHelper::POST_TYPE_OFFER)
            ->where('language_id', $this->locale())->paginate(12);
        return view('offer.index', compact('offers'));
    }

    public function show($page, $slug = null)
    {
        $slug = empty($slug) ? $page : $slug;
        $offer = $this->post->where('is_active', 1)
            ->where('type', ConstantHelper::POST_TYPE_OFFER)
            ->where('language_id', $this->locale())
            ->where('slug', $slug)->first();
        if (!$offer) {
            abort('404');
        }
        return view('offer.show', compact('offer'));
    }
}
