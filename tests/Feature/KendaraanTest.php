<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class KendaraanTest extends TestCase
{
    public function testGetKendaraanByIdAndAddStok()
    {
        $token = $this->getToken();
        $dataMotor = $this->motor();

        $motorResp = $this->withHeaders([
            'Authorization' => 'Bearer ' . $token,
        ])->postJson($this->urlMotor, $dataMotor);
        $motorResp->assertStatus(201);

        $kendaraan_id = $motorResp->json('data')['kendaraan_id'];
        $data = [
            'qty' => 1,
        ];

        $kendaraanResp = $this->withHeaders([
            'Authorization' => 'Bearer ' . $token,
        ])->getJson($this->urlKendaraan . $kendaraan_id);
        $kendaraanResp->assertStatus(200);

        $kendaraanResp->assertJsonStructure([
            'code',
            'data' => [
                'tahun_keluaran',
                'warna',
                'harga',
                'qty',
            ]
        ]);

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $token,
        ])->patchJson($this->urlAddStock . $kendaraan_id, $data);
        $response->assertStatus(200);

        $response->assertJsonStructure([
            'code',
            'data' => [
                'tahun_keluaran',
                'warna',
                'harga',
                'qty',
            ]
        ]);
    }

    public function testGetStokReport()
    {
        $token = $this->getToken();

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $token,
        ])->getJson($this->urlKendaraan);

        $response->assertStatus(200);

        if ($response->json('data')) {
            $response->assertStatus(200)
                ->assertJsonStructure([
                    'code',
                    'data' => [
                        '*' => [
                            '_id',
                            'tahun_keluaran',
                            'warna',
                            'harga',
                            'qty',
                            'terjual',
                            'sisa_stok'
                        ],
                    ],
                ]);
        }
    }
}
