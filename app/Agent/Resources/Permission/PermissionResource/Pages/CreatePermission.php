<?php

namespace App\Agent\Resources\Permission\PermissionResource\Pages;

use App\Agent\Resources\Permission\PermissionResource;
use Filament\Pages\Actions\Action;
use Filament\Resources\Pages\CreateRecord;
use Iotronlab\FilamentMultiGuard\Concerns\ContextualPage;

class CreatePermission extends CreateRecord
{
    use ContextualPage;

    protected static string $resource = PermissionResource::class;

    protected function getCancelFormAction(): Action
    {
        return Action::make('cancel')
            ->label(__('filament::resources/pages/create-record.form.actions.cancel.label'))
            ->requiresConfirmation()
            ->action(fn () => $this->redirect($this->previousUrl ?? static::getResource()::getUrl()))
            ->modalHeading(__('modal.cancel.heading'))
            ->modalSubheading(__('modal.cancel.subheading'))
            ->modalButton(__('modal.cancel.button'))
            ->color('secondary');
    }
}
