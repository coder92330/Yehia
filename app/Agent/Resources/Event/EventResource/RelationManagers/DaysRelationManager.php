<?php

namespace App\Agent\Resources\Event\EventResource\RelationManagers;

use App\Models\City;
use Carbon\Carbon;
use Filament\Forms;
use Filament\Resources\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Resources\Table;
use Filament\Tables;
use HusamTariq\FilamentTimePicker\Forms\Components\TimePickerField;
use function Clue\StreamFilter\fun;

class DaysRelationManager extends RelationManager
{
    protected static string $relationship = 'days';

    protected static ?string $recordTitleAttribute = 'event_id';

    public static function form(Form $form): Form
    {
        return $form
            ->columns(1)
            ->schema([
                Forms\Components\Select::make('type')
                    ->label(__('attributes.days_type'))
                    ->required()
                    ->options([
                        'full' => __('attributes.full_day'),
                        'half' => __('attributes.half_day'),
                    ])
                    ->reactive()
                    ->default('full')
                    ->placeholder(__('attributes.select', ['field' => __('attributes.days_type')]))
                    ->rules(['required', 'string', 'in:full,half']),

                Forms\Components\DatePicker::make('start_at')
                    ->required()
                    ->timezone('Africa/Cairo')
                    ->minDate(fn(RelationManager $livewire) => ($previousDay = $livewire->ownerRecord->days()->latest()->first())
                        ? $previousDay->end_at->addDay()->format('Y-m-d')
                        : Carbon::now()->format('Y-m-d'))
                    ->maxDate(fn(RelationManager $livewire) => $livewire->ownerRecord->end_at)
                    ->placeholder(__('attributes.select', ['field' => __('attributes.start_date')]))
                    ->label(__('attributes.start_date')),

                TimePickerField::make('start_time')
                    ->required()
                    ->visible(fn(callable $get) => $get('type') === 'half')
                    ->reactive()
                    ->afterStateUpdated(fn(\Closure $set, $state) => $set('end_time', Carbon::parse($state)->addHour()->format('h:i:s')))
                    ->label(__('attributes.start_time')),

                TimePickerField::make('end_time')
                    ->required()
                    ->visible(fn(callable $get) => $get('type') === 'half')
                    ->after('start_time')
                    ->label(__('attributes.end_time')),

                Forms\Components\Textarea::make('description')
                    ->required()
                    ->placeholder('Enter a description')
                    ->rules(['required', 'string', 'max:255'])
                    ->label(__('attributes.description')),

                Forms\Components\Repeater::make('sessions')
                    ->relationship('sessions')
                    ->minItems(0)
                    ->schema([
                        Forms\Components\Select::make('city_id')
                            ->label(__('attributes.session_location'))
                            ->required()
                            ->searchable()
                            ->options(fn() => City::all()->pluck('name', 'id'))
                            ->placeholder(__('attributes.select', ['field' => __('attributes.session_location')]))
                            ->rules(['required', 'exists:cities,id']),

                        TimePickerField::make('start_at')
                            ->required()
                            ->beforeOrEqual('end_at')
                            ->reactive()
                            ->afterStateUpdated(fn(\Closure $set, $state) => $set('end_at', Carbon::parse($state)->addHour()->format('h:i:s')))
                            ->label(__('attributes.start_time')),

                        TimePickerField::make('end_at')
                            ->required()
                            ->afterOrEqual('start_at')
                            ->label(__('attributes.end_time')),

                        Forms\Components\Textarea::make('description')
                            ->required()
                            ->placeholder(__('attributes.place', ['field' => __('attributes.description')]))
                            ->rules(['required', 'string', 'max:255'])
                            ->label(__('attributes.description')),
                    ])
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('type')->label(__('attributes.days_type')),
                Tables\Columns\TextColumn::make('start_at')->dateTime()->label(__('attributes.start_date')),
                Tables\Columns\TextColumn::make('end_at')->dateTime()->label(__('attributes.end_date')),
            ])
            ->filters([
                //
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make()
                    ->mutateFormDataUsing(function ($data) {
                        if ($data['type'] === 'half') {
                            if (isset($data['start_time'])) {
                                $data['start_at'] = Carbon::parse($data['start_at'])->format('Y-m-d') . ' ' . $data['start_time'];
                            }

                            if (isset($data['end_time'])) {
                                $data['end_at'] = Carbon::parse($data['start_at'])->addDay()->format('Y-m-d') . ' ' . $data['end_time'];
                            }

                            $data['is_half_day'] = true;

                            unset($data['start_time'], $data['end_time']);
                        } else {
                            if (isset($data['start_at']) && $data['type'] === 'full') {
                                $data['end_at'] = Carbon::parse($data['start_at'])->addDay();
                            }

                            $data['is_half_day'] = false;
                        }

                        return $data;
                    })
            ])
            ->actions([
                Tables\Actions\ViewAction::make()->modalHeading(fn($record) => $record->event->name),
                Tables\Actions\EditAction::make()->modalHeading(fn($record) => __('modal.edit.heading', ['name' => $record->event->name])),
                Tables\Actions\DeleteAction::make()->modalHeading(fn($record) => __('modal.delete.heading')),
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make(),
            ]);
    }
}
