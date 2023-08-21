<?php

namespace App\Filament\Resources\Testimonial;

use Filament\Forms\Components\Card;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Tables;
use App\Models\Testimonial;
use Filament\Resources\{Concerns\Translatable, Form, Resource, Table};
use App\Filament\Resources\Testimonial\TestimonialResource\Pages;
use App\Filament\Resources\Testimonial\TestimonialResource\RelationManagers;

class TestimonialResource extends Resource
{
    use Translatable;

    public static function getTranslatableLocales(): array
    {
        return config('app.locales');
    }

    protected static ?string $model = Testimonial::class;

    protected static ?string $navigationIcon = 'heroicon-o-collection';

    protected static bool $shouldRegisterNavigation = false;

    protected static string | array $middlewares = ['permission:List Testimonials|Create Testimonials|Edit Testimonials'];

    public static function getLabel(): ?string
    {
        return __('navigation.labels.testimonials');
    }

    protected static function getNavigationLabel(): string
    {
        return __('navigation.labels.testimonials');
    }

    protected static function getNavigationGroup(): ?string
    {
        return __('navigation.groups.landing_page');
    }

//    protected static function shouldRegisterNavigation(): bool
//    {
//        return auth()->user()->hasAnyPermission(['List Testimonials', 'Create Testimonials', 'Edit Testimonials']);
//    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Card::make()
                    ->schema([
                        TextInput::make('name')
                            ->label(__('attributes.name'))
                            ->required(),

                        Textarea::make('content')
                            ->label(__('attributes.content'))
                            ->required(),

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
                Tables\Columns\ImageColumn::make('avatar')
                    ->label(__('attributes.avatar'))
                    ->circular(),

                Tables\Columns\TextColumn::make('name')
                    ->label(__('attributes.name'))
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
            'index' => Pages\ListTestimonials::route('/'),
            'create' => Pages\CreateTestimonial::route('/create'),
            'edit' => Pages\EditTestimonial::route('/{record}/edit'),
        ];
    }
}
