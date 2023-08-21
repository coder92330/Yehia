<?php

namespace App\Agent\Resources\Favourite;

use App\Models\Favourite;
use Illuminate\Database\Eloquent\Builder;
use Filament\{Tables, Resources\Table, Resources\Resource};
use Iotronlab\FilamentMultiGuard\Concerns\ContextualResource;
use App\Agent\Resources\Favourite\FavouriteResource\{Pages, RelationManagers};

class FavouriteResource extends Resource
{
    use ContextualResource;

    protected static ?string $model = Favourite::class;

    protected static ?string $navigationIcon = 'heroicon-o-heart';

    protected static ?string $slug = 'favourites';

    protected static string|array $middlewares = 'permission:List Favourites';

    protected static ?int $navigationSort = 9;

    protected static function getNavigationGroup(): ?string
    {
        return __('navigation.groups.tourguides');
    }

    protected static function getNavigationLabel(): string
    {
        return __('navigation.labels.favorite_tourguides');
    }

    protected static function shouldRegisterNavigation(): bool
    {
        return auth('agent')->user()->hasAnyPermission(['List Favourites']);
    }

    protected static function getNavigationBadge(): ?string
    {
        return Favourite::whereRelation('favouriter', 'favouriter_id', auth('agent')->id())->count();
    }

    public static function table(Table $table): Table
    {
        $table
            ->headerActions([
                Tables\Actions\Action::make('grid')
                    ->label(__('attributes.grid_view'))
                    ->icon('heroicon-o-view-grid')
                    ->color(session()->get('favourites_table_layout') === 'grid' ? 'primary' : 'gray-500')
                    ->action(fn() => self::setTableLayout('grid')),

                Tables\Actions\Action::make('list')
                    ->label(__('attributes.list_view'))
                    ->icon('heroicon-o-view-list')
                    ->color(session()->get('favourites_table_layout') !== 'grid' ? 'primary' : 'gray-500')
                    ->action(fn() => self::setTableLayout('list')),
            ])
            ->actions([
                Tables\Actions\DeleteAction::make()
                    ->label(__('attributes.un_favourite'))
                    ->icon('heroicon-s-heart'),
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make(),
            ]);

        return session()->get('favourites_table_layout') === 'grid' ? self::gridView($table) : self::listView($table);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListFavourites::route('/'),
        ];
    }

    private static function setTableLayout($layout)
    {
        return session()->put('favourites_table_layout', $layout ?? 'grid');
    }

    private static function gridView($table)
    {
        return $table
            ->contentGrid([
                'md' => 2,
                'xl' => 2,
            ])
            ->columns([
                Tables\Columns\Layout\View::make('filament.table.favourites')
                    ->components([
                        Tables\Columns\TextColumn::make('favouritable.full_name')
                            ->label(__('attributes.full_name'))
                            ->searchable(query: function (Builder $query, string $search): Builder {
                                return $query
                                    ->whereHas('favouritable', function (Builder $query) use ($search): Builder {
                                        return $query
                                            ->where('first_name', 'like', "%$search%")
                                            ->orWhere('last_name', 'like', "%$search%");
                                    });
                            })
                            ->sortable(query: function (Builder $query, string $direction): Builder {
                                return $query
                                    ->whereHas('favouritable', function (Builder $query) use ($direction): Builder {
                                        return $query
                                            ->orderBy('last_name', $direction)
                                            ->orderBy('first_name', $direction);
                                    });
                            }),

                        Tables\Columns\TextColumn::make('favouritable.email')
                            ->label(__('attributes.email'))
                            ->searchable()
                            ->sortable(query: function (Builder $query, string $direction): Builder {
                                return $query
                                    ->whereHas('favouritable', function (Builder $query) use ($direction): Builder {
                                        return $query
                                            ->orderBy('email', $direction);
                                    });
                            }),

                        Tables\Columns\TextColumn::make('favouritable.phones.number')
                            ->label(__('attributes.phone.title'))
                            ->searchable(query: function (Builder $query, string $search): Builder {
                                return $query
                                    ->whereHas('favouritable', function (Builder $query) use ($search): Builder {
                                        return $query
                                            ->whereHas('phones', function (Builder $query) use ($search): Builder {
                                                return $query
                                                    ->where('number', 'like', "%$search%");
                                            });
                                    });
                            })
                            ->sortable(query: function (Builder $query, string $direction): Builder {
                                return $query
                                    ->whereHas('favouritable', function (Builder $query) use ($direction): Builder {
                                        return $query
                                            ->whereHas('phones', function (Builder $query) use ($direction): Builder {
                                                return $query
                                                    ->orderBy('number', $direction);
                                            });
                                    });
                            }),
                    ]),

            ]);
    }

    private static function listView($table)
    {
        return $table
            ->columns([
                Tables\Columns\ImageColumn::make('favouritable.avatar')
                    ->label(__('attributes.avatar'))
                    ->circular(),

                Tables\Columns\TextColumn::make('favouritable.full_name')
                    ->label(__('attributes.full_name')),

                Tables\Columns\TextColumn::make('favouritable.country.name')
                    ->label(__('attributes.country'))
                    ->sortable()
                    ->searchable(),

                Tables\Columns\TextColumn::make('favouritable.email')
                    ->label(__('attributes.email'))
                    ->color('gray-500')
                    ->sortable()
                    ->searchable(),
            ]);
    }
}
