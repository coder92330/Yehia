<?php

namespace App\Agent\Pages;

use App\Agent\Resources\Event\EventResource\Pages\ListEvents;
use App\Notifications\DatabaseNotification;
use Carbon\Carbon;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Notifications\Notification;
use Filament\Pages\Actions\Action;
use Filament\Pages\Page;
use App\Models\{City, Country, Event, Order, Tourguide};
use Illuminate\Support\Facades\{DB, Log, Route};
use Filament\Resources\Pages\Concerns\UsesResourceForm;
use Illuminate\Database\Eloquent\Model;
use Iotronlab\FilamentMultiGuard\Concerns\ContextualPage;
use Filament\Forms\Components\{DatePicker,
    Grid,
    Radio,
    Repeater,
    Select,
    SpatieMediaLibraryFileUpload,
    Textarea,
    TextInput,
    TimePicker
};
use Suleymanozev\FilamentRadioButtonField\Forms\Components\RadioButton;

class NewEvent extends Page
{
    use ContextualPage, UsesResourceForm, InteractsWithForms;

    protected static ?string $navigationIcon = 'heroicon-o-plus';
    protected static string $view = 'filament.pages.agent.new-event';
    protected static bool $shouldRegisterNavigation = false;
    public $tourguide_id;
    public $selectedRecord;
    public $search;

    protected static function getNavigationLabel(): string
    {
        return __('navigation.labels.new-event');
    }

    public static function getRoutes(): \Closure
    {
        return fn() => Route::get('/new-event/{tourguide_id}', static::class)->name('new-event');
    }

    public function getHeading(): string|\Illuminate\Contracts\Support\Htmlable
    {
        return '';
    }

    public function mount(): void
    {
        $this->tourguide_id = request()->tourguide_id;
    }

    protected function getViewData(): array
    {
        return [
            'company' => auth('agent')->user()->company,
            'tourguide' => Tourguide::find($this->tourguide_id),
            'events' => Event::whereHas('agent.company', fn($q) => $q->where('id', auth('agent')->user()->company->id))
                ->where('end_at', '>=', Carbon::now()->toDateString())
                ->when($this->search, fn($q) => $q->where('name', 'like', "%$this->search%"))
                ->latest()
                ->paginate(10)
        ];
    }

    public function create($event_id): void
    {
        try {
            if (($tourguide = Tourguide::find($this->tourguide_id)) && $tourguide->canAssignToEvent($event_id)) {
                if ($tourguide->availableForBooking($event_id)) {
                    $checkTourguide = auth('agent')->user()->orders()
                        ->where('event_id', $event_id)
                        ->whereHas('tourguides', fn($q) => $q->where('tourguide_id', $this->tourguide_id))
                        ->doesntExist();

                    if ($checkTourguide) {
                        DB::beginTransaction();
                        $order = auth('agent')->user()->orders()->where('event_id', $event_id)->first()
                            ?? auth('agent')->user()->orders()->create(['event_id' => $event_id]);
                        $order->tourguides()->attach($this->tourguide_id);
                        DB::commit();
                        Notification::make()->success()
                            ->title(__('notifications.booking.created.title'))
                            ->body(__('notifications.booking.created.body', ['event' => $order->event->name, 'user' => auth('agent')->user()->full_name]))
                            ->send();
                        $tourguide->notify(new DatabaseNotification(
                            __('notifications.booking.created.title'),
                            __('notifications.booking.created.body', ['event' => $order->event->name, 'user' => auth('agent')->user()->full_name,]),
                            'order',
                            ['id' => $order->id],
                        ));
                        $this->redirectRoute('agent.resources.confirmed-bookings.view', ['record' => $order->id]);
                    } else {
                        Notification::make()->danger()->title(__('notifications.booking.tourguide-already-booked'))->send();
                    }
                } else {
                    Notification::make()->danger()->title(__('notifications.booking.tourguide-not-available'))->send();
                }
            } else {
                Notification::make()->danger()->title(__('notifications.booking.tourguide-cant-assign-to-event', ['day_type' => Event::find($event_id)->days()?->pluck('type')->unique()->first()]))->send();
            }
        } catch (\Exception $e) {
            DB::rollBack();
            Log::channel('agent')->error("Error in NewEvent@createEvent, Error: {$e->getMessage()} in File: {$e->getFile()} on Line: {$e->getLine()}");
            Notification::make()->danger()->title(__('messages.something_went_wrong'))->send();
        }
    }
}
