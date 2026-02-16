<?php

namespace App\Http\Controllers\Manager;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Vehicle;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class VehicleController extends Controller
{
    /**
     * Display all vehicles assigned to the current manager.
     */
    public function index(): View
    {
        $vehicles = Vehicle::where('assigned_manager_id', Auth::id())
            ->with('assignedRider')
            ->get();

        return view('manager.vehicles.index', compact('vehicles'));
    }

    /**
     * Show the form to assign a rider to a vehicle.
     */
    public function showAssign(Vehicle $vehicle): View
    {
        abort_if($vehicle->assigned_manager_id !== Auth::id(), 403, 'This vehicle is not assigned to your management.');

        $availableRiders = User::where('role', 'rider')
            ->where('status', 'active')
            ->whereDoesntHave('assignedVehicle')
            ->orderBy('name')
            ->get();

        return view('manager.vehicles.assign', compact('vehicle', 'availableRiders'));
    }

    /**
     * Assign a rider to the given vehicle.
     */
    public function assignRider(Request $request, Vehicle $vehicle): RedirectResponse
    {
        abort_if($vehicle->assigned_manager_id !== Auth::id(), 403, 'This vehicle is not assigned to your management.');

        $validated = $request->validate([
            'rider_id' => ['required', 'exists:users,id'],
        ]);

        $vehicle->update([
            'assigned_rider_id' => $validated['rider_id'],
        ]);

        $rider = User::find($validated['rider_id']);

        if ($rider && $rider->riderProfile) {
            $rider->riderProfile->update([
                'vehicle_id' => $vehicle->id,
            ]);
        }

        return redirect()->back()->with('success', 'Rider assigned to vehicle successfully.');
    }

    /**
     * Unassign the current rider from the given vehicle.
     */
    public function unassignRider(Vehicle $vehicle): RedirectResponse
    {
        abort_if($vehicle->assigned_manager_id !== Auth::id(), 403, 'This vehicle is not assigned to your management.');

        $rider = $vehicle->assignedRider;

        $vehicle->update([
            'assigned_rider_id' => null,
        ]);

        if ($rider && $rider->riderProfile) {
            $rider->riderProfile->update([
                'vehicle_id' => null,
            ]);
        }

        return redirect()->back()->with('success', 'Rider unassigned from vehicle successfully.');
    }
}
