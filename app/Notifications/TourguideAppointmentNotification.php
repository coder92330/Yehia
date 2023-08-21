<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class TourguideAppointmentNotification extends Notification
{
    use Queueable;


    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct()
    {
    }

    public function toDatabase($notifiable)
    {
        return Notification::make()
            ->title('You have no appointment this month, please check your calendar.')
            ->getDatabaseMessage();
    }
}
