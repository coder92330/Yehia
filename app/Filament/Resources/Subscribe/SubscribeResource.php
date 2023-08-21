<?php

namespace App\Filament\Resources\Subscribe;

use App\Filament\Resources\Subscribe\SubscribeResource\Pages;
use App\Filament\Resources\Subscribe\SubscribeResource\RelationManagers;
use App\Models\Subscribe;
use Filament\Forms;
use Filament\Resources\Form;
use Filament\Resources\Resource;
use Filament\Resources\Table;
use Filament\Tables;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class SubscribeResource extends Resource
{
    protected static ?string $model = Subscribe::class;

    protected static ?string $navigationIcon = 'heroicon-o-user-group';

    public static function getLabel(): ?string
    {
        return __('navigation.labels.subscribes');
    }

    protected static function getNavigationLabel(): string
    {
        return __('navigation.labels.subscribes');
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('email')
                    ->label(__('attributes.email'))
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('created_at')
                    ->label(__('attributes.created_at'))
                    ->dateTime()
                    ->searchable()
                    ->sortable(),
            ])
            ->actions([
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make(),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ManageSubscribes::route('/'),
        ];
    }
}
