<?php

namespace App\Filament\Resources\TourguideNewsNotification;

use App\Filament\Resources\TourguideNewsNotification\TourguideNewsNotificationResource\Pages;
use App\Filament\Resources\TourguideNewsNotification\TourguideNewsNotificationResource\RelationManagers;
use App\Models\TourguideNewsNotification;
use Filament\Forms;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Form;
use Filament\Resources\Resource;
use Filament\Resources\Table;
use Filament\Tables;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class TourguideNewsNotificationResource extends Resource
{
    protected static ?string $model = TourguideNewsNotification::class;

    protected static ?string $navigationIcon = 'heroicon-o-bell';

    public static function getLabel(): ?string
    {
        return __('navigation.labels.tourguide_news_notifications');
    }

    protected static function getNavigationLabel(): string
    {
        return __('navigation.labels.tourguide_news_notifications');
    }

    protected static function getNavigationGroup(): ?string
    {
        return __('navigation.groups.news_notifications');
    }

    public static function form(Form $form): Form
    {
        return $form
            ->columns(1)
            ->schema([
                TextInput::make('title')
                    ->label(__('attributes.title'))
                    ->required(),

                Textarea::make('body')
                    ->label(__('attributes.body'))
                    ->required(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('user.name')
                    ->label(__('attributes.sender_name'))
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('title')
                    ->label(__('attributes.title'))
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('body')
                    ->label(__('attributes.body'))
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('created_at')
                    ->label(__('attributes.send_at'))
                    ->searchable()
                    ->sortable()
                    ->dateTime(),
            ])
            ->filters([
                //
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
            'index' => Pages\ManageTourguideNewsNotifications::route('/'),
        ];
    }
}
