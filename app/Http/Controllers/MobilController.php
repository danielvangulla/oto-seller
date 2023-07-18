<?php

namespace App\Http\Controllers;

use App\Services\Mobil\MobilIService;
use Illuminate\Http\Request;

class MobilController extends Controller
{
    protected $service;

    public function __construct(MobilIService $service)
    {
        $this->service = $service;
    }

    public function index()
    {
        $data = $this->service->getAllData();

        return response()->json([
            'code' => 200,
            'data' => $data
        ], 200);
    }

    public function store(Request $request)
    {
        $data = $this->service->createWithKendaraan($request->all());

        if (!$data) {
            return response()->json([
                'code' => 400,
                'message' => 'Failed to create Mobil.',
            ], 400);
        }

        return response()->json([
            'code' => 201,
            'data' => $data
        ], 201);
    }

    public function show(string $id)
    {
        $data = $this->service->getById($id);

        if (!$data) {
            return response()->json([
                'code' => 404,
                'message' => 'Mobil is not found.',
            ], 404);
        }

        return response()->json([
            'code' => 200,
            'data' => $data
        ], 200);
    }

    public function update(Request $request, string $id)
    {
        $data = $this->service->update($id, $request->all());

        if (!$data) {
            return response()->json([
                'code' => 400,
                'message' => 'Failed to update Mobil.',
            ], 400);
        }

        return response()->json([
            'code' => 201,
            'data' => $data
        ], 201);
    }

    public function destroy(string $id)
    {
        $data = $this->service->delete($id);

        if (!$data) {
            return response()->json([
                'code' => 400,
                'message' => 'Failed to delete Mobil.'
            ], 400);
        }

        return response()->json([
            'code' => 200,
            'message' => 'Mobil deleted successfully.'
        ], 200);
    }
}
