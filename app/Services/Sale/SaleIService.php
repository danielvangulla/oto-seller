<?php

namespace App\Services\Sale;

interface SaleIService
{
    public function getById(string $id);

    public function create(array $data);

    public function createWithBulk(array $data);

    public function getByIdWithRelation(string $id);
}
