<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SystemUpdate;
use App\Services\AuditService;
use App\Services\SystemUpdateService;
use Illuminate\Http\Request;

class SystemUpdateController extends Controller
{
    public function __construct(
        protected SystemUpdateService $updateService,
        protected AuditService $auditService
    ) {}

    public function index()
    {
        $updates = $this->updateService->getUpdateHistory();
        return view('admin.system-update.index', compact('updates'));
    }

    public function upload(Request $request)
    {
        $request->validate([
            'file' => 'required|file|mimes:zip|max:102400',
            'version' => 'required|string|max:50',
            'description' => 'nullable|string|max:1000',
        ]);

        $update = $this->updateService->upload(
            $request->file('file'),
            auth()->user(),
            $request->version,
            $request->description ?? ''
        );

        $this->auditService->log('uploaded', auth()->user(), SystemUpdate::class, $update->id, null, null, 'Uploaded system update v' . $update->version);

        return back()->with('success', 'Update uploaded. Click Apply to install.');
    }

    public function apply(SystemUpdate $update)
    {
        $success = $this->updateService->apply($update);

        $this->auditService->log('applied', auth()->user(), SystemUpdate::class, $update->id, null, null, ($success ? 'Applied' : 'Failed to apply') . ' system update v' . $update->version);

        return back()->with($success ? 'success' : 'error', $success ? 'Update applied successfully.' : 'Update failed. Check logs.');
    }

    public function rollback(SystemUpdate $update)
    {
        $success = $this->updateService->rollback($update);

        $this->auditService->log('rolled_back', auth()->user(), SystemUpdate::class, $update->id, null, null, 'Rolled back system update v' . $update->version);

        return back()->with($success ? 'success' : 'error', $success ? 'Rollback completed.' : 'Rollback failed.');
    }
}
