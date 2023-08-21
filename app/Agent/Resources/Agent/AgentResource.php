<?php

namespace App\Agent\Resources\Agent;

use App\Agent\Resources\Agent\AgentResource\Pages;
use App\Agent\Resources\Agent\AgentResource\RelationManagers;
use App\Models\Agent;
use App\Models\Country;
use Filament\Forms;
use Filament\Resources\Form;
use Filament\Resources\Resource;
use Filament\Resources\Table;
use Filament\Tables;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Validation\Rule;
use Iotronlab\FilamentMultiGuard\Concerns\ContextualResource;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class AgentResource extends Resource
{
    use ContextualResource;

    protected static ?string $model = Agent::class;

    protected static ?string $navigationIcon = 'heroicon-o-users';

    protected static string|array $middlewares = 'permission:List Agents|Create Agents|Edit Agents';

    protected static ?int $navigationSort = 1;

    protected static function getNavigationLabel(): string
    {
        return __('navigation.labels.agents');
    }

    protected static function shouldRegisterNavigation(): bool
    {
        return auth('agent')->user()->whereHas('roles', function ($query) {
                $query->where([['name', 'admin'], ['guard_name', 'agent']])->orWhere([['name', 'super_admin'], ['guard_name', 'agent']]);
            })->exists() || auth('agent')->user()->hasAnyPermission(['List Agents', 'Create Agents', 'Edit Agents']);
    }

    protected static function getNavigationBadge(): ?string
    {
        return Agent::staffs()->where([['id', '!=', auth('agent')->id()], ['company_id', auth('agent')->user()->company_id]])->count();
    }

    protected static function getNavigationBadgeColor(): ?string
    {
        return Agent::where('id', '!=', auth('agent')->id())->count() > 0 ? 'primary' : 'secondary';
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
                        ->email()
                        ->required()
                        ->label(__('attributes.email'))
                        ->rules(['required', 'email'])
                        ->reactive()
                        ->unique('agents', 'email', ignoreRecord: true)
                        ->afterStateUpdated(fn(\Closure $set, $state) => $set('username', explode('@', $state)[0])),

                    Forms\Components\TextInput::make('username')
                        ->required()
                        ->label(__('attributes.username'))
                        ->rules(['required', 'string'])
                        ->unique('agents', 'username', ignoreRecord: true),

                    Forms\Components\TextInput::make('password')
                        ->label(__('attributes.password'))
                        ->required(fn() => \Route::is('agent.resources.agents.create'))
                        ->rules(['min:8', 'confirmed'])
                        ->hint(__('attributes.password_hint', ['min' => 8]))
                        ->password()
                        ->confirmed(),

                    Forms\Components\TextInput::make('password_confirmation')
                        ->label(__('attributes.password_confirmation'))
                        ->requiredWith('password')
                        ->rules('min:8')
                        ->password(),

                    Forms\Components\Select::make('country_id')
                        ->label(__('attributes.country'))
                        ->placeholder(__('attributes.country_placeholder'))
                        ->options(fn() => Country::active()->get()->pluck('name', 'id'))
                        ->rules(['required', 'integer', 'exists:countries,id'])
                        ->searchable(),

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
                        ->hint(__('attributes.image_hint', ['formats' => 'jpeg, jpg, png', 'size' => '2MB']))
                        ->placeholder(__('attributes.image_placeholder', ['attribute' => __('attributes.avatar')]))
                        ->rules(['nullable', 'image', 'max:2048', 'mimes:jpeg,jpg,png'])
                        ->collection('agent_avatar'),

                    Forms\Components\Fieldset::make('Actions')
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

                    Forms\Components\CheckboxList::make('permissions')
                        ->bulkToggleable()
                        ->columns(6)
                        ->label(__('attributes.permissions'))
                        ->hint(__('attributes.permissions_hint'))
                        ->default(Role::where(['guard_name' => 'agent', 'name' => 'user'])->first()->permissions->pluck('id')->toArray())
                        ->relationship("permissions", 'name')
                        ->options(fn() => Permission::whereGuardName('agent')->get()->pluck('name', 'id')),

                    Forms\Components\Repeater::make('phones')
                        ->relationship('phones')
                        ->label(__('attributes.phones'))
                        ->schema([
                            Forms\Components\TextInput::make('number')
                                ->label(__('attributes.number'))
                                ->unique('phones', 'number', ignoreRecord: true)
                                ->tel(),

                            Forms\Components\TextInput::make('country_code')
                                ->label(__('attributes.country_code'))
                                ->required()
                                ->default(Country::active()->where('id', auth('agent')->user()?->country_id)->first()?->country_code),

                            Forms\Components\Select::make('type')
                                ->options([
                                    'mobile' => __('attributes.mobile'),
                                    'home' => __('attributes.home'),
                                    'work' => __('attributes.work'),
                                ])
                                ->required(),

                            Forms\Components\Fieldset::make('Actions')
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
                    ->label('Avatar')
                    ->circular(),

                Tables\Columns\TextColumn::make('full_name')
                    ->label('Name')
                    ->sortable(),

                Tables\Columns\TextColumn::make('country.name')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('company.name')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('email')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('username')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('phones.number')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\BadgeColumn::make('is_active')
                    ->label('Status')
                    ->color(fn($record) => $record->is_active ? 'success' : 'danger')
                    ->enum([
                        true => 'Active',
                        false => 'Inactive',
                    ]),

                Tables\Columns\BadgeColumn::make('is_online')
                    ->label('Online')
                    ->color(fn($record) => $record->is_online ? 'success' : 'danger')
                    ->enum([
                        true => 'Online',
                        false => 'Offline',
                    ]),

                Tables\Columns\TextColumn::make('created_at')
                    ->label('Created At')
                    ->searchable()
                    ->sortable(),
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
