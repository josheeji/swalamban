<?php

namespace App\Http\Controllers\Admin;

use App\Helper\ConstantHelper;
use App\Helper\MediaHelper;
use App\Helper\SettingHelper;
use App\Http\Requests\Admin\NewsRequest;
use App\Repositories\NewsRepository;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Repositories\NewsCategoryRepository;
use Illuminate\Support\Facades\Auth;


class NewsController extends Controller
{

    public $title = 'News';

    protected $news, $preferredLanguage, $newsCategory;

    public function __construct(NewsRepository $news, NewsCategoryRepository $newsCategory)
    {
        $this->news = $news;
        $this->newsCategory = $newsCategory;
        $this->preferredLanguage = SettingHelper::setting('preferred_language') == null ? ConstantHelper::DEFAULT_LANGUAGE : SettingHelper::setting('preferred_language');
        auth()->shouldUse('admin');
    }


    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $this->authorize('master-policy.perform', ['news', 'view']);
        $title = $this->title;
        $news = $this->news->where('language_id', $this->preferredLanguage)->orderBy('display_order', 'asc')->orderBy('published_date', 'desc')->get();

        return view('admin.news.index')->withTitle($title)->withNews($news);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $this->authorize('master-policy.perform', ['news', 'add']);
        $title = 'Add News';
        $categories = $this->newsCategory->with('allChild')
            ->whereNull('parent_id')
            ->whereLanguageId($this->preferredLanguage)
            ->get();

        return view('admin.news.create', ['categories' => $categories])->withTitle($title);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(NewsRequest $request)
    {
        $this->authorize('master-policy.perform', ['news', 'add']);
        $data = $request->except(['image', '_token']);
        if ($request->hasFile('banner')) {
            $filelocation = MediaHelper::upload($request->file('banner'), 'news', true, true);
            $data['banner'] = $filelocation['storage'];
        }
        if ($request->hasFile('image')) {
            $filelocation = MediaHelper::upload($request->file('image'), 'news');
            $data['image'] = $filelocation['storage'];
        }
        if ($request->hasFile('document')) {
            $fileLocation = MediaHelper::upload($request->file('document'), 'news', false);
            $data['document'] = $fileLocation['storage'];
        }
        $data['is_active'] = isset($request->is_active) ? 1 : 0;
        $data['created_by'] = Auth::user()->id;
        $preferred_language = $this->preferredLanguage;
        $preferred_language_item = $data;
        $preferred_language_item['language_id'] = $preferred_language;
        $preferred_language_item['title'] = $data['title'][$preferred_language];
        $preferred_language_item['excerpt'] = $data['excerpt'][$preferred_language];
        $preferred_language_item['description'] = $data['description'][$preferred_language];
        $preferred_insert = $this->news->create($preferred_language_item);
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
                        $lang_items[$count]['excerpt'] = $data['excerpt'][$language_id];
                        $lang_items[$count]['type'] = $preferred_insert->type;
                        $lang_items[$count]['visible_in'] = $preferred_insert->visible_in;
                        $lang_items[$count]['banner'] = $preferred_insert->banner;
                        $lang_items[$count]['image'] = $preferred_insert->image;
                        $lang_items[$count]['document'] = $preferred_insert->image;
                        $lang_items[$count]['description'] = $data['description'][$language_id];
                        $lang_items[$count]['display_order'] = $preferred_insert->display_order == null ? 1 : $preferred_insert->display_order;
                        $lang_items[$count]['category_id'] = $this->news->model()->multiCategory($preferred_insert->category_id, $language_id);
                        $lang_items[$count]['created_by'] = $preferred_insert->created_by;
                        $lang_items[$count]['created_at'] = $preferred_insert->created_at;
                        $count++;
                    }
                }
            }
            if (!empty($lang_items)) {
                $this->news->model()->insert($lang_items);
            }

            return redirect()->route('admin.news.index')->with('flash_notice', 'News Created Successfully.');
        }

        return redirect()->back()->withInput()->with('flash_notice', 'News can not be Create.');
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
        $this->authorize('master-policy.perform', ['news', 'edit']);
        $title = 'Edit News';
        $news = $this->news->find($id);
        $categories = $this->newsCategory->with('allChild')
            ->whereNull('parent_id')
            ->whereLanguageId($this->preferredLanguage)
            ->get();
        $lang_content = $this->news->where('existing_record_id', $news->id)->where('language_id', '!=', $this->preferredLanguage)->get();
        $lang_content = $lang_content->groupBy('language_id');
        $patenr = $this->news->where('id', '!=', $id)->whereLanguageId($this->preferredLanguage)->get();
        $parents = $patenr->all();

        return view('admin.news.edit', ['categories' => $categories])->withTitle($title)
            ->withNews($news)
            ->withLangContent($lang_content)
            ->withParents($parents)
            ->withPreferredLanguage($this->preferredLanguage);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(NewsRequest $request, $id)
    {
        $this->authorize('master-policy.perform', ['news', 'edit']);
        $news = $this->news->find($id);
        $data = $request->except(['image']);
        if ($request->hasFile('banner')) {
            $filelocation = MediaHelper::upload($request->file('banner'), 'news', true, true);
            $data['banner'] = $filelocation['storage'];
        }
        if ($request->hasFile('image')) {
            if (file_exists('storage/' . $news->image) && !empty($news->image)) {
                MediaHelper::destroy($news->image);
            }
            $filelocation = MediaHelper::upload($request->file('image'), 'news');
            $data['image'] = $filelocation['storage'];
        }

        if ($request->hasFile('document')) {
            MediaHelper::destroy($news->document);
            $fileLocation = MediaHelper::upload($request->file('document'), 'news', false);
            $data['document'] = $fileLocation['storage'];
        }
        $data['show_in_notification'] = isset($request->show_in_notification) ? 1 : 0;
        $data['show_image'] = $request->has('show_image') ? 1 : 0;
        $data['is_active'] = isset($request->is_active) ? 1 : 0;
        $preferred_language = $this->preferredLanguage;
        $existing_record_id = $this->news->find($data['post'][$preferred_language]) ?? 0;
        if ($existing_record_id) {
            foreach ($data['title'] as $language_id => $value) {
                if ($data['title'][$language_id] != NULL) {
                    $lang_items = $data;
                    $lang_items['language_id'] = $language_id;
                    $lang_items['existing_record_id'] = ($language_id != $preferred_language) ? $existing_record_id->id : null;
                    $lang_items['title'] = $data['title'][$language_id];
                    $lang_items['excerpt'] = $data['excerpt'][$language_id];
                    $lang_items['description'] = $data['description'][$language_id];
                    $lang_items['banner'] = isset($data['banner']) && !empty($data['banner']) ? $data['banner'] : $existing_record_id->banner;
                    $lang_items['image'] = isset($data['image']) && !empty($data['image']) ? $data['image'] : $existing_record_id->image;
                    $lang_items['document'] = isset($data['document']) && !empty($data['document']) ? $data['document'] : $existing_record_id->document;
                    $lang_items['display_order'] = $existing_record_id->display_order == null ? 1 : $existing_record_id->display_order;
                    $lang_items['category_id'] = $this->news->model()->multiCategory($data['category_id'], $language_id);
                    $this->news->model()->updateOrCreate(['id' => $data['post'][$language_id]], $lang_items);
                }
            }

            return redirect()->route('admin.news.index')->with('flash_notice', 'News updated successfully');
        }

        return redirect()->back()->withInput()->with('flash_notice', 'News can not be Updated ');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, $id)
    {
        $this->authorize('master-policy.perform', ['news', 'delete']);
        $news = $this->news->find($request->get('id'));
        if ($this->news->destroy($news->id)) {
            if (!empty($news->image)) {
                MediaHelper::destroy($news->image);
            }
            $message = 'News item deleted successfully.';

            return response()->json(['status' => 'ok', 'message' => $message], 200);
        }

        return response()->json(['status' => 'error', 'message' => ''], 422);
    }

    public function changeStatus(Request $request)
    {
        $this->authorize('master-policy.perform', ['news', 'changeStatus']);
        $news = $this->news->find($request->get('id'));
        if ($news->is_active == 0) {
            $status = 1;
            $message = 'News with title "' . $news->title . '" is published.';
        } else {
            $status = 0;
            $message = 'News with title "' . $news->title . '" is unpublished.';
        }
        $this->news->changeStatus($news->id, $status);
        $this->news->update($news->id, array('updated_by' => Auth::user()->id));
        $updated = $this->news->find($request->get('id'));
        if ($multiContent = $this->news->where('existing_record_id', $news->id)->first()) {
            $this->news->changeStatus($multiContent->id, $status);
        }

        return response()->json(['status' => 'ok', 'message' => $message, 'response' => $updated], 200);
    }

    public function sort(Request $request)
    {
        $exploded = explode('&', str_replace('item[]=', '', $request->order));
        for ($i = 0; $i < count($exploded); $i++) {
            $this->news->update($exploded[$i], ['display_order' => $i]);
        }

        return json_encode(['status' => 'success', 'value' => 'Successfully reordered.'], 200);
    }

    public function destroyImage(Request $request, $id)
    {
        $news = $this->news->find($id);
        if ($news) {
            switch ($request->post('type')) {
                case 'banner':
                    MediaHelper::destroy($news->banner);
                    $news->banner = null;
                    break;
                case 'image':
                    MediaHelper::destroy($news->image);
                    $news->image = null;
                    break;
                case 'document':
                    MediaHelper::destroy($news->document);
                    $news->document = null;
                    break;
            }
            $news->save();
            $message = 'Deleted successfully.';

            return response()->json(['status' => 'ok', 'message' => $message], 200);
        }

        return response()->json(['status' => 'error', 'message' => ''], 422);
    }
}