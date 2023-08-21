<?php

namespace App\Tourguide\Resources\Profile\ProfileResource\RelationManagers;

use App\Models\Language;
use Filament\Forms;
use Filament\Forms\Components\Select;
use Filament\Resources\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Resources\Table;
use Filament\Tables;
use Filament\Tables\Actions\AttachAction;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class LanguagesRelationManager extends RelationManager
{
    protected static string $relationship = 'languages';
    protected static ?string $inverseRelationship = 'tourguides';

    protected static ?string $recordTitleAttribute = 'languagable_id';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Card::make()
                    ->schema([
                        Forms\Components\Select::make('language_id')
                            ->label(__('attributes.language'))
                            ->placeholder(__('attributes.select', ['field' => __('attributes.language')]))
                            ->required()
                            ->searchable()
                            ->options(fn() => Language::all()->pluck('name', 'id')),

                        Forms\Components\Select::make('level')
                            ->label(__('attributes.level'))
                            ->placeholder(__('attributes.select', ['field' => __('attributes.level')]))
                            ->required()
                            ->default('basic')
                            ->options([
                                'basic' => __('attributes.basic'),
                                'intermediate' => __('attributes.intermediate'),
                                'advanced' => __('attributes.advanced'),
                            ]),

                        Forms\Components\Toggle::make('is_default')
                            ->label(__('attributes.native_language'))
                            ->hint(__('attributes.native_language_hint'))
                            ->onIcon('heroicon-o-check')
                            ->offIcon('heroicon-o-x')
                            ->required()
                            ->default(false),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->label(__('attributes.language'))
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('level')
                    ->label(__('attributes.level'))
                    ->searchable()
                    ->sortable(),

                Tables\Columns\BooleanColumn::make('is_default')
                    ->label(__('attributes.native_language'))
                    ->searchable()
                    ->sortable(),
            ])
            ->headerActions([
                Tables\Actions\AttachAction::make()
                    ->label(__('actions.add', ['resource' => __('attributes.language')]))
                    ->successNotificationTitle(__('notifications.language_added'))
                    ->form(fn(AttachAction $action): array => [
                        Forms\Components\Select::make('language_id')
                            ->label(__('attributes.language'))
                            ->placeholder(__('attributes.select', ['field' => __('attributes.language')]))
                            ->required()
                            ->searchable()
                            ->options(fn() => Language::all()->diff(auth('tourguide')->user()->languages)->pluck('name', 'id')),

                        Forms\Components\Select::make('level')
                            ->label(__('attributes.level'))
                            ->placeholder(__('attributes.select', ['field' => __('attributes.level')]))
                            ->required()
                            ->default('basic')
                            ->options([
                                'beginner' => __('attributes.beginner'),
                                'intermediate' => __('attributes.intermediate'),
                                'advanced' => __('attributes.advanced'),
                            ]),

                        Forms\Components\Toggle::make('is_default')
                            ->label(__('attributes.native_language'))
                            ->hint(__('attributes.native_language_hint'))
                            ->onIcon('heroicon-o-check')
                            ->offIcon('heroicon-o-x')
                            ->required()
                            ->default(false),
                    ])
                    ->mutateFormDataUsing(fn(array $data): array => [
                        'languagable_type' => auth('tourguide')->user()->getMorphClass(),
                        'languagable_id'   => auth('tourguide')->id(),
                        'recordId'         => $data['language_id'],
                        'is_default'       => $data['is_default'],
                        'level'            => $data['level'],
                    ])
                    ->color('primary'),
            ])
            ->actions([
                Tables\Actions\Action::make('edit')
                    ->label(__('actions.edit'))
                    ->icon('heroicon-o-pencil')
                    ->mountUsing(fn (Forms\ComponentContainer $form) =>
                    $form->fill([
                        'level'       => $form->model->level,
                        'is_default'  => $form->model->is_default,
                    ]))
                    ->action(function (array $data, $record): void {
                        auth('tourguide')->user()->languages()->updateExistingPivot($record->id, [
                            'level'      => $data['level'],
                            'is_default' => $data['is_default'],
                        ]);
                    })
                    ->form([
                        Forms\Components\Select::make('level')
                            ->label(__('attributes.level'))
                            ->placeholder(__('attributes.select', ['field' => __('attributes.level')]))
                            ->required()
                            ->default('basic')
                            ->options([
                                'beginner' => __('attributes.beginner'),
                                'intermediate' => __('attributes.intermediate'),
                                'advanced' => __('attributes.advanced'),
                            ]),

                        Forms\Components\Toggle::make('is_default')
                            ->label(__('attributes.native_language'))
                            ->hint(__('attributes.native_language_hint'))
                            ->onIcon('heroicon-o-check')
                            ->offIcon('heroicon-o-x')
                            ->required()
                            ->default(false),
                    ]),
                Tables\Actions\DetachAction::make()
                    ->label(__('actions.remove', ['resource' => __('attributes.language')]))
                    ->icon('heroicon-o-trash')
                    ->color('danger')
                    ->modalHeading(__('actions.remove', ['resource' => __('attributes.language')]))
                    ->successNotificationTitle(__('notifications.language_removed'))
            ])
            ->bulkActions([
                Tables\Actions\DetachBulkAction::make(),
                Tables\Actions\DeleteBulkAction::make(),
            ]);
    }
}
