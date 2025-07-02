<?php

namespace App\Helpers;

use Kreait\Firebase\Messaging\CloudMessage;
use Kreait\Firebase\Messaging\Notification;
use Kreait\Firebase\Exception\MessagingException;
use Kreait\Firebase\Exception\FirebaseException;
use Illuminate\Support\Facades\Log;
use App\Models\Notification as NotificationModel; // Alias to avoid conflict with Kreait\Firebase\Messaging\Notification
use Carbon\Carbon; // To record sent_at timestamp

class FcmNotification
{
    /**
     * Sends a notification to a single device using Firebase Admin SDK and logs it.
     *
     * @param string $token The target device token.
     * @param string $title The title of the notification.
     * @param string $body The body message.
     * @param array  $data Additional custom data.
     * @param int|null $userId Optional: The ID of the user receiving the notification.
     *
     * @return array The result or error message.
     */
    public static function send(string $token, string $title, string $body, array $data = [], ?int $userId = null): array
    {
        $notificationRecord = null; // Initialize to null

        try {
            // 1. Create a notification record in the database first
            $notificationRecord = NotificationModel::create([
                'user_id' => $userId,
                'fcm_token' => $token,
                'title' => $title,
                'body' => $body,
                'data' => $data,
                'status' => 'pending', // Initial status
                'sent_at' => Carbon::now(), // Mark when the send attempt began
            ]);

            // Resolve the Firebase messaging instance from Laravel's service container
            $messaging = app('firebase.messaging');

            // Create a notification object
            $notification = Notification::create($title, $body);

            // Build the cloud message with target token, notification, and extra data
            $message = CloudMessage::withTarget('token', $token)
                ->withNotification($notification)
                ->withData($data);

            // Send the message - the SDK handles OAuth authentication and HTTP calls
            $result = $messaging->send($message);

            // 2. Update the notification record to 'sent' on success
            if ($notificationRecord) {
                $notificationRecord->update(['status' => 'sent']);
            }

            return ['message' => 'Notification sent successfully', 'result' => $result];
        } catch (MessagingException | FirebaseException $e) {
            // 3. Update the notification record to 'failed' on error
            if ($notificationRecord) {
                $notificationRecord->update([
                    'status' => 'failed',
                    'error_message' => $e->getMessage(),
                ]);
            }

            Log::error('Error sending notification', [
                'error' => $e->getMessage(),
                'token' => $token,
                'notification_id' => $notificationRecord->id ?? 'N/A', // Log ID if available
            ]);
            return ['error' => $e->getMessage()];
        }
    }

    /**
     * Sends a notification to multiple devices using Firebase Admin SDK and logs it.
     * This version records the overall outcome of the multicast without detailed per-token failures.
     *
     * @param array  $tokens  An array of target device tokens.
     * @param string $title   The title of the notification.
     * @param string $body    The body message.
     * @param array  $data    Additional custom data.
     *
     * @return array The report from the multicast send or an error message.
     */
    public static function sendToMany(array $tokens, string $title, string $body, array $data = []): array
    {
        if (empty($tokens)) {
            Log::info('No tokens provided for multicast notification.');
            return ['error' => 'No tokens'];
        }

        $notificationRecord = null; // Initialize to null

        try {
            // 1. Create a notification record for the multicast attempt
            // user_id and fcm_token will be null for multicast, as it's for multiple users
            $notificationRecord = NotificationModel::create([
                'title' => $title,
                'body' => $body,
                'data' => array_merge($data, ['target_tokens_count' => count($tokens)]), // Add token count to data
                'status' => 'pending_multicast', // Specific status for multicast
                'sent_at' => Carbon::now(),
            ]);

            $messaging = app('firebase.messaging');
            $notification = Notification::create($title, $body);

            $message = CloudMessage::new()
                ->withNotification($notification)
                ->withData($data);

            // Send the message to multiple tokens (multicast)
            $report = $messaging->sendMulticast($message, $tokens);

            // 2. Update the notification record to 'sent' on success
            if ($notificationRecord) {
                $notificationRecord->update([
                    'status' => 'sent_multicast',
                    'data' => array_merge($notificationRecord->data ?? [], [
                        'success_count' => $report->successes()->count(),
                        'failure_count' => $report->failures()->count(),
                        // Per your request, individual 'failed_tokens_details' are not stored here.
                    ])
                ]);
            }

            return ['report' => $report];
        } catch (MessagingException | FirebaseException $e) {
            // 3. Update the notification record to 'failed' on error
            if ($notificationRecord) {
                $notificationRecord->update([
                    'status' => 'failed_multicast',
                    'error_message' => $e->getMessage(),
                ]);
            }

            Log::error('Error sending multicast notification', [
                'error' => $e->getMessage(),
                'tokens_count' => count($tokens),
                'notification_id' => $notificationRecord->id ?? 'N/A',
            ]);
            return ['error' => $e->getMessage()];
        }
    }
}
