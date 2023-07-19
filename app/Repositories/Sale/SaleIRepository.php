<?php

namespace App\Repositories\Sale;

interface SaleIRepository
{
    public function getById(string $id);

    public function create(array $data);

    public function getByIdWithRelation(string $id);

    public function countSaleByKendaraan(string $id);

    public function countAllSaleByKendaraan();

    public function getModelName();
}
