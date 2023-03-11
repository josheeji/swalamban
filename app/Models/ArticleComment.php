<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;


class ArticleComment extends Model
{
    use SoftDeletes;

    protected $table = 'article_comments';


    protected $fillable = [

        'article_id',
        'full_name',
        'email',
        'comment',
        'is_active'
    ];
    public function getCreatedAt()
    {
        return $this->created_at ? $this->created_at->format('Y M d') : "";
    }
}
