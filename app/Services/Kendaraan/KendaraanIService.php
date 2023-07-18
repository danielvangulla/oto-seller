<?php

namespace App\Services\Kendaraan;

interface KendaraanIService
{
    public function createKendaraan(array $data);

    public function updateKendaraan(string $id, array $data);

    public function getAllWithRelation();

    public function getByIdWithRelation(string $id);
}
