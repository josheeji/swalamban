<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BlogBlock extends Model
{
    protected $fillable = [
        'blog_id',
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
        'existing_record_id',
        'language_id'
    ];

    public function blog()
    {
        return $this->belongsTo(Blog::class, 'blog_id');
    }

    public function existingRecord()
    {
        return $this->belongsTo(BlogBlock::class, 'existing_record_id');
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
        return BlogBlock::where('existing_record_id', $id)->where('language_id', $languageID)->first();
    }
}
