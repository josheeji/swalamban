<?php

namespace App\Http\Controllers\Admin;

use App\Helper\Helper;
use App\Helper\MediaHelper;
use App\Helper\SettingHelper;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Repositories\BlogCategoryRepository;
use App\Repositories\BlogRepository;
use App\Http\Requests\Admin\BlogRequest;
use App\Repositories\BlogBlockRepository;
use Illuminate\Support\Facades\Auth;

class BlogController extends Controller
{
    public $title = 'Blog';


    /**
     * The Blog Category repository implementation.
     *
     * @var BlogCategoryRepository
     */
    protected $category;

    /**
     * The Blog repository implementation.
     *
     * @var BlogRepository
     */
    protected $blog;

    /**
     * Create a new controller instance.
     *
     * @param  BlogCategoryRepository $category
     * @param  BlogRepository $blog
     */
    public function __construct(
        BlogCategoryRepository $category,
        BlogRepository $blog,
        BlogBlockRepository $blogBlock
    ) {
        $this->category = $category;
        $this->blog = $blog;
        $this->blogBlock = $blogBlock;
        $this->preferredLanguage = SettingHelper::setting('preferred_language') == null ? 1 : SettingHelper::setting('preferred_language');
        auth()->shouldUse('admin');
    }

    public function index(Request $request)
    {
        $this->authorize('master-policy.perform', ['blog', 'view']);
        $title = $this->title;
        $blogs = $this->blog->where('language_id', $this->preferredLanguage)->orderby('created_at', 'desc')->get();
        return view('admin.blog.blog.index')->withTitle($title)->withBlogs($blogs);
    }

    public function create()
    {
        $this->authorize('master-policy.perform', ['blog', 'add']);
        $title = 'Add Blog';
        $categories = $this->category->where('language_id', $this->preferredLanguage)->get();
        return view('admin.blog.blog.create')->withTitle($title)->withCategories($categories);
    }

    public function store(BlogRequest $request)
    {
        $this->authorize('master-policy.perform', ['blog', 'add']);
        $data = $request->except(['image', 'blocks']);
        $blocks = $request->get('blocks');
        if ($request->hasFile('image')) {
            $filelocation = MediaHelper::upload($request->file('image'), 'blog', true);
            $data['image'] = $filelocation['storage'];
        }
        if ($request->hasFile('document')) {
            $fileLocation = MediaHelper::upload($request->file('document'), 'blog', false);
            $data['document'] = $fileLocation['storage'];
        }
        if ($request->hasFile('banner')) {
            $filelocation = MediaHelper::upload($request->file('banner'), 'blog', true, true);
            $data['banner'] = $filelocation['storage'];
        }
        $data['is_active'] = isset($data['is_active']) ? 1 : 0;
        $data['show_image'] = isset($data['show_image']) ? 1 : 0;
        $preferred_language = $this->preferredLanguage;
        /**
         * Insert Preferred Language Item First.
         */
        $preferred_language_item = $data;
        $preferred_language_item['language_id'] = $preferred_language;
        $preferred_language_item['title'] = $data['title'][$preferred_language];
        $preferred_language_item['excerpt'] = $data['excerpt'][$preferred_language];
        $preferred_language_item['description'] = $data['description'][$preferred_language];
        $preferred_insert = $this->blog->create($preferred_language_item);
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
                        $lang_items[$count]['description'] = $data['description'][$language_id];
                        //                        $lang_items[$count]['category_id'] = $this->blog->model()->multiCategory($preferred_insert->category_id, $language_id);
                        // $lang_items[$count]['created_by'] = $preferred_insert->created_by;
                        $lang_items[$count]['created_at'] = $preferred_insert->created_at;
                        $lang_items[$count]['published_date'] = $preferred_insert->published_date;
                        $count++;
                    }
                }
            }
            if (!empty($lang_items)) {
                $this->blog->model()->insert($lang_items);
            }

            if (isset($blocks) && !empty($blocks)) {
                $this->saveBlock($preferred_insert, $blocks, $request);
            }

            return redirect()->route('admin.stories.index')->with('flash_notice', 'Blog Created Successfully.');
        }

        return redirect()->back()->withInput()->with('flash_notice', 'Blog can not be Create');
    }

    public function changeStatus(Request $request)
    {
        $this->authorize('master-policy.perform', ['blog', 'changeStatus']);
        $blog = $this->blog->find($request->get('id'));
        if ($blog->is_active == 0) {
            $status = 1;
            $message = 'Blog with title "' . $blog->title . '" is published.';
        } else {
            $status = 0;
            $message = 'Blog with title "' . $blog->title . '" is unpublished.';
        }

        $this->blog->changeStatus($blog->id, $status);
        $updated = $this->blog->find($request->get('id'));
        if ($multiContenct = $this->blog->where('existing_record_id', $blog->id)->first()) {
            $this->blog->changeStatus($multiContenct->id, $status);
        }

        return response()->json(['status' => 'ok', 'message' => $message, 'response' => $updated], 200);
    }

    public function destroy(Request $request)
    {
        $this->authorize('master-policy.perform', ['blog', 'delete']);
        $this->validate($request, [
            'id' => 'required|exists:blogs,id',
        ]);
        $blog = $this->blog->find($request->get('id'));
        $this->blog->destroy($blog->id);
        $message = 'Your blog deleted successfully.';
        return response()->json(['status' => 'ok', 'message' => $message], 200);
    }

    public function edit(Request $request, $id)
    {
        $this->authorize('master-policy.perform', ['blog', 'edit']);
        $title = 'Edit Blog';
        $preferredLanguage = $this->preferredLanguage;
        $blog = $this->blog->find($id);
        $lang_content = $this->blog->where('existing_record_id', $blog->id)->where('language_id', '!=', $this->preferredLanguage)->get();
        $langContent = $lang_content->groupBy('language_id');
        $categories = $this->category->where('language_id', $this->preferredLanguage)->get();
        $blocks = $this->blogBlock->where('blog_id', $id)->where('language_id', $this->preferredLanguage)->get();

        return view('admin.blog.blog.edit', compact('title', 'blog', 'blocks', 'langContent', 'categories', 'preferredLanguage'));
    }

    public function update(BlogRequest $request, $id)
    {
        $blocks = $request->get('blocks');
        $blog = $this->blog->find($id);
        $data = $request->except(['_token', '_method', 'blocks']);

        if ($request->hasFile('banner')) {
            if (file_exists('storage/' . $blog->banner) && !empty($blog->banner)) {
                MediaHelper::destroy($blog->banner);
            }
            $filelocation = MediaHelper::upload($request->file('banner'), 'blog', true, true);
            $data['banner'] = $filelocation['storage'];
        }
        if ($request->hasFile('image')) {
            if (file_exists('storage/' . $blog->image) && !empty($blog->image)) {
                MediaHelper::destroy($blog->image);
            }
            $filelocation = MediaHelper::upload($request->file('image'), 'blog');
            $data['image'] = $filelocation['storage'];
        }

        if ($request->hasFile('document')) {
            if (file_exists('storage/' . $blog->document) && !empty($blog->document)) {
                MediaHelper::destroy($blog->document);
            }
            $fileLocation = MediaHelper::upload($request->file('document'), 'blog', false);
            $data['document'] = $fileLocation['storage'];
        }
        $data['is_active'] = isset($data['is_active']) ? 1 : 0;
        $data['show_image'] = isset($data['show_image']) ? 1 : 0;
        $preferred_language = $this->preferredLanguage;
        $existing_record_id = $this->blog->find($data['post'][$preferred_language]) ?? 0;
        if ($existing_record_id) {
            foreach ($data['title'] as $language_id => $value) {
                if ($data['title'][$language_id] != NULL) {
                    $lang_items = $data;
                    $lang_items['language_id'] = $language_id;
                    $lang_items['existing_record_id'] = ($language_id != $preferred_language) ? $id : null;
                    $lang_items['title'] = $data['title'][$language_id];
                    $lang_items['excerpt'] = $data['excerpt'][$language_id];
                    $lang_items['description'] = $data['description'][$language_id];
                    //                    $lang_items['category_id'] = $this->blog->model()->multiCategory($data['category_id'], $language_id);
                    $lang_items['id'] = $data['post'][$language_id];
                    // $lang_items['created_by'] = $existing_record_id->created_by;
                    $lang_items['created_at'] = $existing_record_id->created_at;
                    $lang_items['published_date'] = $data['published_date'];
                    unset($lang_items['post']);
                    $this->blog->model()->updateOrCreate(['id' => $data['post'][$language_id]], $lang_items);
                }
            }
            if (isset($blocks) && !empty($blocks)) {
                $this->saveBlock($existing_record_id, $blocks, $request);
            }

            return redirect()->route('admin.stories.index')->with('flash_success', 'Stories updated successfully');
        }

        return redirect()->back()->withInput()->with('flash_error', 'Stories can not be Updated.');
    }

    public function block(Request $request)
    {
        $index = $request->get('index');
        return view('admin.blog.blog.block', ['index' => $index]);
    }

    public function removeBlock(Request $request)
    {
        $id = $request->get('id');
        $block =  $this->blogBlock->find($id);
        if ($block->destroy($id)) {
            MediaHelper::destroy($block->image);
            if ($multiContent = $this->blogBlock->where('existing_record_id', $id)->get()) {
                foreach ($multiContent as $content) {
                    $content->destroy($content->id);
                }
            }
            return 'success';
        }
        return 'error';
    }

    public function removeBlockImage(Request $request)
    {
        $id = $request->get('id');
        if ($block =  $this->blogBlock->find($id)) {
            MediaHelper::destroy($block->image);
            $block->image = '';
            $block->save();
            if ($multiContent = $this->blogBlock->where('existing_record_id', $id)->get()) {
                foreach ($multiContent as $content) {
                    $content->image = '';
                    $content->save();
                }
            }
            return 'success';
        }
        return 'error';
    }

    public function saveBlock($content, $blocks, $request)
    {
        $pk = [];
        if (isset($blocks) && is_array($blocks)) {
            foreach ($blocks as $index => $block) {
                $image = '';
                if ($request->hasFile("blocks.{$index}.image")) {
                    $filelocation = MediaHelper::upload($request->file("blocks.{$index}.image"), 'content');
                    $image = $filelocation['storage'];
                }
                foreach ($block as $language => $blockData) {
                    $contentID = $content->id;
                    if ($content->language_id != $language) {
                        if ($multiContent = $this->blog->where('existing_record_id', $content->id)->where('language_id', $language)->first()) {
                            $contentID = $multiContent->id;
                        }
                        if (isset($pk[$index])) {
                            $blockData['existing_record_id'] = $pk[$index];
                        } else {
                        }
                    }
                    $blockData['blog_id'] = $contentID;
                    $blockData['language_id'] = $language;
                    if ($request->hasFile("blocks.{$index}.image")) {
                        $blockData['image'] = $image;
                    }
                    if (isset($blockData['id']) && !empty($blockData['id'])) {
                        $id = $blockData['id'];
                        unset($blockData['id']);
                        $this->blogBlock->model()->updateOrCreate(['id' => $id], $blockData);
                    } else {
                        $blockData['created_by'] = Auth::user()->id;
                        $model = $this->blogBlock->create($blockData);
                        $pk[$index] = $model->id;
                    }
                }
            }
        }
    }

    public function destroyImage(Request $request, $id)
    {
        $content = $this->blog->find($id);
        if ($content) {
            switch ($request->post('type')) {
                case 'banner':
                    MediaHelper::destroy($content->banner);
                    $content->banner = null;
                    break;
                case 'image':
                    MediaHelper::destroy($content->image);
                    $content->image = null;
                    break;
                case 'document':
                    MediaHelper::destroy($content->document);
                    $content->document = null;
                    break;
            }
            if ($content->save()) {
                $preferred_language = $this->preferredLanguage;
                $other_posts = $this->blog->where('existing_record_id', $content->id)->get();
                $other_posts_grouped_language = $other_posts->groupBy('language_id');
                if ($other_posts_grouped_language) {
                    foreach ($other_posts_grouped_language as $language => $records) {
                        foreach ($records as $record) {
                            $this->blog->update($record->id, ['image' => $content->image]);
                        }
                    }
                }
            }
            $message = 'Content deleted successfully.';
            return response()->json(['status' => 'ok', 'message' => $message], 200);
        }
        return response()->json(['status' => 'error', 'message' => ''], 422);
    }
}
