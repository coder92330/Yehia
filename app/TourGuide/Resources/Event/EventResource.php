<?php

namespace App\TourGuide\Resources\Event;

use App\TourGuide\Resources\Event\EventResource\Pages;
use App\TourGuide\Resources\Event\EventResource\RelationManagers\DaysRelationManager;
use App\Models\Event;
use App\Services\Map;
use Filament\Forms;
use Filament\Resources\Concerns\Translatable;
use Filament\Resources\Form;
use Filament\Resources\Resource;
use Filament\Resources\Table;
use Filament\Tables;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Route;
use Iotronlab\FilamentMultiGuard\Concerns\ContextualResource;

class EventResource extends Resource
{
    use ContextualResource, Translatable;

    public static function getTranslatableLocales(): array
    {
        return config('app.locales');
    }

    protected static ?string $model = Event::class;

    protected static ?string $navigationIcon = 'heroicon-o-ticket';

    protected static ?string $slug = 'confirmed-bookings';

    public static function getLabel(): ?string
    {
        return __('navigation.labels.confirmed_bookings');
    }

    protected static function getNavigationLabel(): string
    {
        return __('navigation.labels.confirmed_bookings');
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
                        ->rules('required'),

                    Forms\Components\Select::make('agent_id')
                        ->label(__('attributes.agent'))
                        ->required()
                        ->searchable()
                        ->options(fn() => \App\Models\Agent::all()->pluck('full_name', 'id'))
                        ->placeholder(__('attributes.select', ['field' => __('attributes.agent')]))
                        ->rules('required'),

                    Forms\Components\Select::make('country_id')
                        ->label(__('attributes.location'))
                        ->required()
                        ->searchable()
                        ->options(fn() => \App\Models\Country::active()->get()->pluck('name', 'id'))
                        ->placeholder(__('attributes.select', ['field' => __('attributes.location')]))
                        ->rules('required'),

                    Forms\Components\Textarea::make('description')
                        ->label(__('attributes.description'))
                        ->required()
                        ->placeholder(__('attributes.place', ['field' => __('attributes.description')]))
                        ->rules('required'),

                    Forms\Components\DatePicker::make('start_at')
                        ->label(__('attributes.start_date'))
                        ->required()
                        ->placeholder(__('attributes.place', ['field' => __('attributes.start_date')]))
                        ->rules('required'),

                    // end date
                    Forms\Components\DatePicker::make('end_at')
                        ->label(__('attributes.end_date'))
                        ->required()
                        ->placeholder(__('attributes.place', ['field' => __('attributes.end_date')]))
                        ->rules('required'),

                    Forms\Components\SpatieMediaLibraryFileUpload::make('attachments')
                        ->hint(__('attributes.image_hint', ['formats' => 'jpeg, jpg, png', 'size' => '2MB']))
                        ->placeholder(__('attributes.image_placeholder', ['attribute' => __('attributes.event_cover')]))
                        ->label('Cover Image')
                        ->rules(['image', 'max:2048', 'mimes:jpeg,jpg,png'])
                        ->collection('event_cover_image'),

                    Map::make('location')
                        ->columnSpanFull()
                        ->disableLabel()
                        ->defaultLocation([30.033333, 31.233334]),

                    Forms\Components\Repeater::make('days')
                        ->relationship('days')
                        ->label(__('attributes.days'))
                        ->schema([
                            Forms\Components\DatePicker::make('start_at')
                                ->required()
                                ->placeholder(__('attributes.place', ['field' => __('attributes.start_date')]))
                                ->rules(['required', 'date'])
                                ->label(__('attributes.start_date')),

                            Forms\Components\DatePicker::make('end_at')
                                ->required()
                                ->placeholder(__('attributes.place', ['field' => __('attributes.end_date')]))
                                ->rules(['required', 'date', 'after_or_equal:start_at'])
                                ->label(__('attributes.end_date')),

                            Forms\Components\Textarea::make('description')
                                ->required()
                                ->placeholder(__('attributes.place', ['field' => __('attributes.description')]))
                                ->rules('required')
                                ->label(__('attributes.description')),

                            Forms\Components\Repeater::make('sessions')
                                ->relationship('sessions')
                                ->label(__('attributes.sessions'))
                                ->schema([
                                    Forms\Components\Select::make('country_id')
                                        ->label(__('attributes.location'))
                                        ->required()
                                        ->searchable()
                                        ->options(fn() => \App\Models\Country::active()->get()->pluck('name', 'id'))
                                        ->placeholder(__('attributes.select', ['field' => __('attributes.location')]))
                                        ->rules('required'),

                                    Forms\Components\TimePicker::make('start_at')
                                        ->required()
                                        ->placeholder(__('attributes.place', ['field' => __('attributes.start_date')]))
                                        ->rules(['required', 'date'])
                                        ->label(__('attributes.start_date')),

                                    Forms\Components\TimePicker::make('end_at')
                                        ->required()
                                        ->placeholder(__('attributes.place', ['field' => __('attributes.end_date')]))
                                        ->rules(['required', 'date', 'after_or_equal:start_at'])
                                        ->label(__('attributes.end_date')),

                                    Forms\Components\Textarea::make('description')
                                        ->required()
                                        ->placeholder(__('attributes.place', ['field' => __('attributes.description')]))
                                        ->label(__('attributes.description'))
                                ])
                        ])
                        ->rules('required'),
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
                            ->label(__('attributes.company.logo'))
                            ->circular()
                            ->size(25)
                            ->grow(false),

                        Tables\Columns\TextColumn::make('agent.company.name')
                            ->label(__('attributes.company.name'))
                            ->sortable(query: function (Builder $query, string $direction) {
                                return $query->whereHas('agent', fn($query) => $query->whereHas('company'))
                                    ->orderBy('name', $direction);
                            })
                    ]),

                    Tables\Columns\TextColumn::make('start_at')
                        ->label(__('attributes.event_date'))
                        ->date('M d, Y')
                        ->sortable()
                        ->searchable(),
                ])
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
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
            'view' => Pages\ViewEvent::route('/{record}'),
        ];
    }
}
