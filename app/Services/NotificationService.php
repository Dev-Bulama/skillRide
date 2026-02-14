<?php

namespace App\Services;

use App\Models\NotificationLog;
use App\Models\Payment;
use App\Models\MaintenanceRecord;
use App\Models\User;
use App\Models\Vehicle;
use Illuminate\Support\Collection;

class NotificationService
{
    public function send(User $user, string $type, string $title, string $message, array $data = []): NotificationLog
    {
        return NotificationLog::create([
            'user_id' => $user->id,
            'type' => $type,
            'channel' => $type,
            'title' => $title,
            'message' => $message,
            'data' => $data,
            'status' => 'sent',
            'sent_at' => now(),
        ]);
    }

    public function sendPaymentReminder(User $rider, Payment $payment): void
    {
        $this->send(
            $rider,
            'in_app',
            'Payment Reminder',
            "Your payment of ₦" . number_format($payment->amount, 2) . " is due on " . $payment->due_date->format('M d, Y') . ".",
            ['payment_id' => $payment->id, 'amount' => $payment->amount]
        );
    }

    public function sendMissedPaymentAlert(User $rider, User $manager): void
    {
        $this->send(
            $manager,
            'in_app',
            'Missed Payment Alert',
            "Rider {$rider->name} has missed a payment. Please follow up.",
            ['rider_id' => $rider->id]
        );

        $this->send(
            $rider,
            'in_app',
            'Payment Overdue',
            'You have an overdue payment. Please make your payment to avoid penalties.',
            ['rider_id' => $rider->id]
        );
    }

    public function sendMaintenanceReminder(Vehicle $vehicle, MaintenanceRecord $record): void
    {
        if ($vehicle->assignedRider) {
            $this->send(
                $vehicle->assignedRider,
                'in_app',
                'Maintenance Due',
                "Vehicle {$vehicle->registration_number} has scheduled maintenance: {$record->description}",
                ['vehicle_id' => $vehicle->id, 'record_id' => $record->id]
            );
        }
    }

    public function markAsRead(int $notificationId): void
    {
        NotificationLog::where('id', $notificationId)->update([
            'status' => 'read',
            'read_at' => now(),
        ]);
    }

    public function getUnreadCount(User $user): int
    {
        return NotificationLog::where('user_id', $user->id)
            ->where('status', '!=', 'read')
            ->count();
    }

    public function getUserNotifications(User $user, int $limit = 20): Collection
    {
        return NotificationLog::where('user_id', $user->id)
            ->orderByDesc('created_at')
            ->limit($limit)
            ->get();
    }
}
