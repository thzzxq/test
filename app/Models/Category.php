<?php

namespace App\models;

use Illuminate\Database\Eloquent\Model;
use Prettus\Repository\Contracts\Transformable;
use Prettus\Repository\Traits\TransformableTrait;
use App\Traits\Admin\ActionButtonTrait;

class Category extends Model
{
    use TransformableTrait;
    use ActionButtonTrait;
    protected $fillable = [
        'id',
        'parent_id',
        'title',
        'order',
        'isopen',
        'updated_at',
        'created_at'
    ];

     public function user()
    {
        return $this->hasOne('App\Phone');
    }

}
