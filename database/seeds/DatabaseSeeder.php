<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Add an admin
        $this->call(AdminUserSeeder::class);

        // Add some contents
        $this->call(ContentSeeder::class);
    }
}
