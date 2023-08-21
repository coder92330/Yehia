<?php

namespace App\Filament\Resources\Favourite\FavouriteResource\Pages;

use App\Filament\Resources\Favourite\FavouriteResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\ListRecords;
use Illuminate\Database\Eloquent\Model;

class ListFavourites extends ListRecords
{
    protected static string $resource = FavouriteResource::class;

    protected static string | array $middlewares = ['permission:List Recommended Tourguides'];

    protected function getTableQuery(): \Illuminate\Database\Eloquent\Builder
    {
        return parent::getTableQuery()->where(['favouriter_id' => auth()->id(), 'favouriter_type' => auth()->user()->getMorphClass()]);
    }

    protected function getTableRecordUrlUsing(): ?\Closure
    {
        return fn (Model $record): string => route('filament.resources.tourguides.view', $record->favouritable_id);
    }
}
