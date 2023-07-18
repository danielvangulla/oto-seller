<?php

declare(strict_types=1);

namespace App\Repositories;

use Jenssegers\Mongodb\Eloquent\Model;

class BaseRepository
{
    protected $model;

    public function __construct(Model $model)
    {
        $this->model = $model;
    }

    public function getModelName(): string
    {
        return get_class($this->model);
    }

    public function getAll(): Object
    {
        return $this->model->with('kendaraan')->whereNotNull('kendaraan_id')->get();
    }

    public function create(array $data): Model
    {
        return $this->model->create($data);
    }

    public function getById(string $id): Model
    {
        return $this->model->with('kendaraan')->findOrFail($id);
    }

    public function update(string $id, array $data): Model
    {
        $motor = $this->getById($id);
        $motor->update($data);
        return $motor;
    }

    public function delete(string $id): void
    {
        $motor = $this->getById($id);
        $motor->delete();
    }
}
