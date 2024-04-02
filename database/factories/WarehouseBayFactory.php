<?php

namespace Database\Factories;

use App\Models\WarehouseBay;
use App\Models\Warehouse;
use Illuminate\Database\Eloquent\Factories\Factory;

class WarehouseBayFactory extends Factory
{
    protected $model = WarehouseBay::class;
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'warehouse_id' => Warehouse::factory(),
            'name' => $this->faker->word,
        ];
    }
}
