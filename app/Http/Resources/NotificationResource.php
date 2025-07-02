<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Carbon\Carbon;

class NotificationResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        // Determine 'important' status based on your criteria, e.g., 'emergency' type
        $isImportant = isset($this->data['type']) && $this->data['type'] === 'emergency';

        return [
            'id' => $this->id,
            'title' => $this->title,
            'message' => $this->body, // Use 'body' as 'message' for the frontend
            'time' => $this->sent_at ? Carbon::parse($this->sent_at)->diffForHumans() : null, // "2 mins ago", "1 day ago"
            'type' => $this->data['type'] ?? 'general', // Get 'type' from the 'data' JSON field
            'important' => $isImportant, // You can define logic for this
            'status' => $this->status,
            'error_message' => $this->error_message,
            'user_id' => $this->user_id,
            // You can include other relevant 'data' fields directly if needed, e.g.:
            // 'related_event_id' => $this->data['event_id'] ?? null,
            // 'location_details' => $this->data['location'] ?? null,
        ];
    }
}