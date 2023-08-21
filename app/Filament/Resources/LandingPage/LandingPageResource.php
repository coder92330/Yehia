<?php

namespace App\Filament\Resources\LandingPage;

use Filament\Tables;
use App\Models\LandingPage\LandingPageKey;
use Filament\Resources\{Concerns\Translatable, Resource, Table};
use App\Filament\Resources\LandingPage\LandingPageResource\Pages;
use App\Filament\Resources\LandingPage\LandingPageResource\RelationManagers;

class LandingPageResource extends Resource
{
    use Translatable;

    public static function getTranslatableLocales(): array
    {
        return config('app.locales');
    }

    protected static ?string $model = LandingPageKey::class;

    protected static ?string $navigationIcon = 'heroicon-o-collection';

    protected static ?string $slug = 'home-page';

    protected static string | array $middlewares = ['permission:List Home Page|Edit Home Page'];

    protected static function getNavigationGroup(): ?string
    {
        return __('navigation.groups.website_pages');
    }

    protected static function getNavigationLabel(): string
    {
        return __('navigation.labels.home_page');
    }

    public static function getLabel(): ?string
    {
        return __('navigation.labels.home_page');
    }

    protected static function shouldRegisterNavigation(): bool
    {
        return auth()->user()->hasAnyPermission(['List Home Page', 'Edit Home Page']);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('key')
                    ->label(__('attributes.landing_page_key.section'))
                    ->sortable()
                    ->searchable()
                    ->default('-'),

                Tables\Columns\TextColumn::make('contents.title')
                    ->label(__('attributes.landing_page_key.title'))
                    ->sortable()
                    ->searchable()
                    ->default('-')
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListLandingPages::route('/'),
            'edit' => Pages\EditLandingPage::route('/{record}/edit'),
        ];
    }
}
