<?php

namespace App\Http\Controllers;

use App\Helper\ConstantHelper;
use App\Helper\Helper;
use App\Helper\PageHelper;
use App\Models\NewsCategory;
use App\Repositories\NewsRepository;
use App\Repositories\PostRepository;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Spatie\SchemaOrg\Schema;

class ProjectController extends Controller
{

    protected $post, $preferredLanguage;

    public function __construct(PostRepository $post)
    {
        $this->post = $post;
    }

    protected function locale()
    {
        return Helper::locale();
    }

    public function index()
    {
        $projects = $this->post->where('is_active', 1)
            ->where('language_id', $this->locale())
            ->orderBy('display_order', 'desc')
            ->orderBy('created_at', 'desc')
            ->get();

        return view('project.index')->withProjects($projects);
    }

    public function show($slug)
    {
        $project = $this->post->where('slug', $slug)->where('language_id', $this->locale())
            ->where('is_active', 1)->first();
        if (!$project) {
            abort('404');
        }
        $latest = $this->post->where('is_active', 1)->whereNotIn('id', [$project->id])
            ->where('language_id', $this->locale())
            ->limit(5)->get();
        switch ($project->layout) {
            case 3:
                $view = 'project.show-right';
                break;
            case 2:
                $view = 'project.show-left';
                break;
            default:
                $view = 'project.show';
                break;
        }
        return view($view, compact('project','latest'));
    }
}
