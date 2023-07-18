<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Log;
use Tests\TestCase;

class MobilTest extends TestCase
{
    use WithFaker;

    public function testGetAllMobil()
    {
        $token = $this->getToken();

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $token,
        ])->getJson($this->urlMobil);

        $response->assertStatus(200);

        if ($response->json('data')) {
            $response->assertStatus(200)
                ->assertJsonStructure([
                    'code',
                    'data' => [
                        '*' => [
                            '_id',
                            'mesin',
                            'kapasitas_penumpang',
                            'tipe',
                            'kendaraan' => [
                                '_id',
                                'tahun_keluaran',
                                'warna',
                                'harga',
                            ],
                        ],
                    ],
                ]);
        }
    }

    public function testCreateMobil()
    {
        $token = $this->getToken();
        $data = $this->mobil();

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $token,
        ])->postJson($this->urlMobil, $data);

        $response->assertStatus(201);

        $response->assertJsonStructure(
            $this->mobilResponse()
        );
    }

    public function testGetMobilById()
    {
        $token = $this->getToken();
        $data = $this->mobil();

        $createMobil = $this->withHeaders([
            'Authorization' => 'Bearer ' . $token,
        ])->postJson($this->urlMobil, $data);

        $createMobil->assertStatus(201);

        $mobilId = $createMobil->json('data._id');
        $this->assertNotNull($mobilId, 'Mobil ID is null');

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $token,
        ])->getJson($this->urlMobil . $mobilId);

        $response->assertStatus(200);

        $response->assertJsonStructure(
            $this->mobilResponse()
        );
    }


    public function testMobilCreateFindByIdUpdateDelete()
    {
        $token = $this->getToken();
        $data = $this->mobil();

        $create = $this->withHeaders([
            'Authorization' => 'Bearer ' . $token,
        ])->postJson($this->urlMobil, $data);
        $create->assertStatus(201);

        $mobilId = $create->json('data._id');
        $this->assertNotNull($mobilId, 'Mobil ID is null');

        $findById = $this->withHeaders([
            'Authorization' => 'Bearer ' . $token,
        ])->getJson($this->urlMobil . $mobilId);
        $findById->assertStatus(200);
        $findById->assertJsonStructure(
            $this->mobilResponse()
        );

        $update = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->token,
        ])->putJson($this->urlMobil . $mobilId, $data);
        $update->assertStatus(201);

        $delete = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->token,
        ])->deleteJson($this->urlMobil . $mobilId);
        $delete->assertStatus(200);
    }
}
