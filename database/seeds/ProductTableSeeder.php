<?php

use Illuminate\Database\Seeder;

class ProductTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Define products (and related brands)
        $products = [
            'camaieu'    => 'chemisier imprimé',
            'timberland' => 'pantalon beige',
            'vans'       => 'basket blue'
        ];

        foreach ($products as $brand => $name) {
            $product              = new App\Models\Product();
            $product->id          = str_slug($name);
            $product->name        = $name;
            $product->description = 'A short description';
            $product->price       = rand(1, 100);
            $product->brand_id    = str_slug($brand);

            $product->makeVisible($product->getHidden());

            DB::table('products')->insert($product->toArray());
        }
    }
}
