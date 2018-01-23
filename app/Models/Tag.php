<?php

namespace App\models;

use Illuminate\Database\Eloquent\Model;
use Prettus\Repository\Contracts\Transformable;
use Prettus\Repository\Traits\TransformableTrait;
use App\Traits\Admin\ActionButtonTrait;

class Tag extends Model
{
    use TransformableTrait;
    use ActionButtonTrait;
    protected $table = 'Tag';
    protected $fillable = [
        'title',
    ];

    //   public function belongsToAdmins ()
    // {
    //     return $this->belongsTo(Admin::class,'user_id','id');
    // }

    //   public function belongsToManyTag()
    // {
    //     return $this->belongsToMany('Article','article_tag','article_id','article_id');
    // }

}
