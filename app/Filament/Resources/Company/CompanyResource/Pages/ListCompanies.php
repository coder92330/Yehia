<?php

namespace App\Filament\Resources\Company\CompanyResource\Pages;

use App\Filament\Resources\Company\CompanyResource;
use Filament\Forms\Components\FileUpload;
use Filament\Notifications\Notification;
use Filament\Pages\Actions;
use Filament\Resources\Pages\ListRecords;
use Maatwebsite\Excel\Facades\Excel;

class ListCompanies extends ListRecords
{
    use ListRecords\Concerns\Translatable;

    protected static string $resource = CompanyResource::class;

    protected static string | array $middlewares = ['permission:List Companies'];

    protected function getActions(): array
    {
        return [
            Actions\LocaleSwitcher::make(),
            Actions\CreateAction::make(),
            Actions\Action::make(__('actions.import'))
                ->icon('heroicon-o-cloud-upload')
                ->form([
                    FileUpload::make('file')
                        ->label(__('attributes.file'))
                        ->directory('imports')
                        ->required(),
                ])
                ->action(function (array $data) {
                    Excel::import(new \App\Imports\CompanyImport, "storage/{$data['file']}");
                    Notification::make()
                        ->title(__('notifications.companies_imported'))
                        ->success()
                        ->body(__('notifications.companies_imported_successfully'))
                        ->send();
                }),
        ];
    }
}
