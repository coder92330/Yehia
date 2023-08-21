<?php

namespace App\Filament\Resources\AgentNewsNotification;

use App\Filament\Resources\AgentNewsNotification\AgentNewsNotificationResource\Pages;
use App\Filament\Resources\AgentNewsNotificationResource\RelationManagers;
use App\Models\AgentNewsNotification;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Form;
use Filament\Resources\Resource;
use Filament\Resources\Table;
use Filament\Tables;

class AgentNewsNotificationResource extends Resource
{
    protected static ?string $model = AgentNewsNotification::class;

    protected static ?string $navigationIcon = 'heroicon-o-bell';

    protected static function getNavigationLabel(): string
    {
        return __('navigation.labels.agent_news_notifications');
    }

    public static function getLabel(): ?string
    {
        return __('navigation.labels.agent_news_notifications');
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
                    ->label(__('attributes.message_title'))
                    ->required(),

                Textarea::make('body')
                    ->label(__('attributes.message_body'))
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
                    ->label(__('attributes.message_title'))
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('body')
                    ->label(__('attributes.message_body'))
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('created_at')
                    ->label(__('attributes.sent_at'))
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
            'index' => Pages\ManageAgentNewsNotifications::route('/'),
        ];
    }
}
