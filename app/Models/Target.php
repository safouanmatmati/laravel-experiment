<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Tag;

class Target extends Model
{
    public $incrementing = false;

    public function tags()
    {
        return $this->belongsToMany(Tag::class, 'tag_target');
    }
}
