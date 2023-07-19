<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class SalesTest extends TestCase
{
    public function testCreateSingleSales()
    {
        $token = $this->getToken();

        $dataMotor = $this->motor();

        $motorResp = $this->withHeaders([
            'Authorization' => 'Bearer ' . $token,
        ])->postJson($this->urlMotor, $dataMotor);
        $motorResp->assertStatus(201);

        $kendaraan_id = $motorResp->json('data')['kendaraan_id'];

        $data = [
            "data" => [
                "nama_pembeli" => $this->faker->name,
                "catatan_lain" => $this->faker->words(5),
                "kendaraan_id" => $kendaraan_id,
                "nomor_rangka" => $this->faker->randomNumber(9),
                "nomor_mesin" => $this->faker->randomNumber(9)
            ]
        ];

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $token,
        ])->postJson($this->urlSales, $data);
        $response->assertStatus(201);

        $response->assertJsonStructure([
            'code',
            'data' => [
                'tanggal_jual',
                'nama_pembeli',
                'kendaraan_id',
                'nomor_rangka',
                'nomor_mesin',
                'user_id',
            ]
        ]);
    }

    private function generateBulkBody($length, $kendaraan_id): array
    {
        $body = [];
        for ($i = 0; $i < $length; $i++) {
            $body[] = [
                "kendaraan_id" => $kendaraan_id,
                "nomor_rangka" => $this->faker->randomNumber(9),
                "nomor_mesin" => $this->faker->randomNumber(9)
            ];
        }
        return $body;
    }

    public function testCreateBulkSales()
    {
        $token = $this->getToken();

        $dataMotor = $this->motor();

        $motorResp = $this->withHeaders([
            'Authorization' => 'Bearer ' . $token,
        ])->postJson($this->urlMotor, $dataMotor);
        $motorResp->assertStatus(201);

        $kendaraan_id = $motorResp->json('data')['kendaraan_id'];

        $data = [
            "is_bulk" => true,
            "data" => [
                "head" => [
                    "nama_pembeli" => $this->faker->name,
                    "catatan_lain" => $this->faker->sentence(),
                ],
                "body" => $this->generateBulkBody(2, $kendaraan_id)
            ]
        ];

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $token,
        ])->postJson($this->urlSales, $data);
        $response->assertStatus(201);

        $response->assertJsonStructure([
            'code',
            'data' => [
                [
                    'tanggal_jual',
                    'nama_pembeli',
                    'kendaraan_id',
                    'nomor_rangka',
                    'nomor_mesin',
                    'user_id',
                    'kendaraan',
                ],
            ]
        ]);
    }

    public function testGetAllSales()
    {
        $token = $this->getToken();

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $token,
        ])->getJson($this->urlSales);

        $response->assertStatus(200);

        if ($response->json('data')) {
            $response->assertStatus(200)
                ->assertJsonStructure([
                    'code',
                    'data' => [
                        '*' => [
                            'kendaraan_id',
                            'tahun_keluaran',
                            'warna',
                            'harga',
                            'terjual',
                        ],
                    ],
                ]);
        }
    }
}
