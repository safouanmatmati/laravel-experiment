<?php

namespace App\Helpers;

use Illuminate\Support\Facades\DB;

class Product {

    /**
     * Return preview directory path depending on product.
     *
     * @param  string $product_id
     * @return string
     */
    public static function get_preview_dir_path($product_id)
    {
        return sprintf(
            '%s/%s',
            trim(config('PREVIEW_DIR', 'products/previews/'), '/'),
            $product_id
        );
    }
}
