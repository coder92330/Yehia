<?php

namespace App\Filament\Resources\Admin;

use App\Filament\Resources\Admin\AdminResource\Pages;
use App\Filament\Resources\Admin\AdminResource\RelationManagers\PhoneRelationManager;
use App\Models\Country;
use App\Models\User;
use Filament\Forms;
use Filament\Resources\Form;
use Filament\Resources\Resource;
use Filament\Resources\Table;
use Filament\Tables;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class AdminResource extends Resource
{
    protected static ?string $model = User::class;

    protected static ?string $navigationIcon = 'heroicon-o-user-circle';

    protected static ?string $slug = 'admins';

    protected static ?int $navigationSort = 1;

    protected static string|array $middlewares = ['permission:List Admins|Create Admins|Edit Admins,web'];

    public static function getLabel(): ?string
    {
        return __('navigation.labels.admins');
    }

    protected static function getNavigationLabel(): string
    {
        return __('navigation.labels.guides-navigator-admins');
    }

    protected static function shouldRegisterNavigation(): bool
    {
        return auth()->user()->hasAnyPermission(['List Admins', 'Create Admins', 'Edit Admins']);
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema(
                Forms\Components\Card::make()->schema([

                    Forms\Components\TextInput::make('name')
                        ->label(__('attributes.name'))
                        ->rules(['required', 'string', 'max:80'])
                        ->string()
                        ->required(),

                    Forms\Components\TextInput::make('email')
                        ->label(__('attributes.email'))
                        ->email()
                        ->rules(['required', 'email'])
                        ->unique('users', 'email', ignoreRecord: true)
                        ->required(),

                    Forms\Components\TextInput::make('password')
                        ->label(__('attributes.password'))
                        ->required(fn() => \Route::is('filament.resources.admins.create'))
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
                        ->options(fn() => Country::active()->get()->pluck('name', 'id'))
                        ->searchable()
                        ->rules(['required', 'exists:countries,id']),

                    Forms\Components\CheckboxList::make('permissions')
                        ->bulkToggleable()
                        ->columns(6)
                        ->label(__('attributes.permissions'))
                        ->hint(__('attributes.select', ['name' => __('attributes.permissions')]))
                        ->default(Role::where(['guard_name' => 'agent', 'name' => 'admin'])->first()->permissions->pluck('id')->toArray())
                        ->relationship("permissions", 'name')
                        ->options(fn() => Permission::whereGuardName('web')->get()->pluck('name', 'id')),

                    Forms\Components\Repeater::make('phones')
                        ->relationship('phones')
                        ->label(__('attributes.phones'))
                        ->minItems(0)
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
                                ->default('mobile')
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
            );
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\ImageColumn::make('avatar')
                    ->label(__('attributes.avatar'))
                    ->circular()
                    ->sortable(),

                Tables\Columns\TextColumn::make('name')
                    ->label(__('attributes.full_name'))
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('roles.name')
                    ->label(__('attributes.role'))
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('country.name')
                    ->label(__('attributes.country'))
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('email')
                    ->label(__('attributes.email'))
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('phones.number')
                    ->label(__('attributes.phone.number'))
                    ->default('-')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('created_at')
                    ->label(__('attributes.created_at'))
                    ->date('M d Y')
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

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListAdmins::route('/'),
            'create' => Pages\CreateAdmin::route('/create'),
            'edit' => Pages\EditAdmin::route('/{record}/edit'),
        ];
    }
}
