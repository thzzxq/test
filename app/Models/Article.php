<?php

namespace App\models;

use Illuminate\Database\Eloquent\Model;
use Prettus\Repository\Contracts\Transformable;
use Prettus\Repository\Traits\TransformableTrait;
use App\Traits\Admin\ActionButtonTrait;

class Article extends Model
{
    use TransformableTrait;
    use ActionButtonTrait;
    protected $fillable = [
        'title',
        'abstract',
        'content',
        'content_md',
        'article_image',
        'article_status',
        'diplay_name',
        'comment_count',
        'author'
    ];

      public function belongsToAdmins ()
    {
        return $this->belongsTo(Admin::class,'user_id','id');
    }

      public function belongsToManyTag()
    {
        return $this->belongsToMany(Tag::class,'article_tag','article_id','tag_id');
    }

}


