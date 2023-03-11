<?php

namespace App\Http\Controllers\Admin;

use App\Helper\MediaHelper;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Department\DepartmentStoreRequest;
use App\Http\Requests\Admin\Department\DepartmentUpdateRequest;
use App\Repositories\BranchDirectoryRepository;
use App\Repositories\DepartmentRepository;
use Illuminate\Http\Request;

class DepartmentController extends Controller
{
    public $title = 'Department';

    protected $department, $preferredLanguage, $branch;

    public function __construct(
        DepartmentRepository $department,
        BranchDirectoryRepository $branch
    ) {
        $this->preferredLanguage = session('site_settings')['preferred_language'];
        $this->department = $department;
        $this->branch = $branch;
        auth()->shouldUse('admin');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $this->authorize('master-policy.perform', ['department', 'view']);
        $title = $this->title;
        $departments = $this->department
            ->where('language_id', $this->preferredLanguage)
            ->orderBy('display_order', 'desc')
            ->orderBy('created_at', 'desc')
            ->get();
        return view('admin.department.index', compact('title', 'departments'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $this->authorize('master-policy.perform', ['department', 'add']);
        $title = $this->title;
        $departments = $this->department->departmentList();
        return view('admin.department.create', compact('title', 'departments'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(DepartmentStoreRequest $request)
    {
        $this->authorize('master-policy.perform', ['department', 'add']);
        $data = $request->except(['_token']);
        $data['is_active'] = isset($request['is_active']) ? 1 : 0;

        $preferred_language = $this->preferredLanguage;
        /**
         * Insert Preferred Language Item First.
         */
        $preferred_language_item = $data;
        $preferred_language_item['title'] = $data['title'][$preferred_language];
        $preferred_language_item['email'] = $data['email'];
        $preferred_language_item['phone'] = $data['phone'];

        $preferred_insert = $this->department->create($preferred_language_item);

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
                        $lang_items[$count]['email'] = $preferred_insert->email;
                        $lang_items[$count]['phone'] = $preferred_insert->phone;
                        $lang_items[$count]['slug'] = $preferred_insert->slug;
                        $lang_items[$count]['display_order'] = $preferred_insert->display_order == null ? null : $preferred_insert->display_order;
                        $lang_items[$count]['created_by'] = $preferred_insert->created_by;
                        $lang_items[$count]['created_at'] = $preferred_insert->created_at;
                        $count++;
                    }
                }
            }

            if (!empty($lang_items)) {
                $this->department->model()->insert($lang_items);
            }

            return redirect()->route('admin.department.index')->with('flash_notice', 'Successfully created new department.');
        }
        return redirect()->back()->withInput()->with('flash_notice', 'New department can not be created.');
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
        $this->authorize('master-policy.perform', ['department', 'edit']);
        $department = $this->department->find($id);
        $title = $this->title;
        return view('admin.department.edit', compact('title', 'department'))->withPreferredLanguage($this->preferredLanguage);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(DepartmentUpdateRequest $request, $id)
    {
        $this->authorize('master-policy.perform', ['department', 'edit']);
        $department = $this->department->find($id);
        $data = $request->except(['image']);
        $data['is_active'] = isset($request['is_active']) ? 1 : 0;

        $preferred_language = $this->preferredLanguage;
        $existing_record_id = $this->department->find($data['post'][$preferred_language]) ?? 0;

        if ($existing_record_id) {
            foreach ($data['title'] as $language_id => $value) {

                if ($data['title'][$language_id] != NULL) {
                    $lang_items = $data;
                    $lang_items['language_id'] = $language_id;
                    $lang_items['existing_record_id'] = ($language_id != $preferred_language) ? $existing_record_id->id : null;
                    $lang_items['title'] = $data['title'][$language_id];
                    $lang_items['slug'] = $existing_record_id->slug;
                    $lang_items['phone'] = $data['phone'];
                    $lang_items['email'] = $data['email'];

                    $this->department->model()->updateOrCreate(['id' => $data['post'][$language_id]], $lang_items);
                }
            }
            return redirect()->route('admin.department.index', $department->branch_id)
                ->with('flash_notice', 'Branch updated successfully');
        } else {
            return redirect()->back()->withInput()->with('flash_notice', 'Department can not be updated.');
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $this->authorize('master-policy.perform', ['department', 'delete']);
        $department = $this->department->find($id);
        if ($this->department->destroy($department->id)) {
            if (!empty($department->image)) {
                MediaHelper::destroy($department->image);
            }
            $message = 'Department deleted successfully.';
            return response()->json(['status' => 'ok', 'message' => $message], 200);
        }
        return response()->json(['status' => 'error', 'message' => ''], 422);
    }

    public function changeStatus(Request $request)
    {
        $this->authorize('master-policy.perform', ['department', 'changeStatus']);
        $department = $this->department->find($request->get('id'));
        if ($department->is_active == 0) {
            $status = 1;
            $message = 'Department is published.';
        } else {
            $status = 0;
            $message = 'Department is unpublished.';
        }

        $this->department->changeStatus($department->id, $status);
        $this->department->update($department->id, array('updated_by' => auth()->id()));
        $updated = $this->department->find($request->get('id'));
        if ($multiContent = $this->department->where('existing_record_id', $department->id)->first()) {
            $this->department->changeStatus($multiContent->id, $status);
        }
        return response()->json(['status' => 'ok', 'message' => $message, 'response' => $updated], 200);
    }

    public function sort(Request $request)
    {
        $preferred_language = $this->preferredLanguage;
        $exploded = explode('&', str_replace('item[]=', '', $request->order));

        for ($i = 0; $i < count($exploded); $i++) {
            $this->department->update($exploded[$i], ['display_order' => $i]);
        }

        $other_posts = $this->department->whereIn('existing_record_id', $exploded)->get();
        $other_posts_grouped_language = $other_posts->groupBy('language_id');
        $english_sort = $this->department->where('language_id', $preferred_language)->pluck('display_order', 'id')->toArray();

        if ($other_posts_grouped_language) {
            foreach ($other_posts_grouped_language as $language => $records) {
                foreach ($records as $record) {
                    $this->department->update($record->id, ['display_order' => $english_sort[$record->existing_record_id]]);
                }
            }
        }
        return json_encode(['status' => 'success', 'value' => 'Successfully reordered.'], 200);
    }
}
