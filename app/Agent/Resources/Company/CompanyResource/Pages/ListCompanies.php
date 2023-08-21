<?php

namespace App\Agent\Resources\Company\CompanyResource\Pages;

use App\Agent\Resources\Company\CompanyResource;
use App\Models\Company;
use Filament\Pages\Actions;
use Filament\Resources\Pages\ListRecords;
use Iotronlab\FilamentMultiGuard\Concerns\ContextualPage;

class ListCompanies extends ListRecords
{
    use ListRecords\Concerns\Translatable, ContextualPage;

    protected static string $resource = CompanyResource::class;

    protected static string | array $middlewares = ['permission:List Companies'];

    protected static string $view = 'filament.pages.agent.about-company';

    protected function getViewData(): array
    {
        return [
            'record' => auth('agent')->user()->company,
        ];
    }

    public function getHeading(): string
    {
        return '';
    }

    public function redirectToRoute($route)
    {
        return redirect()->to(route($route, auth('agent')->user()->company->id));
    }

    protected function getActions(): array
    {
        return [
            Actions\LocaleSwitcher::make(),
        ];
    }
}
