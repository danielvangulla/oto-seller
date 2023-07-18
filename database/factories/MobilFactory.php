<?php

namespace Database\Factories;

use App\Models\Mobil;
use Illuminate\Database\Eloquent\Factories\Factory;

class MobilFactory extends Factory
{
    protected $model = Mobil::class;

    public function definition()
    {
        return [
            'mesin' => $this->faker->word,
            'kapasitas_penumpang' => $this->faker->randomNumber(2),
            'tipe' => $this->faker->word,
        ];
    }
}
