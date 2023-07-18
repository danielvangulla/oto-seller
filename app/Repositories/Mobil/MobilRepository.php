<?php

namespace App\Repositories\Mobil;

use App\Models\Mobil;
use App\Repositories\BaseRepository;

class MobilRepository extends BaseRepository implements MobilIRepository
{
    protected $model;

    public function __construct(Mobil $model)
    {
        $this->model = $model;
    }
}
