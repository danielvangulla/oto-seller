<?php

namespace App\Http\Controllers;

use App\Services\Motor\MotorIService;
use Illuminate\Http\Request;

class MotorController extends Controller
{
    protected $service;

    public function __construct(MotorIService $service)
    {
        $this->service = $service;
    }

    public function index()
    {
        $data = $this->service->getAllData();

        return response()->json([
            'code' => 200,
            'data' => $data
        ]);
    }

    public function store(Request $request)
    {
        $data = $this->service->createWithKendaraan($request->all());

        if (!$data) {
            return response()->json([
                'code' => 400,
                'message' => 'Failed to create Motor.',
            ], 404);
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
                'code' => 400,
                'message' => 'Motor is not found.',
            ], 404);
        }

        return response()->json([
            'code' => 200,
            'data' => $data
        ]);
    }

    public function update(Request $request, string $id)
    {
        $data = $this->service->update($id, $request->all());

        if (!$data) {
            return response()->json([
                'code' => 400,
                'message' => 'Failed to update Motor.',
            ], 404);
        }

        return response()->json([
            'code' => 200,
            'data' => $data
        ]);
    }

    public function destroy(string $id)
    {
        $data = $this->service->delete($id);

        if (!$data) {
            return response()->json([
                'code' => 400,
                'message' => 'Failed to delete Motor.'
            ]);
        }

        return response()->json([
            'code' => 200,
            'message' => 'Motor deleted successfully.'
        ]);
    }
}
