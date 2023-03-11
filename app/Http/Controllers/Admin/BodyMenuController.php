<?php

namespace App\Http\Controllers\Admin;

use App\Helper\ConstantHelper;
use App\Helper\MediaHelper;
use App\Helper\SettingHelper;
use App\Http\Controllers\Controller;
use App\Repositories\BodyMenuRepository;
use App\Repositories\SidebarMenuRepository;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class BodyMenuController extends Controller
{
    protected $preferredLanguage, $bodyMenuRepository;

    public $title = 'Body Menu';
    public $view = 'bodyMenu';
    public $route = 'body-menu';
    public $permission = 'body-menu';

    public function __construct(BodyMenuRepository $bodyMenuRepository)
    {
        $this->bodyMenuRepository = $bodyMenuRepository;
        $this->preferredLanguage = SettingHelper::setting('preferred_language') == null ? 1 : SettingHelper::setting('preferred_language');
        auth()->shouldUse('admin');
    }

    /**
     * Display a listing of the resource.
     *
     * @return Response
     * @throws AuthorizationException
     */
    public function index()
    {
        $this->authorize('master-policy.perform', [$this->permission, 'view']);
        $title = $this->title;
        $bodyMenu = $this->bodyMenuRepository->where('language_id', $this->preferredLanguage)->orderby('display_order', 'asc')->orderBy('created_at', 'desc')->get();
        return view('admin.' . $this->view . '.index', ['datas' => $bodyMenu, 'title' => $title, 'route' => $this->route, 'permission' => $this->permission]);

    }

    /**
     * Show the form for creating a new resource.
     *
     * @param Request $request
     * @return Response
     */
    public function create()
    {
        $title = $this->title;
        $route = $this->route;
        return view('admin.' . $this->view . '.create', compact('title', 'route'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return Response
     * @throws AuthorizationException
     */
    public function store(Request $request)
    {
        $this->authorize('master-policy.perform', [$this->permission, 'add']);
        $data = $request->except(['_token', 'post','show_in_nepali']);

//        if ($request->hasFile('image')) {
//            $filelocation = MediaHelper::upload($request->file('image'), 'body-menu', true, true);
//            $data['image'] = $filelocation['storage'];
//        }

        $preferred_language = $this->preferredLanguage;
        /**
         * Insert Preferred Language Item First.
         */

        $request->validate([
            'title.' . $preferred_language => ['required', 'string'],
            'value.' . $preferred_language => ['required', 'string'],
//            'image' => 'required',
        ],
            [
                'title.' . $preferred_language.'.required'=>'The title field is required.',
                'value.' . $preferred_language.'.required'=>'The icon field is required.',
                'image'=>'The link field is required.']);
        $data['is_active'] = isset($data['is_active']) ? 1 : 0;

        $preferred_language_item = $data;
        $preferred_language_item['language_id'] = $preferred_language;
        $preferred_language_item['title'] = $data['title'][$preferred_language];
        $preferred_language_item['value'] = $data['value'][$preferred_language];
        $preferred_language_item['image'] = $data['image'];
        $preferred_insert = $this->bodyMenuRepository->create($preferred_language_item);
        if ($preferred_insert) {
            $lang_items = [];
            $count = 0;
            foreach ($request['post'] as $language_id => $value) {
                if ($language_id != $preferred_language) {
                    if ($request['post'][$language_id] != NULL) {
                        if ($request->has('show_in_nepali') && $request->show_in_nepali == 1) {

                            $lang_items[$count] = $data;
                            $lang_items[$count]['existing_record_id'] = $preferred_insert->id;
                            $lang_items[$count]['title'] = $preferred_insert->title;
                            $lang_items[$count]['value'] = $preferred_insert->value;
                            $lang_items[$count]['image'] = $preferred_insert->image;
                            $lang_items[$count]['language_id'] = $language_id;
                            $lang_items[$count]['is_active'] = $preferred_insert->is_active;
                            $lang_items[$count]['created_by'] = $preferred_insert->created_by;
                            $lang_items[$count]['created_at'] = $preferred_insert->created_at;
                            $count++;
                        } else {
                            if ($request['title'][$language_id] != NULL) {

                                $lang_items[$count] = $data;
                                $lang_items[$count]['existing_record_id'] = $preferred_insert->id;
                                $lang_items[$count]['title'] = $data['title'][$language_id];
                                $lang_items[$count]['value'] = $data['value'][$language_id];
                                $lang_items[$count]['image'] = $data['image'];
                                $lang_items[$count]['language_id'] = $language_id;
                                $lang_items[$count]['is_active'] = $preferred_insert->is_active;
                                $lang_items[$count]['created_by'] = $preferred_insert->created_by;
                                $lang_items[$count]['created_at'] = $preferred_insert->created_at;
                                $count++;
                            }
                        }
                    }
                }
            }
            if (!empty($lang_items)) {
                unset($lang_items[0]['q']);
                $this->bodyMenuRepository->model()->insert($lang_items);
            }

            return redirect()->route('admin.' . $this->route . '.index')
                ->with('flash_notice', $this->title . ' Created Successfully.');
        }

        return redirect()->back()->withInput()
            ->with('flash_notice', $this->title . ' can not be created.');
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return void
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param int $id
     * @return Response
     * @throws AuthorizationException
     */
    public function edit($id)
    {
        $this->authorize('master-policy.perform', [$this->permission, 'edit']);
        $fy = $this->bodyMenuRepository->find($id);
        $title = $this->title;
        $route = $this->route;
        $lang_content = $this->bodyMenuRepository->where('existing_record_id', $fy->id)->where('language_id', '!=', $this->preferredLanguage)->get();
        $lang_content = $lang_content->groupBy('language_id');
        return view('admin.' . $this->view . '.edit', ['title' => $title, 'edit_data' => $fy, 'route' => $route])
            ->withLangContent($lang_content)
            ->withPreferredLanguage($this->preferredLanguage);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param Request $request
     * @param int $id
     * @return Response
     * @throws AuthorizationException
     */
    public function update(Request $request, $id)
    {
        $this->authorize('master-policy.perform', [$this->permission, 'edit']);
        $data = $request->except(['_token', '_method']);
        $post = $this->bodyMenuRepository->find($id);
//        if ($request->hasFile('image')) {
//            MediaHelper::destroy($post->image);
//            $filelocation = MediaHelper::upload($request->file('image'), 'body-menu', true, true);
//            $data['image'] = $filelocation['storage'];
//        }
        $data['is_active'] = isset($request['is_active']) ? 1 : 0;
        $preferred_language = $this->preferredLanguage;
        $request->validate([
            'title.' . $preferred_language => ['required', 'string'],
            'value.' . $preferred_language => ['required', 'string'],
//            'image' => 'required',
        ],[
            'title.' . $preferred_language.'.required'=>'The title field is required.',
            'value.' . $preferred_language.'.required'=>'The icon field is required.','image'=>'The link field is required.']);
        $existing_record_id = $this->bodyMenuRepository->find($data['update'][$preferred_language]) ?? 0;

        if ($existing_record_id) {
            foreach ($data['title'] as $language_id => $value) {
                if ($data['title'][$language_id] != NULL) {
                    $lang_items = $data;
                    $lang_items['language_id'] = $language_id;
                    $lang_items['title'] = $data['title'][$language_id];
                    $lang_items['value'] = $data['value'][$language_id];
                    $lang_items['image'] = $data['image'];
                    $lang_items['existing_record_id'] = ($language_id != $preferred_language) ? $existing_record_id->id : null;

                    $this->bodyMenuRepository->model()->updateOrCreate(['id' => $data['update'][$language_id]], $lang_items);
                }
            }

            return redirect()->route('admin.' . $this->route . '.index')
                ->with('flash_notice', $this->title . ' Updated Successfully.');
        } else {

            return redirect()->back()->withInput()
                ->with('flash_notice', $this->title . ' can not be updated.');
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return Response
     * @throws AuthorizationException
     */
    public function destroy($id)
    {
        $this->authorize('master-policy.perform', [$this->permission, 'delete']);
        $serviceCharge = $this->bodyMenuRepository->find($id);
        $this->bodyMenuRepository->where('existing_record_id', $id)->delete();
        if ($this->bodyMenuRepository->destroy($serviceCharge->id)) {
            if (!empty($serviceCharge->image)) {
                MediaHelper::destroy($serviceCharge->image);
            }
            $message = $this->title . ' deleted successfully.';

            return response()->json(['status' => 'ok', 'message' => $message], 200);
        }

        return response()->json(['status' => 'error', 'message' => ''], 422);
    }

    public function changeStatus(Request $request)
    {
        $this->authorize('master-policy.perform', [$this->permission, 'changeStatus']);
        $serviceCharge = $this->bodyMenuRepository->find($request->get('id'));
        $status = $serviceCharge->is_active == 0 ? 1 : 0;
        $message = $serviceCharge->is_active == 0 ? $this->title . ' is published.' : $this->title . '  is unpublished.';
        $this->bodyMenuRepository->changeStatus($serviceCharge->id, $status);
        $this->bodyMenuRepository->update($serviceCharge->id, array('updated_by' => auth()->id()));
        $updated = $this->bodyMenuRepository->find($request->get('id'));
        if ($multiContenct = $this->bodyMenuRepository->where('existing_record_id', $serviceCharge->id)->first()) {
            $this->bodyMenuRepository->changeStatus($multiContenct->id, $status);
        }

        return response()->json(['status' => 'ok', 'message' => $message, 'response' => $updated], 200);
    }

    public function sort(Request $request)
    {
        $exploded = explode('&', str_replace('item[]=', '', $request->order));
        $language_id = $request->language_id ?? 1;
        for ($i = 0; $i < count($exploded); $i++) {
            $this->bodyMenuRepository->update($exploded[$i], ['display_order' => $i]);
        }
        $preferred_language = $this->preferredLanguage;
        $other_posts = $this->bodyMenuRepository->whereIn('existing_record_id', $exploded)->get();
        $other_posts_grouped_language = $other_posts->groupBy('language_id');
        $english_sort = $this->bodyMenuRepository->where('language_id', $preferred_language)->pluck('display_order', 'id')->toArray();
        if ($other_posts_grouped_language) {
            foreach ($other_posts_grouped_language as $language => $records) {
                foreach ($records as $record) {
                    $this->bodyMenuRepository->update($record->id, ['display_order' => $english_sort[$record->existing_record_id]]);
                }
            }
        }

        return json_encode(['status' => 'success', 'value' => 'Successfully reordered.'], 200);
    }

    public function destroyImage(Request $request, $id)
    {
        $item = $this->bodyMenuRepository->find($id);
        if ($item) {
            switch ($request->post('type')) {
                case 'banner':
                    MediaHelper::destroy($item->banner);
                    $item->banner = null;
                    break;
                case 'image':
                    MediaHelper::destroy($item->image);
                    $item->image = null;
                    break;
                case 'document':
                    MediaHelper::destroy($item->document);
                    $item->document = null;
                    break;
            }
            $item->save();
            $message = 'Deleted successfully.';

            return response()->json(['status' => 'ok', 'message' => $message], 200);
        }

        return response()->json(['status' => 'error', 'message' => ''], 422);
    }
}
