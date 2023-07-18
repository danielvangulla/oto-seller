<?php

namespace Database\Factories;

use App\Models\Kendaraan;
use Illuminate\Database\Eloquent\Factories\Factory;

class KendaraanFactory extends Factory
{
    protected $model = Kendaraan::class;

    public function definition()
    {
        return [
            'tahun_keluaran' => $this->faker->year,
            'warna' => $this->faker->colorName,
            'harga' => $this->faker->numberBetween(1000, 100000),
        ];
    }
}
