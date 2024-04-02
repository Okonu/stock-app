<?php

namespace Database\Factories;

use App\Models\Garden;
use App\Models\Owner;
use Illuminate\Database\Eloquent\Factories\Factory;

class GardenFactory extends Factory
{
    protected $model = Garden::class;
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'owner_id' => Owner::factory(),
            'name' => $this->faker->word,
        ];
    }
}
