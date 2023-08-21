<?php

namespace App\Notifications;

use App\Facades\Firebase;
use Illuminate\Bus\Queueable;
use App\Services\FilamentNotifications;
use Illuminate\Notifications\Notification;


class DatabaseNotification extends Notification
{
    use Queueable;

    public string $title;
    public string $body;
    public string $type;
    public mixed $details;
    public mixed $unreadNotificationsCount;

    public function __construct(string $title, string $body = null, $type = null, $details = null, $unreadNotificationsCount = null)
    {
        $this->title = $title;
        $this->body = $body;
        $this->type = $type;
        $this->details = $details;
        $this->unreadNotificationsCount = $unreadNotificationsCount;
    }

    public function via($notifiable): array
    {
        return ['database'];
    }

    public function toDatabase($notifiable): array
    {
        $this->sendFirebaseNotifications($notifiable);

        return $this->body
            ? FilamentNotifications::make()
                ->title($this->title)
                ->body($this->body)
                ->type($this->type)
                ->details($this->details)
                ->getDatabaseMessage()
            : FilamentNotifications::make()->title($this->title)->getDatabaseMessage();
    }

    private function sendFirebaseNotifications($notifiable): void
    {
        Firebase::withTitle($this->title)
            ->withBody($this->body)
            ->withModel($notifiable)
            ->withToken($notifiable->device_key)
            ->withAdditionalData([
                'type'                       => $this->type,
                'details'                    => json_encode($this->details),
                'unread_notifications_count' => $this->unreadNotificationsCount ?? $notifiable->unreadNotifications()->count()
            ])->send();
    }
}
