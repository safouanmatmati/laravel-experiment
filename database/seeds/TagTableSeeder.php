<?php

use Illuminate\Database\Seeder;

class TagTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        foreach (['top', 'casual', 'street'] as $name) {
            $tag       = new App\Models\Target();
            $tag->id   = $name;
            $tag->name = $name;
            $tag->makeVisible($tag->getHidden());

            DB::table('tags')->insert($tag->toArray());
        }
    }
}
