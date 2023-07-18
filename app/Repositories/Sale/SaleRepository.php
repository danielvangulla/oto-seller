<?php

namespace App\Repositories\Sale;

use App\Models\Sale;
use App\Repositories\BaseRepository;

class SaleRepository extends BaseRepository implements SaleIRepository
{
    protected $model;

    public function __construct(Sale $model)
    {
        $this->model = $model;
    }

    public function getByIdWithRelation(string $id): ?Sale
    {
        return $this->model->with('kendaraan')->find($id);
    }
}
