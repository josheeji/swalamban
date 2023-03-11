<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\Admin\ArticleStoreRequest;
use App\Http\Requests\Admin\ArticleUpdateRequest;
use App\Repositories\ArticleRepository;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Str;

class ArticleController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    protected $article;
    protected $type = [
        "Link"    => "Link",
        "Text"  =>"Text"
    ];

    public function __construct(ArticleRepository $article)
    {
        $this->article = $article;
        auth()->shouldUse('admin');
    }

    public function index()
    {
        $this->authorize('master-policy.perform',['article', 'view']);
        $perpage = '100';
        $articles  = $this->article->orderBy('display_order', 'asc')->orderBy('created_at', 'desc')->paginate($perpage);
        return view('admin.article.index')->withArticles($articles);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $this->authorize('master-policy.perform',['article', 'add']);
        $type = $this->type;
        return view('admin.article.create')->withType($type);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(ArticleStoreRequest $request)
    {
        $this->authorize('master-policy.perform',['article', 'add']);
        $data = $request->except(['image']);
        if($request->get('image')){
            $saveName = sha1(date('YmdHis').Str::random(3));
            $image = $request->get('image');
            $image = str_replace('data:image/png;base64','',$image);
            $image= str_replace('','+',$image);
            $imageData= base64_decode($image);
            $data['image'] = 'article/'.$saveName.'.png';
            Storage::put($data['image'],$imageData);
        }
        $data['type'] = $request['type'] == 'Link' ? 2 : 1;
        $data['is_active'] = isset($request['is_active']) ? 1 : 0;
        if($this->article->create($data)){
            return redirect()->route('admin.article.index')->with('flash_notice','Article Created SuccessFully');

        }
        return redirect()->route()->withInput()->with('flash_notice','Article can not be Create');
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
        $this->authorize('master-policy.perform',['article', 'edit']);
        $article = $this->article->find($id);
        $type = $this->type;
        return view('admin.article.edit')->withType($type)->withArticle($article);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(ArticleUpdateRequest $request, $id)
    {
        $this->authorize('master-policy.perform',['article', 'edit']);
        $article = $this->article->find($id);
        $data = $request->except(['image','_token','_method']);
        if($request->get('image')){
            $saveName = sha1(date('YmdHis').Str::random(3));
            $image = $request->get('image');
            $image = str_replace('data:image/png;base64','',$image);
            $image= str_replace('','+',$image);
            $imageData= base64_decode($image);
            $data['image'] = 'room/'.$saveName.'.png';
            Storage::put($data['image'],$imageData);
            Storage::delete(  $article->image);
        }
        $data['type'] = $request['type'] == 'Link' ? 2 : 1;
        $data['is_active'] = isset($request['is_active']) ? 1 : 0;

        if($this->article->update(  $article->id,$data))
        {
            return redirect()->route('admin.article.index')->with('flash_notice','Acticle Updated SuccessFully');

        }
        return redirect()->back()->withInput()->with('flash_notice','Acticle can not be  Updated ');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy( Request $request, $id)
    {
        $this->authorize('master-policy.perform',['article', 'delete']);
        $article = $this->article->find($request->get('id'));
        if($this->article->destroy($article->id)){
            $message = 'Article deleted successfully.';
            return response()->json(['status' => 'ok', 'message' => $message], 200);
        }
        return response()->json(['status' => 'error', 'message' => ''], 200);
    }
    public function changeStatus(Request $request)
    {
        $this->authorize('master-policy.perform',['article', 'changeStatus']);
        $article = $this->article->find($request->get('id'));
        if ($article->is_active == 0) {
            $status = '1';
            $message = 'Activity with title "' . $article->title . '" is published.';
        } else {
            $status = '0';
            $message = 'activity with title "' . $article->title . '" is unpublished.';
        }
        $this->article->changeStatus($article->id, $status);
        $this->article->update($article->id,['is_active' => $status]);
        $updated = $this->article->find($request->get('id'));
        return response()->json(['status' => 'ok', 'message' => $message, 'response' => $updated], 200);
    }

      public function sort(Request $request){
        $exploded = explode('&', str_replace('item[]=', '', $request->order));
        for ($i=0; $i < count($exploded) ; $i++) {
            $this->article->update($exploded[$i], ['display_order' => $i]);
        }
        return json_encode(['status' => 'success', 'value' => 'Successfully reordered.'], 200);
    }
}
