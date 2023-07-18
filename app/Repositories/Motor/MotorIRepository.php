<?php

namespace App\Repositories\Motor;

interface MotorIRepository
{
    public function getAll();

    public function create(array $data);

    public function getById(string $id);

    public function update(string $id, array $data);

    public function delete(string $id);

    public function getModelName();
}
