<?php

namespace App\Http\Controllers;

use App\Helper\ConstantHelper;
use App\Repositories\ContentRepository;
use App\Repositories\PostRepository;

class ServiceController extends Controller
{
    protected $post, $content;

    public function __construct(PostRepository $post, ContentRepository $content)
    {
        $this->post = $post;
        $this->content = $content;
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $services = $this->post->where('type', ConstantHelper::POST_TYPE_SERVICE)->where('is_active', 1)->get();

        return view('service.index', ['services' => $services]);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($slug)
    {
        if (!$service = $this->post->where('type', ConstantHelper::POST_TYPE_SERVICE)->where('is_active', 1)->where('slug', $slug)->first()) {
            if (!$content = $this->content->where('slug', $slug)->where('is_active', 1)->first()) {
                abort('404');
            } else {
                return redirect()->route('content.show', $content->slug);
            }
        }

        return view('service.show', ['service' => $service]);
    }
}
