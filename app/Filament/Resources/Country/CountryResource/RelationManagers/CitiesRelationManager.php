<?php

namespace App\Filament\Resources\Country\CountryResource\RelationManagers;

use Filament\Forms;
use Filament\Forms\Components\Toggle;
use Filament\Resources\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Resources\Table;
use Filament\Tables;
use Filament\Tables\Columns\ToggleColumn;

class CitiesRelationManager extends RelationManager
{
    protected static string $relationship = 'cities';

    protected static ?string $recordTitleAttribute = 'name';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Card::make([
                    //                Forms\Components\Select::make('state_id')
                    //                    ->relationship('state', 'name')
                    //                    ->required(),
                    Forms\Components\TextInput::make('name')
                        ->label(__('attributes.name'))
                        ->required()
                        ->maxLength(255),

                    Toggle::make('is_active')
                        ->label(__('attributes.active'))
                        ->default(true)
                        ->onIcon('heroicon-o-check')
                        ->offIcon('heroicon-o-x')
                ])
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
//                Tables\Columns\TextColumn::make('state.name', 'State'),
                Tables\Columns\TextColumn::make('name')->label(__('attributes.name'))->searchable()->sortable(),
                ToggleColumn::make('is_active')->label(__('attributes.active')),
            ])
            ->filters([
                //
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make(),
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make(),
            ]);
    }
}
