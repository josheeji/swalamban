<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Repositories\MembersRepository;
use App\Repositories\NewsRepository;
use App\Repositories\TeamRepository;

class MemberController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    protected $team;

    public function __construct(
        TeamRepository $team
    ) {
        $this->team = $team;
    }
    public function index()
    {
        $members = $this->team->where('is_active', '1')->orderBy('display_order', 'asc')->get();
        return view('member.index')
            ->withMembers($members);
    }

    public function member()
    {
        // $members = $this->team->where('is_active', '1')->orderBy('display_order', 'asc')->get();
        return view('member.member');
        // ->withMembers($members);
    }
    public function student()
    {
        // $members = $this->team->where('is_active', '1')->orderBy('display_order', 'asc')->get();
        return view('member.student');
        // ->withMembers($members);
    }
    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $member = $this->team->findOrFail($id);
        $news = $this->news->orderBy('created_at', 'desc')->where('is_active', '1')->take(5)->get();
        return view('member.show')
            ->withMember($member)
            ->withNews($news);
    }
}