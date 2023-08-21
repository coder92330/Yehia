<?php

namespace App\Filament\Resources\Agent;

use App\Filament\Resources\Agent\AgentResource\Pages;
use App\Filament\Resources\Agent\AgentResource\RelationManagers;
use App\Models\Agent;
use App\Models\Company;
use App\Models\Country;
use Filament\Forms;
use Filament\Resources\Form;
use Filament\Resources\Resource;
use Filament\Resources\Table;
use Filament\Tables;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Validation\Rule;

class AgentResource extends Resource
{
    protected static ?string $model = Agent::class;

    protected static ?string $navigationIcon = 'heroicon-o-users';

    protected static ?string $slug = 'company-admins';

    protected static ?int $navigationSort = 3;

    protected static string|array $middlewares = ['permission:List Company Admins|Create Company Admins|Edit Company Admins'];

    protected static function getNavigationGroup(): ?string
    {
        return __('navigation.groups.companies');
    }

    protected static function getNavigationLabel(): string
    {
        return __('navigation.labels.company-admins');
    }

    public static function getLabel(): ?string
    {
        return __('navigation.labels.company-admins');
    }

    protected static function shouldRegisterNavigation(): bool
    {
        return auth()->user()->hasAnyPermission(['List Company Admins', 'Create Company Admins', 'Edit Company Admins']);
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Card::make()
                    ->schema([
                        Forms\Components\TextInput::make('first_name')
                            ->label(__('attributes.first_name'))
                            ->rules(['required', 'string', 'max:80'])
                            ->required(),

                        Forms\Components\TextInput::make('last_name')
                            ->label(__('attributes.last_name'))
                            ->rules(['required', 'string', 'max:80'])
                            ->required(),

                        Forms\Components\TextInput::make('email')
                            ->email()
                            ->required()
                            ->label(__('attributes.email'))
                            ->rules(['required', 'email'])
                            ->unique('agents', 'email', ignoreRecord: true)
                            ->afterStateUpdated(fn(\Closure $set, $state) => $set('username', explode('@', $state)[0])),

                        Forms\Components\TextInput::make('username')
                            ->required()
                            ->label(__('attributes.username'))
                            ->rules(['required', 'string'])
                            ->unique('agents', 'username', ignoreRecord: true),

                        Forms\Components\TextInput::make('password')
                            ->label(__('attributes.password'))
                            ->required(fn() => \Route::is('filament.resources.company-admins.create'))
                            ->rules(['min:8', 'confirmed'])
                            ->hint(__('attributes.password_hint', ['min' => 8]))
                            ->password()
                            ->confirmed(),

                        Forms\Components\TextInput::make('password_confirmation')
                            ->label(__('attributes.password_confirmation'))
                            ->requiredWith('password')
                            ->rules('min:8')
                            ->password(),

//                        Forms\Components\Select::make('country_id')
//                            ->label('Country')
//                            ->placeholder('Select a country')
//                            ->options(fn() => Country::active()->get()->pluck('name', 'id'))
//                            ->rules(['required', 'integer', 'exists:countries,id'])
//                            ->searchable(),

                        Forms\Components\Select::make('company_id')
                            ->label(__('attributes.company_name'))
                            ->placeholder(__('attributes.select', ['field' => __('attributes.company_name')]))
                            ->options(Company::all()->pluck('name', 'id'))
                            ->rules(['required', 'integer', 'exists:companies,id'])
                            ->searchable()
                            ->required(),

                        Forms\Components\DatePicker::make('birthdate')
                            ->label(__('attributes.birthdate'))
                            ->before(now()->subYears(18))
                            ->reactive()
                            ->afterStateUpdated(fn(\Closure $set, $state) => $set('age', now()->diffInYears($state))),

                        Forms\Components\TextInput::make('age')
                            ->label(__('attributes.age'))
                            ->numeric()
                            ->rules('integer'),

                        Forms\Components\TextInput::make('years_of_experience')
                            ->label(__('attributes.years_of_experience'))
                            ->numeric()
                            ->rules('integer'),

                        Forms\Components\TextInput::make('facebook')
                            ->label(__('attributes.facebook'))
                            ->rules(['nullable', 'url'])
                            ->url(),

                        Forms\Components\TextInput::make('twitter')
                            ->label(__('attributes.twitter'))
                            ->url()
                            ->nullable()
                            ->rules(['nullable', 'url']),

                        Forms\Components\TextInput::make('instagram')
                            ->label(__('attributes.instagram'))
                            ->url()
                            ->nullable()
                            ->rules(['nullable', 'url']),

                        Forms\Components\TextInput::make('linkedin')
                            ->label(__('attributes.linkedin'))
                            ->url()
                            ->nullable()
                            ->rules(['nullable', 'url']),

                        Forms\Components\SpatieMediaLibraryFileUpload::make('avatar')
                            ->label(__('attributes.avatar'))
                            ->rules(['nullable', 'image', 'max:2048', 'mimes:jpeg,jpg,png'])
                            ->hint(__('attributes.image_hint', ['formats' => 'jpeg, jpg, png', 'size' => '2MB']))
                            ->placeholder(__('attributes.image_placeholder', ['attribute' => __('attributes.avatar')]))
                            ->collection('agent_avatar'),

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

                        Forms\Components\Repeater::make('phones')
                            ->relationship('phones')
                            ->label(__('attributes.phones'))
                            ->schema([
                                Forms\Components\TextInput::make('number')
                                    ->label(__('attributes.phone.number'))
                                    ->unique('phones', 'number', ignoreRecord: true)
                                    ->startsWith(['0', '1'])

                                    ->tel(),

                                Forms\Components\TextInput::make('country_code')
                                    ->label(__('attributes.phone.country_code'))
                                    ->required()
                                    ->default(Country::active()->where('id', auth()->user()->country_id)->first()->country_code),

                                Forms\Components\Select::make('type')
                                    ->label(__('attributes.phone.type'))
                                    ->options([
                                        'mobile' => __('attributes.mobile'),
                                        'home' => __('attributes.home'),
                                        'work' => __('attributes.work'),
                                    ])
                                    ->required(),

                                Forms\Components\Fieldset::make('Actions')
                                    ->label(__('attributes.actions'))
                                    ->columns(2)
                                    ->schema([
                                        Forms\Components\Toggle::make('is_primary')
                                            ->label(__('attributes.primary'))
                                            ->default(true),

                                        Forms\Components\Toggle::make('is_active')
                                            ->label(__('attributes.active'))
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

                Tables\Columns\TextColumn::make('country.name')
                    ->label(__('attributes.country'))
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('company.name')
                    ->label(__('attributes.company.name'))
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

                Tables\Columns\TextColumn::make('roles.name')
                    ->formatStateUsing(fn(string $state): string => strtolower($state) === 'user' ? 'Staff' : ucfirst($state))
                    ->label(__('attributes.role'))
                    ->searchable()
                    ->sortable(),

//                Tables\Columns\TextColumn::make('phones.number')
//                    ->searchable()
//                    ->sortable(),

                Tables\Columns\BadgeColumn::make('is_active')
                    ->label(__('attributes.status'))
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
            'index' => Pages\ListAgents::route('/'),
            'create' => Pages\CreateAgent::route('/create'),
            'edit' => Pages\EditAgent::route('/{record}/edit'),
        ];
    }
}
