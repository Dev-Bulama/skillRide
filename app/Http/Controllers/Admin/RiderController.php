<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Route;
use App\Models\User;
use App\Models\Vehicle;
use App\Services\AuditService;
use App\Services\RiderService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class RiderController extends Controller
{
    public function __construct(
        protected RiderService $riderService,
        protected AuditService $auditService
    ) {}

    public function index(Request $request)
    {
        $query = User::where('role', 'rider')->with('riderProfile.assignedVehicle');

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%")
                    ->orWhere('phone', 'like', "%{$search}%");
            });
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('verified')) {
            $query->where('is_verified', $request->verified === 'yes');
        }

        $riders = $query->orderByDesc('created_at')->paginate(15)->withQueryString();

        return view('admin.riders.index', compact('riders'));
    }

    public function create()
    {
        $vehicles = Vehicle::whereNull('assigned_rider_id')->where('status', 'active')->get();
        $routes = Route::where('status', 'active')->get();
        return view('admin.riders.create', compact('vehicles', 'routes'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users',
            'phone' => 'required|string|max:20',
            'password' => 'required|string|min:8',
            'address' => 'nullable|string',
            'city' => 'nullable|string',
            'state' => 'nullable|string',
            'date_of_birth' => 'nullable|date',
            'gender' => 'nullable|in:male,female',
            'next_of_kin_name' => 'nullable|string',
            'next_of_kin_phone' => 'nullable|string',
            'guarantor_name' => 'nullable|string',
            'guarantor_phone' => 'nullable|string',
            'guarantor_address' => 'nullable|string',
            'vehicle_id' => 'nullable|exists:vehicles,id',
            'route_id' => 'nullable|exists:routes,id',
        ]);

        $userData = collect($validated)->only([
            'name', 'email', 'phone', 'password', 'address', 'city', 'state',
            'date_of_birth', 'gender', 'next_of_kin_name', 'next_of_kin_phone',
        ])->toArray();
        $userData['password'] = Hash::make($userData['password']);

        $profileData = collect($validated)->only([
            'guarantor_name', 'guarantor_phone', 'guarantor_address',
        ])->toArray();
        $profileData['assigned_vehicle_id'] = $validated['vehicle_id'] ?? null;
        $profileData['assigned_route_id'] = $validated['route_id'] ?? null;

        $rider = $this->riderService->register($userData, $profileData);

        $this->auditService->log('created', auth()->user(), User::class, $rider->id, null, $userData, 'Created rider ' . $rider->name);

        return redirect()->route('admin.riders.index')->with('success', 'Rider created successfully.');
    }

    public function show(User $rider)
    {
        abort_if($rider->role !== 'rider', 404);
        $rider->load(['riderProfile.assignedVehicle', 'riderProfile.assignedRoute', 'payments' => fn($q) => $q->latest()->limit(20)]);
        $paymentSummary = $this->riderService->getPaymentSummary($rider);
        return view('admin.riders.show', compact('rider', 'paymentSummary'));
    }

    public function edit(User $rider)
    {
        abort_if($rider->role !== 'rider', 404);
        $rider->load('riderProfile');
        $vehicles = Vehicle::where(function ($q) use ($rider) {
            $q->whereNull('assigned_rider_id')
                ->orWhere('assigned_rider_id', $rider->id);
        })->where('status', 'active')->get();
        $routes = Route::where('status', 'active')->get();
        return view('admin.riders.edit', compact('rider', 'vehicles', 'routes'));
    }

    public function update(Request $request, User $rider)
    {
        abort_if($rider->role !== 'rider', 404);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $rider->id,
            'phone' => 'required|string|max:20',
            'address' => 'nullable|string',
            'city' => 'nullable|string',
            'state' => 'nullable|string',
        ]);

        $old = $rider->toArray();
        $rider->update($validated);

        $this->auditService->log('updated', auth()->user(), User::class, $rider->id, $old, $validated, 'Updated rider ' . $rider->name);

        return redirect()->route('admin.riders.show', $rider)->with('success', 'Rider updated successfully.');
    }

    public function destroy(User $rider)
    {
        abort_if($rider->role !== 'rider', 404);
        $rider->update(['status' => 'inactive']);
        return redirect()->route('admin.riders.index')->with('success', 'Rider deactivated.');
    }

    public function approve(User $rider)
    {
        abort_if($rider->role !== 'rider', 404);
        $this->riderService->approve($rider);
        $this->auditService->log('approved', auth()->user(), User::class, $rider->id, null, null, 'Approved rider ' . $rider->name);
        return back()->with('success', 'Rider approved and activated.');
    }

    public function suspend(Request $request, User $rider)
    {
        abort_if($rider->role !== 'rider', 404);
        $reason = $request->input('reason', 'No reason specified');
        $this->riderService->suspend($rider, $reason);
        $this->auditService->log('suspended', auth()->user(), User::class, $rider->id, null, ['reason' => $reason], 'Suspended rider ' . $rider->name);
        return back()->with('success', 'Rider suspended.');
    }

    public function activate(User $rider)
    {
        abort_if($rider->role !== 'rider', 404);
        $this->riderService->activate($rider);
        return back()->with('success', 'Rider activated.');
    }

    public function verificationDashboard()
    {
        $pendingRiders = User::where('role', 'rider')
            ->where('is_verified', false)
            ->with('riderProfile')
            ->orderBy('created_at')
            ->paginate(20);

        return view('admin.riders.verification', compact('pendingRiders'));
    }

    public function documents(User $rider)
    {
        abort_if($rider->role !== 'rider', 404);
        $rider->load('riderProfile');
        return view('admin.riders.documents', compact('rider'));
    }
}
