<?php

namespace App\Filament\Resources\State;

use App\Filament\Resources\State\StateResource\Pages\{ListStates, CreateState, ViewState, EditState};
use App\Filament\Resources\State\StateResource\Pages;
use App\Models\State;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Resources\{Concerns\Translatable, Form, Resource, Table};
use Filament\Tables;
use Filament\Tables\Columns\ToggleColumn;

class StateResource extends Resource
{
    use Translatable;

    public static function getTranslatableLocales(): array
    {
        return config('app.locales');
    }

    protected static ?string $model = State::class;

    protected static ?string $navigationIcon = 'heroicon-o-flag';

    protected static bool $shouldRegisterNavigation = false;

    protected static string | array $middlewares = ['permission:List States|Create States|Edit States|View States'];

    public static function getLabel(): ?string
    {
        return __('navigation.labels.states');
    }

    protected static function getNavigationLabel(): string
    {
        return __('navigation.labels.states');
    }

    protected static function getNavigationGroup(): ?string
    {
        return __('navigation.groups.settings');
    }

    protected static function shouldRegisterNavigation(): bool
    {
        return auth()->user()->hasAnyPermission(['List States', 'Create States', 'Edit States', 'View States']);
    }


    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Select::make('country_id')
                    ->label(__('attributes.country'))
                    ->relationship('country', 'name')
                    ->required(),

                TextInput::make('name')
                    ->label(__('attributes.name'))
                    ->required()
                    ->maxLength(255),

                Toggle::make('is_active')
                    ->label(__('attributes.active'))
                    ->onColor('success')
                    ->offColor('danger')
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('country.name')->label(__('attributes.country')),
                Tables\Columns\TextColumn::make('name')->label(__('attributes.name')),
                ToggleColumn::make('is_active')->label(__('attributes.active')),
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make(),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => ListStates::route('/'),
            'create' => CreateState::route('/create'),
            'view' => ViewState::route('/{record}'),
            'edit' => EditState::route('/{record}/edit'),
        ];
    }
}
