<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class MotorTest extends TestCase
{
    use WithFaker;

    public function testGetAllMotor()
    {
        $token = $this->getToken();

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $token,
        ])->getJson($this->urlMotor);
        $response->assertStatus(200);

        if ($response->json('data')) {
            $response->assertStatus(200)
                ->assertJsonStructure([
                    'code',
                    'data' => [
                        '*' => [
                            '_id',
                            'mesin',
                            'tipe_suspensi',
                            'tipe_transmisi',
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

    public function testCreateMotor()
    {
        $token = $this->getToken();
        $data = $this->motor();

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $token,
        ])->postJson($this->urlMotor, $data);
        $response->assertStatus(201);

        $response->assertJsonStructure(
            $this->motorResponse()
        );
    }

    public function testGetMotorById()
    {
        $token = $this->getToken();
        $data = $this->motor();

        $createMotor = $this->withHeaders([
            'Authorization' => 'Bearer ' . $token,
        ])->postJson($this->urlMotor, $data);
        $createMotor->assertStatus(201);

        $motorId = $createMotor->json('data._id');
        $this->assertNotNull($motorId, 'Motor ID is null');

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $token,
        ])->getJson($this->urlMotor . $motorId);
        $response->assertStatus(200);

        $response->assertJsonStructure(
            $this->motorResponse()
        );
    }

    public function testMotorCreateFindByIdUpdateDelete()
    {
        $token = $this->getToken();
        $data = $this->motor();

        $create = $this->withHeaders([
            'Authorization' => 'Bearer ' . $token,
        ])->postJson($this->urlMotor, $data);
        $create->assertStatus(201);

        $motorId = $create->json('data._id');
        $this->assertNotNull($motorId, 'Motor ID is null');

        $findById = $this->withHeaders([
            'Authorization' => 'Bearer ' . $token,
        ])->getJson($this->urlMotor . $motorId);
        $findById->assertStatus(200);
        $findById->assertJsonStructure(
            $this->motorResponse()
        );

        $update = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->token,
        ])->putJson($this->urlMotor . $motorId, $data);
        $update->assertStatus(200);

        $delete = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->token,
        ])->deleteJson($this->urlMotor . $motorId);
        $delete->assertStatus(200);
    }
}
