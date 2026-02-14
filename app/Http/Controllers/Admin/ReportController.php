<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\ReportService;
use Carbon\Carbon;
use Illuminate\Http\Request;

class ReportController extends Controller
{
    public function __construct(protected ReportService $reportService) {}

    public function index()
    {
        return view('admin.reports.index');
    }

    public function revenue(Request $request)
    {
        $from = Carbon::parse($request->input('from', now()->startOfMonth()));
        $to = Carbon::parse($request->input('to', now()));
        $report = $this->reportService->generateRevenueReport($from, $to);
        return view('admin.reports.revenue', compact('report', 'from', 'to'));
    }

    public function riders(Request $request)
    {
        $riders = \App\Models\User::where('role', 'rider')->with('riderProfile.assignedVehicle')->paginate(20);
        return view('admin.reports.riders', compact('riders'));
    }

    public function fleet()
    {
        $report = $this->reportService->generateFleetUtilizationReport();
        return view('admin.reports.fleet', compact('report'));
    }

    public function export(Request $request)
    {
        $type = $request->input('type', 'revenue');
        $from = Carbon::parse($request->input('from', now()->startOfMonth()));
        $to = Carbon::parse($request->input('to', now()));

        if ($type === 'arrears') {
            $data = $this->reportService->generatePaymentArrearsReport()
                ->map(fn($r) => ['Rider ID' => $r->rider_id, 'Total Arrears' => $r->total_arrears, 'Missed Count' => $r->missed_count])
                ->toArray();
        } else {
            $report = $this->reportService->generateRevenueReport($from, $to);
            $data = collect($report['daily'])->map(fn($amount, $date) => ['Date' => $date, 'Amount' => $amount])->values()->toArray();
        }

        $path = $this->reportService->exportToCsv($data, "{$type}-report-" . date('Y-m-d') . '.csv');
        return response()->download(storage_path('app/' . $path));
    }

    public function downloadPdf(Request $request)
    {
        $from = Carbon::parse($request->input('from', now()->startOfMonth()));
        $to = Carbon::parse($request->input('to', now()));
        $report = $this->reportService->generateRevenueReport($from, $to);

        $path = $this->reportService->exportToPdf('exports.revenue-report', compact('report', 'from', 'to'), 'revenue-report-' . date('Y-m-d') . '.pdf');
        return response()->download(storage_path('app/' . $path));
    }
}
