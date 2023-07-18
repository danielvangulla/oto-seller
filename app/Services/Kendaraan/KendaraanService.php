<?php

declare(strict_types=1);

namespace App\Services\Kendaraan;

use App\Models\Kendaraan;
use App\Repositories\Kendaraan\KendaraanIRepository;
use App\Services\BaseService;
use Exception;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class KendaraanService extends BaseService implements KendaraanIService
{
    protected $kendaraanRepository;

    public function __construct(KendaraanIRepository $kendaraanRepository)
    {
        $this->kendaraanRepository = $kendaraanRepository;
    }

    public function getAllWithRelation(): Collection
    {
        $kendaraan = $this->kendaraanRepository->getAllWithRelation();

        foreach ($kendaraan as $k => $v) {
            $v = $this->filterRemoveNull($v);

            if (!$v) {
                unset($kendaraan[$k]);
            }
        }

        return $kendaraan;
    }

    public function getByIdWithRelation(string $id): ?Kendaraan
    {
        try {
            $kendaraan = $this->kendaraanRepository->getByIdWithRelation($id);

            return $this->filterRemoveNull($kendaraan);
        } catch (ModelNotFoundException $exception) {
            $this->errorLog(
                "Error on getByIdWithRelation, ID: $id",
                $this->kendaraanRepository->getModelName()
            );
            return null;
        } catch (Exception $e) {
            $this->errorLog(
                "",
                $this->kendaraanRepository->getModelName()
            );
            return (object)["Error" => "Exception."];
        }
    }

    public function createKendaraan(array $data): Kendaraan
    {
        return $this->kendaraanRepository->create($data);
    }

    public function updateKendaraan(string $id, array $data): Kendaraan
    {
        return $this->kendaraanRepository->update($id, $data);
    }

    private function filterRemoveNull($data): ?Kendaraan
    {
        if ($data->mobil == null and $data->motor == null) {
            return null;
        }

        if ($data->mobil == null) {
            unset($data->mobil);
        }

        if ($data->motor == null) {
            unset($data->motor);
        }

        return $data;
    }
}
