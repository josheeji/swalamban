<?php

namespace App\Http\Controllers;

use App\Repositories\BonusCategoryRepository;
use App\Repositories\BonusRepository;
use Illuminate\Http\Request;

class BonusController extends Controller
{

    protected $category, $bonus;

    public function __construct(BonusRepository $bonus, BonusCategoryRepository $category)
    {
        $this->bonus = $bonus;
        $this->category = $category;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $categories =  $this->category->where('is_active', 1)->orderBy('title', 'asc')->get();
        return view('bonus.index', ['categories' => $categories]);
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
        //
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
        //
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

    public function search(Request $request)
    {
        if (!$request->ajax()) {
            abort(404);
        }
        $category_id = $request->post('category_id');
        $name = strtoupper(str_replace(' ', '', $request->post('name')));
        $boid = $request->post('boid');

        $bonus = $this->bonus->where('category_id', $category_id)
            ->where('searchable_name', $name)
            ->where('boid', $boid);
        if ($request->has('fathers_name') && $request->post('fathers_name') != '') {
            $fathersname = strtoupper(str_replace(' ', '', $request->post('fathers_name')));
            $bonus = $bonus->where('searchable_fathers_name', $fathersname);
        }
        if ($request->has('shareholder_no') && $request->post('shareholder_no') != '') {
            $bonus = $bonus->where('shareholder_no', $request->post('shareholder_no'));
        }
        $bonus = $bonus->where('is_active', 1)
            ->first();
        return view('bonus.search', ['bonus' => $bonus]);
    }

    public function dev()
    {
        $categories =  $this->category->where('is_active', 1)->orderBy('title', 'asc')->get();
        return view('bonus.dev', ['categories' => $categories]);
    }
}
