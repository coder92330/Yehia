<?php

namespace App\Filament\Resources\Event;

use App\Filament\Resources\Event\EventResource\Pages;
use App\Filament\Resources\Event\EventResource\RelationManagers\DaysRelationManager;
use App\Models\Agent;
use App\Models\City;
use App\Services\Map;
use Filament\Forms;
use Filament\Resources\Concerns\Translatable;
use Filament\Resources\Form;
use Filament\Resources\Resource;
use Filament\Resources\Table;
use Filament\Tables;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Route;

class EventResource extends Resource
{
    use Translatable;

    public static function getTranslatableLocales(): array
    {
        return config('app.locales');
    }

    protected static ?string $model = \App\Models\Event::class;

    protected static ?string $navigationIcon = 'heroicon-o-ticket';

    protected static ?string $slug = 'bookings';

    protected static string | array $middlewares = ['permission:List Bookings|Create Bookings|Edit Bookings|View Bookings'];

    protected static function getNavigationGroup(): ?string
    {
        return __('navigation.groups.bookings');
    }

    protected static function getNavigationLabel(): string
    {
        return __('navigation.labels.bookings');
    }

    public static function getLabel(): ?string
    {
        return __('navigation.labels.bookings');
    }

    protected static function shouldRegisterNavigation(): bool
    {
        return auth()->user()->hasAnyPermission(['List Bookings', 'Create Bookings', 'Edit Bookings', 'View Bookings']);
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Card::make()->schema([

                    Forms\Components\TextInput::make('name')
                        ->label(__('attributes.event'))
                        ->autofocus()
                        ->required()
                        ->placeholder(__('attributes.place', ['field' => __('attributes.event')]))
                        ->rules(['required', 'string', 'max:80']),

                    Forms\Components\Select::make('agent_id')
                        ->label(__('attributes.agent'))
                        ->required()
                        ->options(fn() => Agent::all()->pluck('full_name', 'id'))
                        ->rules(['required', 'exists:agents,id']),

                    Forms\Components\Select::make('city_id')
                        ->label(__('attributes.event_location'))
                        ->required()
                        ->searchable()
                        ->options(fn() => City::all()->pluck('name', 'id'))
                        ->placeholder(__('attributes.select', ['field' => __('attributes.event_location')]))
                        ->rules(['required', 'exists:cities,id']),

                    Forms\Components\Textarea::make('description')
                        ->required()
                        ->placeholder(__('attributes.place', ['field' => __('attributes.description')]))
                        ->rules(['required', 'string', 'max:255']),

                    Forms\Components\DatePicker::make('start_at')
                        ->label(__('attributes.start_date'))
                        ->required()
                        ->placeholder(__('attributes.place', ['field' => __('attributes.start_date')]))
                        ->beforeOrEqual('end_at'),

                    Forms\Components\DatePicker::make('end_at')
                        ->label(__('attributes.end_date'))
                        ->required()
                        ->afterOrEqual('start_at')
                        ->placeholder(__('attributes.place', ['field' => __('attributes.end_date')])),

                    Forms\Components\SpatieMediaLibraryFileUpload::make('event_cover_image')
                        ->label(__('attributes.event_cover'))
                        ->hint(__('attributes.image_hint', ['formats' => 'jpeg, jpg, png', 'size' => '2MB']))
                        ->placeholder(__('attributes.image_placeholder', ['attribute' => __('attributes.event_cover')]))
                        ->rules(['required', 'image', 'max:2048', 'mimes:jpeg,jpg,png'])
                        ->collection('event_cover_image'),

                    Map::make('location')
                        ->label(__('attributes.location'))
                        ->autocomplete(fieldName: 'full_address')
                        ->autocompleteReverse(true)
                        ->columnSpanFull()
                        ->disableLabel()
                        ->geolocate(fn() => !Route::Is('filament.resources.bookings.view'))
                        ->geolocateLabel('Use My Location')
                        ->defaultLocation([30.033333, 31.233334]),

                    Forms\Components\Repeater::make('days')
                        ->relationship('days')
                        ->label(__('attributes.event_days'))
                        ->rules(['required', 'array'])
                        ->minItems(0)
                        ->schema([
                            Forms\Components\DatePicker::make('start_at')
                                ->label(__('attributes.start_date'))
                                ->required()
                                ->placeholder(__('attributes.place', ['field' => __('attributes.start_date')]))
                                ->beforeOrEqual('end_at'),

                            Forms\Components\DatePicker::make('end_at')
                                ->required()
                                ->placeholder(__('attributes.place', ['field' => __('attributes.end_date')]))
                                ->afterOrEqual('start_at')
                                ->label(__('attributes.end_date')),

                            Forms\Components\Textarea::make('description')
                                ->required()
                                ->placeholder(__('attributes.place', ['field' => __('attributes.description')]))
                                ->rules(['required', 'string', 'max:255'])
                                ->label(__('attributes.description')),

                            Forms\Components\Repeater::make('sessions')
                                ->relationship('sessions')
                                ->label(__('attributes.event_sessions'))
                                ->minItems(0)
                                ->schema([
                                    Forms\Components\Select::make('city_id')
                                        ->label(__('attributes.event_location'))
                                        ->required()
                                        ->searchable()
                                        ->options(fn() => City::all()->pluck('name', 'id'))
                                        ->placeholder(__('attributes.select', ['field' => __('attributes.event_location')]))
                                        ->rules(['required', 'exists:cities,id']),

                                    Forms\Components\TimePicker::make('start_at')
                                        ->required()
                                        ->placeholder(__('attributes.place', ['field' => __('attributes.start_date')]))
                                        ->beforeOrEqual('end_at')
                                        ->label(__('attributes.start_date')),

                                    Forms\Components\TimePicker::make('end_at')
                                        ->required()
                                        ->placeholder(__('attributes.place', ['field' => __('attributes.end_date')]))
                                        ->afterOrEqual('start_at')
                                        ->label(__('attributes.end_date')),

                                    Forms\Components\Textarea::make('description')
                                        ->required()
                                        ->placeholder(__('attributes.place', ['field' => __('attributes.description')]))
                                        ->rules(['required', 'string', 'max:255'])
                                        ->label(__('attributes.description'))
                                ])
                        ])
                ])
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\Layout\Split::make([
                    Tables\Columns\Layout\Stack::make([
                        Tables\Columns\TextColumn::make('name')
                            ->size('lg')
                            ->label(__('attributes.event'))
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
                            ->label(__('attributes.company.name'))
                            ->sortable(query: function (Builder $query, string $direction) {
                                return $query->whereHas('agent', fn($query) => $query->whereHas('company'))
                                    ->orderBy('name', $direction);
                            }),

                        Tables\Columns\TextColumn::make('agent.full_name')
                            ->label(__('attributes.agent'))
                            ->visible(fn() => auth()->user()->whereHas('roles', function ($query) {
            $query->where([['name', 'admin'], ['guard_name', 'agent']])
                ->orWhere([['name', 'super_admin'], ['guard_name', 'agent']]);
        }))
                    ]),

                    Tables\Columns\TextColumn::make('start_at')
                        ->label(__('attributes.start_date'))
                        ->dateTime()
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
            //
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
