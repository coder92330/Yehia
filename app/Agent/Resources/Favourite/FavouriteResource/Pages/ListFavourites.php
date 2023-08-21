<?php

namespace App\Agent\Resources\Favourite\FavouriteResource\Pages;

use App\Agent\Resources\Favourite\FavouriteResource;
use Filament\Resources\Pages\ListRecords;
use Filament\Tables\Actions\Position;
use Illuminate\Database\Eloquent\Model;

class ListFavourites extends ListRecords
{
    protected static string $resource = FavouriteResource::class;

    protected static string | array $middlewares = 'permission:List Favourites';

    protected function getTableQuery(): \Illuminate\Database\Eloquent\Builder
    {
        return parent::getTableQuery()->where([
            'favouriter_id' => auth('agent')->id(),
            'favouriter_type' => auth('agent')->user()->getMorphClass()
        ]);
    }

    protected function getTableRecordUrlUsing(): ?\Closure
    {
        return fn (Model $record): string => route('agent.resources.tourguides.view', $record->favouritable_id);
    }

    protected function getTableActionsPosition(): ?string
    {
        return session()->get('favourites_table_layout') === 'grid' ? Position::BottomCorner : null;
    }
}
