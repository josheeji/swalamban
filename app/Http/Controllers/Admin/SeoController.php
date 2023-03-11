<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\Admin\SeoRequest;
use App\Repositories\SeoRepository;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class SeoController extends Controller
{

    public $title = 'Seo';

    protected $seo;

    public function __construct(
        SeoRepository $seo
    )
    {
        $this->seo = $seo;
        auth()->shouldUse('admin');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $seo = $this->seo->get();
        return view('admin.seo.index')
            ->withSeos($seo)
            ->withTitle($this->title)
            ->withAdmin(auth()->user());
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $title = 'Add Seo';
        return view('admin.seo.create')
            ->withTitle($title);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(SeoRequest $request)
    {
        if ($this->seo->create($request->all())) {
            return redirect()->route('admin.seos.index')
                ->with('flash_notice', 'Seo Created.')
                ->with('status', 'success');
        }
        return redirect()->back()
            ->with('flash_error', 'Oops! Something went wrong.')
            ->with('status', 'failed')
            ->withInput();

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
        $title = $this->title;
        $seo = $this->seo->find($id);
        if ($seo) {
            return view('admin.seo.edit')
                ->withSeo($seo)
                ->withTitle($title);
        }
        return abort(404);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function update(SeoRequest $request, $id)
    {
        $inputs = $request->all();
        $seo = $this->seo->find($id);
        if ($this->seo->update($seo->id, $inputs)) {
            return redirect()->route('admin.seos.index')
                ->with('flash_notice', 'Seo Updated.')
                ->with('status', 'success');
        }
        return redirect()->back()
            ->with('flash_error', 'Oops! Something went wrong.')
            ->with('status', 'failed')
            ->withInput();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function destroy($id)
    {
        $seo = $this->seo->find($id);
        $this->authorize('delete', $seo);

        if ($this->seo->destroy($seo->id)) {
            $message = 'Your seo is deleted successfully.';
            return response()->json(['status' => 'ok', 'message' => $message], 200);
        }
        return response()->json(['status' => 'error', 'message' => ''], 422);
    }
}
