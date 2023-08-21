<?php

namespace App\Filament\Resources\Tourguide;

use App\Filament\Resources\Tourguide\TourguideResource\Pages;
use App\Filament\Resources\Tourguide\TourguideResource\RelationManagers;
use App\Models\City;
use App\Models\Country;
use App\Models\Event;
use App\Models\Tourguide;
use Filament\Forms;
use Filament\Notifications\Notification;
use Filament\Resources\Concerns\Translatable;
use Filament\Resources\Form;
use Filament\Resources\Resource;
use Filament\Resources\Table;
use Filament\Tables;
use Filament\Tables\Filters\Layout;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;

class TourguideResource extends Resource
{
    use Translatable;

    public static function getTranslatableLocales(): array
    {
        return config('app.locales');
    }

    protected static ?string $model = Tourguide::class;

    protected static ?string $navigationIcon = 'heroicon-o-user-group';

    protected static ?string $slug = 'tourguides';

    protected static string|array $middlewares = ['permission:List Tourguides|Create Tourguides|Edit Tourguides|View Tourguides'];

    public static function getLabel(): ?string
    {
        return __('navigation.labels.tourguides');
    }

    protected static function getNavigationLabel(): string
    {
        return __('navigation.labels.tourguides');
    }

    protected static function getNavigationGroup(): ?string
    {
        return __('navigation.groups.tourguides');
    }

    protected static function shouldRegisterNavigation(): bool
    {
        return auth()->user()->hasAnyPermission(['List Tourguides', 'Create Tourguides', 'Edit Tourguides', 'View Tourguides']);
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Card::make()->schema([
                    Forms\Components\TextInput::make('first_name')
                        ->label(__('attributes.first_name'))
                        ->rules(['required', 'string', 'max:80'])
                        ->required(),

                    Forms\Components\TextInput::make('last_name')
                        ->label(__('attributes.last_name'))
                        ->rules(['required', 'string', 'max:80'])
                        ->required(),

                    Forms\Components\TextInput::make('email')
                        ->label(__('attributes.email'))
                        ->email()
                        ->unique('tourguides', 'email', ignoreRecord: true)
                        ->afterStateUpdated(fn(\Closure $set, $state) => $set('username', explode('@', $state)[0]))
                        ->required(),

                    Forms\Components\TextInput::make('username')
                        ->label(__('attributes.username'))
                        ->rules(['required', 'string', 'max:80'])
                        ->unique('tourguides', 'username', ignoreRecord: true)
                        ->required(),

                    Forms\Components\TextInput::make('password')
                        ->label(__('attributes.password'))
                        ->required(fn() => \Route::is('filament.resources.tourguides.create'))
                        ->rules(['min:8', 'confirmed'])
                        ->password()
                        ->confirmed(),

                    Forms\Components\TextInput::make('password_confirmation')
                        ->label(__('attributes.password_confirmation'))
                        ->requiredWith('password')
                        ->rules('min:8')
                        ->password(),

                    Forms\Components\Select::make('city_id')
                        ->label(__('attributes.location'))
                        ->placeholder(__('attributes.select', ['field' => __('attributes.location')]))
                        ->options(fn() => City::active()->get()->pluck('name', 'id'))
                        ->rules(['required', 'integer', 'exists:cities,id'])
                        ->searchable()
                        ->required(),

                    Forms\Components\Select::make('gender')
                        ->label(__('attributes.gender'))
                        ->placeholder(__('attributes.select', ['field' => __('attributes.gender')]))
                        ->options([
                            'male' => __('attributes.male'),
                            'female' => __('attributes.female')
                        ])
                        ->rules(['required', 'in:male,female'])
                        ->searchable()
                        ->required(),

                    Forms\Components\DatePicker::make('birthdate')
                        ->label(__('attributes.birthdate'))
                        ->before(now()->subYears(18))
                        ->reactive()
                        ->afterStateUpdated(fn(\Closure $set, $state) => $set('age', now()->diffInYears($state))),

                    Forms\Components\TextInput::make('age')
                        ->label(__('attributes.age'))
                        ->integer()
                        ->rules(['integer']),

                    Forms\Components\TextInput::make('education')
                        ->label(__('attributes.education'))
                        ->rules(['string', 'max:100']),

                    Forms\Components\TextInput::make('years_of_experience')
                        ->label(__('attributes.years_of_experience'))
                        ->integer(),

                    Forms\Components\TextInput::make('facebook')
                        ->label(__('attributes.facebook'))
                        ->rules(['nullable', 'url'])
                        ->url(),

                    Forms\Components\TextInput::make('twitter')
                        ->label(__('attributes.twitter'))
                        ->rules(['nullable', 'url'])
                        ->url(),

                    Forms\Components\TextInput::make('instagram')
                        ->label(__('attributes.instagram'))
                        ->rules(['nullable', 'url'])
                        ->url(),

                    Forms\Components\TextInput::make('linkedin')
                        ->label(__('attributes.linkedin'))
                        ->rules(['nullable', 'url'])
                        ->url(),

                    Forms\Components\Textarea::make('bio')
                        ->label(__('attributes.bio'))
                        ->rules(['nullable', 'string', 'max:500']),

                    Forms\Components\SpatieMediaLibraryFileUpload::make('avatar')
                        ->label(__('attributes.avatar'))
                        ->hint(__('attributes.image_hint', ['formats' => 'jpeg, jpg, png', 'size' => '2MB']))
                        ->placeholder(__('attributes.image_placeholder', ['attribute' => __('attributes.avatar')]))
                        ->rules(['nullable', 'image', 'max:2048', 'mimes:jpeg,jpg,png'])
                        ->collection('tourguide_avatar'),

                    Forms\Components\Fieldset::make('Actions')
                        ->label(__('attributes.actions'))
                        ->columns(2)
                        ->schema([
                            Forms\Components\Toggle::make('is_active')
                                ->label(__('attributes.active'))
                                ->onIcon('heroicon-o-check')
                                ->offIcon('heroicon-o-x')
                                ->default(true),

                            Forms\Components\Toggle::make('email_verified_at')
                                ->label(__('attributes.email_verified_at'))
                                ->onIcon('heroicon-o-check')
                                ->offIcon('heroicon-o-x')
                                ->default(true),
                        ]),

                    Forms\Components\Repeater::make('certificates')
                        ->label(__('attributes.certificates'))
                        ->relationship('certificates')
                        ->defaultItems(0)
                        ->schema([
                            Forms\Components\TextInput::make('title')
                                ->label(__('attributes.certificate.title'))
                                ->required(),

                            Forms\Components\TextInput::make('authority')
                                ->label(__('attributes.certificate.authority'))
                                ->required(),

                            Forms\Components\DatePicker::make('date')
                                ->label(__('attributes.certificate.date'))
                                ->required(),

                            Forms\Components\TextInput::make('description')
                                ->label(__('attributes.description'))
                                ->nullable(),
                        ]),

                    Forms\Components\Repeater::make('work_experiences')
                        ->label(__('attributes.work_experiences'))
                        ->relationship('work_experiences')
                        ->defaultItems(0)
                        ->schema([
                            Forms\Components\TextInput::make('title')
                                ->label(__('attributes.work_experience.job_title'))
                                ->required(),

                            Forms\Components\TextInput::make('company')
                                ->label(__('attributes.work_experience.company'))
                                ->required(),

                            Forms\Components\TextInput::make('location')
                                ->label(__('attributes.work_experience.location'))
                                ->required(),

                            Forms\Components\TextInput::make('description')
                                ->label(__('attributes.description'))
                                ->nullable(),

                            Forms\Components\DatePicker::make('start_date')
                                ->label(__('attributes.start_date'))
                                ->required(),

                            Forms\Components\DatePicker::make('end_date')
                                ->label(__('attributes.end_date'))
                                ->visible(fn(callable $get) => $get('is_current'))
                                ->required(fn(callable $get) => !$get('is_current'))
                                ->minDate(fn(callable $get) => $get('start_date'))
                                ->maxDate(now())
                                ->before(now()),

                            Forms\Components\Toggle::make('is_current')
                                ->label(__('attributes.current'))
                                ->onIcon('heroicon-o-check')
                                ->offIcon('heroicon-o-x')
                                ->reactive()
                                ->afterStateUpdated(fn(\Closure $set, $state) => $set('end_date', $state ? null : now()))
                                ->default(true),
                        ]),

                    Forms\Components\Repeater::make('phones')
                        ->label(__('attributes.phones'))
                        ->relationship('phones')
                        ->defaultItems(0)
                        ->schema([
                            Forms\Components\TextInput::make('number')
                                ->label(__('attributes.phone.number'))
                                ->unique('phones', 'number', ignoreRecord: true)
                                ->startsWith(['0', '1'])

                                ->tel(),

                            Forms\Components\TextInput::make('country_code')
                                ->label(__('attributes.phone.country_code'))
                                ->default(auth()->user()->country->country_code)
                                ->required(),

                            Forms\Components\Select::make('type')
                                ->label(__('attributes.phone.type'))
                                ->placeholder(__('attributes.select', ['field' => __('attributes.phone.type')]))
                                ->required()
                                ->default('mobile')
                                ->options([
                                    'mobile' => __('attributes.mobile'),
                                    'home'   => __('attributes.home'),
                                    'work'   => __('attributes.work'),
                                ]),

                            Forms\Components\Fieldset::make('Actions')
                                ->label(__('attributes.actions'))
                                ->columns(2)
                                ->schema([
                                    Forms\Components\Toggle::make('is_primary')
                                        ->label(__('attributes.primary'))
                                        ->onIcon('heroicon-o-check')
                                        ->offIcon('heroicon-o-x')
                                        ->default(true),

                                    Forms\Components\Toggle::make('is_active')
                                        ->label(__('attributes.active'))
                                        ->onIcon('heroicon-o-check')
                                        ->offIcon('heroicon-o-x')
                                        ->default(true),
                                ]),
                        ]),
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

                Tables\Columns\TextColumn::make('full_name')
                    ->label(__('attributes.full_name'))
                    ->sortable(),

                Tables\Columns\TextColumn::make('city.name')
                    ->label(__('attributes.location'))
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
                    ->label(__('attributes.registered_at'))
                    ->dateTime()
                    ->searchable()
                    ->sortable(),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\Action::make(__('actions.recommend'))
                    ->requiresConfirmation()
                    ->icon('heroicon-o-star')
                    ->color('warning')
                    ->visible(fn($record) => !$record->favourites()->where(['favouriter_id' => auth()->id(), 'favouriter_type' => auth()->user()->getMorphClass()])->exists())
                    ->action(function ($record) {
                        if ($record->favourites()->where(['favouriter_id' => auth()->id(), 'favouriter_type' => auth()->user()->getMorphClass()])->exists()) {
                            return;
                        }
                        auth()->user()->favourites()->create([
                            'favouritable_id' => $record->id,
                            'favouritable_type' => $record->getMorphClass(),
                        ]);
                        Notification::make()
                            ->success()
                            ->title(__('notifications.recommendation_added'))
                            ->body(__('notifications.recommendation_added_body', ['name' => $record->full_name]))
                            ->send();
                    }),

                Tables\Actions\Action::make('Unrecommend')
                    ->label(__('actions.unrecommend'))
                    ->action(function ($record) {
                        $record->favourites()->where(['favouriter_id' => auth()->id(), 'favouriter_type' => auth()->user()->getMorphClass()])->delete();
                        Notification::make()
                            ->success()
                            ->title(__('notifications.recommendation_removed'))
                            ->body(__('notifications.recommendation_removed_body', ['name' => $record->full_name]))
                            ->send();
                    })
                    ->visible(fn($record) => $record->favourites()->where(['favouriter_id' => auth()->id(), 'favouriter_type' => auth()->user()->getMorphClass()])->exists())
                    ->requiresConfirmation()
                    ->color('danger')
                    ->icon('heroicon-s-star'),

                Tables\Actions\Action::make('new_chat')
                    ->label(__('actions.new_chat'))
                    ->icon('heroicon-o-chat')
                    ->color('success')
                    ->action(fn($record) => redirect()->route('filament.pages.chat', $record->id)),
            ])
            ->headerActions([
                Tables\Actions\Action::make('Export')
                    ->label(__('actions.export'))
                    ->icon('heroicon-o-archive')
                    ->color('primary')
                    ->form([
                        Forms\Components\Select::make('type')
                            ->label(__('attributes.export_type'))
                            ->options([
                                'all' => __('attributes.all_tourguides'),
                                'date' => __('attributes.date_range'),
                            ])
                            ->default('all')
                            ->reactive()
                            ->required(),

                        Forms\Components\DatePicker::make('from')
                            ->label(__('attributes.from_date'))
                            ->visible(fn(callable $get) => $get('type') === 'date')
                            ->required(),

                        Forms\Components\DatePicker::make('to')
                            ->label(__('attributes.to_date'))
                            ->visible(fn(callable $get) => $get('type') === 'date')
                            ->default(now())
                            ->required(),
                    ])
                    ->modalButton('Export')
                    ->action(fn(array $data) => Excel::download(new \App\Exports\TourguideExport($data['from'] ?? null, $data['to'] ?? null, null), "tourguides_" . now()->format('Y_m_d_H_i_s') . ".xlsx")),
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make(),
                Tables\Actions\BulkAction::make('export')
                    ->label(__('actions.export'))
                    ->icon('heroicon-o-archive')
                    ->color('primary')
                    ->action(fn(Collection $records) => Excel::download(new \App\Exports\TourguideExport(null, null, $records->pluck('id')->toArray()), "tourguides_" . now()->format('Y_m_d_H_i_s') . ".xlsx")),

                Tables\Actions\BulkAction::make('recommend')
                    ->label(__('actions.recommend'))
                    ->requiresConfirmation()
                    ->icon('heroicon-o-star')
                    ->color('warning')
                    ->action(function (Collection $records) {
                        try {
                            DB::beginTransaction();
                            // Check if any of the records are already recommended
                            $ids = $records->pluck('id')->intersect(auth()->user()->favourites()->pluck('favouritable_id'))->isNotEmpty()
                                ? $records->pluck('id')->diff(auth()->user()->favourites()->pluck('favouritable_id'))
                                : $records->pluck('id');

                            if ($ids->isEmpty()) {
                                Notification::make()
                                    ->danger()
                                    ->title(__('notifications.recommendation_error'))
                                    ->body(__('notifications.tourguides_already_recommended'))
                                    ->send();
                                return;
                            }
                            // Add the rest to the recommendations
                            auth()->user()->favourites()->createMany($ids->map(fn($id) => [
                                'favouritable_id' => $id,
                                'favouritable_type' => Tourguide::class,
                            ]));
                            DB::commit();
                            Notification::make()->success()
                                ->title(__('notifications.selected_tourguides_added_to_recommendations'))
                                ->body(__('notifications.selected_tourguides_added_to_recommendations_body'))
                                ->send();
                        } catch (\Exception $e) {
                            DB::rollBack();
                            Notification::make()
                                ->danger()
                                ->title(__('notifications.recommendation_error'))
                                ->body(__('notifications.recommendation_error_while_adding_tourguides'))
                                ->send();
                        }
                    }),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListTourguides::route('/'),
            'create' => Pages\CreateTourguide::route('/create'),
            'edit' => Pages\EditTourguide::route('/{record}/edit'),
            'view' => Pages\ViewTourguide::route('/{record}/view'),
        ];
    }
}
