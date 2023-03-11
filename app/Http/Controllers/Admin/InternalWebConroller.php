<?php

namespace App\Http\Controllers\Admin;

use App\Helper\MediaHelper;
use App\Helper\PageHelper;
use App\Helper\SettingHelper;
use App\Http\Requests\Admin\InternalWebRequest;
use App\Repositories\InternalWebRepository;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Repositories\InternalWebCategoryRepository;
use Illuminate\Support\Facades\Auth;

class InternalWebConroller extends Controller
{
    public $title = 'Download';

    protected $internalWeb, $preferredLanguage, $internalWebCategory;

    public function __construct(InternalWebRepository $internalWeb, InternalWebCategoryRepository $internalWebCategory)
    {
        $this->internalWeb = $internalWeb;
        $this->internalWebCategory = $internalWebCategory;
        $this->preferredLanguage = SettingHelper::setting('preferred_language') == null ? 1 : SettingHelper::setting('preferred_language');

        auth()->shouldUse('admin');
    }

    protected function directivesCategory()
    {
        $category = $this->internalWebCategory->whereIn('slug', ['directives-one', 'directives-two', 'directives-three'])->pluck('id')->toArray();
        return $category;
    }

    protected function multiCategory($category_id, $language_id)
    {
        if ($category = $this->internalWebCategory->where('existing_record_id', $category_id)->where('language_id', $language_id)->first()) {
            return $category->id;
        }
        return $category_id;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $this->authorize('master-policy.performArray', [['download', 'download-specific-category'], 'view']);
        $title = 'Download';
        $directiveCat = PageHelper::checkDirectiveCat();
        if ($directiveCat == true) {
            $category = $this->directivesCategory();
            $downloads = $this->internalWeb->with('category')->where('language_id', $this->preferredLanguage)
                ->whereIn('category_id', $category)
                ->orderBy('display_order', 'asc')
                ->orderBy('created_at', 'desc')
                ->get();
        } else {
            $downloads = $this->internalWeb->with('category')->where('language_id', $this->preferredLanguage)
                ->orderBy('display_order', 'asc')
                ->orderBy('created_at', 'desc')
                ->get();
        }

        return view('admin.internalWeb.index')->withTitle($title)->withDownloads($downloads);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $this->authorize('master-policy.performArray', [['download', 'download-specific-category'], 'add']);
        $title = 'Add Internal Web';
        $directiveCat = PageHelper::checkDirectiveCat();
        if ($directiveCat == true) {
            $parents = $this->internalWeb->with('allChild')->whereNull('parent_id')->whereLanguageId($this->preferredLanguage)->get();
            $categories = $this->internalWebCategory->with('allChild')
                ->whereIn('slug', ['directives-one', 'directives-two', 'directives-three'])
                ->whereNull('parent_id')
                ->whereLanguageId($this->preferredLanguage)
                ->get();
        } else {
            $parents = $this->internalWeb->with('allChild')->whereNull('parent_id')->whereLanguageId($this->preferredLanguage)->get();
            $categories = $this->internalWebCategory->with('allChild')
                ->whereNull('parent_id')
                ->whereLanguageId($this->preferredLanguage)
                ->get();
        }


        return view('admin.internalWeb.create')
            ->withTitle($title)
            ->withParents($parents)
            ->withCategories($categories);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(InternalWebRequest $request)
    {
        $this->authorize('master-policy.performArray', [['download', 'download-specific-category'], 'add']);
        $data = $request->except(['file']);
        if ($request->hasFile('file')) {
            // $filelocation = MediaHelper::upload($request->file('file'), 'download');
            // $data['file'] = $filelocation['storage'];
            $fileName = rand(0, 9999) . '-' . $request->file('file')->getClientOriginalName();
            $filelocation = MediaHelper::uploadDocument($request->file('file'), 'internal-web',  $fileName);
            $data['file'] = $filelocation;
        }
        $data['is_active'] = isset($data['is_active']) ? 1 : 0;

        $preferred_language = $this->preferredLanguage;
        /**
         * Insert Preferred Language Item First.
         */
        $preferred_language_item = $data;
        $preferred_language_item['language_id'] = $preferred_language;
        $preferred_language_item['title'] = $data['title'][$preferred_language];
        //        $preferred_language_item['description'] = $data['description'][$preferred_language];'
        // dd($preferred_language_item);
        $preferred_insert = $this->internalWeb->create($preferred_language_item);
        if ($preferred_insert) {
            $lang_items = [];
            $count = 0;
            unset($data['_token']);
            foreach ($data['title'] as $language_id => $value) {
                if ($language_id != $preferred_language) {
                    if ($data['title'][$language_id] != NULL) {
                        $lang_items[$count] = $data;
                        $lang_items[$count]['existing_record_id'] = $preferred_insert->id;
                        $lang_items[$count]['language_id'] = $language_id;
                        $lang_items[$count]['title'] = $data['title'][$language_id];
                        $lang_items[$count]['slug'] = $preferred_insert->slug;
                        //                        $lang_items[$count]['description'] = $data['description'][$language_id];
                        $lang_items[$count]['created_by'] = $preferred_insert->created_by;
                        $lang_items[$count]['created_at'] = $preferred_insert->created_at;
                        $lang_items[$count]['published_date'] = $preferred_insert->published_date;
                        $lang_items[$count]['category_id'] = $this->multiCategory($data['category_id'], $language_id);
                        $lang_items[$count]['display_order'] = $preferred_insert->display_order == null ? 1 :  $preferred_insert->display_order;
                        $count++;
                    }
                }
            }
            if (!empty($lang_items)) {
                $this->internalWeb->model()->insert($lang_items);
            }

            return redirect()->route('admin.internal-web.index')
                ->with('flash_notice', 'Internal Web Created Successfully.');
        }

        return redirect()->back()->withInput()
            ->with('flash_notice', 'Internal Web can not be Create');
    }

    /**
     * Display the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $this->authorize('master-policy.performArray', [['download', 'download-specific-category'], 'edit']);
        $title = 'Edit Internal Web';
        $preferredLanguage = $this->preferredLanguage;
        $download = $this->internalWeb->find($id);
        $lang_content = $this->internalWeb->where('existing_record_id', $download->id)->where('language_id', '!=', $this->preferredLanguage)->get();
        $langContent = $lang_content->groupBy('language_id');
        $patenr = $this->internalWeb->where('id', '!=', $id)->with('allchild')->whereNull('parent_id')->whereLanguageId($this->preferredLanguage)->get();
        $parents = $patenr->where('parent_id', '!=', $id)->all();

        $directiveCat = PageHelper::checkDirectiveCat();
        if ($directiveCat == true) {
            $categories = $this->internalWebCategory->with('allChild')
                ->whereIn('slug', ['directives-one', 'directives-two', 'directives-three'])
                ->whereNull('parent_id')
                ->whereLanguageId($this->preferredLanguage)
                ->get();
        } else {
            $categories = $this->internalWebCategory->with('allChild')
                ->whereNull('parent_id')
                ->whereLanguageId($this->preferredLanguage)
                ->get();
        }

        return view('admin.internalWeb.edit', compact('title', 'download', 'langContent', 'parents', 'categories', 'preferredLanguage'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function update(InternalWebRequest $request, $id)
    {
        $this->authorize('master-policy.performArray', [['download', 'download-specific-category'], 'edit']);
        $download = $this->internalWeb->find($id);
        $data = $request->except(['file', '_token', '_method']);

        if ($request->hasFile('file')) {
            MediaHelper::destroy($download->file);
            // $filelocation = MediaHelper::upload($request->file('file'), 'download');
            // $data['file'] = $filelocation['storage'];
            $fileName = rand(0, 9999) . '-' . $request->file('file')->getClientOriginalName();
            $filelocation = MediaHelper::uploadDocument($request->file('file'), 'internal-web',  $fileName);
            $data['file'] = $filelocation;
        }

        $data['is_active'] = isset($data['is_active']) ? 1 : 0;
        $preferred_language = $this->preferredLanguage;
        $existing_record_id = $this->internalWeb->find($data['post'][$preferred_language]) ?? 0;
        if ($existing_record_id) {
            foreach ($data['title'] as $language_id => $value) {
                if ($data['title'][$language_id] != NULL) {
                    $lang_items = $data;
                    $lang_items['language_id'] = $language_id;
                    $lang_items['existing_record_id'] = ($language_id != $preferred_language) ? $existing_record_id->id : null;
                    $lang_items['file'] = isset($data['file']) && !empty($data['file']) ? $data['file'] : $existing_record_id->file;
                    $lang_items['title'] = $data['title'][$language_id];
                    $lang_items['slug'] = $existing_record_id->slug;
                    $lang_items['description'] = $data['description'][$language_id];
                    $lang_items['id'] = $data['post'][$language_id];
                    unset($lang_items['post']);
                    unset($lang_items['q']);
                    // dd($lang_items);
                    $lang_items['category_id'] = $this->multiCategory($data['category_id'], $language_id);
                    $lang_items['display_order'] = $existing_record_id->display_order == null ? 1 : $existing_record_id->display_order;
                    $this->internalWeb->model()->updateOrInsert(['id' => $data['post'][$language_id]], $lang_items);
                }
            }

            return redirect()->route('admin.internal-web.index')
                ->with('flash_notice', 'Internal web updated successfully');
        }

        return redirect()->back()->withInput()
            ->with('flash_notice', 'Internal web can not be Updated.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, $id)
    {
        $download = $this->internalWeb->find($request->get('id'));
        $this->internalWeb->where('existing_record_id', $id)->delete();
        $this->internalWeb->where('parent_id', $id)->delete();
        $this->internalWeb->update($download->id, array('deleted_by' => Auth::user()->id));
        if ($this->internalWeb->destroy($download->id)) {
            $message = 'Internal web item deleted successfully.';

            return response()->json(['status' => 'ok', 'message' => $message], 200);
        }

        return response()->json(['status' => 'error', 'message' => ''], 422);
    }

    public function changeStatus(Request $request)
    {
        $download = $this->internalWeb->find($request->get('id'));
        $status = $download->is_active == 0 ? 1 : 0;
        $message = $download->is_active == 0 ? 'Internal Web with title "' . $download->title . '" is published.' : 'Internal Web with title "' . $download->title . '" is unpublished.';
        $this->internalWeb->changeStatus($download->id, $status);
        $this->internalWeb->update($download->id, array('updated_by' => Auth::user()->id));
        $updated = $this->internalWeb->find($request->get('id'));

        return response()->json(['status' => 'ok', 'message' => $message, 'response' => $updated], 200);
    }

    public function sort(Request $request)
    {
        $preferred_language = $this->preferredLanguage;
        $exploded = explode('&', str_replace('item[]=', '', $request->order));
        for ($i = 0; $i < count($exploded); $i++) {
            $this->internalWeb->update($exploded[$i], ['display_order' => $i]);
        }
        $other_posts = $this->internalWeb->whereIn('existing_record_id', $exploded)->get();
        $other_posts_grouped_language = $other_posts->groupBy('language_id');
        $english_sort = $this->internalWeb->where('language_id', $preferred_language)->pluck('display_order', 'id')->toArray();
        if ($other_posts_grouped_language) {
            foreach ($other_posts_grouped_language as $language => $records) {
                foreach ($records as $record) {
                    $this->internalWeb->update($record->id, ['display_order' => $english_sort[$record->existing_record_id]]);
                }
            }
        }

        return json_encode(['status' => 'success', 'value' => 'Successfully reordered.'], 200);
    }
}
