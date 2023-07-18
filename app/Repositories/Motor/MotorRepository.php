<?php

namespace App\Repositories\Motor;

use App\Models\Motor;
use App\Repositories\BaseRepository;
use App\Repositories\Motor\MotorIRepository;

class MotorRepository extends BaseRepository implements MotorIRepository
{
    protected $model;

    public function __construct(Motor $model)
    {
        $this->model = $model;
    }
}
