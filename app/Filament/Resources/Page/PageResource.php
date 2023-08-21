<?php

namespace App\Filament\Resources\Page;

use App\Filament\Resources\PageResource\Pages;
use App\Filament\Resources\PageResource\RelationManagers;
use App\Models\Page;
use Creagia\FilamentCodeField\CodeField;
use Filament\Forms;
use Filament\Resources\Concerns\Translatable;
use Filament\Resources\Form;
use Filament\Resources\Resource;
use Filament\Resources\Table;
use Filament\Tables;
use Illuminate\Support\Str;
use Mohamedsabil83\FilamentFormsTinyeditor\Components\TinyEditor;

class PageResource extends Resource
{
    use Translatable;

    public static function getTranslatableLocales(): array
    {
        return config('app.locales');
    }

    protected static ?string $model = Page::class;

    protected static ?string $navigationIcon = 'heroicon-o-collection';

    public static function getLabel(): ?string
    {
        return __('navigation.labels.website-pages');
    }

    protected static function getNavigationLabel(): string
    {
        return __('navigation.labels.website-pages');
    }

    protected static function getNavigationGroup(): ?string
    {
        return __('navigation.groups.website-pages');
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Card::make([
                    Forms\Components\TextInput::make('title')
                        ->label(__('attributes.title'))
                        ->required(),

                    Forms\Components\TextInput::make('slug')
                        ->label(__('attributes.slug'))
                        ->required(),

                    Forms\Components\SpatieMediaLibraryFileUpload::make('header')
                        ->label(__('attributes.header'))
                        ->collection('page_header'),

                    TinyEditor::make('body')
                        ->label(__('attributes.body'))
                        ->showMenuBar()
                        ->fileAttachmentsDirectory('dynamic-pages')
                        ->required(),

                    CodeField::make('style')
                        ->label(__('attributes.style'))
                        ->cssField()
                        ->withLineNumbers()
                        ->hint(__('attributes.style_hint', ['tag' => '<code>&lt;style&gt;</code>'])),

                    Forms\Components\Fieldset::make('Actions')
                        ->label(__('attributes.actions'))
                        ->columns(2)
                        ->schema([
                            Forms\Components\Toggle::make('is_active')
                                ->label(__('attributes.active_page'))
                                ->default(true)
                                ->onIcon('heroicon-s-check')
                                ->offIcon('heroicon-s-x')
                                ->required(),

                            Forms\Components\Toggle::make('is_header_active')
                                ->label(__('attributes.active_header'))
                                ->default(true)
                                ->onIcon('heroicon-s-check')
                                ->offIcon('heroicon-s-x')
                                ->required(),
                        ]),
                ])
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('title')->label(__('attributes.title')),
                Tables\Columns\TextColumn::make('slug')->label(__('attributes.slug')),
                Tables\Columns\TextColumn::make('full_url')->url(fn($record) => $record->full_url, true)->label(__('attributes.full_url')),
                Tables\Columns\IconColumn::make('is_active')->boolean()->label(__('attributes.active_page')),
                Tables\Columns\IconColumn::make('is_header_active')->label(__('attributes.active_header'))->boolean(),
                Tables\Columns\TextColumn::make('created_at')->date()->label(__('attributes.created_at')),
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
            'index' => PageResource\Pages\ListPages::route('/'),
            'create' => PageResource\Pages\CreatePage::route('/create'),
            'edit' => PageResource\Pages\EditPage::route('/{record}/edit'),
        ];
    }
}
