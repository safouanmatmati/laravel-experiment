<?php

use Illuminate\Database\Seeder;

class AdminUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     * Create and store an admin user.
     * Retrieve values from .env file
     *
     * @return void
     */
    public function run()
    {
        $admin           = new App\Models\User();
        $admin->name     = config('ADMIN_NAME', 'admin');
        $admin->email    = config('ADMIN_EMAIL', 'admin.email@fake.com');
        $admin->password = bcrypt(config('ADMIN_PWD', 'admin_pwd'));
        $admin->is_admin = true;
        $admin->makeVisible($admin->getHidden());

        DB::table('users')->insert($admin->toArray());
    }
}
