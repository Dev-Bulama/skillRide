<?php

namespace App\Services;

use App\Models\AuditLog;
use App\Models\User;
use Illuminate\Support\Collection;

class AuditService
{
    public function log(
        string $action,
        ?User $user,
        ?string $modelType,
        ?int $modelId,
        ?array $oldValues,
        ?array $newValues,
        ?string $description = null
    ): AuditLog {
        return AuditLog::create([
            'user_id' => $user?->id,
            'action' => $action,
            'model_type' => $modelType,
            'model_id' => $modelId,
            'old_values' => $oldValues,
            'new_values' => $newValues,
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
            'description' => $description ?? "{$action} on {$modelType} #{$modelId}",
        ]);
    }

    public function getUserActivity(User $user, int $limit = 50): Collection
    {
        return AuditLog::where('user_id', $user->id)
            ->orderByDesc('created_at')
            ->limit($limit)
            ->get();
    }

    public function getModelHistory(string $modelType, int $modelId): Collection
    {
        return AuditLog::where('model_type', $modelType)
            ->where('model_id', $modelId)
            ->with('user')
            ->orderByDesc('created_at')
            ->get();
    }

    public function getRecentActivity(int $limit = 100): Collection
    {
        return AuditLog::with('user')
            ->orderByDesc('created_at')
            ->limit($limit)
            ->get();
    }
}
