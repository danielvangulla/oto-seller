<?php

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
    protected $motorRepository;
    protected $kendaraanRepository;

    public function __construct(MobilIRepository $motorRepository, KendaraanRepository $kendaraanRepository)
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

    public function createWithKendaraan(array $data): ?Mobil
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

            $motorData = $this->mobilOnly($data);
            $motorData['kendaraan_id'] = $kendaraan->id;
            $motor = $this->motorRepository->create($motorData);

            return $this->motorRepository->getById($motor->id);
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

    public function getById(string $id): ?Mobil
    {
        try {
            return $this->motorRepository->getById($id);
        } catch (ModelNotFoundException $exception) {
            $this->errorLog(
                ">> id: $id",
                $this->motorRepository->getModelName()
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
                $this->motorRepository->getModelName()
            );
            return null;
        }

        try {
            $motorData = $this->mobilOnly($data);
            $motor = $this->motorRepository->update($id, $motorData);

            $kendaraanData = $this->kendaraanOnly($data);
            $this->kendaraanRepository->update($motor->kendaraan_id, $kendaraanData);

            return $this->motorRepository->getById($motor->id);
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
            'kapasitas_penumpang' => 'required',
            'tipe' => 'required',
            'tahun_keluaran' => 'required',
            'warna' => 'required',
            'harga' => 'required',
        ];

        return Validator::make($data, $rules);
    }
}
