<?php

namespace App\Agent\Resources\Company;

use App\Agent\Resources\Company\CompanyResource\Pages;
use App\Models\City;
use App\Models\Company;
use Filament\Forms;
use Filament\Forms\Components\SpatieMediaLibraryFileUpload;
use Filament\Resources\Concerns\Translatable;
use Filament\Resources\Form;
use Filament\Resources\Resource;
use Filament\Resources\Table;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Iotronlab\FilamentMultiGuard\Concerns\ContextualResource;

class CompanyResource extends Resource
{
    use Translatable, ContextualResource;

    public static function getTranslatableLocales(): array
    {
        return config('app.locales');
    }

    protected static ?string $model = Company::class;

    protected static ?string $navigationIcon = 'heroicon-o-office-building';

    protected static ?string $slug = 'my-company';

    protected static ?int $navigationSort = 2;

    protected static function getNavigationLabel(): string
    {
        return __('navigation.labels.company-profile');
    }

    public static function getLabel(): ?string
    {
        return __('navigation.labels.company-profile');
    }

    protected static function getNavigationGroup(): ?string
    {
        return __('navigation.groups.companies');
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Card::make()->schema([

                    Forms\Components\TextInput::make('name')
                        ->label(__('attributes.name'))
                        ->placeholder('Name')
                        ->required(),

                    Forms\Components\TextInput::make('email')
                        ->label(__('attributes.email'))
                        ->placeholder('Email')
                        ->email()
                        ->required(),

//                    Forms\Components\Select::make('package_id')
//                        ->label(__('attributes.package'))
//                        ->relationship('package', 'name')
//                        ->options(\App\Models\Package::all()->pluck('name', 'id'))
//                        ->searchable()
//                        ->required(),

                    Forms\Components\TextInput::make('website')
                        ->label(__('attributes.website')),

                    Forms\Components\TextInput::make('address')
                        ->label(__('attributes.address')),

                    Forms\Components\Select::make('city_id')
                        ->label(__('attributes.city'))
                        ->searchable()
                        ->options(City::all()->pluck('name', 'id')),

                    Forms\Components\Textarea::make('specialties')
                        ->label(__('attributes.specialties')),

                    Forms\Components\Textarea::make('description')
                        ->label(__('attributes.description')),

                    Forms\Components\TextInput::make('facebook')
                        ->label(__('attributes.facebook')),

                    Forms\Components\TextInput::make('twitter')
                        ->label(__('attributes.twitter')),

                    Forms\Components\TextInput::make('instagram')
                        ->label(__('attributes.instagram')),

                    Forms\Components\TextInput::make('linkedin')
                        ->label(__('attributes.linkedin')),

                    SpatieMediaLibraryFileUpload::make('logo')
                        ->label(__('attributes.logo'))
                        ->rules(['image', 'max:2048', 'mimes:jpeg,jpg,png'])
                        ->collection('companies_logo')
                        ->hint(__('attributes.image_hint', ['formats' => 'jpeg, jpg, png', 'size' => '2MB']))
                        ->placeholder(__('attributes.logo_placeholder', ['attribute' => __('attributes.logo')])),

                    SpatieMediaLibraryFileUpload::make('cover')
                        ->label(__('attributes.cover'))
                        ->hint(__('attributes.image_hint', ['formats' => 'jpeg, jpg, png', 'size' => '2MB']))
                        ->placeholder(__('attributes.image_placeholder', ['attribute' => __('attributes.cover')]))
                        ->rules(['image', 'max:2048', 'mimes:jpeg,jpg,png'])
                        ->collection('companies_cover'),
                ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                    ->label(__('attributes.name'))
                    ->searchable()
                    ->sortable(),

                TextColumn::make('email')
                    ->label(__('attributes.email'))
                    ->searchable()
                    ->sortable(),

                TextColumn::make('package.name')
                    ->label(__('attributes.package'))
                    ->searchable()
                    ->sortable(),

                TextColumn::make('website')
                    ->label(__('attributes.website'))
                    ->searchable()
                    ->sortable(),

                TextColumn::make('created_at')
                    ->label(__('attributes.created_at'))
                    ->date()
                    ->searchable()
                    ->sortable(),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make(),]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListCompanies::route('/'),
            'edit' => Pages\EditCompany::route('/{record}/edit'),
        ];
    }
}
