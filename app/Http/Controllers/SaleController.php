<?php

namespace App\Http\Controllers;

use App\Services\Kendaraan\KendaraanIService;
use App\Services\Sale\SaleIService;
use Illuminate\Http\Request;

class SaleController extends Controller
{
    protected $saleService;
    protected $kendaraanService;

    public function __construct(SaleIService $saleService, KendaraanIService $kendaraanService)
    {
        $this->saleService = $saleService;
        $this->kendaraanService = $kendaraanService;
    }

    public function index()
    {
        $data = $this->saleService->getAllByKendaraan();

        return response()->json([
            'code' => 200,
            'data' => $data
        ], 200);
    }

    public function store(Request $request)
    {
        $isBulk = $request->is_bulk; // If True ? Bulk : Single
        $requestData = $request->data;

        $data = null;
        if ($isBulk) {
            // Bulk Insert
            $data = $this->saleService->createWithBulk($requestData);
        } else {
            // Single Insert
            $data = $this->saleService->create($requestData);
        }

        if (!$data) {
            return response()->json([
                'code' => 400,
                'message' => 'Failed to create Sales.',
            ], 400);
        }

        return response()->json([
            'code' => 201,
            'data' => $data
        ], 201);
    }

    public function show($id)
    {
        $data = $this->saleService->getById($id);

        if (!$data) {
            return response()->json([
                'code' => 404,
                'message' => 'Data Sale is not found.',
            ], 404);
        }

        $kendaraan = $this->kendaraanService->getByIdWithRelation($data->kendaraan_id);
        if (!$kendaraan) {
            return response()->json([
                'code' => 404,
                'message' => 'Relationship with Kendaraan is not found.',
            ], 404);
        }

        $data->kendaraan = $kendaraan;

        return response()->json([
            'code' => 200,
            'data' => $data
        ], 200);
    }
}
