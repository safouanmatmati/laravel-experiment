<?php

use Illuminate\Database\Seeder;

class TargetTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        foreach (['man', 'woman', 'children'] as $name) {
            $target       = new App\Models\Target();
            $target->id   = $name;
            $target->name = $name;
            $target->makeVisible($target->getHidden());

            DB::table('targets')->insert($target->toArray());
        }
    }
}
