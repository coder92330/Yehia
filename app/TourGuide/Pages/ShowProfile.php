<?php

namespace App\TourGuide\Pages;

use Filament\Pages\Page;
use Iotronlab\FilamentMultiGuard\Concerns\ContextualPage;

class ShowProfile extends Page
{
    use ContextualPage;

    protected static ?string $navigationIcon = 'heroicon-o-user-circle';

    protected static string $view = 'filament.pages.tourguide.view-profile';

    protected static ?string $slug = 'show-profile';

    protected static bool $shouldRegisterNavigation = false;

    protected static function getNavigationLabel(): string
    {
        return __('navigation.labels.show-profile');
    }

    public function getHeading(): string
    {
        return '';
    }

    protected function getViewData(): array
    {
        return [
            'record' => auth('tourguide')->user(),
        ];
    }

    protected function getBreadcrumbs(): array
    {
        return [
            url()->current() => __('breadcrumbs.profile'),
        ];
    }

    public function editPortfolio()
    {
        return redirect()->route('tour-guide.pages.profile');
    }
}
