<?php
/**
 * Created by PhpStorm.
 * User: Amit Shrestha <amitshrestha221@gmail.com> <https://amitstha.com.np>
 * Date: 10/2/18
 * Time: 2:24 PM
 */

namespace App\Repositories;

use App\Models\ArticleComment;
use DB;

class ArticleCommentRepository extends Repository
{
     public function __construct(ArticleComment  $article_comment)
    {
        $this->model = $article_comment;
    }

}