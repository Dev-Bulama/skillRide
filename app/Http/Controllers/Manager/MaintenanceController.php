<?php

namespace App\Http\Controllers\Manager;

use App\Http\Controllers\Controller;
use App\Models\MaintenanceRecord;
use App\Models\Vehicle;
use App\Services\MaintenanceService;
use Illuminate\Http\Request;

class MaintenanceController extends Controller
{
    public function __construct(protected MaintenanceService $maintenanceService) {}

    public function index(Request $request)
    {
        $vehicleIds = Vehicle::where('assigned_manager_id', auth()->id())->pluck('id');

        $query = MaintenanceRecord::whereIn('vehicle_id', $vehicleIds)->with(['vehicle', 'reportedBy']);

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        if ($request->filled('priority')) {
            $query->where('priority', $request->priority);
        }

        $records = $query->orderByDesc('created_at')->paginate(15)->withQueryString();
        return view('manager.maintenance.index', compact('records'));
    }

    public function show(MaintenanceRecord $record)
    {
        $record->load(['vehicle', 'reportedBy', 'media']);
        return view('manager.maintenance.show', compact('record'));
    }

    public function approve(MaintenanceRecord $record)
    {
        $this->maintenanceService->updateStatus($record, 'completed');
        return back()->with('success', 'Maintenance record approved.');
    }

    public function schedules()
    {
        $vehicleIds = Vehicle::where('assigned_manager_id', auth()->id())->pluck('id');
        $upcoming = MaintenanceRecord::whereIn('vehicle_id', $vehicleIds)
            ->whereIn('status', ['pending', 'in_progress'])
            ->with('vehicle')
            ->orderBy('next_due_date')
            ->get();
        return view('manager.maintenance.schedules', compact('upcoming'));
    }
}
