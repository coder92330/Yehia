<?php

namespace App\Filament\Resources\Package;

use App\Filament\Resources\Package\PackageResource\Pages;
use App\Filament\Resources\Package\PackageResource\RelationManagers;
use App\Models\Package;
use App\Models\Style;
use Filament\Forms;
use Filament\Resources\Concerns\Translatable;
use Filament\Resources\Form;
use Filament\Resources\Resource;
use Filament\Resources\Table;
use Filament\Tables;

class PackageResource extends Resource
{
    use Translatable;

    public static function getTranslatableLocales(): array
    {
        return config('app.locales');
    }

    protected static ?string $model = Package::class;

    protected static ?string $navigationIcon = 'heroicon-o-cube';

    protected static ?int $navigationSort = 1;

    public static function getLabel(): ?string
    {
        return __('navigation.labels.packages');
    }

    protected static function getNavigationGroup(): ?string
    {
        return __('navigation.groups.companies');
    }

    protected static function getNavigationLabel(): string
    {
        return __('navigation.labels.packages');
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Card::make([
                    Forms\Components\TextInput::make('name')
                        ->label(__('attributes.name'))
                        ->required()
                        ->maxLength(255),

                    Forms\Components\Textarea::make('description')
                        ->label(__('attributes.description'))
                        ->required(),

                    Forms\Components\TextInput::make('price')
                        ->label(__('attributes.price'))
                        ->integer()
                        ->required(),

                    Forms\Components\TextInput::make('duration')
                        ->label(__('attributes.duration'))
                        ->integer()
                        ->required(),

                    Forms\Components\Select::make('duration_type')
                        ->label(__('attributes.duration_type'))
                        ->options([
                            'day' => __('attributes.duration_types.day'),
                            'month' => __('attributes.duration_types.month'),
                            'year' => __('attributes.duration_types.year'),
                        ])
                        ->required(),

                    Forms\Components\TextInput::make('admin_users_limit')
                        ->label(__('attributes.admin_users_count'))
                        ->integer()
                        ->required(),

                    Forms\Components\TextInput::make('users_limit')
                        ->label(__('attributes.staff_users_count'))
                        ->integer()
                        ->required(),

                    Forms\Components\Select::make('styles')
                        ->label(__('attributes.styles'))
                        ->relationship('styles', 'name')
                        ->options(fn() => \App\Models\Style::selectRaw('if(name = "violet", "Default", CONCAT(UPPER(SUBSTRING(name,1,1)),SUBSTRING(name,2))) as name, styles.id')
                            ->pluck('name', 'id'))
                        ->multiple()
                        ->default([Style::defaultStyleId()])
                        ->required(),

                    Forms\Components\Toggle::make('is_active')
                        ->label(__('attributes.active'))
                        ->default(true)
                        ->onIcon('heroicon-o-check')
                        ->offIcon('heroicon-o-x')
                        ->required(),
                ])
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\ImageColumn::make('icon')
                    ->label(__('attributes.icon'))
                    ->circular(),

                Tables\Columns\TextColumn::make('name')->label(__('attributes.name')),

                Tables\Columns\TextColumn::make('price')->label(__('attributes.price')),

                Tables\Columns\TextColumn::make('duration_name')->label(__('attributes.duration')),

                Tables\Columns\TextColumn::make('admin_users_limit')->label(__('attributes.admin_users_count')),

                Tables\Columns\TextColumn::make('users_limit')->label(__('attributes.staff_users_count')),

                Tables\Columns\IconColumn::make('is_active')->label(__('attributes.active'))->boolean(),

                Tables\Columns\TextColumn::make('start_at')->label(__('attributes.start_at'))->dateTime(),

                Tables\Columns\TextColumn::make('end_at')->label(__('attributes.end_at'))->dateTime(),
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
            'index' => Pages\ListPackages::route('/'),
            'create' => Pages\CreatePackage::route('/create'),
            'edit' => Pages\EditPackage::route('/{record}/edit'),
        ];
    }
}
