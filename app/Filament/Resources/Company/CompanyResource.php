<?php

namespace App\Filament\Resources\Company;

use App\Filament\Resources\Company\CompanyResource\Pages;
use App\Filament\Resources\Company\CompanyResource\RelationManagers;
use App\Models\City;
use App\Models\Company;
use App\Models\Country;
use Filament\Forms;
use Filament\Resources\Concerns\Translatable;
use Filament\Resources\Form;
use Filament\Resources\Resource;
use Filament\Resources\Table;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Facades\Hash;
use Maatwebsite\Excel\Facades\Excel;

class CompanyResource extends Resource
{
    use Translatable;

    public static function getTranslatableLocales(): array
    {
        return config('app.locales');
    }

    protected static ?string $model = Company::class;

    protected static ?string $navigationIcon = 'heroicon-o-office-building';

    protected static ?string $slug = 'companies';

    protected static string|array $middlewares = ['permission:List Companies|Create Companies|Edit Companies'];

    protected static ?int $navigationSort = 2;

    protected static function getNavigationLabel(): string
    {
        return __('navigation.labels.companies');
    }

    public static function getLabel(): ?string
    {
        return __('navigation.labels.companies');
    }

    protected static function getNavigationGroup(): ?string
    {
        return __('navigation.groups.companies');
    }

    protected static function shouldRegisterNavigation(): bool
    {
        return auth()->user()->hasAnyPermission(['List Companies', 'Create Companies', 'Edit Companies']);
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

                    Forms\Components\Select::make('package_id')
                        ->label(__('attributes.package'))
                        ->relationship('package', 'name')
                        ->options(\App\Models\Package::all()->pluck('name', 'id'))
                        ->searchable()
                        ->required(),

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

                    Forms\Components\Repeater::make('agents')
                        ->label(__('attributes.company_admins'))
                        ->relationship('agents')
                        ->hiddenOn('edit')
                        ->defaultItems(0)
                        ->schema([
                            Forms\Components\TextInput::make('first_name')
                                ->label(__('attributes.first_name'))
                                ->required(),

                            Forms\Components\TextInput::make('last_name')
                                ->label(__('attributes.last_name'))
                                ->required(),

                            Forms\Components\TextInput::make('email')
                                ->label(__('attributes.email'))
                                ->email()
                                ->reactive()
                                ->afterStateUpdated(fn(\Closure $set, $state) => $set('username', explode('@', $state)[0]))
                                ->required(),

                            Forms\Components\TextInput::make('username')
                                ->label(__('attributes.username'))
                                ->placeholder('Username')
                                ->required(),

                            Forms\Components\TextInput::make('password')
                                ->label(__('attributes.password'))
                                ->placeholder('Password')
                                ->password()
                                ->confirmed()
                                ->required(),

                            Forms\Components\TextInput::make('password_confirmation')
                                ->label(__('attributes.password_confirmation'))
                                ->placeholder('Confirm Password')
                                ->password()
                                ->required(),
                        ]),
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
            ->headerActions([
                Tables\Actions\Action::make('Export')
                    ->label(__('actions.export'))
                    ->icon('heroicon-o-archive')
                    ->color('primary')
                    ->form([
                        Forms\Components\Select::make('type')
                            ->label(__('attributes.export_type'))
                            ->options([
                                'all' => __('attributes.all_companies'),
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
                    ->action(fn(array $data) => Excel::download(new \App\Exports\CompanyExport($data['from'] ?? null, $data['to'] ?? null, null), "companies_" . now()->format('Y_m_d_H_i_s') . ".xlsx")),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\Action::make('new_chat')
                    ->label(__('actions.new_chat'))
                    ->icon('heroicon-o-chat')
                    ->color('success')
                    ->action(fn($record) => redirect()->route('filament.pages.company-chat', $record->id)),
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make(),
                Tables\Actions\BulkAction::make('export')
                    ->label(__('actions.export'))
                    ->icon('heroicon-o-archive')
                    ->color('primary')
                    ->action(fn(Collection $records) => Excel::download(new \App\Exports\CompanyExport(null, null, $records->pluck('id')->toArray()), "companies_" . now()->format('Y_m_d_H_i_s') . ".xlsx")),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            RelationManagers\AgentsRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListCompanies::route('/'),
            'create' => Pages\CreateCompany::route('/create'),
            'edit' => Pages\EditCompany::route('/{record}/edit'),
        ];
    }
}
