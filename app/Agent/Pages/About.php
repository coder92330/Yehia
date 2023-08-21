<?php

namespace App\Agent\Pages;

use Filament\Pages\Page;
use Illuminate\Contracts\Support\Htmlable;
use Iotronlab\FilamentMultiGuard\Concerns\ContextualPage;

class About extends Page
{
    use ContextualPage;

    protected static ?string $navigationIcon = 'heroicon-o-office-building';

    protected static string $view = 'filament.pages.agent.about-company';

    protected static ?string $slug = 'company-profile';

    protected static ?int $navigationSort = 1;

    protected static bool $shouldRegisterNavigation = false;

    protected static function getNavigationGroup(): ?string
    {
        return __('navigation.groups.companies');
    }

    protected static function getNavigationLabel(): string
    {
        return __('navigation.labels.company-profile');
    }


    protected function getHeading(): string|Htmlable
    {
        return '';
    }

    protected function getBreadcrumbs(): array
    {
        return [
            route('filament.pages.dashboard') => __('breadcrumbs.dashboard'),
            url()->current() => __('breadcrumbs.company-profile'),
        ];
    }

    protected function getViewData(): array
    {
        return [
            'record' => auth('agent')->user()->company,
        ];
    }

    protected function getLayoutData(): array
    {
        return [...parent::getLayoutData(), 'vite' => true];
    }

    public function redirectToRoute($route)
    {
        return redirect()->to(route($route));
    }
}
