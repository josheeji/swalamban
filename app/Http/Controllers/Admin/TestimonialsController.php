<?php

namespace App\Http\Controllers\Admin;

use App\Helper\ConstantHelper;
use App\Helper\MediaHelper;
use App\Helper\SettingHelper;
use App\Http\Requests\Admin\TestimonialStoreRequest;
use App\Http\Requests\Admin\TestimonialUpdateRequest;
use App\Repositories\TestimonialsRepository;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Intervention\Image\ImageManager;
use Image;

class TestimonialsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    protected $testimonials;
    public  function __construct(TestimonialsRepository $testimonials)
    {
        $this->testimonials = $testimonials;
        $this->preferredLanguage = SettingHelper::setting('preferred_language') == null ? 1 : SettingHelper::setting('preferred_language');
        auth()->shouldUse('admin');
    }

    public function index()
    {
        $this->authorize('master-policy.perform', ['testimonial', 'view']);
        $testimonials = $this->testimonials->where('language_id', $this->preferredLanguage)->orderBy('display_order', 'asc')->orderBy('created_at', 'desc')->get();
        return view('admin.testimonials.index')->withTestimonials($testimonials);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $this->authorize('master-policy.perform', ['testimonial', 'add']);
        return view('admin.testimonials.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(TestimonialStoreRequest $request)
    {
        $this->authorize('master-policy.perform', ['testimonial', 'add']);
        $data = $request->except(['image']);
        if ($request->hasFile('banner')) {
            $filelocation = MediaHelper::upload($request->file('banner'), 'testimonial', true, false);
            $data['banner'] = $filelocation['storage'];
        }
        if ($request->hasFile('image')) {
            $filelocation = MediaHelper::upload($request->file('image'), 'testimonial', true, false);
            $data['image'] = $filelocation['storage'];
        }
        $data['is_active'] = isset($data['is_active']) ? 1 : 0;
        $data['rating'] = isset($data['rating']) ? 1 : '';
        $preferred_language = $this->preferredLanguage;
        /*
         *
         * Insert Preferred Language Item First.
         */
        $preferred_language_item = $data;
        $preferred_language_item['language_id'] = $preferred_language;
        $preferred_language_item['name'] = $data['name'][$preferred_language];
        $preferred_language_item['designation'] = $data['designation'][$preferred_language];
        $preferred_language_item['description'] = $data['description'][$preferred_language];
        $preferred_insert = $this->testimonials->create($preferred_language_item);
        if ($preferred_insert) {
            $lang_items = [];
            $count = 0;
            unset($data['_token']);
            foreach ($data['name'] as $language_id => $value) {
                if ($language_id != $preferred_language) {
                    if ($data['name'][$language_id] != NULL) {
                        $lang_items[$count] = $data;
                        $lang_items[$count]['existing_record_id'] = $preferred_insert->id;
                        $lang_items[$count]['language_id'] = $language_id;
                        $lang_items[$count]['name'] = $data['name'][$language_id];
                        // $lang_items[$count]['banner'] = isset($data['banner']) && !empty($data['banner']) ? $data['banner'] : '';
                        $lang_items[$count]['image'] = isset($data['image']) && !empty($data['image']) ? $data['image'] : '';
                        $lang_items[$count]['designation'] = $data['designation'][$language_id];
                        $lang_items[$count]['description'] = $data['description'][$language_id];
                        $lang_items[$count]['display_order'] = $preferred_insert->display_order == null ? 0 : $preferred_insert->display_order;
                        // $lang_items[$count]['created_by'] = $preferred_insert->created_by;
                        // $lang_items[$count]['created_at'] = $preferred_insert->created_at;
                        $count++;
                    }
                }
            }
            if (!empty($lang_items)) {
                $this->testimonials->model()->insert($lang_items);
            }
            return redirect()->route('admin.testimonials.index')->with('flash_success', 'Testimonial created successfully.');
        }
        return redirect()->back()->withInput()->with('flash_notice', 'Testimonial can not be added.');
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
        $this->authorize('master-policy.perform', ['testimonial', 'edit']);
        $testimonial = $this->testimonials->find($id);
        $lang_content = $this->testimonials->where('existing_record_id', $testimonial->id)->where('language_id', '!=', $this->preferredLanguage)->get();
        $lang_content = $lang_content->groupBy('language_id');
        return view('admin.testimonials.edit', ['testimonial' => $testimonial, 'langContent' => $lang_content, 'preferredLanguage' => $this->preferredLanguage]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(TestimonialUpdateRequest $request, $id)
    {
        // dd($request->all());

        $this->authorize('master-policy.perform', ['testimonial', 'edit']);
        $testimonial = $this->testimonials->find($id);
        $data = $request->except(['image', '_token', '_method']);
        // dd($data);
        if ($request->hasFile('banner')) {
            MediaHelper::destroy($testimonial->banner);
            $filelocation = MediaHelper::upload($request->file('banner'), 'testimonial', true, false);
            $data['banner'] = $filelocation['storage'];
        }
        if ($request->hasFile('image')) {
            MediaHelper::destroy($testimonial->image);
            $filelocation = MediaHelper::upload($request->file('image'), 'testimonial', true, false);
            $data['image'] = $filelocation['storage'];
        }
        $data['is_active'] = isset($request['is_active']) ? 1 : 0;
        $data['rating'] = isset($data['rating']) ? 1 : '';
        $preferred_language = $this->preferredLanguage;

        $existing_record_id = $this->testimonials->find($data['post'][$preferred_language]) ?? 0;

        if ($existing_record_id) {
            foreach ($data['name'] as $language_id => $value) {
                if ($data['name'][$language_id] != NULL) {

                    $lang_items = $data;
                    $lang_items['image'] = isset($data['image']) && !empty($data['image']) ? $data['image'] : $existing_record_id->image;
                    // $lang_items['banner'] = isset($data['banner']) && !empty($data['banner']) ? $data['banner'] : $existing_record_id->banner;
                    $lang_items['language_id'] = $language_id;
                    $lang_items['existing_record_id'] = ($language_id != $preferred_language) ? $existing_record_id->id : null;
                    $lang_items['name'] = $data['name'][$language_id];
                    $lang_items['designation'] = $data['designation'][$language_id];
                    $lang_items['description'] = $data['description'][$language_id];
                    // // if ($language_id != 1) {
                    // //   
                    // // }
                    // // dd($lang_items);
                    // $this->testimonials->model()->updateOrInsert(['id' => $data['post'][$language_id]], $lang_items);

                    unset($lang_items['post']);
                    $this->testimonials->model()->updateOrInsert(['id' => $data['post'][$language_id]], $lang_items);
                }
            }
            return redirect()->route('admin.testimonials.index')
                ->with('flash_success', 'Testimonials updated successfully');
        } else {
            return redirect()->back()->withInput()->with('flash_notice', 'Testimonials can not be updated.');
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, $id)
    {
        abort_if(Gate::denies('master-policy.perform', ['testimonial', 'delete']), 403);

        $this->validate($request, [
            'id' => 'required|exists:testimonials,id',
        ]);
        $this->testimonials->where('existing_record_id', $id)->delete();
        $testimonial = $this->testimonials->findOrfail($id);
        $this->testimonials->destroy($testimonial->id);
        $message = 'Testimonials deleted successfully.';
        return response()->json(['status' => 'ok', 'message' => $message], 200);
    }
    public function changeStatus(Request $request)
    {
        $this->authorize('master-policy.perform', ['testimonial', 'changeStatus']);
        $testimonial = $this->testimonials->find($request->get('id'));
        if ($testimonial->is_active == 0) {
            $status = '1';
            $message = 'Testimonials with title "' . $testimonial->name . '" is published.';
        } else {
            $status = '0';
            $message = 'Testimonials with title "' . $testimonial->name . '" is unpublished.';
        }
        $this->testimonials->changeStatus($testimonial->id, $status);
        $this->testimonials->update($testimonial->id, ['is_active' => $status]);
        $updated = $this->testimonials->find($request->get('id'));
        if ($multiContent = $this->testimonials->where('existing_record_id', $testimonial->id)->first()) {
            $this->testimonials->changeStatus($multiContent->id, $status);
        }
        return response()->json(['status' => 'ok', 'message' => $message, 'response' => $updated], 200);
    }

    public function sort(Request $request)
    {
        $exploded = explode('&', str_replace('item[]=', '', $request->order));
        for ($i = 0; $i < count($exploded); $i++) {
            $this->testimonials->update($exploded[$i], ['display_order' => $i]);
        }
        return json_encode(['status' => 'success', 'value' => 'Successfully reordered.'], 200);
    }
}