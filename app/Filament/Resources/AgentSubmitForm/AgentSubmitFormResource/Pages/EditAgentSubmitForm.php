<?php

namespace App\Filament\Resources\AgentSubmitForm\AgentSubmitFormResource\Pages;

use App\Filament\Resources\AgentSubmitForm\AgentSubmitFormResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\EditRecord;

class EditAgentSubmitForm extends EditRecord
{
    protected static string $resource = AgentSubmitFormResource::class;

    protected function getActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
