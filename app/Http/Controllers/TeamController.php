<?php

namespace App\Http\Controllers;

use App\Helper\Helper;
use App\Helper\SettingHelper;
use App\Models\MenuItems;
use App\Repositories\TeamCategoryRepository;
use App\Repositories\TeamRepository;
use Artesaos\SEOTools\Facades\OpenGraph;
use Artesaos\SEOTools\Facades\SEOMeta;
use Illuminate\Http\Request;

class TeamController extends Controller
{

    protected $preferredLanguage, $teamCategory, $team;

    public  function __construct(TeamCategoryRepository $teamCategory, TeamRepository $team)
    {
        $this->teamCategory = $teamCategory;
        $this->team = $team;
        $this->preferredLanguage = Helper::locale();
    }


    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // $category = $this->teamCategory->where('is_active', 1)->where('language_id', $this->preferredLanguage)->orderBy('display_order', 'asc')->whereNotIn('slug', ['board-of-directors'])->firstOrFail();
        // $teams = $this->team->where('is_active', 1)->where('language_id', $this->preferredLanguage)->orderBy('display_order', 'asc')->where('category_id', $category->id)->get();
        $categories = $this->teamCategory->where('is_active', 1)->where('language_id', $this->preferredLanguage)->orderBy('display_order', 'asc')->get();
        $menu = MenuItems::where('link_url', request()->path())->first();
        return view('team.index', ['team' => $categories, 'menu' => $menu]);
    }

    public function show($id)
    {
        $this->preferredLanguage = Helper::locale();
        $team = $this->team->where('id', $id)->where('is_active', 1)->firstOrFail();
        // dd($team);
        if ($team->description == '<p><br></p>') {
            return redirect()->back();
        } else {
            return view('team.show', ['team' => $team]);
        }
    }

    public function managementTeam()
    {
        $category = $this->teamCategory->where('is_active', 1)->where('language_id', $this->preferredLanguage)->orderBy('display_order', 'asc')->where('slug', 'management-team')->firstOrFail();
        $teams = $this->team->where('is_active', 1)->where('language_id', $this->preferredLanguage)->orderBy('display_order', 'asc')->where('category_id', $category->id)->get();
        return view('team.index', ['teams' => $teams]);
    }

    public function bod()
    {
        $category = $this->teamCategory->where('is_active', 1)->where('language_id', Helper::locale())->orderBy('display_order', 'asc')->where('slug', 'board-of-directors')->firstOrFail();
        $teams = $this->team->where('is_active', 1)->where('position', 0)->where('language_id', Helper::locale())->orderBy('display_order', 'asc')->where('category_id', $category->id)->get();
        $chairman = $this->team->where('is_active', 1)->where('position', 1)->where('language_id', Helper::locale())->orderBy('display_order', 'asc')->where('category_id', $category->id)->first();
        return view('bod.index', ['team' => $teams, 'category' => $category, 'chairman' => $chairman]);
    }
    public function categoryWiseTeam($slug)
    {
        $category = $this->teamCategory->where('is_active', 1)->where('language_id', $this->preferredLanguage)->orderBy('display_order', 'asc')->where('slug', $slug)->firstOrFail();
        $team = $this->team->where('position', 0)->where('is_active', 1)->where('language_id', $this->preferredLanguage)->orderBy('display_order', 'asc')->where('category_id', $category->id)->get();
        $chairman = $this->team->where('is_active', 1)->where('position', 1)->where('language_id', Helper::locale())->orderBy('display_order', 'asc')->where('category_id', $category->id)->first();
        $menu = MenuItems::where('link_url', request()->path())
            ->orWhere('link_url', '/' . request()->path())->first();

        SEOMeta::setDescription($menu->title ??  'Swabalamban Laghubitta Bittiya Sanstha Ltd.');

        OpenGraph::setDescription($menu->title ??   'Swabalamban Laghubitta Bittiya Sanstha Ltd.');
        OpenGraph::setTitle($menu->title ?? SettingHelper::setting('site_title'));
        OpenGraph::addImages([isset($menu->image) ? asset('storage/' . @$menu->image) : asset('swabalamban/images/titlebg.jpg')]);
        OpenGraph::setUrl(url()->current());

        return view('team.index', ['category' => $category, 'team' => $team, 'menu' => $menu, 'chairman' => $chairman]);
    }
}
