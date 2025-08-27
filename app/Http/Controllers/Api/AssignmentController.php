<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\DeliveryAssignment;
use Illuminate\Http\Request;

class AssignmentController extends Controller
{
    public function accept(Request $request, $assignmentId)
    {
        $driver = auth()->user()->deliveryMan;

        $assignment = DeliveryAssignment::findOrFail($assignmentId);

        // Ensure only the assigned driver can accept
        if ($assignment->delivery_man_id !== $driver->id) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        if ($assignment->status !== 'pending') {
            return response()->json(['message' => 'Assignment cannot be accepted.'], 422);
        }

        $assignment->update(['status' => 'accepted']);

        // Update order status
        $order = $assignment->order;
        $order->update(['status' => 'in_progress']);

        // Notify customer that order is in progress
        $customer = $order->user;
        if ($customer) {
            $customer->notify(new \App\Notifications\OrderInProgress($order));
        }

        return response()->json(['message' => 'Assignment accepted.', 'assignment' => $assignment, 'order' => $order]);
    }

    public function reject(Request $request, $assignmentId)
    {
        $driver = auth()->user()->deliveryMan;

        $assignment = DeliveryAssignment::findOrFail($assignmentId);

        // Ensure only the assigned driver can reject
        if ($assignment->delivery_man_id !== $driver->id) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        if ($assignment->status !== 'pending') {
            return response()->json(['message' => 'Assignment cannot be rejected.'], 422);
        }

        $assignment->update(['status' => 'rejected']);

        // Re-assign to next available driver using NearestDriverAssigner
        $order = $assignment->order;
        $meters = config('delivery.assignment_search_radius');
        $assigner = app(\App\Services\NearestDriverAssigner::class);
        $nextAssignment = $assigner->assign($order, $meters);

        if (!$nextAssignment) {
            $order->update(['status' => 'cancelled']);
        }

        return response()->json([
            'message' => 'Assignment rejected.',
            'assignment' => $assignment,
            'next_assignment' => $nextAssignment,
        ]);
    }
}
