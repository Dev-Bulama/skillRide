<?php

namespace App\Http\Controllers\Rider;

use App\Http\Controllers\Controller;
use App\Models\MaintenanceRecord;
use App\Services\MaintenanceService;
use Illuminate\Http\Request;

class MaintenanceController extends Controller
{
    public function __construct(protected MaintenanceService $maintenanceService) {}

    public function index()
    {
        $rider = auth()->user();
        $vehicleId = $rider->riderProfile?->assigned_vehicle_id;

        $records = MaintenanceRecord::where('reported_by', $rider->id)
            ->orWhere(fn($q) => $vehicleId ? $q->where('vehicle_id', $vehicleId) : null)
            ->with('vehicle')
            ->orderByDesc('created_at')
            ->paginate(15);

        return view('rider.maintenance.index', compact('records'));
    }

    public function create()
    {
        return view('rider.maintenance.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'type' => 'required|in:routine,repair,emergency,inspection',
            'description' => 'required|string|max:2000',
            'priority' => 'required|in:low,medium,high,critical',
            'media' => 'required|array|min:1',
            'media.*' => 'file|mimes:jpg,jpeg,png,mp4,mov,avi|max:20480',
        ]);

        $rider = auth()->user();
        $vehicleId = $rider->riderProfile?->assigned_vehicle_id;

        $record = $this->maintenanceService->createRecord([
            'vehicle_id' => $vehicleId,
            'reported_by' => $rider->id,
            'type' => $validated['type'],
            'description' => $validated['description'],
            'priority' => $validated['priority'],
            'status' => 'pending',
        ]);

        foreach ($request->file('media') as $file) {
            $this->maintenanceService->addMedia($record, $file, $rider);
        }

        return redirect()->route('rider.maintenance.index')->with('success', 'Maintenance report submitted.');
    }

    public function show(MaintenanceRecord $record)
    {
        abort_if($record->reported_by !== auth()->id(), 403);
        $record->load('media', 'vehicle');
        return view('rider.maintenance.show', compact('record'));
    }
}
