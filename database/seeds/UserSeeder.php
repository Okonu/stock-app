<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        User::create([
            'name' => 'John Doe',
            'phone' => '0712345678',
            'email' => 'example@gmail.com',
            'password' => Hash::make('wanderlust'),
        ]);

        User::create([
            'name' => 'John Smith',
            'phone' => '0711234567',
            'email' => 'john.smith@gmail.com',
            'password' => Hash::make('wanderlust'),
        ]);

        User::create([
            'name' => 'Jane Doe',
            'phone' => '071123456',
            'email' => 'jdoe@example.com',
            'password' => Hash::make('wanderlust'),
        ]);
    }
}
