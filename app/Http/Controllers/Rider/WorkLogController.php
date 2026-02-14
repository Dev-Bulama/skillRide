<?php

namespace App\Http\Controllers\Rider;

use App\Http\Controllers\Controller;
use App\Models\WorkLog;
use App\Services\RiderService;
use Illuminate\Http\Request;

class WorkLogController extends Controller
{
    public function __construct(protected RiderService $riderService) {}

    public function index()
    {
        $rider = auth()->user();
        $currentLog = WorkLog::where('rider_id', $rider->id)->whereNull('clock_out_at')->latest()->first();
        $isClockIn = $currentLog !== null;
        $workLogs = WorkLog::where('rider_id', $rider->id)->orderByDesc('clock_in_at')->paginate(15);
        $assistant = $rider->riderProfile;

        return view('rider.work.index', compact('currentLog', 'isClockIn', 'workLogs', 'assistant'));
    }

    public function clockIn(Request $request)
    {
        $request->validate([
            'latitude' => 'required|numeric',
            'longitude' => 'required|numeric',
        ]);

        $rider = auth()->user();
        $existing = WorkLog::where('rider_id', $rider->id)->whereNull('clock_out_at')->first();

        if ($existing) {
            return back()->with('error', 'You are already clocked in.');
        }

        $this->riderService->clockIn($rider, $request->latitude, $request->longitude);
        return back()->with('success', 'Clocked in successfully.');
    }

    public function clockOut(Request $request)
    {
        $request->validate([
            'latitude' => 'required|numeric',
            'longitude' => 'required|numeric',
        ]);

        $rider = auth()->user();
        $this->riderService->clockOut($rider, $request->latitude, $request->longitude);
        return back()->with('success', 'Clocked out successfully.');
    }

    public function registerAssistant(Request $request)
    {
        $request->validate([
            'assistant_name' => 'required|string|max:255',
            'assistant_phone' => 'required|string|max:20',
        ]);

        $rider = auth()->user();
        $rider->riderProfile?->update([
            'assistant_name' => $request->assistant_name,
            'assistant_phone' => $request->assistant_phone,
        ]);

        return back()->with('success', 'Assistant registered.');
    }
}
