<?php

declare(strict_types=1);

namespace App\Services\Mobil;

use App\Models\Mobil;
use App\Repositories\Kendaraan\KendaraanRepository;
use App\Repositories\Mobil\MobilIRepository;
use App\Services\BaseService;
use Exception;
use Illuminate\Contracts\Validation\Validator as ValidationValidator;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\Validator;

class MobilService extends BaseService implements MobilIService
{
    protected $mobilRepository;
    protected $kendaraanRepository;

    public function __construct(MobilIRepository $mobilRepository, KendaraanRepository $kendaraanRepository)
    {
        $this->mobilRepository = $mobilRepository;
        $this->kendaraanRepository = $kendaraanRepository;
    }

    public function getAllData(): Collection
    {
        try {
            $data = $this->mobilRepository->getAll();
            return $data;
        } catch (Exception $e) {
            $this->errorLog(
                "",
                $this->mobilRepository->getModelName()
            );
            return (object)["Error" => "Exception."];
        }
    }

    public function createWithKendaraan(array $data): ?Mobil
    {
        $validator = $this->validate($data);

        if ($validator->fails()) {
            $errors = $validator->errors()->all();
            $this->errorLog(
                "createWithKendaraan Validation failed. Errors: " . implode(', ', $errors),
                $this->mobilRepository->getModelName()
            );
            return null;
        }

        try {
            $kendaraanData = $this->kendaraanOnly($data);
            $kendaraan = $this->kendaraanRepository->create($kendaraanData);

            $motorData = $this->mobilOnly($data);
            $motorData['kendaraan_id'] = $kendaraan->id;
            $motor = $this->mobilRepository->create($motorData);

            return $this->getById($motor->id);
        } catch (ModelNotFoundException $exception) {
            $this->errorLog(
                "Error on createWithKendaraan, Data: " . json_encode($data),
                $this->mobilRepository->getModelName()
            );
            return null;
        } catch (Exception $e) {
            $this->errorLog(
                "",
                $this->mobilRepository->getModelName()
            );
            return (object)["Error" => "Exception."];
        }
    }

    public function getById(string $id): ?Mobil
    {
        try {
            $motor = $this->mobilRepository->getById($id);
            $kendaraan = $this->kendaraanRepository->getById($motor->kendaraan_id);
            $motor->kendaraan = $kendaraan;
            return $motor;
        } catch (ModelNotFoundException $exception) {
            $this->errorLog(
                ">> id: $id",
                $this->mobilRepository->getModelName()
            );
            return null;
        }
    }

    public function update(string $id, array $data): ?Mobil
    {
        $validator = $this->validate($data);

        if ($validator->fails()) {
            $errors = $validator->errors()->all();
            $this->errorLog(
                "createWithKendaraan Validation failed. Errors: " . implode(', ', $errors),
                $this->mobilRepository->getModelName()
            );
            return null;
        }

        try {
            $motorData = $this->mobilOnly($data);
            $motor = $this->mobilRepository->update($id, $motorData);

            $kendaraanData = $this->kendaraanOnly($data);
            $this->kendaraanRepository->update($motor->kendaraan_id, $kendaraanData);

            return $this->getById($motor->id);
        } catch (ModelNotFoundException $exception) {
            $this->errorLog(
                "on Update. ID: $id, Data: " . json_encode($data),
                $this->mobilRepository->getModelName()
            );
            return null;
        } catch (Exception $e) {
            $this->errorLog(
                "",
                $this->mobilRepository->getModelName()
            );
            return (object)["Error" => "Exception."];
        }
    }

    public function delete(string $id): bool
    {
        try {
            $this->mobilRepository->delete($id);
            return true;
        } catch (ModelNotFoundException $exception) {
            $this->errorLog(
                "Error on Delete Motor ID: $id",
                $this->mobilRepository->getModelName()
            );
            return false;
        } catch (Exception $e) {
            $this->errorLog(
                "",
                $this->mobilRepository->getModelName()
            );
            return false;
        }
    }

    private function validate($data): ValidationValidator
    {
        $rules = [
            'mesin' => 'required',
            'kapasitas_penumpang' => 'required',
            'tipe' => 'required',
            'tahun_keluaran' => 'required',
            'warna' => 'required',
            'harga' => 'required',
        ];

        return Validator::make($data, $rules);
    }
}
