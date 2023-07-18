<?php

namespace App\Http\Controllers;

use App\Services\Kendaraan\KendaraanIService;
use Illuminate\Http\Request;

class KendaraanController extends Controller
{
    protected $kendaraanService;

    public function __construct(KendaraanIService $kendaraanService)
    {
        $this->kendaraanService = $kendaraanService;
    }

    public function index()
    {
        $data = $this->kendaraanService->getAllWithRelation();

        return response()->json([
            'code' => 200,
            'data' => $data
        ], 200);
    }

    public function show($id)
    {
        $data = $this->kendaraanService->getByIdWithRelation($id);

        if (!$data) {
            return response()->json([
                'code' => 404,
                'message' => 'Kendaraan is not found.',
            ], 404);
        }

        return response()->json([
            'code' => 200,
            'data' => $data
        ], 200);
    }
}
