<?php

namespace Tests;

use App\Models\User;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use Illuminate\Foundation\Testing\WithFaker;

abstract class TestCase extends BaseTestCase
{
    use CreatesApplication, WithFaker;

    public $token;
    protected $urlKendaraan = '/api/kendaraan/';
    protected $urlAddStock = '/api/add-stock/';
    protected $urlMotor = '/api/motor/';
    protected $urlMobil = '/api/mobil/';
    protected $urlSales = '/api/sales/';

    public function user()
    {
        return [
            'name' => 'Unit Test',
            'email' => 'unit@test.com',
            'password' => 'password',
            'password_confirmation' => 'password',
        ];
    }

    public function motor()
    {
        return array_merge(
            [
                "mesin" => $this->faker->word(),
                "tipe_suspensi" => $this->faker->word(),
                "tipe_transmisi" => $this->faker->word(),
            ],
            $this->kendaraan()
        );
    }

    public function motorResponse()
    {
        return [
            'code',
            'data' => [
                '_id',
                'mesin',
                'tipe_suspensi',
                'tipe_transmisi',
                'kendaraan_id',
                'kendaraan' => [
                    '_id',
                    'tahun_keluaran',
                    'warna',
                    'harga',
                ],
            ],
        ];
    }

    public function mobil()
    {
        return array_merge(
            [
                "mesin" => $this->faker->word(),
                "kapasitas_penumpang" => $this->faker->numberBetween(4, 8),
                "tipe" => $this->faker->word(),
            ],
            $this->kendaraan()
        );
    }

    public function mobilResponse()
    {
        return [
            'code',
            'data' => [
                '_id',
                'mesin',
                'kapasitas_penumpang',
                'tipe',
                'kendaraan_id',
                'kendaraan' => [
                    '_id',
                    'tahun_keluaran',
                    'warna',
                    'harga',
                ],
            ],
        ];
    }

    public function kendaraan()
    {
        return [
            "tahun_keluaran" => $this->faker->year(),
            "warna" => $this->faker->colorName,
            "harga" => $this->faker->numberBetween(5000000000, 200000000)
        ];
    }

    protected function setUp(): void
    {
        parent::setUp();

        $user = $this->user();
        $loginResponse = $this->postJson('/api/register', $user);
        $loginResponse->assertStatus(200);
        $loginData = $loginResponse->json('data');
        $this->token = $loginData['access_token'] ?? null;
    }

    protected function tearDown(): void
    {
        $this->cleanDummyUser();

        parent::tearDown();
    }

    public function getToken()
    {
        if (!$this->token) {
            $user = $this->user();
            $loginResponse = $this->postJson('/api/login', $user);
            $loginResponse->assertStatus(200);
            $loginData = $loginResponse->json('data');
            $this->token = $loginData['access_token'] ?? null;
        }
        return $this->token;
    }

    public function cleanDummyUser()
    {
        $user = $this->user();
        $data = User::where('email', $user['email'])->first();
        if ($data) {
            $data->delete();
        }
    }
}
