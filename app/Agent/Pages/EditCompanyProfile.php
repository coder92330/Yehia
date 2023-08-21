<?php

namespace App\Agent\Pages;

use App\Models\Company;
use Filament\Forms\Components\Card;
use Filament\Forms\Components\SpatieMediaLibraryFileUpload;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Iotronlab\FilamentMultiGuard\Concerns\ContextualPage;

class EditCompanyProfile extends Page
{
    use ContextualPage, InteractsWithForms;

    protected static ?string $navigationIcon = 'heroicon-o-office-building';

    protected static string $view = 'filament.pages.agent.edit-company-profile';

    protected static string|array $middlewares = 'permission:Edit Company Profile';

    protected static bool $shouldRegisterNavigation = false;

    protected static ?int $navigationSort = 11;

    public Company $company;

    protected static function getNavigationGroup(): ?string
    {
        return __('navigation.groups.companies');
    }

    protected static function getNavigationLabel(): string
    {
        return __('navigation.labels.company-profile');
    }

    protected function getBreadcrumbs(): array
    {
        return [
            route('filament.pages.dashboard') => __('Home'),
            url()->current() => __('Company Profile'),
        ];
    }

    protected function getFormModel(): Model|string|null
    {
        return Company::class;
    }

    public function mount()
    {
        $this->company = auth('agent')->user()->company;
        $this->form->fill(auth('agent')->user()->company->toArray());
    }

    protected function getFormSchema(): array
    {
        return [
            Card::make()->schema([
                TextInput::make('name')
                    ->label(__('attributes.name'))
                    ->placeholder(__('attributes.name'))
                    ->required(),

                TextInput::make('email')
                    ->label(__('attributes.email'))
                    ->placeholder(__('attributes.email'))
                    ->email()
                    ->required(),

                TextInput::make('website')
                    ->label(__('attributes.website'))
                    ->placeholder(__('attributes.website'))
                    ->required(),

                TextInput::make('address')
                    ->label(__('attributes.address'))
                    ->placeholder(__('attributes.address'))
                    ->required(),

                Textarea::make('specialties')
                    ->label(__('attributes.specialties'))
                    ->placeholder(__('attributes.specialties')),

                Textarea::make('description')
                    ->label(__('attributes.description'))
                    ->placeholder(__('attributes.description')),

                TextInput::make('facebook')
                    ->label(__('attributes.facebook'))
                    ->placeholder(__('attributes.facebook')),

                TextInput::make('twitter')
                    ->label(__('attributes.twitter'))
                    ->placeholder(__('attributes.twitter')),

                TextInput::make('instagram')
                    ->label(__('attributes.instagram'))
                    ->placeholder(__('attributes.instagram')),

                TextInput::make('linkedin')
                    ->label(__('attributes.linkedin'))
                    ->placeholder(__('attributes.linkedin')),

                SpatieMediaLibraryFileUpload::make('logo')
                    ->label(__('attributes.logo'))
                    ->model($this->company)
                    ->rules(['image', 'max:2048', 'mimes:jpeg,jpg,png'])
                    ->collection('companies_logo')
                    ->hint(__('attributes.image_hint', ['formats' => 'jpeg, jpg, png', 'size' => '2MB']))
                    ->placeholder(__('attributes.logo_placeholder', ['attribute' => __('attributes.logo')])),

                SpatieMediaLibraryFileUpload::make('cover')
                    ->label(__('attributes.cover'))
                    ->model($this->company)
                    ->hint(__('attributes.image_hint', ['formats' => 'jpeg, jpg, png', 'size' => '2MB']))
                    ->placeholder(__('attributes.image_placeholder', ['attribute' => __('attributes.cover')]))
                    ->rules(['image', 'max:2048', 'mimes:jpeg,jpg,png'])
                    ->collection('companies_cover'),
            ]),
        ];
    }

    public function save()
    {
        try {
            DB::beginTransaction();
            $this->company->update($this->form->getState());
            $this->form->model($this->company)->saveRelationships();
            Notification::make()->success()->title(__('messages.company_profile_updated'))->send();
            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            Notification::make()->title($e->getMessage())->danger()->send();
        }
    }
}
