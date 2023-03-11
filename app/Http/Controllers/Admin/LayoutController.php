<?php

namespace App\Http\Controllers\Admin;

use App\Helper\Helper;
use App\Helper\MediaHelper;
use App\Http\Controllers\Controller;
use App\Repositories\ContentRepository;
use App\Repositories\LayoutOptionRepository;
use App\Repositories\LayoutRepository;
use App\Repositories\MenuRepository;
use Illuminate\Http\Request;

class LayoutController extends Controller
{

    protected $layout, $layoutOption, $menu;
    public $title = 'Layouts';

    public function __construct(LayoutRepository $layout, LayoutOptionRepository $layoutOption, MenuRepository $menu, ContentRepository $content)
    {
        auth()->shouldUse('admin');
        $this->layout = $layout;
        $this->layoutOption = $layoutOption;
        $this->menu = $menu;
        $this->content = $content;
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $this->authorize('master-policy.perform', ['layout', 'view']);
        $layouts = $this->layout->where('is_active', 1)->get();

        return view('admin.layout.index', compact('layouts'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
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
        $this->authorize('master-policy.perform', ['layout', 'edit']);

        $layout = $this->layout->find($id);
        $options = $this->layoutOption->where('layout_id', $id)->where('language_id', Helper::locale())->get();
        $menus = $this->menu->where('is_active', 1)->where('language_id', Helper::locale())->get();
        $optionMultiContent = $this->layoutOption->where('language_id', '!=', Helper::locale())->where('type', 2)->get()->toArray();
        $multiContent = [];
        if (!empty($optionMultiContent)) {
            foreach ($optionMultiContent as $key => $content) {
                $multiContent[$content['language_id']][$content['existing_record_id']] = $content;
            }
        }
        $contents = $this->content->with('allChild')->whereNull('parent_id')->whereLanguageId(Helper::locale())->get();
        return view('admin.layout.edit', compact('layout', 'options', 'menus', 'multiContent', 'contents'));
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
        $this->authorize('master-policy.perform', ['layout', 'edit']);

        $data = $request->except(['_token', '_method', 'block', 'block3']);
        $blocks = $request->get('block');
        $block3 = $request->get('block3');
        if (is_array($data) || is_array($blocks)) {
            foreach ($data as $key => $value) {
                $this->layoutOption->update($key, ['menu_id' => $value]);
            }
            
            foreach ($block3['content_id'] as $key => $value) {
                $this->layoutOption->update($key, ['content_id' => $value]);
            }

            // foreach ($blocks[1] as $key => $block) {
            //     if ($request->hasFile("block.{$key}.0.image")) {
            //         $filelocation = MediaHelper::upload($request->file("block.{$key}.0.image"), 'block', false);
            //         $block[0]['image'] = $filelocation['storage'];
            //     }
            //     $block['link_target'] = $request->has("block.{$key}.0.link_target") ? 1 : 0;
            //     $block['external_link'] = $request->has("block.{$key}.0.external_link") ? 1 : 0;
            //     $this->layoutOption->update($key, $block[0]);
            // }
            $contentID = isset($blocks['content_id']) ? $blocks['content_id'] : [];
            unset($blocks['content_id']);

            foreach ($blocks as $language => $content) {
                foreach ($content as $key => $block) {
                    foreach ($block as $index => $data) {
                        if ($request->hasFile("block.{$key}.{$index}.image")) {
                            $filelocation = MediaHelper::upload($request->file("block.{$key}.{$index}.image"), 'block', false);
                            $data['image'] = $filelocation['storage'];
                        }
                        $data['existing_record_id'] = $language != Helper::locale() ? $key : null;
                        $data['language_id'] = $language;
                        $data['link_target'] = $request->has("block.{$key}.{$index}.link_target") ? 1 : 0;
                        $data['external_link'] = $request->has("block.{$key}.{$index}.external_link") ? 1 : 0;
                        $id = Helper::locale() == $language ? $key : $index;
                        if (isset($contentID[$key])) {
                            $data['content_id'] = $contentID[$key];
                        }
                        $this->layoutOption->model()->updateOrCreate(['id' => $id], $data);
                    }
                }
            }

            return redirect()->route('admin.layout.index')
                ->with('flash_notice', 'Layout options updated successfully');
        }

        return redirect()->back()->withInput()->with('flash_notice', 'Layout options can not be updated.');
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
