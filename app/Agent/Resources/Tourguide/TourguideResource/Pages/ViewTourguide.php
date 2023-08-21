<?php

namespace App\Agent\Resources\Tourguide\TourguideResource\Pages;

use App\Agent\Resources\Tourguide\TourguideResource;
use App\Models\Event;
use App\Models\Tourguide;
use Filament\Notifications\Notification;
use Filament\Pages\Actions;
use Filament\Pages\Actions\LocaleSwitcher;
use Filament\Resources\Pages\ViewRecord;
use Illuminate\Contracts\Support\Htmlable;
use Iotronlab\FilamentMultiGuard\Concerns\ContextualPage;

class ViewTourguide extends ViewRecord
{
    use ContextualPage, ViewRecord\Concerns\Translatable;

    protected static string $resource = TourguideResource::class;

    protected static string $view = 'filament.pages.agent.view-tourguide';

    public $event_id;

    protected function getHeading(): string|Htmlable
    {
        return '';
    }

    public function mount($record): void
    {
        parent::mount($record);
        $this->record->views()->create([
            'viewer_id'     => auth('agent')->id(),
            'viewer_type'   => auth('agent')->user()->getMorphClass(),
            'viewable_id'   => $this->record->id,
            'viewable_type' => Tourguide::class,
            'ip_address'    => request()->ip(),
            'user_agent'    => request()->userAgent(),
            'referer'       => request()->headers->get('referer'),
            'viewed_at'     => now(),
        ]);
    }

    public function toggleFavorite()
    {
        if (auth('agent')->user()->favourites()->where(['favouritable_id' => $this->record->id, 'favouritable_type' => Tourguide::class])->exists()) {
            auth('agent')->user()->favourites()
                ->where([
                    'favouritable_id' => $this->record->id,
                    'favouritable_type' => Tourguide::class
                ])
                ->delete();
            Notification::make()->success()
                ->title(__('attributes.tourguide_removed_from_favourites', ['tourguide' => Tourguide::find($this->record->id)->full_name]))
                ->send();
        } else {
            auth('agent')->user()->favourites()->create([
                'favouritable_id' => $this->record->id,
                'favouritable_type' => Tourguide::class
            ]);
            Notification::make()->success()
                ->title(__('attributes.tourguide_added_to_favourites', ['tourguide' => Tourguide::find($this->record->id)->full_name]))
                ->send();
        }
    }

    protected function getViewData(): array
    {
        return [...parent::getViewData(), 'events' => Event::all()];
    }

    public function chat($tourguide_id)
    {
        return $this->event_id
            ? $this->redirectRoute('agent.pages.chat', ['tourguide_id' => $tourguide_id, 'event_id' => $this->event_id])
            : $this->redirectRoute('agent.pages.chat', ['tourguide_id' => $tourguide_id]);
    }

    protected function getActions(): array
    {
        return [
            LocaleSwitcher::make(),
        ];
    }
}
