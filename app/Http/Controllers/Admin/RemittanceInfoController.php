<?php

namespace App\Http\Controllers\Admin;

use App\Helper\ConstantHelper;
use App\Helper\MediaHelper;
use App\Http\Controllers\Controller;
use App\Repositories\PostCategoryRepository;
use App\Repositories\PostRepository;
use Illuminate\Http\Request;

class RemittanceInfoController extends Controller
{

    protected $post, $postCategory, $preferredLanguage;
    public $title = 'Remittance Info';

    public function __construct(PostRepository $post, PostCategoryRepository $postCategory)
    {
        auth()->shouldUse('admin');
        $this->post = $post;
        $this->postCategory = $postCategory;
        $this->preferredLanguage = session('site_settings')['preferred_language'];
    }


    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
         $this->authorize('master-policy.perform', ['remittance-info', 'edit']);

        $post = $this->post->where('type', ConstantHelper::POST_TYPE_REMITTANCE_INFO)->where('language_id', $this->preferredLanguage)->first();
        $title = 'Edit - Remittance Info';
        $lang_content = $this->post->where('existing_record_id', $post->id)->where('language_id', '!=', $this->preferredLanguage)->get();
        $lang_content = $lang_content->groupBy('language_id');

        return view('admin.remittanceInfo.create', compact('title', 'post'))
            ->withLangContent($lang_content)
            ->withPreferredLanguage($this->preferredLanguage);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

        $this->authorize('master-policy.perform', ['remittance-alliances', 'edit']);
        $post = $this->post->where('type', ConstantHelper::POST_TYPE_REMITTANCE_INFO)->where('language_id', $this->preferredLanguage)->first();
        $data = $request->except(['banner', 'image', '_token', '_method']);
        if ($request->hasFile('banner')) {
            MediaHelper::destroy($post->image);
            $filelocation = MediaHelper::upload($request->file('banner'), 'remittance-info', true, true);
            $data['banner'] = $filelocation['storage'];
        }
        if ($request->hasFile('image')) {
            MediaHelper::destroy($post->image);
            $filelocation = MediaHelper::upload($request->file('image'), 'remittance-info');
            $data['image'] = $filelocation['storage'];
        }
        $data['is_active'] = isset($request['is_active']) ? 1 : 0;
        $preferred_language = $this->preferredLanguage;

        $existing_record_id = $this->post->find($data['post'][$preferred_language]) ?? 0;

        if ($existing_record_id) {
            foreach ($data['title'] as $language_id => $value) {

                if ($data['title'][$language_id] != NULL) {
                    $lang_items = $data;
                    $lang_items['image'] = isset($data['image']) && !empty($data['image']) ? $data['image'] : $existing_record_id->image;
                    $lang_items['banner'] = isset($data['banner']) && !empty($data['banner']) ? $data['banner'] : $existing_record_id->banner;
                    $lang_items['language_id'] = $language_id;
                    $lang_items['existing_record_id'] = ($language_id != $preferred_language) ? $existing_record_id->id : null;
                    $lang_items['title'] = $data['title'][$language_id];
                    $lang_items['description'] = $data['description'][$language_id];

                    $this->post->model()->updateOrCreate(['id' => $data['post'][$language_id]], $lang_items);
                }
            }
            return redirect()->route('admin.remittance-info.create')
                ->with('flash_notice', 'Information updated successfully');
        } else {
            return redirect()->back()->withInput()->with('flash_notice', 'Information can not be updated.');
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
