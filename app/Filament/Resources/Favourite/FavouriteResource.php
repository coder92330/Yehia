<?php

namespace App\Filament\Resources\Favourite;

use App\Filament\Resources\Favourite\FavouriteResource\Pages;
use App\Filament\Resources\Favourite\FavouriteResource\RelationManagers;
use App\Models\Favourite;
use Filament\Forms;
use Filament\Resources\Form;
use Filament\Resources\Resource;
use Filament\Resources\Table;
use Filament\Tables;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class FavouriteResource extends Resource
{
    protected static ?string $model = Favourite::class;

    protected static ?string $navigationIcon = 'heroicon-o-star';

    protected static ?string $slug = 'recommended-tourguides';

    protected static ?int $navigationSort = 2;

    protected static string|array $middlewares = ['permission:List Recommended Tourguides'];

    protected static function getNavigationLabel(): string
    {
        return __('navigation.labels.recommended_tourguides');
    }

    protected static function getNavigationGroup(): ?string
    {
        return __('navigation.groups.tourguides');
    }

    public static function getLabel(): ?string
    {
        return __('navigation.labels.recommended_tourguide');
    }

    protected static function shouldRegisterNavigation(): bool
    {
        return auth()->user()->hasAnyPermission(['List Recommended Tourguides']);
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
                    ->label(__('attributes.unrecommend'))
                    ->icon('heroicon-s-star')
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
                'md' => Favourite::count() > 1 ? 2 : 1,
                'xl' => Favourite::count() > 1 ? 2 : 1,
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
                            ->label(__('attributes.phone.number'))
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

                Tables\Columns\TextColumn::make('favouritable.city.name')
                    ->label(__('attributes.city'))
                    ->sortable(),

                Tables\Columns\TextColumn::make('favouritable.email')
                    ->label(__('attributes.email'))
                    ->color('gray-500')
                    ->sortable(),
            ]);
    }
}
