<?php
/**
 * Created by PhpStorm.
 * User: Amit Shrestha <amitshrestha221@gmail.com> <https://amitstha.com.np>
 * Date: 10/2/18
 * Time: 2:24 PM
 */

namespace App\Repositories;

use App\Models\Article;
use App\Repositories\Repository;
use DB;

class ArticleRepository extends Repository
{
    public function __construct(Article $article)
    {
        $this->model = $article;
    }
    public function create($input){
        $article = $this->model->create($input);
        $article->seo()->create(['page'=>$article->title]);
        return true;
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
            $article = $this->model->findOrFail($id);
           if($article){
                $article->seo()->delete();
                $article->delete();
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
}