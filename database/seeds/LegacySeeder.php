<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Legacy;

class LegacySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Legacy::factory()->count(10)->create();
    }
}
