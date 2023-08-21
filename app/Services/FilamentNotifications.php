<?php

namespace App\Services;

use Filament\Notifications\Notification;

class FilamentNotifications extends Notification
{
    private string $type;
    private mixed $details;

    public function type($type)
    {
        $this->type = $type;
        return $this;
    }

    public function details($details)
    {
        $this->details = $details;
        return $this;
    }

    public function toArray(): array
    {
        return [
            ...parent::toArray(),
            'type' => $this->type,
            'details' => $this->details
        ];
    }
}
