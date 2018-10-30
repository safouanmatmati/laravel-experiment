<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Target;
use App\Models\Product;

class Tag extends Model
{
    public $incrementing = false;

    public function targets()
    {
        return $this->belongsToMany(Target::class, 'tag_target');
    }

    public function products()
    {
        return $this->belongsToMany(Product::class, 'tag_product');
    }
}
