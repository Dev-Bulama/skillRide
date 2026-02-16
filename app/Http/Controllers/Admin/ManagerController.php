<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Route;
use App\Models\User;
use App\Services\AuditService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class ManagerController extends Controller
{
    public function __construct(protected AuditService $auditService) {}

    public function index(Request $request)
    {
        $query = User::where('role', 'manager')
            ->with('managerProfile')
            ->withCount(['managedVehicles as managed_routes_count']);

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%");
            });
        }

        $managers = $query->orderByDesc('created_at')->paginate(15)->withQueryString();

        return view('admin.managers.index', compact('managers'));
    }

    public function create()
    {
        return view('admin.managers.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users',
            'phone' => 'required|string|max:20',
            'password' => 'required|string|min:8',
            'department' => 'nullable|string',
            'zone' => 'nullable|string',
            'ward' => 'nullable|string',
            'commission_rate' => 'nullable|numeric|min:0|max:100',
            'bank_name' => 'nullable|string',
            'bank_account_number' => 'nullable|string',
            'bank_account_name' => 'nullable|string',
        ]);

        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'phone' => $validated['phone'],
            'password' => Hash::make($validated['password']),
            'role' => 'manager',
            'status' => 'active',
            'is_verified' => true,
            'verified_at' => now(),
        ]);

        $user->managerProfile()->create(
            collect($validated)->only([
                'department', 'zone', 'ward', 'commission_rate',
                'bank_name', 'bank_account_number', 'bank_account_name',
            ])->toArray()
        );

        $this->auditService->log('created', auth()->user(), User::class, $user->id, null, null, 'Created manager ' . $user->name);

        return redirect()->route('admin.managers.index')->with('success', 'Manager created successfully.');
    }

    public function show(User $manager)
    {
        abort_if($manager->role !== 'manager', 404);
        $manager->load('managerProfile');
        $assignedRoutes = Route::where('assigned_manager_id', $manager->id)->get();
        return view('admin.managers.show', compact('manager', 'assignedRoutes'));
    }

    public function edit(User $manager)
    {
        abort_if($manager->role !== 'manager', 404);
        $manager->load('managerProfile');
        return view('admin.managers.edit', compact('manager'));
    }

    public function update(Request $request, User $manager)
    {
        abort_if($manager->role !== 'manager', 404);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $manager->id,
            'phone' => 'required|string|max:20',
            'status' => 'required|in:active,inactive,suspended',
            'department' => 'nullable|string',
            'zone' => 'nullable|string',
            'ward' => 'nullable|string',
            'commission_rate' => 'nullable|numeric|min:0|max:100',
        ]);

        $manager->update(collect($validated)->only(['name', 'email', 'phone', 'status'])->toArray());
        $manager->managerProfile?->update(collect($validated)->only(['department', 'zone', 'ward', 'commission_rate'])->toArray());

        return redirect()->route('admin.managers.show', $manager)->with('success', 'Manager updated.');
    }

    public function destroy(User $manager)
    {
        abort_if($manager->role !== 'manager', 404);
        $manager->update(['status' => 'inactive']);
        return redirect()->route('admin.managers.index')->with('success', 'Manager deactivated.');
    }

    public function assignRoutes(Request $request, User $manager)
    {
        abort_if($manager->role !== 'manager', 404);
        $request->validate(['route_ids' => 'required|array', 'route_ids.*' => 'exists:routes,id']);

        Route::whereIn('id', $request->route_ids)->update(['assigned_manager_id' => $manager->id]);

        return back()->with('success', 'Routes assigned successfully.');
    }
}
