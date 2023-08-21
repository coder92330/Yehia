<?php

namespace App\Filament\Resources\City;

use App\Models\Country;
use App\Filament\Resources\City\CityResource\Pages\{CreateCity, EditCity, ListCities, ViewCity};
use App\Filament\Resources\City\CityResource\Pages;
use App\Filament\Resources\City\CityResource\RelationManagers;
use App\Models\City;
use Filament\Forms;
use Filament\Forms\Components\Toggle;
use Filament\Resources\Concerns\Translatable;
use Filament\Resources\Form;
use Filament\Resources\Resource;
use Filament\Resources\Table;
use Filament\Tables;
use Filament\Tables\Columns\ToggleColumn;

class CityResource extends Resource
{
    use Translatable;

    public static function getTranslatableLocales(): array
    {
        return config('app.locales');
    }

    protected static ?string $model = City::class;

    protected static ?string $navigationIcon = 'heroicon-o-flag';

    protected static ?int $navigationSort = 2;

    protected static string | array $middlewares = ['permission:List Cities|Create Cities|Edit Cities|View Cities'];

    public static function getLabel(): ?string
    {
        return __('navigation.labels.cities');
    }

    protected static function getNavigationLabel(): string
    {
        return __('navigation.labels.cities');
    }

    protected static function getNavigationGroup(): ?string
    {
        return __('navigation.groups.settings');
    }

    protected static function shouldRegisterNavigation(): bool
    {
        return auth()->user()->hasAnyPermission(['List Cities', 'Create Cities', 'Edit Cities', 'View Cities']);
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Card::make([
//                    Forms\Components\Select::make('state_id')
//                        ->relationship('state', 'name')
//                        ->required(),

                    Forms\Components\Select::make('country_id')
                        ->label(__('attributes.country'))
                        ->relationship('country', 'name')
                        ->options(Country::active()->get()->pluck('name', 'id'))
                        ->searchable()
                        ->required(),

                    Forms\Components\TextInput::make('name')
                        ->label(__('attributes.name'))
                        ->required()
                        ->maxLength(255),

                    Toggle::make('is_active')
                        ->label(__('attributes.active'))
                        ->onIcon('heroicon-o-check')
                        ->offIcon('heroicon-o-x')
                        ->default(true)
                ])
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('country.name')->label(__('attributes.country')),
//                Tables\Columns\TextColumn::make('state.name'),
                Tables\Columns\TextColumn::make('name')->label(__('attributes.name')),
                ToggleColumn::make('is_active')->label(__('attributes.active')),
            ])
            ->filters([
                //
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
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListCities::route('/'),
            'create' => CreateCity::route('/create'),
            'view' => ViewCity::route('/{record}'),
            'edit' => EditCity::route('/{record}/edit'),
        ];
    }
}
