<?php

declare(strict_types=1);

namespace App\Repositories\Kendaraan;

use App\Models\Kendaraan;
use App\Repositories\BaseRepository;

class KendaraanRepository implements KendaraanIRepository
{
    protected $model;

    public function __construct(Kendaraan $model)
    {
        $this->model = $model;
    }

    public function create(array $data): Kendaraan
    {
        return $this->model->create($data);
    }

    public function getById(string $id): Kendaraan
    {
        return $this->model->findOrFail($id);
    }

    public function update(string $id, array $data): Kendaraan
    {
        $kendaraan = $this->getById($id);
        $kendaraan->update($data);
        return $kendaraan;
    }
}
