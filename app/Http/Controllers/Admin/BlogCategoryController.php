<?php

namespace App\Http\Controllers\Admin;

use App\Helper\Helper;
use App\Helper\MediaHelper;
use App\Helper\SettingHelper;
use Image;
use Illuminate\Http\Request;
use Intervention\Image\ImageManager;
use App\Http\Controllers\Controller;
use App\Repositories\BlogCategoryRepository;
use App\Http\Requests\Admin\BlogCategoryRequest;


class BlogCategoryController extends Controller
{
    public $title = 'Blog Category';
    /**
     * The BlogCategoryRepository implementation.
     *
     * @var $blogCategory
     */
    protected $blogCategory;

    /**
     * Create a new controller instance.
     *
     * @param  BlogCategoryRepository $blogCategory
     */
    public function __construct(BlogCategoryRepository $blogCategory)
    {
        $this->blogCategory = $blogCategory;
        $this->preferredLanguage = SettingHelper::setting('preferred_language') == null ? 1 : SettingHelper::setting('preferred_language');
        auth()->shouldUse('admin');
    }

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
        $this->authorize('master-policy.perform', ['blog-category', 'view']);
        $title = $this->title;
        $categories = $this->blogCategory->where('language_id', Helper::locale())->orderBy('display_order', 'asc')->get();
        return view('admin.blog.category.index', compact('title', 'categories'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create()
    {
        $this->authorize('master-policy.perform', ['blog-category', 'add']);
        $title = 'Create Category';
        return view('admin.blog.category.create', compact('title'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @return Response
     */
    public function store(BlogCategoryRequest $request)
    {
        $this->authorize('master-policy.perform', ['blog-category', 'add']);
        $data = $request->except(['_token']);
        $preferred_language = $this->preferredLanguage;
        /**
         * Insert Preferred Language Item First.
         */
        $preferred_language_item = $data;
        $preferred_language_item['language_id'] = $preferred_language;
        $preferred_language_item['title'] = $data['title'][$preferred_language];
        $preferred_insert = $this->blogCategory->create($preferred_language_item);
        if ($preferred_insert) {
            $lang_items = [];
            $count = 0;
            foreach ($data['title'] as $language_id => $value) {
                if ($language_id != $preferred_language) {
                    if ($data['title'][$language_id] != NULL) {
                        $lang_items[$count] = $data;
                        $lang_items[$count]['existing_record_id'] = $preferred_insert->id;
                        $lang_items[$count]['language_id'] = $language_id;
                        $lang_items[$count]['title'] = $data['title'][$language_id];
                        $lang_items[$count]['slug'] = $preferred_insert->slug;
                        $lang_items[$count]['display_order'] = $preferred_insert->display_order == null ? 0 : $preferred_insert->display_orders;
                        $lang_items[$count]['created_by'] = $preferred_insert->created_by;
                        $lang_items[$count]['created_at'] = $preferred_insert->created_at;
                        $count++;
                    }
                }
            }
            if (!empty($lang_items)) {
                $this->blogCategory->model()->insert($lang_items);
            }

            return redirect()->route('admin.blog-categories.index')->with('flash_notice', 'Blog category Created Successfully.');
        }

        return redirect()->back()->withInput()->with('flash_notice', 'Blog category can not be created.');
    }

    public function changeStatus(Request $request)
    {
        $this->authorize('master-policy.perform', ['blog-category', 'changeStatus']);
        $category = $this->blogCategory->find($request->get('id'));
        if ($category->is_active == 0) {
            $status = 1;
            $message = 'Category with name "' . $category->name . '" is published.';
        } else {
            $status = 0;
            $message = 'Category with name "' . $category->name . '" is unpublished.';
        }
        $this->blogCategory->changeStatus($category->id, $status);
        $updated = $this->blogCategory->find($request->get('id'));

        $this->blogCategory->changeStatus($category->id, $status);
        $this->blogCategory->update($category->id, array('updated_by' => auth()->id()));
        $updated = $this->blogCategory->find($request->get('id'));
        if ($multiContenct = $this->blogCategory->where('existing_record_id', $category->id)->first()) {
            $this->blogCategory->changeStatus($multiContenct->id, $status);
        }

        return response()->json(['status' => 'ok', 'message' => $message, 'response' => $updated], 200);
    }

    public function destroy(Request $request)
    {
        $this->authorize('master-policy.perform', ['blog-category', 'delete']);
        $this->validate($request, [
            'id' => 'required|exists:blog_categories,id',
        ]);
        $category = $this->blogCategory->find($request->get('id'));
        MediaHelper::destroy($category->image);
        $this->blogCategory->destroy($category->id);
        $message = 'Blog category is deleted successfully.';
        return response()->json(['status' => 'ok', 'message' => $message], 200);
    }

    public function edit($id)
    {
        $this->authorize('master-policy.perform', ['blog-category', 'edit']);
        $title = 'Edit Category';
        $blogCategory = $this->blogCategory->find($id);
        $lang_content = $this->blogCategory->where('existing_record_id', $blogCategory->id)->where('language_id', '!=', $this->preferredLanguage)->get();
        $lang_content = $lang_content->groupBy('language_id');

        return view('admin.blog.category.edit', compact('title'))
            ->withBlogCategory($blogCategory)
            ->withLangContent($lang_content)
            ->withPreferredLanguage($this->preferredLanguage);
    }

    public function update(BlogCategoryRequest $request, $id)
    {
        $this->authorize('master-policy.perform', ['blog-category', 'edit']);
        $category = $this->blogCategory->find($id);
        $data = $request->except(['_token', '_method']);
        $data['link_target'] = isset($request['link_target']) ? 1 : 0;
        $data['is_active'] = isset($request['is_active']) ? 1 : 0;
        $data['edit'] = isset($request['edit']) ? 1 : 0;
        $preferred_language = $this->preferredLanguage;
        $existing_record_id = $this->blogCategory->find($data['post'][$preferred_language]) ?? 0;
        if ($existing_record_id) {
            foreach ($data['title'] as $language_id => $value) {
                if ($data['title'][$language_id] != NULL) {
                    $lang_items = $data;
                    $lang_items['language_id'] = $language_id;
                    $lang_items['existing_record_id'] = ($language_id != $preferred_language) ? $existing_record_id->id : null;
                    $lang_items['title'] = $data['title'][$language_id];
                    $lang_items['slug'] = $existing_record_id->slug;
                    unset($lang_items['post']);
                    $this->blogCategory->model()->updateOrCreate(['id' => $data['post'][$language_id]], $lang_items);
                }
            }

            return redirect()->route('admin.blog-categories.index')->with('flash_notice', 'Blog category updated successfully');
        } else {

            return redirect()->back()->withInput()->with('flash_notice', 'Blog category can not be updated.');
        }
    }

    public function sort(Request $request)
    {
        $exploded = explode('&', str_replace('item[]=', '', $request->order));
        $language_id = $request->language_id ?? 1;
        for ($i = 0; $i < count($exploded); $i++) {
            $this->blogCategory->update($exploded[$i], ['display_order' => $i]);
        }
        $preferred_language = $this->preferredLanguage;
        $other_posts = $this->blogCategory->whereIn('existing_record_id', $exploded)->get();
        $other_posts_grouped_language = $other_posts->groupBy('language_id');
        $english_sort = $this->blogCategory->where('language_id', $preferred_language)->pluck('display_order', 'id')->toArray();
        if ($other_posts_grouped_language) {
            foreach ($other_posts_grouped_language as $language => $records) {
                foreach ($records as $record) {
                    $this->blogCategory->update($record->id, ['display_order' => $english_sort[$record->existing_record_id]]);
                }
            }
        }

        return json_encode(['status' => 'success', 'value' => 'Successfully reordered.'], 200);
    }
}
