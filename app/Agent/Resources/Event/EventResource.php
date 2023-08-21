<?php

namespace App\Agent\Resources\Event;

use Carbon\Carbon;
use App\Services\Map;
use Filament\Forms;
use Filament\Tables;
use App\Models\{Agent, City, Event};
use Filament\Resources\{Concerns\Translatable, Form, RelationManagers\RelationManager, Resource, Table};
use App\Agent\Resources\Event\EventResource\Pages;
use HusamTariq\FilamentTimePicker\Forms\Components\TimePickerField;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Route;
use Iotronlab\FilamentMultiGuard\Concerns\ContextualResource;
use App\Agent\Resources\Event\EventResource\RelationManagers\DaysRelationManager;
use function Symfony\Component\String\s;

class EventResource extends Resource
{
    use ContextualResource, Translatable;

    public static function getTranslatableLocales(): array
    {
        return config('app.locales');
    }

    protected static ?string $model = Event::class;

    protected static ?string $navigationIcon = 'heroicon-o-ticket';

    protected static ?string $slug = 'bookings';

    protected static string|array $middlewares = 'permission:List Bookings|View Bookings|Create Bookings|Edit Bookings';

    protected static ?int $navigationSort = 4;

    public static function getLabel(): ?string
    {
        return __('navigation.labels.bookings');
    }

    protected static function getNavigationLabel(): string
    {
        return __('navigation.labels.bookings');
    }

    protected static function getNavigationGroup(): ?string
    {
        return __('navigation.groups.bookings');
    }

    protected static function shouldRegisterNavigation(): bool
    {
        return auth('agent')->user()->hasAnyPermission(['List Bookings', 'View Bookings', 'Create Bookings', 'Edit Bookings']);
    }

    protected static function getNavigationBadge(): ?string
    {
        return auth('agent')->user()->whereHas('roles', function ($query) {
            $query->where([['name', 'admin'], ['guard_name', 'agent']])
                ->orWhere([['name', 'super_admin'], ['guard_name', 'agent']]);
        })
            ? Event::whereRelation('agent.company', 'id', auth('agent')->user()->company_id)->count()
            : Event::whereAgentId(auth('agent')->id())->count();
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Card::make()->schema([
                    Forms\Components\TextInput::make('name')
                        ->label(__('attributes.name'))
                        ->autofocus()
                        ->required()
                        ->placeholder(__('attributes.place', ['field' => __('attributes.name')]))
                        ->rules(['required', 'string', 'max:80']),

                    Forms\Components\Select::make('agent_id')
                        ->label(__('attributes.agent'))
                        ->required()
                        ->searchable()
                        ->visible(fn() => auth('agent')->user()->admins()->exists())
                        ->options(fn() => Agent::staffs()
                            ->whereCompanyId(auth('agent')->user()->company_id)
                            ->get()
                            ->pluck('full_name', 'id'))
                        ->placeholder(__('attributes.select', ['field' => __('attributes.agent')]))
                        ->rules(['required', 'exists:agents,id']),

                    Forms\Components\Select::make('city_id')
                        ->label(__('attributes.location'))
                        ->required()
                        ->searchable()
                        ->options(fn() => City::all()->pluck('name', 'id'))
                        ->placeholder(__('attributes.select', ['field' => __('attributes.location')]))
                        ->rules(['required', 'exists:cities,id']),

                    Forms\Components\Textarea::make('description')
                        ->label(__('attributes.description'))
                        ->required()
                        ->placeholder(__('attributes.place', ['field' => __('attributes.description')]))
                        ->rules(['required', 'string']),

                    Forms\Components\Select::make('days_type')
                        ->options([
                            'multi' => __('attributes.multi_days'),
                            'full' => __('attributes.full_day'),
                            'half' => __('attributes.half_day'),
                            'none' => __('attributes.none'),
                        ])
                        ->reactive()
                        ->default('multi')
                        ->placeholder(__('attributes.select', ['field' => __('attributes.event_type')]))
                        ->rules(['string', 'in:full,half,multi,none'])
                        ->label(__('attributes.event_type')),

                    Forms\Components\DatePicker::make('start_at')
                        ->label(__('attributes.start_date'))
                        ->reactive()
                        ->required()
                        ->placeholder(__('attributes.place', ['field' => __('attributes.start_date')]))
                        ->beforeOrEqual(fn(callable $get) => $get('days_type') === 'multi' ? $get('end_at') : null),

                    Forms\Components\DatePicker::make('end_at')
                        ->label(__('attributes.end_date'))
                        ->required()
                        ->visible(fn(callable $get) => $get('days_type') === 'multi')
                        ->minDate(fn(callable $get) => Carbon::parse($get('start_at'))->addDay()->format('Y-m-d'))
                        ->afterOrEqual('start_at')
                        ->placeholder(__('attributes.place', ['field' => __('attributes.end_date')])),

                    Forms\Components\SpatieMediaLibraryFileUpload::make('event_cover_image')
                        ->hint(__('attributes.image_hint', ['formats' => 'jpeg, jpg, png', 'size' => '2MB']))
                        ->placeholder(__('attributes.image_placeholder', ['attribute' => __('attributes.cover_image')]))
                        ->rules(['required', 'image', 'max:2048', 'mimes:jpeg,jpg,png'])
                        ->label(__('attributes.cover_image'))
                        ->collection('event_cover_image'),

                    Forms\Components\TextInput::make('full_address')
                        ->label(__('attributes.meeting_point'))
                        ->placeholder(__('attributes.search', ['field' => __('attributes.meeting_point')]))
                        ->hint(__('attributes.meeting_point_hint')),

                    Map::make('location')
                        ->autocomplete(fieldName: 'full_address')
                        ->autocompleteReverse(true)
                        ->columnSpanFull()
                        ->disableLabel()
                        ->geolocate(fn() => !Route::Is('agent.resources.bookings.view'))
                        ->geolocateLabel(__('attributes.use_current_location'))
                        ->defaultLocation([30.033333, 31.233334]),

                    ...static::multiDaysForm(),

                    ...static::fullDaysForm(),

                    ...static::halfDaysForm(),
                ])
            ]);
    }

    private static function multiDaysForm(): array
    {
        return [
            Forms\Components\Repeater::make('days')
                ->relationship('days')
                ->label(__('attributes.days'))
                ->minItems(0)
                ->maxItems(fn(callable $get) => $get('start_at') && $get('end_at') ? Carbon::parse($get('start_at'))->diffInDays(Carbon::parse($get('end_at'))) : 0)
                ->visible(fn(callable $get) => $get('days_type') === 'multi')
                ->mutateRelationshipDataBeforeCreateUsing(function ($data, callable $get) {
                    $data['start_at'] = Carbon::parse($get('start_at'))->format('Y-m-d') . ' ' . $data['start_time'];
                    $data['end_at'] = Carbon::parse($get('start_at'))->addDay()->format('Y-m-d') . ' ' . $data['end_time'];
                    $data['type'] = 'multi';
                    return $data;
                })
                ->mutateRelationshipDataBeforeSaveUsing(function ($data, callable $get) {
                    $data['start_at'] = Carbon::parse($get('start_at'))->format('Y-m-d') . ' ' . $data['start_time'];
                    $data['end_at'] = Carbon::parse($get('start_at'))->addDay()->format('Y-m-d') . ' ' . $data['end_time'];
                    $data['type'] = 'multi';
                    return $data;
                })
                ->schema([
                    TimePickerField::make('start_time')
                        ->required()
                        ->reactive()
                        ->afterStateUpdated(fn(\Closure $set, $state) => $set('end_time', Carbon::parse($state)->addHour()->format('h:i:s')))
                        ->label(__('attributes.start_time')),

                    TimePickerField::make('end_time')
                        ->required()
//                        ->after('start_time')
                        ->label(__('attributes.end_time')),

                    Forms\Components\Textarea::make('description')
                        ->required()
                        ->placeholder(__('attributes.place', ['field' => __('attributes.description')]))
                        ->rules(['required', 'string', 'max:255'])
                        ->label(__('attributes.description')),

                    ...static::sessionForm(),
                ]),
        ];
    }

    private static function fullDaysForm(): array
    {
        return [
            Forms\Components\Repeater::make('days')
                ->relationship('days')
                ->label(__('attributes.days'))
                ->minItems(0)
                ->maxItems(1)
                ->visible(fn(callable $get) => $get('days_type') === 'full')
                ->mutateRelationshipDataBeforeCreateUsing(fn($data) => self::handleHalfAndFullDays($data, 'full'))
                ->mutateRelationshipDataBeforeSaveUsing(fn($data) => self::handleHalfAndFullDays($data, 'full'))
                ->schema([
                    TimePickerField::make('start_time')
                        ->required()
                        ->label(__('attributes.start_time')),

                    TimePickerField::make('end_time')
                        ->required()
//                        ->after('start_at')
                        ->label(__('attributes.end_time')),

                    Forms\Components\Textarea::make('description')
                        ->required()
                        ->placeholder(__('attributes.place', ['field' => __('attributes.description')]))
                        ->rules(['required', 'string', 'max:255'])
                        ->label(__('attributes.description')),

                    ...static::sessionForm(),
                ]),
        ];
    }

    private static function halfDaysForm(): array
    {
        return [
            Forms\Components\Repeater::make('days')
                ->relationship('days')
                ->label(__('attributes.days'))
                ->minItems(0)
                ->maxItems(1)
                ->visible(fn(callable $get) => $get('days_type') === 'half')
                ->mutateRelationshipDataBeforeCreateUsing(fn($data) => self::handleHalfAndFullDays($data, 'half'))
                ->mutateRelationshipDataBeforeSaveUsing(fn($data) => self::handleHalfAndFullDays($data, 'half'))
                ->schema([
                    TimePickerField::make('start_time')
                        ->required()
                        ->label(__('attributes.start_time')),

                    TimePickerField::make('end_time')
                        ->required()
//                        ->after('start_time')
                        ->label(__('attributes.end_time')),

                    Forms\Components\Textarea::make('description')
                        ->required()
                        ->placeholder(__('attributes.place', ['field' => __('attributes.description')]))
                        ->rules(['required', 'string', 'max:255'])
                        ->label(__('attributes.description')),

                    ...static::sessionForm(),
                ]),
        ];
    }

    private static function sessionForm(): array
    {
        return [
            Forms\Components\Repeater::make('sessions')
                ->relationship('sessions')
                ->minItems(0)
                ->schema([
                    Forms\Components\Select::make('city_id')
                        ->label(__('attributes.session_location'))
                        ->required()
                        ->searchable()
                        ->options(fn() => City::all()->pluck('name', 'id'))
                        ->placeholder(__('attributes.search', ['field' => __('attributes.session_location')]))
                        ->rules(['required', 'exists:cities,id']),

                    TimePickerField::make('start_at')
                        ->required()
//                        ->beforeOrEqual('end_at')
                        ->label(__('attributes.start_time')),

                    TimePickerField::make('end_at')
                        ->required()
//                        ->afterOrEqual('start_at')
                        ->label(__('attributes.end_time')),

                    Forms\Components\Textarea::make('description')
                        ->required()
                        ->placeholder(__('attributes.place', ['field' => __('attributes.description')]))
                        ->rules(['required', 'string', 'max:255'])
                        ->label(__('attributes.description')),
                ])
        ];
    }

    private static function handleHalfAndFullDays($data, $type)
    {
        $data['type'] = $type;

        if (isset($data['start_time'])) {
            $data['start_at'] = Carbon::parse(now()->format('Y-m-d'))->format('Y-m-d') . ' ' . $data['start_time'];
        }

        if (isset($data['end_time'])) {
            $data['end_at'] = Carbon::parse(now()->format('Y-m-d'))->format('Y-m-d') . ' ' . $data['end_time'];
        }

        return $data;
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\Layout\Split::make([
                    Tables\Columns\Layout\Stack::make([
                        Tables\Columns\TextColumn::make('name')
                            ->size('lg')
                            ->label(__('attributes.name'))
                            ->sortable()
                            ->searchable(),

                        Tables\Columns\Layout\View::make('filament.table.bookings')
                    ]),

                    Tables\Columns\Layout\Split::make([
                        Tables\Columns\ImageColumn::make('agent.company.logo')
                            ->label(__('attributes.company_logo'))
                            ->circular()
                            ->size(25)
                            ->grow(false),

                        Tables\Columns\TextColumn::make('agent.company.name')
                            ->label(__('attributes.company_name'))
                            ->sortable(query: function (Builder $query, string $direction) {
                                return $query->whereHas('agent', fn($query) => $query->whereHas('company'))
                                    ->orderBy('name', $direction);
                            }),

                        Tables\Columns\TextColumn::make('agent.full_name')
                            ->label(__('attributes.agent_name'))
                            ->visible(fn() => auth('agent')->user()->whereHas('roles', function ($query) {
                                $query->where([['name', 'admin'], ['guard_name', 'agent']])
                                    ->orWhere([['name', 'super_admin'], ['guard_name', 'agent']]);
                            }))
                    ]),

                    Tables\Columns\TextColumn::make('start_at')
                        ->label(__('attributes.start_date'))
                        ->date()
                        ->sortable()
                        ->searchable(),
                ])
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make(),
            ]);
    }

    public static function getRelations(): array
    {
        return [
//            DaysRelationManager::class
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListEvents::route('/'),
            'create' => Pages\CreateEvent::route('/create'),
            'view' => Pages\ViewEvent::route('/{record}'),
            'edit' => Pages\EditEvent::route('/{record}/edit'),
        ];
    }
}
