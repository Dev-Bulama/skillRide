<?php

namespace App\Http\Controllers\Manager;

use App\Http\Controllers\Controller;
use App\Models\Route;
use App\Models\Vehicle;
use App\Services\GpsTrackingService;

class RouteController extends Controller
{
    public function __construct(protected GpsTrackingService $gpsService) {}

    public function index()
    {
        $routes = Route::where('assigned_manager_id', auth()->id())
            ->withCount('riderProfiles')
            ->get();
        return view('manager.routes.index', compact('routes'));
    }

    public function show(Route $route)
    {
        abort_if($route->assigned_manager_id !== auth()->id(), 403);
        $route->load('riderProfiles.user', 'riderProfiles.assignedVehicle');
        return view('manager.routes.show', compact('route'));
    }

    public function track()
    {
        $vehicles = Vehicle::where('assigned_manager_id', auth()->id())
            ->where('status', 'active')
            ->with('assignedRider:id,name,phone')
            ->get();

        $vehicleMapData = $vehicles->map(function ($v) {
            return [
                'id' => $v->id,
                'reg' => $v->registration_number,
                'lat' => $v->current_latitude,
                'lng' => $v->current_longitude,
                'rider' => $v->assignedRider?->name,
            ];
        })->values();

        return view('manager.routes.track', compact('vehicles', 'vehicleMapData'));
    }
}
