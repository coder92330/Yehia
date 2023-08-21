<?php

namespace App\Filament\Resources\Country;

use Filament\Forms\Components\Card;
use Filament\Resources\Concerns\Translatable;
use App\Filament\Resources\Country\CountryResource\Pages\{ListCountries, CreateCountry, ViewCountry, EditCountry};
use App\Filament\Resources\Country\CountryResource\RelationManagers\CitiesRelationManager;
use App\Filament\Resources\Country\CountryResource\RelationManagers\StatesRelationManager;
use App\Filament\Resources\Country\CountryResource\Pages;
use App\Models\Country;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Resources\Form;
use Filament\Resources\Resource;
use Filament\Resources\Table;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ToggleColumn;

class CountryResource extends Resource
{
    use Translatable;

    public static function getTranslatableLocales(): array
    {
        return config('app.locales');
    }

    protected static ?string $model = Country::class;

    protected static ?string $navigationIcon = 'heroicon-o-map';

    protected static ?int $navigationSort = 1;

    protected static string | array $middlewares = ['permission:List Countries|Create Countries|Edit Countries|View Countries'];

    protected static function getNavigationGroup(): ?string
    {
        return __('navigation.groups.settings');
    }

    protected static function getNavigationLabel(): string
    {
        return __('navigation.labels.countries');
    }

    public static function getLabel(): ?string
    {
        return __('navigation.labels.countries');
    }

    protected static function shouldRegisterNavigation(): bool
    {
        return auth()->user()->hasAnyPermission(['List Countries', 'Create Countries', 'Edit Countries', 'View Countries']);
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Card::make([
                    TextInput::make('name')
                        ->label(__('attributes.name'))
                        ->required()
                        ->autofocus()
                        ->placeholder('Name'),

                    TextInput::make('country_code')
                        ->label(__('attributes.country_code'))
                        ->required()
                        ->integer(),

                    Toggle::make('is_active')
                        ->label(__('attributes.active'))
                        ->onIcon('heroicon-o-check')
                        ->offIcon('heroicon-o-x')
                ])
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')->sortable()->searchable()->label(__('attributes.name')),
                TextColumn::make('country_code')->sortable()->searchable()->label(__('attributes.country_code')),
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

    public static function getRelations(): array
    {
        return [
//            StatesRelationManager::class,
            CitiesRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListCountries::route('/'),
            'create' => CreateCountry::route('/create'),
            'view' => ViewCountry::route('/{record}'),
            'edit' => EditCountry::route('/{record}/edit'),
        ];
    }
}
