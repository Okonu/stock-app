<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call([
            GardenSeeder::class,
            GradeSeeder::class,
            OwnerSeeder::class,
            WarehouseSeeder::class,
            PackageSeeder::class,
            UserSeeder::class,
        ]);
    }
}
