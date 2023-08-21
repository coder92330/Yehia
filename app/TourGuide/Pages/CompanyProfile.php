<?php

namespace App\TourGuide\Pages;

use App\Models\Company;
use Filament\Pages\Page;
use Illuminate\Contracts\Support\Htmlable;
use Illuminate\Support\Facades\Route;
use Iotronlab\FilamentMultiGuard\Concerns\ContextualPage;

class CompanyProfile extends Page
{
    use ContextualPage;

    protected static ?string $navigationIcon = 'heroicon-o-office-building';

    protected static string $view = 'filament.pages.tourguide.company-profile';

    protected static ?int $navigationSort = 4;

    protected static bool $shouldRegisterNavigation = false;

    protected static ?string $slug = 'company-profile';

    public $company_id;

    protected static function getNavigationLabel(): string
    {
        return __('navigation.labels.company-profile');
    }

    protected function getHeading(): string|Htmlable
    {
        return '';
    }

    public static function getRoutes(): \Closure
    {
        return fn() => Route::get('/company-profile/{company_id}', static::class)->name('company-profile');
    }

    protected function getBreadcrumbs(): array
    {
        return [
            route('tour-guide.pages.dashboard') => __('breadcrumbs.dashboard'),
            url()->current() => __('breadcrumbs.company-profile'),
        ];
    }


    public function mount()
    {
        $this->company_id = request('company_id');
    }

    protected function getViewData(): array
    {
        return [
            'record' => Company::find($this->company_id)
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
