<?php

namespace Database\Factories;

use App\Models\Motor;
use Illuminate\Database\Eloquent\Factories\Factory;

class MotorFactory extends Factory
{
    protected $model = Motor::class;

    public function definition()
    {
        return [
            'mesin' => $this->faker->word,
            'tipe_suspensi' => $this->faker->word,
            'tipe_transmisi' => $this->faker->word,
        ];
    }
}
