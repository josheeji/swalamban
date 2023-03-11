<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PostBlock extends Model
{
    protected $fillable = [
        'post_id',
        'title',
        'subtitle',
        'image',
        'description',
        'link',
        'link_text',
        'link_target',
        'display_order',
        'status',
        'is_active',
        'created_by',
        'updated_by',
    ];

    public function post()
    {
        return $this->belongsTo(Post::class, 'post_id');
    }

    public function existingRecord()
    {
        return $this->belongsTo(PostBlock::class, 'existing_record_id');
    }

    public function createdBy()
    {
        return $this->belongsTo(Admin::class, 'created_by');
    }

    public function updatedBy()
    {
        return $this->belongsTo(Admin::class, 'updated_by');
    }

    public function multiBlock($id, $languageID)
    {
        return PostBlock::where('existing_record_id', $id)->where('language_id', $languageID)->first();
    }
}
