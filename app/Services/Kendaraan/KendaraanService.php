<?php

declare(strict_types=1);

namespace App\Services\Kendaraan;

use App\Repositories\Kendaraan\KendaraanIRepository;

class KendaraanService implements KendaraanIService
{
    protected $KendaraanIRepository;

    public function __construct(KendaraanIRepository $KendaraanIRepository)
    {
        $this->KendaraanIRepository = $KendaraanIRepository;
    }

    public function createKendaraan(array $data)
    {
        return $this->KendaraanIRepository->create($data);
    }

    public function updateKendaraan(string $id, array $data): Object
    {
        return $this->KendaraanIRepository->update($id, $data);
    }
}
