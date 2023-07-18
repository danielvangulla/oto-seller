<?php

declare(strict_types=1);

namespace App\Services\Motor;

use App\Models\Motor;
use App\Repositories\Kendaraan\KendaraanRepository;
use App\Repositories\Motor\MotorIRepository;
use App\Services\BaseService;
use Exception;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\Validator;
use Illuminate\Contracts\Validation\Validator as ValidationValidator;
use Illuminate\Database\Eloquent\Collection;

class MotorService extends BaseService implements MotorIService
{
    protected $motorRepository;
    protected $kendaraanRepository;

    public function __construct(MotorIRepository $motorRepository, KendaraanRepository $kendaraanRepository)
    {
        $this->motorRepository = $motorRepository;
        $this->kendaraanRepository = $kendaraanRepository;
    }

    public function getAllData(): Collection
    {
        try {
            $data = $this->motorRepository->getAll();
            return $data;
        } catch (Exception $e) {
            $this->errorLog(
                "",
                $this->motorRepository->getModelName()
            );
            return (object)["Error" => "Exception."];
        }
    }

    public function createWithKendaraan(array $data): ?Motor
    {
        $validator = $this->validate($data);

        if ($validator->fails()) {
            $errors = $validator->errors()->all();
            $this->errorLog(
                "createWithKendaraan Validation failed. Errors: " . implode(', ', $errors),
                $this->motorRepository->getModelName()
            );
            return null;
        }

        try {
            $kendaraanData = $this->kendaraanOnly($data);
            $kendaraan = $this->kendaraanRepository->create($kendaraanData);

            $motorData = $this->motorOnly($data);
            $motorData['kendaraan_id'] = $kendaraan->id;
            $motor = $this->motorRepository->create($motorData);

            return $this->getById($motor->id);
        } catch (ModelNotFoundException $exception) {
            $this->errorLog(
                "Error on createWithKendaraan, Data: " . json_encode($data),
                $this->motorRepository->getModelName()
            );
            return null;
        } catch (Exception $e) {
            $this->errorLog(
                "",
                $this->motorRepository->getModelName()
            );
            return (object)["Error" => "Exception."];
        }
    }

    public function getById(string $id): ?Motor
    {
        try {
            $motor = $this->motorRepository->getById($id);
            $kendaraan = $this->kendaraanRepository->getById($motor->kendaraan_id);
            $motor->kendaraan = $kendaraan;
            return $motor;
        } catch (ModelNotFoundException $exception) {
            $this->errorLog(
                ">> id: $id",
                $this->motorRepository->getModelName()
            );
            return null;
        }
    }

    public function update(string $id, array $data): ?Motor
    {
        $validator = $this->validate($data);

        if ($validator->fails()) {
            $errors = $validator->errors()->all();
            $this->errorLog(
                "createWithKendaraan Validation failed. Errors: " . implode(', ', $errors),
                $this->motorRepository->getModelName()
            );
            return null;
        }

        try {
            $motorData = $this->motorOnly($data);
            $motor = $this->motorRepository->update($id, $motorData);

            $kendaraanData = $this->kendaraanOnly($data);
            $this->kendaraanRepository->update($motor->kendaraan_id, $kendaraanData);

            return $this->getById($motor->id);
        } catch (ModelNotFoundException $exception) {
            $this->errorLog(
                "on Update. ID: $id, Data: " . json_encode($data),
                $this->motorRepository->getModelName()
            );
            return null;
        } catch (Exception $e) {
            $this->errorLog(
                "",
                $this->motorRepository->getModelName()
            );
            return (object)["Error" => "Exception."];
        }
    }

    public function delete(string $id): bool
    {
        try {
            $this->motorRepository->delete($id);
            return true;
        } catch (ModelNotFoundException $exception) {
            $this->errorLog(
                "Error on Delete Motor ID: $id",
                $this->motorRepository->getModelName()
            );
            return false;
        } catch (Exception $e) {
            $this->errorLog(
                "",
                $this->motorRepository->getModelName()
            );
            return false;
        }
    }

    private function validate($data): ValidationValidator
    {
        $rules = [
            'mesin' => 'required',
            'tipe_suspensi' => 'required',
            'tipe_transmisi' => 'required',
            'tahun_keluaran' => 'required',
            'warna' => 'required',
            'harga' => 'required',
        ];

        return Validator::make($data, $rules);
    }
}
