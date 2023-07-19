<?php

declare(strict_types=1);

namespace App\Services\Kendaraan;

use App\Models\Kendaraan;
use App\Repositories\Kendaraan\KendaraanIRepository;
use App\Repositories\Sale\SaleIRepository;
use App\Services\BaseService;
use Exception;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\Validator;

class KendaraanService extends BaseService implements KendaraanIService
{
    protected $kendaraanRepository;
    protected $saleRepository;

    public function __construct(KendaraanIRepository $kendaraanRepository, SaleIRepository $saleRepository)
    {
        $this->kendaraanRepository = $kendaraanRepository;
        $this->saleRepository = $saleRepository;
    }

    public function getAllWithRelation()
    {
        $kendaraan = $this->kendaraanRepository->getAllWithRelation();
        $sales = $this->saleRepository->countAllSaleByKendaraan();

        foreach ($kendaraan as $k => $v) {
            $v->terjual = $this->getJual($sales, $v->id);
            $v->sisa_stok = $v->qty - $v->terjual;

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

    public function addStock(string $id, array $data): ?Kendaraan
    {
        $rules = [
            'qty' => 'required|numeric',
        ];

        $validator = Validator::make($data, $rules);

        if ($validator->fails()) {
            $errors = $validator->errors()->all();
            $this->errorLog(
                "createWithKendaraan Validation failed. Errors: " . implode(', ', $errors),
                $this->kendaraanRepository->getModelName()
            );
            return null;
        }

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

    private function getJual($sales, $kendaraan_id)
    {
        foreach ($sales as $k => $v) {
            if ($v->id === $kendaraan_id) {
                return $v->terjual;
            }
        }
    }
}
