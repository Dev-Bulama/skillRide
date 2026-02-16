<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Vehicle;
use App\Services\AuditService;
use App\Services\VehicleService;
use Illuminate\Http\Request;

class VehicleController extends Controller
{
    public function __construct(
        protected VehicleService $vehicleService,
        protected AuditService $auditService
    ) {}

    public function index(Request $request)
    {
        $query = Vehicle::with(['assignedRider', 'assignedManager']);

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('registration_number', 'like', "%{$search}%")
                    ->orWhere('make', 'like', "%{$search}%")
                    ->orWhere('model', 'like', "%{$search}%");
            });
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('type')) {
            $query->where('type', $request->type);
        }

        $vehicles = $query->orderByDesc('created_at')->paginate(15)->withQueryString();

        return view('admin.vehicles.index', compact('vehicles'));
    }

    public function create()
    {
        $riders = User::where('role', 'rider')->where('status', 'active')->get();
        $managers = User::where('role', 'manager')->where('status', 'active')->get();
        return view('admin.vehicles.create', compact('riders', 'managers'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'registration_number' => 'required|string|unique:vehicles',
            'type' => 'required|in:tricycle,keke_napep,motorcycle,car,bus,truck',
            'make' => 'required|string|max:100',
            'model' => 'required|string|max:100',
            'year' => 'nullable|integer|min:1990|max:' . (date('Y') + 1),
            'color' => 'nullable|string|max:50',
            'vin_number' => 'nullable|string|unique:vehicles',
            'status' => 'required|in:active,maintenance,inactive,decommissioned',
            'insurance_expiry' => 'nullable|date',
            'road_worthiness_expiry' => 'nullable|date',
            'purchase_price' => 'nullable|numeric|min:0',
            'assigned_rider_id' => 'nullable|exists:users,id',
            'assigned_manager_id' => 'nullable|exists:users,id',
            'notes' => 'nullable|string|max:1000',
        ]);

        $vehicle = $this->vehicleService->create($validated);

        $this->auditService->log('created', auth()->user(), Vehicle::class, $vehicle->id, null, $validated, 'Created vehicle ' . $vehicle->registration_number);

        return redirect()->route('admin.vehicles.index')->with('success', 'Vehicle created successfully.');
    }

    public function show(Vehicle $vehicle)
    {
        $vehicle->load(['assignedRider', 'assignedManager', 'owner', 'maintenanceRecords.reportedBy']);
        return view('admin.vehicles.show', compact('vehicle'));
    }

    public function edit(Vehicle $vehicle)
    {
        $riders = User::where('role', 'rider')->where('status', 'active')->get();
        $managers = User::where('role', 'manager')->where('status', 'active')->get();
        return view('admin.vehicles.edit', compact('vehicle', 'riders', 'managers'));
    }

    public function update(Request $request, Vehicle $vehicle)
    {
        $validated = $request->validate([
            'registration_number' => 'required|string|unique:vehicles,registration_number,' . $vehicle->id,
            'type' => 'required|in:tricycle,keke_napep,motorcycle,car,bus,truck',
            'make' => 'required|string|max:100',
            'model' => 'required|string|max:100',
            'year' => 'nullable|integer|min:1990|max:' . (date('Y') + 1),
            'color' => 'nullable|string|max:50',
            'vin_number' => 'nullable|string|unique:vehicles,vin_number,' . $vehicle->id,
            'status' => 'required|in:active,maintenance,inactive,decommissioned',
            'insurance_expiry' => 'nullable|date',
            'road_worthiness_expiry' => 'nullable|date',
            'purchase_price' => 'nullable|numeric|min:0',
            'assigned_rider_id' => 'nullable|exists:users,id',
            'assigned_manager_id' => 'nullable|exists:users,id',
            'notes' => 'nullable|string|max:1000',
        ]);

        $old = $vehicle->toArray();
        $this->vehicleService->update($vehicle, $validated);

        $this->auditService->log('updated', auth()->user(), Vehicle::class, $vehicle->id, $old, $validated, 'Updated vehicle ' . $vehicle->registration_number);

        return redirect()->route('admin.vehicles.show', $vehicle)->with('success', 'Vehicle updated successfully.');
    }

    public function destroy(Vehicle $vehicle)
    {
        $this->auditService->log('deleted', auth()->user(), Vehicle::class, $vehicle->id, $vehicle->toArray(), null, 'Deleted vehicle ' . $vehicle->registration_number);
        $vehicle->delete();
        return redirect()->route('admin.vehicles.index')->with('success', 'Vehicle deleted successfully.');
    }

    public function assignRider(Request $request, Vehicle $vehicle)
    {
        $request->validate(['rider_id' => 'required|exists:users,id']);
        $rider = User::findOrFail($request->rider_id);
        $this->vehicleService->assignRider($vehicle, $rider);
        return back()->with('success', "Rider {$rider->name} assigned to vehicle.");
    }

    public function unassignRider(Vehicle $vehicle)
    {
        $this->vehicleService->unassignRider($vehicle);
        return back()->with('success', 'Rider unassigned from vehicle.');
    }

    public function generateQr(Vehicle $vehicle)
    {
        $qrPath = $this->vehicleService->generateQrCode($vehicle);
        return back()->with('success', 'QR code generated.');
    }

    public function track(Vehicle $vehicle)
    {
        $vehicle->load('assignedRider');
        return view('admin.vehicles.track', compact('vehicle'));
    }

    public function exportList()
    {
        $vehicles = Vehicle::with(['assignedRider', 'assignedManager'])->get();
        $data = $vehicles->map(fn($v) => [
            'Registration' => $v->registration_number,
            'Type' => $v->type,
            'Make' => $v->make,
            'Model' => $v->model,
            'Status' => $v->status,
            'Rider' => $v->assignedRider?->name ?? 'N/A',
            'Manager' => $v->assignedManager?->name ?? 'N/A',
        ])->toArray();

        $reportService = app(\App\Services\ReportService::class);
        $path = $reportService->exportToCsv($data, 'vehicles-' . date('Y-m-d') . '.csv');

        return response()->download(storage_path('app/' . $path));
    }
}
