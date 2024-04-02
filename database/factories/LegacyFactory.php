<?php

namespace Database\Factories;

use App\Models\Legacy;
use Illuminate\Database\Eloquent\Factories\Factory;

class LegacyFactory extends Factory
{
    protected $model = Legacy::class;
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'garden' => $this->faker->word,
            'invoice' => $this->faker->randomNumber(5),
            'qty' => $this->faker->randomNumber(),
            'grade' => $this->faker->word,
            'package' => $this->faker->word,
            'mismatch' => $this->faker->boolean(),
            'comment' => $this->faker->sentence,
        ];
    }
}
