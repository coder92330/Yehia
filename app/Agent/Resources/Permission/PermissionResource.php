<?php

namespace App\Agent\Resources\Permission;

use Filament\Tables;
use Spatie\Permission\Models\Permission;
use Filament\Resources\{Form, Resource, Table};
use App\Agent\Resources\PermissionResource\Pages;
use App\Agent\Resources\PermissionResource\RelationManagers;
use Iotronlab\FilamentMultiGuard\Concerns\ContextualResource;
use App\Agent\Resources\Permission\PermissionResource\Pages\ListPermissions;

class PermissionResource extends Resource
{
    use ContextualResource;

    protected static ?string $model = Permission::class;

    protected static ?string $navigationIcon = 'heroicon-o-cube';

//    protected static ?string $navigationLabel = 'Packages';
//
//    protected static ?string $slug = 'packages';
//
//    protected static ?string $label = 'Packages';

//    protected static string|array $middlewares = 'role:admin,agent';

    protected static ?int $navigationSort = 9;

    protected static bool $shouldRegisterNavigation = false;

//    protected static function shouldRegisterNavigation(): bool
//    {
//        return auth('agent')->user()->whereHas('roles', function ($query) {
        //     $query->where([['name', 'admin'], ['guard_name', 'agent']])
        //         ->orWhere([['name', 'super_admin'], ['guard_name', 'agent']]);
        // });
//    }

    protected static function getNavigationLabel(): string
    {
        return __('navigation.labels.permissions');
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                //
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->label(__('attributes.name'))
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('created_at')
                    ->label(__('attributes.created_at'))
                    ->date('M d, Y')
                    ->searchable()
                    ->sortable(),
            ])
            ->bulkActions([]);
    }

    public static function getPages(): array
    {
        return [
            'index' => ListPermissions::route('/'),
        ];
    }
}
