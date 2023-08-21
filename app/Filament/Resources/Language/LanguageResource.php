<?php

namespace App\Filament\Resources\Language;

use App\Filament\Resources\Language\LanguageResource\Pages\{CreateLanguage, EditLanguage, ListLanguages};
use App\Filament\Resources\LanguageResource\RelationManagers;
use Filament\Resources\{Concerns\Translatable, Form, Resource, Table};
use Filament\{Forms, Tables};
use App\Models\Language;

class LanguageResource extends Resource
{
    use Translatable;

    public static function getTranslatableLocales(): array
    {
        return config('app.locales');
    }

    protected static ?string $model = Language::class;

    protected static ?string $navigationIcon = 'heroicon-o-translate';

    protected static ?int $navigationSort = 3;

    protected static string | array $middlewares = ['permission:List Languages|Edit Languages|Create Languages'];

    protected static function getNavigationGroup(): ?string
    {
        return __('navigation.groups.settings');
    }

    protected static function getNavigationLabel(): string
    {
        return __('navigation.labels.languages');
    }

    protected static function label(): string
    {
        return __('navigation.labels.languages');
    }

    protected static function shouldRegisterNavigation(): bool
    {
        return auth()->user()->hasAnyPermission(['List Languages', 'Edit Languages', 'Create Languages']);
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Card::make([
                    Forms\Components\TextInput::make('name')
                        ->label(__('attributes.language_name'))
                        ->required()
                        ->maxLength(255),
                ])
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')->label(__('attributes.language_name')),
                Tables\Columns\TextColumn::make('created_at')->label(__('attributes.created_at'))->dateTime()
            ])
            ->filters([
                //
            ])
            ->actions([
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
            'index' => ListLanguages::route('/'),
            'create' => CreateLanguage::route('/create'),
            'edit' => EditLanguage::route('/{record}/edit'),
        ];
    }
}
