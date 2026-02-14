<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AuditLog;
use App\Models\User;
use Illuminate\Http\Request;

class AuditController extends Controller
{
    public function index(Request $request)
    {
        $query = AuditLog::with('user');

        if ($request->filled('user_id')) {
            $query->where('user_id', $request->user_id);
        }
        if ($request->filled('action')) {
            $query->where('action', $request->action);
        }
        if ($request->filled('from')) {
            $query->whereDate('created_at', '>=', $request->from);
        }
        if ($request->filled('to')) {
            $query->whereDate('created_at', '<=', $request->to);
        }

        $logs = $query->orderByDesc('created_at')->paginate(30)->withQueryString();
        $users = User::select('id', 'name')->orderBy('name')->get();

        return view('admin.audit.index', compact('logs', 'users'));
    }

    public function show(AuditLog $log)
    {
        $log->load('user');
        return view('admin.audit.show', compact('log'));
    }

    public function userActivity(User $user)
    {
        $logs = AuditLog::where('user_id', $user->id)
            ->orderByDesc('created_at')
            ->paginate(30);

        return view('admin.audit.user', compact('user', 'logs'));
    }
}
