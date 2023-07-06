<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        // $this->call(UsersTableSeeder::class);
        DB::table('users')->insert([
            [
                'name' => 'Admin',
                'phone' => '0711223344',
                'password' => bcrypt('bbssvvdd'),
                'created_at' => date('Y-m-d H:i:s'),
                'role' => 'admin',
            ],
            [
                'name' => 'Staff',
                'phone' => '0722334455',
                'password' => bcrypt('ppxxwwdd'),
                'created_at' => date('Y-m-d H:i:s'),
                'role' => 'staff',
            ],
        ]);
    }
}
