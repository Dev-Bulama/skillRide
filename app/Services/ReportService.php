<?php

namespace App\Services;

use App\Models\Payment;
use App\Models\User;
use App\Models\Vehicle;
use App\Models\WorkLog;
use Carbon\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

class ReportService
{
    public function generateRevenueReport(Carbon $from, Carbon $to): array
    {
        $payments = Payment::where('status', 'completed')
            ->whereBetween('paid_at', [$from, $to])
            ->get();

        return [
            'period' => ['from' => $from->format('Y-m-d'), 'to' => $to->format('Y-m-d')],
            'total_revenue' => $payments->sum('amount'),
            'total_transactions' => $payments->count(),
            'average_payment' => $payments->count() > 0 ? $payments->avg('amount') : 0,
            'by_method' => $payments->groupBy('payment_method')->map->sum('amount'),
            'daily' => $payments->groupBy(fn($p) => $p->paid_at->format('Y-m-d'))->map->sum('amount'),
        ];
    }

    public function generateRiderPerformanceReport(User $rider): array
    {
        $payments = Payment::where('rider_id', $rider->id)->get();
        $workLogs = WorkLog::where('rider_id', $rider->id)->get();

        return [
            'rider' => $rider->only('id', 'name', 'email', 'phone'),
            'total_paid' => $payments->where('status', 'completed')->sum('amount'),
            'total_due' => $payments->where('status', 'pending')->sum('amount'),
            'arrears' => $payments->where('status', 'pending')->where('due_date', '<', now())->sum('amount'),
            'payment_count' => $payments->where('status', 'completed')->count(),
            'total_work_hours' => $workLogs->sum('total_hours'),
            'average_daily_hours' => $workLogs->count() > 0 ? $workLogs->avg('total_hours') : 0,
            'work_days' => $workLogs->count(),
        ];
    }

    public function generateFleetUtilizationReport(): array
    {
        $vehicles = Vehicle::withCount(['maintenanceRecords', 'gpsLocations'])->get();

        return [
            'total_vehicles' => $vehicles->count(),
            'active' => $vehicles->where('status', 'active')->count(),
            'maintenance' => $vehicles->where('status', 'maintenance')->count(),
            'inactive' => $vehicles->where('status', 'inactive')->count(),
            'utilization_rate' => $vehicles->count() > 0
                ? round(($vehicles->where('status', 'active')->count() / $vehicles->count()) * 100, 1)
                : 0,
            'by_type' => $vehicles->groupBy('type')->map->count(),
            'maintenance_costs' => (float) DB::table('maintenance_records')
                ->where('status', 'completed')
                ->sum('cost'),
        ];
    }

    public function generatePaymentArrearsReport(): Collection
    {
        return Payment::where('status', 'pending')
            ->where('due_date', '<', now())
            ->with('rider:id,name,phone,email')
            ->selectRaw('rider_id, SUM(amount) as total_arrears, MIN(due_date) as oldest_due, COUNT(*) as missed_count')
            ->groupBy('rider_id')
            ->orderByDesc('total_arrears')
            ->get();
    }

    public function exportToCsv(array $data, string $filename): string
    {
        $path = 'exports/' . $filename;
        $fullPath = storage_path('app/' . $path);

        if (!is_dir(dirname($fullPath))) {
            mkdir(dirname($fullPath), 0755, true);
        }

        $handle = fopen($fullPath, 'w');
        if (!empty($data)) {
            fputcsv($handle, array_keys($data[0]));
            foreach ($data as $row) {
                fputcsv($handle, $row);
            }
        }
        fclose($handle);

        return $path;
    }

    public function exportToPdf(string $view, array $data, string $filename): string
    {
        $pdf = app('dompdf.wrapper');
        $pdf->loadView($view, $data);
        $path = 'exports/' . $filename;
        $fullPath = storage_path('app/' . $path);

        if (!is_dir(dirname($fullPath))) {
            mkdir(dirname($fullPath), 0755, true);
        }

        $pdf->save($fullPath);
        return $path;
    }
}
