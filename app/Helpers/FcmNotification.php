<?php

namespace App\Helpers;

use Kreait\Firebase\Messaging\CloudMessage;
use Kreait\Firebase\Messaging\Notification;
use Kreait\Firebase\Exception\MessagingException;
use Kreait\Firebase\Exception\FirebaseException;
use Illuminate\Support\Facades\Log;

class FcmNotification
{
    /**
     * Sends a notification to a single device using Firebase Admin SDK.
     *
     * @param string $token The target device token.
     * @param string $title The title of the notification.
     * @param string $body The body message.
     * @param array  $data Additional custom data.
     *
     * @return array The result or error message.
     */
    public static function send(string $token, string $title, string $body, array $data = []): array
    {
        try {
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

            return ['message' => 'Notification sent successfully', 'result' => $result];
        } catch (MessagingException | FirebaseException $e) {
            Log::error('Error sending notification', [
                'error' => $e->getMessage(),
                'token' => $token,
            ]);
            return ['error' => $e->getMessage()];
        }
    }

    /**
     * Sends a notification to multiple devices using Firebase Admin SDK.
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

        try {
            $messaging = app('firebase.messaging');
            $notification = Notification::create($title, $body);

            // Create a message without a specific target – we use sendMulticast for multiple tokens
            $message = CloudMessage::new()
                ->withNotification($notification)
                ->withData($data);

            // Send the message to multiple tokens (multicast)
            $report = $messaging->sendMulticast($message, $tokens);

            return ['report' => $report];
        } catch (MessagingException | FirebaseException $e) {
            Log::error('Error sending multicast notification', [
                'error' => $e->getMessage(),
                'tokens' => $tokens,
            ]);
            return ['error' => $e->getMessage()];
        }
    }
}
