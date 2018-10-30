<?php

use Illuminate\Database\Seeder;

class BrandTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        foreach (['camaieu', 'timberland', 'vans'] as $name) {
            $target       = new App\Models\Target();
            $target->id   = str_slug($name);
            $target->name = $name;
            $target->makeVisible($target->getHidden());

            DB::table('brands')->insert($target->toArray());
        }

        foreach (factory(App\Models\Brand::class, 5)->make() as $data) {
            $data->makeVisible($data->getHidden());

            DB::table('brands')->insert($data->toArray());
        }
    }
}
