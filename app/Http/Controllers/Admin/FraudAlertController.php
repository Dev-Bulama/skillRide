<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\FraudAlert;
use Illuminate\Http\Request;

class FraudAlertController extends Controller
{
    public function index(Request $request)
    {
        $query = FraudAlert::with(['user', 'vehicle']);

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        if ($request->filled('severity')) {
            $query->where('severity', $request->severity);
        }

        $alerts = $query->orderByDesc('created_at')->paginate(20)->withQueryString();
        return view('admin.reports.fraud', compact('alerts'));
    }

    public function show(FraudAlert $alert)
    {
        $alert->load(['user', 'vehicle', 'resolvedBy']);
        return view('admin.reports.fraud-show', compact('alert'));
    }

    public function resolve(Request $request, FraudAlert $alert)
    {
        $request->validate(['notes' => 'nullable|string']);
        $alert->update([
            'status' => 'resolved',
            'resolved_by' => auth()->id(),
            'resolved_at' => now(),
        ]);
        return back()->with('success', 'Alert resolved.');
    }

    public function dismiss(FraudAlert $alert)
    {
        $alert->update(['status' => 'dismissed', 'resolved_by' => auth()->id(), 'resolved_at' => now()]);
        return back()->with('success', 'Alert dismissed.');
    }
}
