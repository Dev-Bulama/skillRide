<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\NotificationService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class NotificationController extends Controller
{
    public function __construct(protected NotificationService $notificationService) {}

    public function index(Request $request): JsonResponse
    {
        $notifications = $this->notificationService->getUserNotifications(auth()->user(), $request->input('limit', 20));
        return response()->json(['success' => true, 'data' => $notifications]);
    }

    public function markRead(int $id): JsonResponse
    {
        $this->notificationService->markAsRead($id);
        return response()->json(['success' => true]);
    }

    public function unreadCount(): JsonResponse
    {
        $count = $this->notificationService->getUnreadCount(auth()->user());
        return response()->json(['success' => true, 'count' => $count]);
    }
}
