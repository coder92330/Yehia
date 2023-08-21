<?php

namespace App\Filament\Resources\Tourguide\TourguideResource\Pages;

use App\Filament\Resources\Tourguide\TourguideResource;
use App\Models\Event;
use App\Models\Tourguide;
use Filament\Pages\Actions;
use Filament\Pages\Actions\LocaleSwitcher;
use Filament\Resources\Pages\ViewRecord;
use Illuminate\Contracts\Support\Htmlable;
use Illuminate\Database\Eloquent\Model;

class ViewTourguide extends ViewRecord
{
    use ViewRecord\Concerns\Translatable;

    protected static string $resource = TourguideResource::class;

    protected static string|array $middlewares = ['permission:View Tourguides'];

    protected static string $view = 'filament.pages.view-tourguide';

    public $event_id;

    protected function getHeading(): string|Htmlable
    {
        return '';
    }

    public function mount($record): void
    {
        parent::mount($record);
        $this->record->views()->create([
            'viewer_id' => auth()->id(),
            'viewer_type' => auth()->user()->getMorphClass(),
            'viewable_id' => $this->record->id,
            'viewable_type' => Tourguide::class,
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
            'referer' => request()->headers->get('referer'),
            'viewed_at' => now(),
        ]);
    }

    public function bookNow()
    {
        return redirect()->route('filament.pages.book-now', ['user_id' => $this->record->id]);
    }

    public function toggleFavorite()
    {
        auth()->user()->favourites()
            ->where([
                'favouritable_id' => $this->record->id,
                'favouritable_type' => Tourguide::class
            ])
            ->exists()
            ? auth()->user()->favourites()
            ->where([
                'favouritable_id' => $this->record->id,
                'favouritable_type' => Tourguide::class
            ])
            ->delete()
            : auth()->user()->favourites()->create([
            'favouritable_id' => $this->record->id,
            'favouritable_type' => Tourguide::class
        ]);

        return redirect()->back();
    }

    protected function getViewData(): array
    {
        return [...parent::getViewData(), 'events' => Event::all()];
    }

    public function chat($tourguide_id)
    {
        $data = $this->event_id
            ? ['tourguide_id' => $tourguide_id, 'event_id' => $this->event_id]
            : ['tourguide_id' => $tourguide_id];

        return $this->redirectRoute('filament.pages.chat', $data);
    }

    protected function getActions(): array
    {
        return [
            LocaleSwitcher::make(),
        ];
    }
}
