<?php

namespace Database\Seeders;

use App\Models\Warehouse;
use App\Models\WarehouseBay;
use Illuminate\Database\Seeder;

class WarehouseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Warehouse::factory()
            ->count(5)
            ->create()
            ->each(function ($warehouse) {
                WarehouseBay::factory()
                    ->count(3)
                    ->create(['warehouse_id' => $warehouse->id]);
            });
    }
}
