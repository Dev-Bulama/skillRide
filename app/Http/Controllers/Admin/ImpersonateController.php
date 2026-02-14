<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Services\AuditService;
use Illuminate\Support\Facades\Auth;

class ImpersonateController extends Controller
{
    public function __construct(protected AuditService $auditService) {}

    public function start(User $user)
    {
        if ($user->role === 'admin') {
            return back()->with('error', 'Cannot impersonate another admin.');
        }

        $this->auditService->log('impersonate_start', auth()->user(), User::class, $user->id, null, null, 'Started impersonating ' . $user->name);

        session(['original_admin_id' => auth()->id(), 'impersonate_id' => $user->id]);
        Auth::login($user);

        return redirect()->route('dashboard');
    }

    public function stop()
    {
        $adminId = session('original_admin_id');
        if (!$adminId) {
            return redirect()->route('login');
        }

        $admin = User::find($adminId);
        if (!$admin || $admin->role !== 'admin') {
            session()->forget(['original_admin_id', 'impersonate_id']);
            return redirect()->route('login');
        }

        $this->auditService->log('impersonate_stop', $admin, null, null, null, null, 'Stopped impersonating');

        session()->forget(['original_admin_id', 'impersonate_id']);
        Auth::login($admin);

        return redirect()->route('admin.dashboard');
    }
}
