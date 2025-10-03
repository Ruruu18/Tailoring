<?php

namespace App\Services;

use App\Models\Notification;
use App\Models\User;
use App\Models\Order;
use App\Models\Appointment;

class NotificationService
{
    /**
     * Create a notification for a user
     */
    public static function create(User $user, string $title, string $message, string $type = 'system', bool $sendSms = false): Notification
    {
        $notification = Notification::create([
            'user_id' => $user->id,
            'title' => $title,
            'message' => $message,
            'type' => $type,
            'is_read' => false,
            'sent_at' => now(),
        ]);

        if ($sendSms && $user->phone && self::shouldSendSms($type)) {
            $smsService = new SemaphoreService();
            $smsMessage = $title . ': ' . $message;
            $smsService->sendSms($user->phone, $smsMessage);
        }

        return $notification;
    }

    /**
     * Check if SMS should be sent for notification type
     */
    private static function shouldSendSms(string $type): bool
    {
        $smsEnabledTypes = [
            'order_update',
            'payment_update',
            'appointment',
            'payment_reminder'
        ];

        return in_array($type, $smsEnabledTypes);
    }

    /**
     * Send order status update notification
     */
    public static function orderStatusUpdated(Order $order, string $oldStatus, string $newStatus, bool $sendSms = true): void
    {
        $messages = [
            'pending' => [
                'title' => 'Order Received',
                'message' => "Your order #{$order->order_number} has been received and is being reviewed by our team."
            ],
            'quoted' => [
                'title' => 'Order Quoted',
                'message' => "Your order #{$order->order_number} has been quoted at ₱" . number_format($order->total_amount, 2) . ". Please review and confirm."
            ],
            'confirmed' => [
                'title' => 'Order Confirmed',
                'message' => "Your order #{$order->order_number} has been confirmed and will begin production soon."
            ],
            'in_progress' => [
                'title' => 'Order In Progress',
                'message' => "Great news! Your order #{$order->order_number} is now being crafted by our skilled tailors."
            ],
            'ready' => [
                'title' => 'Order Ready for Pickup!',
                'message' => "Your order #{$order->order_number} is ready for pickup! Please contact us to schedule collection."
            ],
            'completed' => [
                'title' => 'Order Completed',
                'message' => "Your order #{$order->order_number} has been successfully completed. Thank you for choosing our services!"
            ],
            'cancelled' => [
                'title' => 'Order Cancelled',
                'message' => "Your order #{$order->order_number} has been cancelled. Please contact us if you have any questions."
            ]
        ];

        if (isset($messages[$newStatus])) {
            self::create(
                $order->user,
                $messages[$newStatus]['title'],
                $messages[$newStatus]['message'],
                'order_update',
                $sendSms
            );
        }
    }

    /**
     * Send payment received notification
     */
    public static function paymentReceived(Order $order, float $amount, bool $sendSms = true): void
    {
        $title = 'Payment Received';
        $message = "Payment of ₱" . number_format($amount, 2) . " has been received for order #{$order->order_number}.";

        if ($order->total_amount > $order->paid_amount) {
            $balance = $order->total_amount - $order->paid_amount;
            $message .= " Remaining balance: ₱" . number_format($balance, 2);
        } else {
            $message .= " Your order is now fully paid!";
        }

        self::create($order->user, $title, $message, 'payment_update', $sendSms);
    }

    /**
     * Send payment reminder notification
     */
    public static function paymentReminder(Order $order, bool $sendSms = true): void
    {
        $balance = $order->total_amount - $order->paid_amount;

        $title = 'Payment Reminder';
        $message = "Reminder: Outstanding balance of ₱" . number_format($balance, 2) . " for order #{$order->order_number}.";

        if ($order->status === 'ready') {
            $message .= " Your order is ready for pickup once payment is completed.";
        }

        self::create($order->user, $title, $message, 'payment_reminder', $sendSms);
    }

    /**
     * Send appointment confirmation notification
     */
    public static function appointmentCreated(Appointment $appointment, bool $sendSms = true): void
    {
        $title = 'Appointment Scheduled';
        $message = "Your appointment has been scheduled for " . $appointment->appointment_date->format('F j, Y \a\t g:i A') .
                   " for " . ucwords(str_replace('_', ' ', $appointment->service_type)) . ".";

        self::create($appointment->user, $title, $message, 'appointment', $sendSms);
    }

    /**
     * Send appointment reminder notification
     */
    public static function appointmentReminder(Appointment $appointment, bool $sendSms = true): void
    {
        $title = 'Appointment Reminder';
        $message = "Reminder: You have an appointment tomorrow (" . $appointment->appointment_date->format('F j, Y') .
                   ") at " . $appointment->appointment_date->format('g:i A') .
                   " for " . ucwords(str_replace('_', ' ', $appointment->service_type)) . ".";

        self::create($appointment->user, $title, $message, 'appointment', $sendSms);
    }

    /**
     * Send appointment cancelled notification
     */
    public static function appointmentCancelled(Appointment $appointment, bool $sendSms = true): void
    {
        $title = 'Appointment Cancelled';
        $message = "Your appointment scheduled for " . $appointment->appointment_date->format('F j, Y \a\t g:i A') .
                   " has been cancelled. Please contact us to reschedule.";

        self::create($appointment->user, $title, $message, 'appointment', $sendSms);
    }

    /**
     * Send measurement update reminder
     */
    public static function measurementUpdateReminder(User $user): void
    {
        $title = 'Update Your Measurements';
        $message = "It's been a while since you last updated your measurements. Please review and update them for better fitting garments.";

        self::create($user, $title, $message, 'system');
    }

    /**
     * Send new design notification
     */
    public static function newDesignsAvailable(User $user): void
    {
        $title = 'New Designs Available';
        $message = "Check out our latest design collection! New styles and patterns have been added to our brochure.";

        self::create($user, $title, $message, 'system');
    }

    /**
     * Send welcome notification for new customers
     */
    public static function welcomeNewCustomer(User $user): void
    {
        $title = 'Welcome to Our Tailoring Shop!';
        $message = "Welcome {$user->name}! We're excited to help you create beautiful, custom-fitted garments. Start by adding your measurements and exploring our designs.";

        self::create($user, $title, $message, 'system');
    }

    /**
     * Send order quote request notification (when customer places order)
     */
    public static function orderQuoteRequested(Order $order): void
    {
        $title = 'Quote Request Submitted';
        $message = "Your order #{$order->order_number} has been submitted for quotation. Our team will review your requirements and provide pricing within 24 hours.";

        self::create($order->user, $title, $message, 'order_update');
    }
}