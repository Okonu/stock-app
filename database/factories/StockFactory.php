<?php

namespace Database\Factories;

use App\Models\Stock;
use Illuminate\Database\Eloquent\Factories\Factory;

class StockFactory extends Factory
{
    protected $model = Stock::class;
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'user_id' => User::factory(),
            'warehouse_id' => Warehouse::factory(),
            'warehouse_bay_id' => WarehouseBay::factory(),
            'owner_id' => Owner::factory(),
            'grade_id' => Grade::factory(),
            'package_id' => Package::factory(),
            'invoice' => $this->faker->randomNumber(5),
            'qty' => $this->faker->randomNumber(),
            'year' => $this->faker->year(),
            'remark' => $this->faker->sentence,
            'mismatch' => $this->faker->boolean(),
            'comment' => $this->faker->sentence,
        ];

    }
}
