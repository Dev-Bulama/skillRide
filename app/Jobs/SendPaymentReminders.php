<?php

namespace App\Jobs;

use App\Models\Payment;
use App\Services\NotificationService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class SendPaymentReminders implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function handle(NotificationService $notificationService): void
    {
        $reminderHours = (int) config('app.payment_reminder_hours', 24);

        $upcomingPayments = Payment::where('status', 'pending')
            ->whereBetween('due_date', [now(), now()->addHours($reminderHours)])
            ->with('rider')
            ->get();

        foreach ($upcomingPayments as $payment) {
            if ($payment->rider) {
                $notificationService->sendPaymentReminder($payment->rider, $payment);
            }
        }

        $overduePayments = Payment::where('status', 'pending')
            ->where('due_date', '<', now())
            ->with(['rider.riderProfile.assignedVehicle.assignedManager'])
            ->get();

        foreach ($overduePayments as $payment) {
            if ($payment->rider) {
                $manager = $payment->rider->riderProfile?->assignedVehicle?->assignedManager;
                if ($manager) {
                    $notificationService->sendMissedPaymentAlert($payment->rider, $manager);
                }
            }
        }
    }
}
