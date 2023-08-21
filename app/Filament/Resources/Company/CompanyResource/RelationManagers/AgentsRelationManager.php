<?php

namespace App\Filament\Resources\Company\CompanyResource\RelationManagers;

use App\Models\Agent;
use Filament\Forms;
use Filament\Resources\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Resources\Table;
use Filament\Tables;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Route;
use Spatie\Permission\Models\Role;

class AgentsRelationManager extends RelationManager
{
    protected static string $relationship = 'agents';

    protected static ?string $recordTitleAttribute = 'name';

    protected static ?string $inverseRelationship = 'company';

    protected function getTableQuery(): Builder|Relation
    {
        return parent::getTableQuery()->whereHas('roles', function ($query) {
            $query->where([['name', 'super_admin'], ['guard_name', 'agent']])
                ->orWhere([['name', 'admin'], ['guard_name', 'agent']]);
        });
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('first_name')
                    ->label(__('attributes.first_name'))
                    ->columnSpanFull()
                    ->required(),

                Forms\Components\TextInput::make('last_name')
                    ->label(__('attributes.last_name'))
                    ->columnSpanFull()
                    ->required(),

                Forms\Components\TextInput::make('email')
                    ->label(__('attributes.email'))
                    ->columnSpanFull()
                    ->email()
                    ->reactive()
                    ->afterStateUpdated(fn(\Closure $set, $state) => $set('username', explode('@', $state)[0]))
                    ->required(),

                Forms\Components\TextInput::make('username')
                    ->label(__('attributes.username'))
                    ->placeholder('Username')
                    ->columnSpanFull()
                    ->required(),

                Forms\Components\TextInput::make('password')
                    ->label(__('attributes.password'))
                    ->columnSpanFull()
                    ->password()
                    ->confirmed()
                    ->required(fn($record) => !isset($record)),

                Forms\Components\TextInput::make('password_confirmation')
                    ->label(__('attributes.password_confirmation'))
                    ->columnSpanFull()
                    ->password()
                    ->requiredWith('password'),
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
                    ->label(__('attributes.full_name')),

                Tables\Columns\TextColumn::make('roles.name')
                    ->label(__('attributes.role'))
                    ->formatStateUsing(fn(string $state): string => strtolower($state) === 'user' ? 'Staff' :
                        (str_contains($state, '_') ? ucwords(str_replace('_', ' ', $state)) : ucwords($state))),

                Tables\Columns\TextColumn::make('email')
                    ->label(__('attributes.email')),

                Tables\Columns\TextColumn::make('username')
                    ->label(__('attributes.username')),

                Tables\Columns\TextColumn::make('created_at')
                    ->label(__('attributes.created_at'))
                    ->date(),
            ])
            ->filters([
                //
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make()
                    ->label(__('actions.add_company_admin'))
                    ->modalHeading(__('actions.add_company_admin'))
                    ->hidden(fn(RelationManager $livewire) => $livewire->ownerRecord->is_exceeding_package_admins_limit)
                    ->mutateFormDataUsing(function (array $data) {
                        if (isset($data['password'])) {
                            $data['password'] = Hash::make($data['password']);
                            unset($data['password_confirmation']);
                        }
                        return $data;
                    })
                    ->after(function (Agent $record) {
                        $record->company->agents->count() <= 1
                            ? $record->assignRole(Role::findByName('super_admin', 'agent'))
                            : $record->assignRole(Role::findByName('admin', 'agent'));
                    })
            ])
            ->actions([
                Tables\Actions\EditAction::make()
                    ->modalHeading(__('actions.edit_company_admin'))
                    ->mutateFormDataUsing(function (array $data) {
                        if (isset($data['password'])) {
                            $data['password'] = Hash::make($data['password']);
                            unset($data['password_confirmation']);
                        } else {
                            unset($data['password'], $data['password_confirmation']);
                        }
                        return $data;
                    }),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make(),
            ]);
    }
}
