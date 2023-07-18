<?php

namespace App\Services\Mobil;

interface MobilIService
{
    public function getAllData();

    public function createWithKendaraan(array $data);

    public function getById(string $id);

    public function update(string $id, array $data);

    public function delete(string $id);
}
