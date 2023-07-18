<?php

declare(strict_types=1);

namespace App\Services\Sale;

use App\Models\Sale;
use App\Repositories\Kendaraan\KendaraanIRepository;
use App\Repositories\Sale\SaleIRepository;
use App\Services\BaseService;
use Carbon\Carbon;
use Exception;
use Illuminate\Contracts\Validation\Validator as ValidationValidator;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class SaleService extends BaseService implements SaleIService
{
    protected $saleRepository;
    protected $kendaraanRepository;

    public function __construct(SaleIRepository $saleRepository, KendaraanIRepository $kendaraanRepository)
    {
        $this->saleRepository = $saleRepository;
        $this->kendaraanRepository = $kendaraanRepository;
    }

    public function create(array $data): ?Sale
    {
        $validator = $this->validate($data);

        if ($validator->fails()) {
            $errors = $validator->errors()->all();
            $this->errorLog(
                "Sales Validation failed. Errors: " . implode(', ', $errors),
                $this->saleRepository->getModelName()
            );
            return null;
        }

        try {
            $data['user_id'] = Auth::user()->id;

            if (!isset($data['tanggal_jual'])) {
                $data['tanggal_jual'] = Carbon::now();
            } else {
                $data['tanggal_jual'] = Carbon::parse($data['tanggal_jual']);
            }

            $sale = $this->saleRepository->create($data);
            return $sale;
        } catch (ModelNotFoundException $exception) {
            $this->errorLog(
                "Error on Create Sales, Data: " . json_encode($data),
                $this->saleRepository->getModelName()
            );
            return null;
        } catch (Exception $e) {
            $this->errorLog(
                "",
                $this->saleRepository->getModelName()
            );
            return (object)["Error" => "Exception."];
        }
    }

    public function createWithBulk(array $data)
    {
        $salesData = [];
        foreach ($data['body'] as $k => $v) {
            $singleData = array_merge(
                $data['head'],
                [
                    "tanggal_jual" => Carbon::now(),
                    "kendaraan_id" => $v['kendaraan_id'],
                    "nomor_rangka" => $v['nomor_rangka'],
                    "nomor_mesin" => $v['nomor_mesin'],
                ],
            );

            $created = $this->create($singleData);

            $kendaraan = $this->kendaraanRepository->getByIdWithRelation($v['kendaraan_id']);
            if ($kendaraan->motor == null) {
                unset($kendaraan->motor);
            }

            $created->kendaraan = $kendaraan;
            $salesData[] = $created;
        }
        return $salesData;
    }

    public function getById(string $id): Sale
    {
        try {
            $sale = $this->saleRepository->getById($id);
            return $sale;
        } catch (ModelNotFoundException $exception) {
            $this->errorLog(
                "Error on getById Sales, ID: $id",
                $this->saleRepository->getModelName()
            );
            return null;
        } catch (Exception $e) {
            $this->errorLog(
                "",
                $this->saleRepository->getModelName()
            );
            return (object)["Error" => "Exception."];
        }
    }

    public function getByIdWithRelation(string $id): ?Sale
    {
        try {
            return $this->saleRepository->getByIdWithRelation($id);
        } catch (ModelNotFoundException $exception) {
            $this->errorLog(
                "Error on getByIdWithRelation Sales, ID: $id",
                $this->saleRepository->getModelName()
            );
            return null;
        } catch (Exception $e) {
            $this->errorLog(
                "",
                $this->saleRepository->getModelName()
            );
            return (object)["Error" => "Exception."];
        }
    }

    private function validate(array $data): ValidationValidator
    {
        $rules = [
            'kendaraan_id' => 'required',
            'nama_pembeli' => 'required',
            'nomor_rangka' => 'required',
            'nomor_mesin' => 'required',
            'catatan_lain' => 'required'
        ];

        return Validator::make($data, $rules);
    }
}
