<?php

/**
 * Created by PhpStorm.
 * User: Amit Shrestha <amitshrestha221@gmail.com> <https://amitstha.com.np>
 * Date: 9/20/18
 * Time: 12:08 PM
 */

namespace App\Repositories;

use App\Classes\SiteMapGenerator;
use App\Models\Content;
use DB;

class ContentRepository extends Repository
{
    public function __construct(Content $content, SiteMapGenerator $site_map)
    {
        $this->model = $content;
        $this->site_map = $site_map;
    }

    public function create($input)
    {
        $content = $this->model->create($input);
        // $content->seo()->create(['page' => $content->title]);
        // $url_route = route('page.content.index', $content->slug);
        // $this->site_map->generate($url_route);
        return $content;
    }

    public function update($id, $inputs)
    {
        $update = $this->model->findOrFail($id);
        $update->fill($inputs)->save();
        return $update;
    }


    public function destroy($id)
    {
        DB::beginTransaction();
        try {
            $content = $this->model->findOrFail($id);
            if ($content) {
                // $content->seo()->delete();
                $content->delete();
                DB::commit();
                return true;
            }
            DB::commit();
            return false;
        } catch (\Illuminate\Database\QueryException $e) {
            DB::rollback();
            return false;
        }
    }

    public function childContents($id)
    {
        return $this->model->where('parent_id', $id)->where('is_active', '1')->orderBy('created_at', 'desc')->get();
    }
}
