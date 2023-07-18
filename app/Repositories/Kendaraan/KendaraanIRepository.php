<?php

namespace App\Repositories\Kendaraan;

interface KendaraanIRepository
{
    public function create(array $data);

    public function getById(string $id);

    public function update(string $id, array $data);

    public function getModelName();

    public function getAllWithRelation();

    public function getByIdWithRelation(string $id);
}
