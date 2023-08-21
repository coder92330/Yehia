<?php

namespace App\TourGuide\Resources\Company;

use App\TourGuide\Resources\Company\CompanyResource\Pages;
use App\TourGuide\Resources\Company\CompanyResource\RelationManagers;
use App\Models\City;
use App\Models\Company;
use App\Models\Country;
use Filament\Forms;
use Filament\Resources\Form;
use Filament\Resources\Resource;
use Filament\Resources\Table;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class CompanyResource extends Resource
{
    protected static ?string $model = Company::class;

    protected static ?string $navigationIcon = 'heroicon-o-office-building';

    protected static ?string $slug = 'companies';

    protected static bool $shouldRegisterNavigation = false;

    protected static function getNavigationLabel(): string
    {
        return __('navigation.labels.companies');
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\ImageColumn::make('logo')
                    ->label(__('attributes.company.logo'))
                    ->circular(),

                TextColumn::make('name')
                    ->label(__('attributes.company.name'))
                    ->searchable()
                    ->sortable(),

                TextColumn::make('email')
                    ->label(__('attributes.company.email'))
                    ->searchable()
                    ->sortable(),

                TextColumn::make('address')
                    ->label(__('attributes.company.address'))
                    ->searchable()
                    ->sortable(),

                TextColumn::make('website')
                    ->label(__('attributes.company.website'))
                    ->searchable()
                    ->sortable(),
            ])
            ->actions([
//                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
//                Tables\Actions\DeleteBulkAction::make(),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListCompanies::route('/'),
        ];
    }
}
