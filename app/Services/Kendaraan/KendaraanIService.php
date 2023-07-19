<?php

namespace App\Services\Kendaraan;

interface KendaraanIService
{
    public function getAllWithRelation();

    public function getByIdWithRelation(string $id);

    public function addStock(string $id, array $data);
}
