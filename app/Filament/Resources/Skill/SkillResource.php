<?php

namespace App\Filament\Resources\Skill;

use App\Filament\Resources\Skill\SkillResource\Pages\{CreateSkill, EditSkill, ListSkills};
use App\Filament\Resources\SkillResource\RelationManagers;
use Filament\Resources\{Concerns\Translatable, Form, Resource, Table};
use Filament\{Forms, Tables};
use App\Models\Skill;

class SkillResource extends Resource
{
    use Translatable;

    public static function getTranslatableLocales(): array
    {
        return config('app.locales');
    }

    protected static ?string $model = Skill::class;

    protected static ?string $navigationIcon = 'heroicon-o-academic-cap';

    protected static ?int $navigationSort = 4;

    protected static string | array $middlewares = ['permission:List Skills|Create Skills|Edit Skills'];

    public static function getLabel(): ?string
    {
        return __('navigation.labels.skills');
    }

    protected static function getNavigationLabel(): string
    {
        return __('navigation.labels.skills');
    }

    protected static function getNavigationGroup(): ?string
    {
        return __('navigation.groups.settings');
    }

    protected static function shouldRegisterNavigation(): bool
    {
        return auth()->user()->hasAnyPermission(['List Skills', 'Create Skills', 'Edit Skills']);
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('name')
                    ->label(__('attributes.name'))
                    ->required()
                    ->maxLength(255),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')->label(__('attributes.name')),
                Tables\Columns\TextColumn::make('created_at')->label(__('attributes.created_at'))->dateTime(),
                Tables\Columns\TextColumn::make('updated_at')->label(__('attributes.updated_at'))->dateTime(),
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
            'index' => ListSkills::route('/'),
            'create' => CreateSkill::route('/create'),
            'edit' => EditSkill::route('/{record}/edit'),
        ];
    }
}
