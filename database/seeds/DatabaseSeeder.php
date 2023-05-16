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
                'phone' => '0705274875',
                'password' => bcrypt('wanderlust'),
                'created_at' => date('Y-m-d H:i:s'),
                'role' => 'admin',
            ],
            [
                'name' => 'Staff',
                'phone' => '0715274875',
                'password' => bcrypt('wanderlust'),
                'created_at' => date('Y-m-d H:i:s'),
                'role' => 'staff',
            ],
        ]);
    }
}
