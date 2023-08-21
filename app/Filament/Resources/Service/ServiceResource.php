<?php

namespace App\Filament\Resources\Service;

use App\Filament\Resources\Service\ServiceResource\Pages;
use App\Filament\Resources\Service\ServiceResource\RelationManagers;
use App\Models\Service;
use Filament\Forms;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Resources\Concerns\Translatable;
use Filament\Resources\Form;
use Filament\Resources\Resource;
use Filament\Resources\Table;
use Filament\Tables;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class ServiceResource extends Resource
{
    use Translatable;

    public static function getTranslatableLocales(): array
    {
        return config('app.locales');
    }

    protected static ?string $model = Service::class;

    protected static ?string $navigationIcon = 'heroicon-o-document-report';

    protected static string | array $middlewares = ['permission:List Services|Create Services|Edit Services'];

    public static function getLabel(): ?string
    {
        return __('navigation.labels.services');
    }

    protected static function getNavigationLabel(): string
    {
        return __('navigation.labels.services');
    }

    protected static function getNavigationGroup(): ?string
    {
        return __('navigation.groups.website');
    }

    protected static function shouldRegisterNavigation(): bool
    {
        return false;
//        return auth()->user()->hasAnyPermission(['List Services', 'Create Services', 'Edit Services']);
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Card::make()
                    ->schema([
                        TextInput::make('title')
                            ->label(__('attributes.title'))
                            ->placeholder(__('attributes.title'))
                            ->required(),

                        Textarea::make('content')
                            ->label(__('attributes.content'))
                            ->placeholder(__('attributes.content'))
                            ->required(),

                        Forms\Components\SpatieMediaLibraryFileUpload::make("images")
                            ->multiple()
                            ->hint(__('attributes.image_hint', ['formats' => 'jpeg, jpg, png', 'size' => '2MB']))
                            ->placeholder(__('attributes.image_placeholder', ['attribute' => __('attributes.section_image')]))
                            ->rules(['image', 'max:2048', 'mimes:jpeg,jpg,png'])
                            ->collection('services'),

                        Toggle::make('is_published')
                            ->label(__('attributes.published'))
                            ->onIcon('heroicon-o-check')
                            ->offIcon('heroicon-o-x'),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('title')
                    ->label(__('attributes.title'))
                    ->sortable()
                    ->searchable(),

                Tables\Columns\TextColumn::make('content')
                    ->label(__('attributes.content'))
                    ->limit(50),

                Tables\Columns\ToggleColumn::make('is_published')
                    ->label(__('attributes.published'))
                    ->onIcon('heroicon-o-check')
                    ->offIcon('heroicon-o-x'),
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
            'index' => Pages\ListServices::route('/'),
            'create' => Pages\CreateService::route('/create'),
            'edit' => Pages\EditService::route('/{record}/edit'),
        ];
    }
}
