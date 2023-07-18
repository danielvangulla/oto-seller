<?php

namespace App\Repositories\Mobil;

interface MobilIRepository
{
    public function getAll();

    public function create(array $data);

    public function getById(string $id);

    public function update(string $id, array $data);

    public function delete(string $id);

    public function getModelName();
}
