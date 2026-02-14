<?php

namespace App\Services;

use App\Models\SystemUpdate;
use App\Models\User;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Log;
use ZipArchive;

class SystemUpdateService
{
    public function upload(UploadedFile $file, User $admin, string $version, string $description): SystemUpdate
    {
        $path = $file->store('system-updates', 'local');

        return SystemUpdate::create([
            'uploaded_by' => $admin->id,
            'file_path' => $path,
            'original_name' => $file->getClientOriginalName(),
            'file_size' => $file->getSize(),
            'version' => $version,
            'description' => $description,
            'status' => 'pending',
        ]);
    }

    public function apply(SystemUpdate $update): bool
    {
        try {
            $update->update(['status' => 'processing']);

            // Create backup first
            $backupPath = $this->createBackup();
            $update->update(['backup_path' => $backupPath]);

            // Extract the zip file
            $zipPath = storage_path('app/' . $update->file_path);
            $extractPath = base_path();

            $zip = new ZipArchive();
            if ($zip->open($zipPath) !== true) {
                throw new \Exception('Failed to open zip file');
            }

            $zip->extractTo($extractPath);
            $zip->close();

            // Run migrations
            Artisan::call('migrate', ['--force' => true]);

            // Clear caches
            Artisan::call('config:clear');
            Artisan::call('cache:clear');
            Artisan::call('view:clear');
            Artisan::call('route:clear');

            $update->update([
                'status' => 'completed',
                'applied_at' => now(),
                'log' => 'Update applied successfully. Migrations: ' . Artisan::output(),
            ]);

            return true;
        } catch (\Exception $e) {
            Log::error('System update failed: ' . $e->getMessage());

            $update->update([
                'status' => 'failed',
                'log' => 'Error: ' . $e->getMessage(),
            ]);

            return false;
        }
    }

    public function rollback(SystemUpdate $update): bool
    {
        try {
            if (!$update->backup_path || !file_exists(storage_path('app/' . $update->backup_path))) {
                throw new \Exception('Backup not found');
            }

            $zip = new ZipArchive();
            if ($zip->open(storage_path('app/' . $update->backup_path)) !== true) {
                throw new \Exception('Failed to open backup');
            }

            $zip->extractTo(base_path());
            $zip->close();

            Artisan::call('migrate', ['--force' => true]);
            Artisan::call('config:clear');
            Artisan::call('cache:clear');

            $update->update([
                'status' => 'rolled_back',
                'rolled_back_at' => now(),
                'log' => $update->log . "\nRolled back at " . now(),
            ]);

            return true;
        } catch (\Exception $e) {
            Log::error('Rollback failed: ' . $e->getMessage());
            return false;
        }
    }

    public function createBackup(): string
    {
        $backupDir = storage_path('app/backups');
        if (!is_dir($backupDir)) {
            mkdir($backupDir, 0755, true);
        }

        $backupPath = 'backups/backup-' . date('Y-m-d-His') . '.zip';
        $zip = new ZipArchive();
        $zip->open(storage_path('app/' . $backupPath), ZipArchive::CREATE);

        $files = new \RecursiveIteratorIterator(
            new \RecursiveDirectoryIterator(base_path(), \RecursiveDirectoryIterator::SKIP_DOTS),
            \RecursiveIteratorIterator::LEAVES_ONLY
        );

        $excludeDirs = ['vendor', 'node_modules', '.git', 'storage/app/backups', 'storage/app/system-updates'];

        foreach ($files as $file) {
            $filePath = $file->getRealPath();
            $relativePath = substr($filePath, strlen(base_path()) + 1);

            $skip = false;
            foreach ($excludeDirs as $dir) {
                if (str_starts_with($relativePath, $dir)) {
                    $skip = true;
                    break;
                }
            }

            if (!$skip && !$file->isDir()) {
                $zip->addFile($filePath, $relativePath);
            }
        }

        $zip->close();
        return $backupPath;
    }

    public function getUpdateHistory(): Collection
    {
        return SystemUpdate::with('uploadedBy')
            ->orderByDesc('created_at')
            ->get();
    }
}
