<?php

namespace App\Services;

use App\Models\MaintenanceMedia;
use App\Models\MaintenanceRecord;
use App\Models\User;
use App\Models\Vehicle;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Collection;

class MaintenanceService
{
    public function createRecord(array $data): MaintenanceRecord
    {
        return MaintenanceRecord::create($data);
    }

    public function updateStatus(MaintenanceRecord $record, string $status): void
    {
        $updates = ['status' => $status];

        if ($status === 'in_progress' && !$record->started_at) {
            $updates['started_at'] = now();
        }

        if ($status === 'completed') {
            $updates['completed_at'] = now();
            if ($record->vehicle) {
                $record->vehicle->update(['status' => 'active']);
            }
        }

        $record->update($updates);
    }

    public function addMedia(MaintenanceRecord $record, UploadedFile $file, User $uploader): MaintenanceMedia
    {
        $path = $file->store('maintenance/' . $record->id, 'public');
        $type = str_starts_with($file->getMimeType(), 'video/') ? 'video'
            : (str_starts_with($file->getMimeType(), 'image/') ? 'image' : 'document');

        return MaintenanceMedia::create([
            'maintenance_record_id' => $record->id,
            'file_path' => $path,
            'file_type' => $type,
            'original_name' => $file->getClientOriginalName(),
            'file_size' => $file->getSize(),
            'uploaded_by' => $uploader->id,
        ]);
    }

    public function getUpcomingMaintenance(): Collection
    {
        return MaintenanceRecord::where('status', '!=', 'completed')
            ->where('status', '!=', 'cancelled')
            ->where(function ($q) {
                $q->whereNotNull('next_due_date')
                    ->where('next_due_date', '<=', now()->addDays(7));
            })
            ->orWhere(function ($q) {
                $q->where('status', 'pending');
            })
            ->with(['vehicle', 'reportedBy'])
            ->orderBy('next_due_date')
            ->get();
    }

    public function getVehicleMaintenanceHistory(Vehicle $vehicle): Collection
    {
        return $vehicle->maintenanceRecords()
            ->with(['reportedBy', 'media'])
            ->orderByDesc('created_at')
            ->get();
    }

    public function getMaintenanceStats(): array
    {
        return [
            'total' => MaintenanceRecord::count(),
            'pending' => MaintenanceRecord::where('status', 'pending')->count(),
            'in_progress' => MaintenanceRecord::where('status', 'in_progress')->count(),
            'completed' => MaintenanceRecord::where('status', 'completed')->count(),
            'total_cost' => (float) MaintenanceRecord::where('status', 'completed')->sum('cost'),
        ];
    }
}
