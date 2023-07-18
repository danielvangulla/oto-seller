<?php

namespace App\Services\Motor;

interface MotorIService
{
    public function getAllData();

    public function createWithKendaraan(array $data);

    public function getById(string $id);

    public function update(string $id, array $data);

    public function delete(string $id);
}
