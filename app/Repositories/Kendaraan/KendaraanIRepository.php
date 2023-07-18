<?php

namespace App\Repositories\Kendaraan;

interface KendaraanIRepository
{
    public function create(array $data);

    public function getById(string $id);

    public function update(string $id, array $data);
}
