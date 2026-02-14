<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\GpsTrackingService;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class GpsController extends Controller
{
    public function __construct(protected GpsTrackingService $gpsService) {}

    public function updateLocation(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'vehicle_id' => 'required|exists:vehicles,id',
            'latitude' => 'required|numeric|between:-90,90',
            'longitude' => 'required|numeric|between:-180,180',
            'speed' => 'nullable|numeric|min:0',
            'heading' => 'nullable|numeric|between:0,360',
            'altitude' => 'nullable|numeric',
            'accuracy' => 'nullable|numeric|min:0',
        ]);

        $location = $this->gpsService->recordLocation(
            $validated['vehicle_id'],
            auth()->id(),
            $validated
        );

        return response()->json(['success' => true, 'data' => $location]);
    }

    public function getVehicleLocation(int $vehicleId): JsonResponse
    {
        $location = $this->gpsService->getLatestLocation($vehicleId);
        return response()->json(['success' => true, 'data' => $location]);
    }

    public function getActiveVehicles(): JsonResponse
    {
        $vehicles = $this->gpsService->getAllActiveLocations();
        return response()->json(['success' => true, 'data' => $vehicles]);
    }

    public function getVehicleTrail(Request $request, int $vehicleId): JsonResponse
    {
        $from = Carbon::parse($request->input('from', now()->startOfDay()));
        $to = Carbon::parse($request->input('to', now()));
        $trail = $this->gpsService->getVehicleTrail($vehicleId, $from, $to);
        return response()->json(['success' => true, 'data' => $trail]);
    }
}
