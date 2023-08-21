<?php

namespace App\Agent\Pages;

use App\Filament\Pages\Dashboard as PagesDashboard;
use Iotronlab\FilamentMultiGuard\Concerns\ContextualPage;

class Dashboard extends PagesDashboard
{
    use ContextualPage;

    protected function getViewData(): array
    {
        return [
            ...parent::getViewData(),
            ...$this->getAlert(),
        ];
    }

    private function getAlert(): array
    {
        $alerts = [];
        if (auth('agent')->user()->hasRole('admin') && auth('agent')->user()->package()->expired()->exists()) {
            $alerts[] = [
                'type' => auth('agent')->user()->package()->expired()->exists() ? 'danger' : 'warning',
                'message' => __('messages.package_expired'),
                'dismissable' => false,
            ];
        }

        if (auth('agent')->user()->hasRole('agent')) {
            auth('agent')->user()->events()
                ->whereDate('start_at', now()->addDays(2))
                ->whereHas('orders', fn($q) => $q->where([['status', 'approved'], ['agent_status', 'approved']]))
                ->each(function ($event) use (&$alerts) {
                    $alerts[] = [
                        'type' => 'info',
                        'message' => __('messages.event_starting', ['event' => $event->name, 'date' => $event->start_at->format('d M Y'), 'time' => $event->start_at->diffForHumans()]),
                        'dismissable' => true,
                    ];
                });
        }

        return ['alerts' => $alerts];
    }
}
