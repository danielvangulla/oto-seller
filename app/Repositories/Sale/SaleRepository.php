<?php

namespace App\Repositories\Sale;

use App\Models\Sale;
use App\Repositories\BaseRepository;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\DB;

class SaleRepository extends BaseRepository implements SaleIRepository
{
    protected $model;

    public function __construct(Sale $model)
    {
        $this->model = $model;
    }

    public function getByIdWithRelation(string $id): ?Sale
    {
        return $this->model->with('kendaraan')->findOrFail($id);
    }

    public function countSaleByKendaraan(string $id): int
    {
        return $this->model->where('kendaraan_id', $id)->count();
    }

    public function countAllSaleByKendaraan(): Collection
    {
        return $this->model->raw(function ($collection) {
            return $collection->aggregate([
                [
                    '$group' => [
                        '_id' => '$kendaraan_id',
                        'terjual' => ['$sum' => 1],
                    ],
                ]
            ]);
        });
    }
}
