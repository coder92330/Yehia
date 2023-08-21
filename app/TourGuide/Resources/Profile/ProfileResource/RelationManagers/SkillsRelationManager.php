<?php

namespace App\Tourguide\Resources\Profile\ProfileResource\RelationManagers;

use App\Models\Skill;
use Filament\Forms;
use Filament\Resources\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Resources\Table;
use Filament\Tables;
use Filament\Tables\Actions\AttachAction;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class SkillsRelationManager extends RelationManager
{
    protected static string $relationship = 'skills';

    protected static ?string $recordTitleAttribute = 'skillable_id';

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')->label(__('attributes.skill')),
                Tables\Columns\TextColumn::make('level')->label(__('attributes.level')),
            ])
            ->headerActions([
                Tables\Actions\AttachAction::make()
                    ->label(__('buttons.add_skill'))
                    ->successNotificationTitle(__('notifications.skill_added'))
                    ->form(fn(AttachAction $action): array => [
                        Forms\Components\Select::make('skill_id')
                            ->label(__('attributes.skill'))
                            ->placeholder(__('attributes.select', ['field' => __('attributes.skill')]))
                            ->required()
                            ->searchable()
                            ->options(fn() => Skill::all()->diff(auth('tourguide')->user()->skills)->pluck('name', 'id')),

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
                    ])
                    ->mutateFormDataUsing(fn(array $data): array => [
                        'skillable_id'   => auth('tourguide')->id(),
                        'skillable_type' => auth('tourguide')->user()->getMorphClass(),
                        'recordId'       => $data['skill_id'],
                        'level'          => $data['level'],
                    ])
                    ->color('primary')
            ])
            ->actions([
                Tables\Actions\Action::make('edit')
                    ->label(__('buttons.edit'))
                    ->icon('heroicon-o-pencil')
                    ->mountUsing(fn (Forms\ComponentContainer $form) => $form->fill(['level' => $form->model->level]))
                    ->action(function (array $data, $record): void {
                        auth('tourguide')->user()->skills()->updateExistingPivot($record->id, ['level' => $data['level']]);
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
                    ]),
                Tables\Actions\DetachAction::make()
                    ->label(__('buttons.remove'))
                    ->icon('heroicon-o-trash')
                    ->color('danger')
                    ->modalHeading(__('headings.remove_skill'))
                    ->successNotificationTitle(__('notifications.skill_removed'))
            ])
            ->bulkActions([
                Tables\Actions\DetachBulkAction::make(),
                Tables\Actions\DeleteBulkAction::make(),
            ]);
    }
}
