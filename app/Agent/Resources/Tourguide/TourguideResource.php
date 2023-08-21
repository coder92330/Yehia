<?php

namespace App\Agent\Resources\Tourguide;

use App\Agent\Resources\Tourguide\TourguideResource\Pages;
use App\Agent\Resources\Tourguide\TourguideResource\RelationManagers;
use App\Models\Tourguide;
use Filament\Forms;
use Filament\Resources\Concerns\Translatable;
use Filament\Resources\Form;
use Filament\Resources\Resource;
use Filament\Resources\Table;
use Filament\Tables;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Iotronlab\FilamentMultiGuard\Concerns\ContextualResource;

class TourguideResource extends Resource
{
    use ContextualResource, Translatable;

    public static function getTranslatableLocales(): array
    {
        return config('app.locales');
    }

    protected static ?string $model = Tourguide::class;

    protected static ?string $navigationIcon = 'heroicon-o-user-group';

    protected static ?string $slug = 'tourguides';

    protected static string|array $middlewares = 'permission:List Tourguides';

    protected static ?int $navigationSort = 8;

    protected static function getNavigationGroup(): ?string
    {
        return __('navigation.groups.tourguides');
    }

    protected static function getNavigationLabel(): string
    {
        return __('navigation.labels.tourguides');
    }

    protected static function shouldRegisterNavigation(): bool
    {
        return auth('agent')->user()->hasAnyPermission(['List Tourguides']);
    }

    protected static function getNavigationBadge(): ?string
    {
        return Tourguide::count();
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Card::make()->schema([
                    Forms\Components\TextInput::make('first_name')
                        ->label(__('attributes.first_name'))
                        ->required(),

                    Forms\Components\TextInput::make('last_name')
                        ->label(__('attributes.last_name'))
                        ->required(),

                    Forms\Components\TextInput::make('username')
                        ->label(__('attributes.username'))
                        ->required(),

                    Forms\Components\TextInput::make('email')
                        ->label(__('attributes.email'))
                        ->required(),

                    Forms\Components\TextInput::make('password')
                        ->label(__('attributes.password'))
                        ->required(fn() => \Route::is('filament.tourguides.create'))
                        ->password()
                        ->confirmed(),

                    Forms\Components\TextInput::make('password_confirmation')
                        ->label(__('attributes.password_confirmation'))
                        ->requiredWith('password')
                        ->password(),

                    Forms\Components\Select::make('country_id')
                        ->label(__('attributes.country'))
                        ->placeholder(__('attributes.select', ['attribute' => __('attributes.country')]))
                        ->options(fn() => \App\Models\Country::active()->get()->pluck('name', 'id'))
                        ->required(),

                    Forms\Components\Select::make('gender')
                        ->label(__('attributes.gender'))
                        ->placeholder(__('attributes.select', ['field' => __('attributes.gender')]))
                        ->options([
                            'male'   => __('attributes.male'),
                            'female' => __('attributes.female'),
                        ])
                        ->rules(['required', 'in:male,female'])
                        ->searchable()
                        ->required(),

                    Forms\Components\DatePicker::make('birthdate')
                        ->label(__('attributes.birthdate'))
                        ->before(now()->subYears(18)),

                    Forms\Components\TextInput::make('education')
                        ->label(__('attributes.education')),

                    Forms\Components\TextInput::make('age')
                        ->label(__('attributes.age')),

                    Forms\Components\TextInput::make('years_of_experience')
                        ->label(__('attributes.years_of_experience')),

                    Forms\Components\TextInput::make('facebook')
                        ->label(__('attributes.facebook'))
                        ->url(),

                    Forms\Components\TextInput::make('twitter')
                        ->label(__('attributes.twitter'))
                        ->url(),

                    Forms\Components\TextInput::make('instagram')
                        ->label(__('attributes.instagram'))
                        ->url(),

                    Forms\Components\TextInput::make('linkedin')
                        ->label(__('attributes.linkedin'))
                        ->url(),

                    Forms\Components\Fieldset::make('Actions')
                        ->columns(3)
                        ->schema([
                            Forms\Components\Toggle::make('is_active')
                                ->label(__('attributes.active'))
                                ->onIcon('heroicon-o-check')
                                ->offIcon('heroicon-o-x')
                                ->default(true),

                            Forms\Components\Toggle::make('is_online')
                                ->label(__('attributes.online'))
                                ->onIcon('heroicon-o-check')
                                ->offIcon('heroicon-o-x')
                                ->default(true),

                            Forms\Components\Toggle::make('email_verified_at')
                                ->label(__('attributes.email_verified_at'))
                                ->onIcon('heroicon-o-check')
                                ->offIcon('heroicon-o-x')
                                ->default(true),
                        ])
                ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('full_name')
                    ->label(__('attributes.name'))
                    ->sortable(),

                Tables\Columns\TextColumn::make('country.name')
                    ->label(__('attributes.country'))
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('email')
                    ->label(__('attributes.email'))
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('username')
                    ->label(__('attributes.username'))
                    ->searchable()
                    ->sortable(),

                Tables\Columns\BadgeColumn::make('is_active')
                    ->label(__('attributes.active'))
                    ->color(fn($record) => $record->is_active ? 'success' : 'danger')
                    ->enum([
                        true => __('attributes.active'),
                        false => __('attributes.inactive'),
                    ]),

                Tables\Columns\BadgeColumn::make('is_online')
                    ->label(__('attributes.online'))
                    ->color(fn($record) => $record->is_online ? 'success' : 'danger')
                    ->enum([
                        true => __('attributes.online'),
                        false => __('attributes.offline'),
                    ]),

                Tables\Columns\TextColumn::make('created_at')
                    ->label(__('attributes.created_at'))
                    ->dateTime()
                    ->searchable()
                    ->sortable(),
            ])
            ->actions([
                Tables\Actions\Action::make('Favourite')
                    ->label(__('actions.favourite'))
                    ->requiresConfirmation()
                    ->icon('heroicon-o-heart')
                    ->visible(fn($record) => !$record->favourites()->whereFavouriterId(auth('agent')->id())->exists())
                    ->action(function ($record) {
                        if (!$record->favourites()->whereFavouriterId(auth('agent')->id())->exists()) {
                            auth('agent')->user()->favourites()->create([
                                'favouritable_id' => $record->id,
                                'favouritable_type' => $record->getMorphClass(),
                            ]);
                        }
                    }),

                Tables\Actions\Action::make('Unfavourite')
                    ->label(__('actions.unfavourite'))
                    ->requiresConfirmation()
                    ->action(fn($record) => $record->favourites()->whereFavouriterId(auth('agent')->id())->delete())
                    ->visible(fn($record) => $record->favourites()->whereFavouriterId(auth('agent')->id())->exists())
                    ->icon('heroicon-s-heart'),

                Tables\Actions\Action::make('new_chat')
                    ->label(__('actions.new_chat'))
                    ->icon('heroicon-o-chat-alt-2')
                    ->color('success')
                    ->action(fn($record) => redirect()->route('agent.pages.chat', ['user_id' => $record->id])),
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
            'index' => Pages\ListTourguides::route('/'),
            'view' => Pages\ViewTourguide::route('/{record}/view'),
        ];
    }
}
