<?php

namespace App\Filament\Pages;

use Carbon\Carbon;
use Closure;
use Filament\Notifications\Notification;
use Filament\Pages\Actions\Action;
use Filament\Pages\Page;
use App\Models\{Company, Country, Event, Tourguide};
use Illuminate\Support\Facades\{DB, Log, Route};
use Filament\Resources\Pages\Concerns\UsesResourceForm;
use Iotronlab\FilamentMultiGuard\Concerns\ContextualPage;
use Filament\Forms\Components\{
    DatePicker,
    Grid,
    Placeholder,
    Repeater,
    Select,
    SpatieMediaLibraryFileUpload,
    Textarea,
    TextInput,
    TimePicker
};

class NewEvent extends Page
{
    use UsesResourceForm;

    protected static ?string $navigationIcon = 'heroicon-o-plus';

    protected static string $view = 'filament.pages.new-event';

    protected static bool $shouldRegisterNavigation = false;

    public $tourguide_id;

    protected static function getNavigationLabel(): string
    {
        return __('navigation.labels.new-event');
    }

    public static function getRoutes(): Closure
    {
        return fn() => Route::get('/new-event/{tourguide_id}', static::class)->name('new-event');
    }

    public function getHeading(): string|\Illuminate\Contracts\Support\Htmlable
    {
        return '';
    }

    protected function getFormSchema(): array
    {
        return [
            Grid::make(4)
                ->schema([
                    TextInput::make('name')
                        ->columnSpan(2)
                        ->label('Event Name')
                        ->autofocus()
                        ->required()
                        ->placeholder('Enter a name'),

                    DatePicker::make('start_at')
                        ->columnSpan(2)
                        ->label('Select Event Date')
                        ->required()
                        ->placeholder('Enter a start date')
                        ->minDate(now()->format('Y-m-d'))
                        ->hint('Choose the exact period of the event'),

                    Textarea::make('description')
                        ->columnSpan(2)
                        ->label('Event Description')
                        ->required()
                        ->placeholder('Enter a description'),

                    Repeater::make('days')
                        ->columnSpan(2)
                        ->label('Event Days')
                        ->columns(2)
                        ->schema([
                            DatePicker::make('start_at')
                                ->label('Start Date')
                                ->required()
                                ->placeholder('Enter a start date')
                                ->reactive()
                                ->afterStateUpdated(function (\Closure $set, $state) {
                                    $set('end_at', Carbon::parse($state)->addDay()->format('Y-m-d'));
                                }),

                            DatePicker::make('end_at')
                                ->label('End Date')
                                ->required()
                                ->placeholder('Enter a end date')
                                ->after('start_at')
                                ->reactive()
                                ->minDate(now()->format('Y-m-d'))
                                ->rules(['required', 'date', 'after_or_equal:start_at']),

                            Textarea::make('description')
                                ->columnSpanFull()
                                ->disableLabel()
                                ->required()
                                ->placeholder('Enter a description'),

                            Repeater::make('sessions')
                                ->label('Day Sessions')
                                ->columnSpanFull()
                                ->columns(2)
                                ->schema([
                                    Select::make('country_id')
                                        ->label('Location')
                                        ->columnSpanFull()
                                        ->required()
                                        ->searchable()
                                        ->options(fn() => \App\Models\Country::active()->get()->pluck('name', 'id'))
                                        ->placeholder('Select a location')
                                        ->rules('required'),

                                    TimePicker::make('start_at')
                                        ->required()
                                        ->columnSpan(1)
                                        ->placeholder('Enter a start date')
                                        ->rules(['required', 'date'])
                                        ->label('Start Date'),

                                    TimePicker::make('end_at')
                                        ->required()
                                        ->columnSpan(1)
                                        ->placeholder('Enter an end date')
                                        ->rules(['required', 'date', 'after_or_equal:start_at'])
                                        ->label('End Date'),

                                    Textarea::make('description')
                                        ->required()
                                        ->columnSpanFull()
                                        ->placeholder('Enter a description')
                                        ->label('Description'),
                                ])
                        ]),

                    Select::make('country_id')
                        ->required()
                        ->searchable()
                        ->columnSpan(2)
                        ->label('Location of Event')
                        ->options(Country::active()->get()->pluck('name', 'id'))
                        ->placeholder('Select a location'),

                    Placeholder::make('location')
                        ->columnSpan(2)
                        ->label('Location of Event')
                        ->hint('Drag the marker to the exact location of the event'),

//                    Map::make('location')
//                        ->columnSpan(2)
//                        ->label('Location of Event')
//                        ->required()
//                        ->hint('Drag the marker to the exact location of the event'),

                    SpatieMediaLibraryFileUpload::make('event_cover')
                        ->columnSpan(2)
                        ->hint('Accepted formats: jpeg, jpg, png. Max file size 2MB.')
                        ->rules(['image', 'max:2048', 'mimes:jpeg,jpg,png'])
                        ->label('Event Cover'),
                ]),
        ];
    }

    public function mount(): void
    {
        abort_unless(auth()->user()->hasPermissionTo('New Event'), 403, __('messages.unauthorized'));
        $this->tourguide_id = request()->tourguide_id;
        $this->form->fill();
    }

    protected function getViewData(): array
    {
        return [
            'company' => auth()->user()->company ?? Company::first(),
            'tourguide' => Tourguide::find(request()->tourguide_id),
        ];
    }

    public function createEvent()
    {
        try {
            DB::beginTransaction();
            $data = $this->form->getState();
            $event = auth()->user()->events()->create([
                'name' => $data['name'],
                'description' => $data['description'],
                'agent_id' => auth()->id(),
                'country_id' => $data['country_id'],
                'start_at' => $data['start_at'],
                'end_at' => $data['end_at'] ?? Carbon::parse($data['start_at'])->addDays(1),
            ]);
            $event->days()->createMany($data['days']);
            $event->days->each(fn($day) => $day->sessions()->createMany($day['sessions']));
            $order = auth()->user()->orders()->create(['event_id' => $event->id, 'tourguide_id' => $this->tourguide_id]);
            DB::commit();
            $this->form->fill();
            Notification::make()->success()->title('Order Created Successfully')->send();
            $this->redirectRoute('filament.resources.confirmed-bookings.view', ['record' => $order->id]);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::channel()->error("Error in NewEvent@createEvent, Error: {$e->getMessage()} in File: {$e->getFile()} on Line: {$e->getLine()}");
            Notification::make()->danger()->title('Something went wrong')->send();
            $this->redirectRoute('filament.resources.confirmed-bookings.view', ['record' => $order->id]);
        }
    }
}
