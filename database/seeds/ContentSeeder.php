<?php

use Illuminate\Database\Seeder;

class ContentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Add content for every table
        $this->call(TargetTableSeeder::class);
        $this->call(BrandTableSeeder::class);
        $this->call(TagTableSeeder::class);
        $this->call(ProductTableSeeder::class);

        // Define relations between data

        // Add tags to targets
        $tag_target = [
            'top'    => 'woman',
            'casual' => 'man',
            'street' => 'children'
        ];

        foreach ($tag_target as $tag => $target) {
            $data            = new App\Models\TagTarget();
            $data->tag_id    = str_slug($tag);
            $data->target_id = str_slug($target);
            $data->makeVisible($data->getHidden());

            DB::table('tag_target')->insert($data->toArray());
        }

        // Add tags to products
        $tag_product = [
            'top'    => 'chemisier imprimé',
            'casual' => 'pantalon beige',
            'street' => 'basket blue'
        ];

        foreach ($tag_product as $tag => $product) {
            $data             = new App\Models\TagProduct();
            $data->tag_id     = str_slug($tag);
            $data->product_id = str_slug($product);
            $data->makeVisible($data->getHidden());

            DB::table('tag_product')->insert($data->toArray());
        }
    }
}
