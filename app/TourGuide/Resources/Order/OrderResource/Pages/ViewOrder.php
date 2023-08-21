<?php

namespace App\TourGuide\Resources\Order\OrderResource\Pages;

use App\TourGuide\Resources\Order\OrderResource;
use Filament\Notifications\Notification;
use Filament\Pages\Actions;
use Filament\Resources\Pages\ViewRecord;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class ViewOrder extends ViewRecord
{
    protected static string $resource = OrderResource::class;

    protected static string $view = 'filament.pages.tourguide.view-order';

    protected function getTableQuery(): Builder
    {
        return parent::getTableQuery()->withPivot('status');
    }

    public function changeStatus($status)
    {
        try {
            DB::beginTransaction();
            $this->record->tourguides()->updateExistingPivot(auth('tourguide')->id(), ['status' => $status]);
            DB::commit();
            Notification::make()->success()->title(__('notifications.status_changed', ['status' => ucfirst($status)]))->send();
        } catch (\Exception $exception) {
            DB::rollBack();
            Log::channel('tourguide')->error("Error in ViewOrder@changeStatus: {$exception->getMessage()} in File: {$exception->getFile()} on Line: {$exception->getLine()}");
            Notification::make()->danger()->title(__('messages.something_went_wrong'))->send();
        }
    }

    protected function getActions(): array
    {
        return [];
    }
}
