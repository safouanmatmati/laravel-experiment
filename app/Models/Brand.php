<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Product;

class Brand extends Model
{
    public $incrementing = false;

    public function products()
    {
        return $this->hasMany(Product::class);
    }
}
