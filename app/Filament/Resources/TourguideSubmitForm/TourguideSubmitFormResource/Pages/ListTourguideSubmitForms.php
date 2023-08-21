<?php

namespace App\Filament\Resources\TourguideSubmitForm\TourguideSubmitFormResource\Pages;

use App\Filament\Resources\TourguideSubmitForm\TourguideSubmitFormResource;
use App\Imports\TourguideSubmitFormImport;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\TextInput;
use Filament\Notifications\Notification;
use Filament\Pages\Actions;
use Filament\Resources\Pages\ListRecords;
use Maatwebsite\Excel\Facades\Excel;

class ListTourguideSubmitForms extends ListRecords
{
    protected static string $resource = TourguideSubmitFormResource::class;

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
                    Excel::import(new TourguideSubmitFormImport, "storage/{$data['file']}");
                    Notification::make()
                        ->title(__('notifications.toruguide_submit_forms_imported'))
                        ->success()
                        ->body(__('notifications.toruguide_submit_forms_imported_successfully'))
                        ->send();
                }),
        ];
    }
}
