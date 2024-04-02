<?php

namespace Database\Seeders;

use App\Models\Garden;
use Illuminate\Database\Seeder;

class GardenSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Garden::factory()->count(10)->create();
    }
}
