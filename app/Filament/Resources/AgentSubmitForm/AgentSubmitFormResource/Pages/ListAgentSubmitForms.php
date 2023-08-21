<?php

namespace App\Filament\Resources\AgentSubmitForm\AgentSubmitFormResource\Pages;

use App\Filament\Resources\AgentSubmitForm\AgentSubmitFormResource;
use App\Imports\AgentSubmitFormImport;
use Filament\Forms\Components\FileUpload;
use Filament\Notifications\Notification;
use Filament\Pages\Actions;
use Filament\Resources\Pages\ListRecords;
use Maatwebsite\Excel\Facades\Excel;

class ListAgentSubmitForms extends ListRecords
{
    protected static string $resource = AgentSubmitFormResource::class;

    protected function getActions(): array
    {
        return [
            Actions\Action::make('import')
                ->label(__('actions.import'))
                ->icon('heroicon-o-cloud-upload')
                ->form([
                    FileUpload::make('file')
                        ->label(__('attributes.file'))
                        ->directory('imports')
                        ->required(),
                ])
                ->action(function (array $data) {
                    Excel::import(new AgentSubmitFormImport, "storage/{$data['file']}");
                    Notification::make()
                        ->title(__('notifications.imported'))
                        ->success()
                        ->body(__('notifications.agent_submit_forms_imported'))
                        ->send();
                }),
        ];
    }
}
