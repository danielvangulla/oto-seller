<?php

namespace App\Services;

use Illuminate\Support\Facades\Log;

class BaseService
{
    public function motorOnly(array $data): array
    {
        return [
            'mesin' => $data['mesin'],
            'tipe_suspensi' => $data['tipe_suspensi'],
            'tipe_transmisi' => $data['tipe_transmisi']
        ];
    }

    public function mobilOnly(array $data): array
    {
        return [
            'mesin' => $data['mesin'],
            'kapasitas_penumpang' => $data['kapasitas_penumpang'],
            'tipe' => $data['tipe']
        ];
    }

    public function kendaraanOnly(array $data): array
    {
        return [
            'tahun_keluaran' => $data['tahun_keluaran'],
            'warna' => $data['warna'],
            'harga' => $data['harga']
        ];
    }

    public function errorLog($log, $model): void
    {
        $trace = debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS, 2);
        $caller = $trace[1]['class'] . '::' . $trace[1]['function'];
        $errorMessage = "Error $caller on model $model";

        Log::error("$errorMessage $log");
    }
}
