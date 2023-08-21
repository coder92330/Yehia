<?php

namespace App\Agent\Pages;

use App\Models\Agent;
use App\Models\Country;
use App\Models\Style;
use Filament\Forms\Components\Card;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Fieldset;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\SpatieMediaLibraryFileUpload;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Iotronlab\FilamentMultiGuard\Concerns\ContextualPage;

class Profile extends Page
{
    use ContextualPage;

    protected static ?string $navigationIcon = 'heroicon-o-user';

    protected static string $view = 'filament.pages.profile';

    protected static ?string $slug = 'profile';

    protected static ?int $navigationSort = 10;

//    protected static bool $shouldRegisterNavigation = false;

    public Agent $agent;

    protected function getTitle(): string
    {
        return __('navigation.labels.profile');
    }

    protected static function getNavigationLabel(): string
    {
        return __('navigation.labels.profile');
    }

    protected function getBreadcrumbs(): array
    {
        return [
            route('agent.pages.dashboard') => 'Home',
            url()->current() => 'Profile',
        ];
    }

    protected function getFormModel(): Model|string|null
    {
        return Agent::class;
    }

    public function mount()
    {
        $this->agent = auth('agent')->user();
        $this->form->fill([
            ...auth('agent')->user()->toArray(),
            'phones' => auth('agent')->user()->phones,
        ]);
    }

    protected function getFormSchema(): array
    {
        return [
            Card::make()->schema([

                TextInput::make('first_name')
                    ->label(__('attributes.first_name'))
                    ->required(),

                TextInput::make('last_name')
                    ->label(__('attributes.last_name'))
                    ->required(),

                TextInput::make('email')
                    ->label(__('attributes.email'))
                    ->required(),

                TextInput::make('username')
                    ->label(__('attributes.username'))
                    ->required(),

                TextInput::make('password')
                    ->label(__('attributes.password'))
                    ->required(fn() => \Route::is('filament.tourguides.create'))
                    ->password()
                    ->confirmed(),

                TextInput::make('password_confirmation')
                    ->label(__('attributes.password_confirmation'))
                    ->requiredWith('password')
                    ->password(),

                Select::make('country_id')
                    ->label(__('attributes.country'))
                    ->placeholder(__('attributes.country_placeholder'))
                    ->options(fn() => \App\Models\Country::active()->get()->pluck('name', 'id'))
                    ->required(),

                Select::make('company_id')
                    ->label(__('attributes.company.name'))
                    ->placeholder(__('attributes.company_placeholder'))
                    ->options(fn() => \App\Models\Company::all()->pluck('name', 'id'))
                    ->required(),

                Select::make('style_id')
                    ->label(__('attributes.style'))
                    ->placeholder(__('attributes.style_placeholder'))
                    ->visible(fn() => auth('agent')->user()->company->styles->isNotEmpty())
                    ->options(fn() => auth('agent')->user()->company->styles()
                        ->selectRaw('if(name = "violet", "Default", CONCAT(UPPER(SUBSTRING(name,1,1)),SUBSTRING(name,2))) as name, styles.id')
                        ->pluck('name', 'id'))
                    ->default(Style::defaultStyleId()),

                DatePicker::make('birthdate')
                    ->before(now()->subYears(18)),

                TextInput::make('age')->label(__('attributes.age')),

                TextInput::make('years_of_experience')->label(__('attributes.years_of_experience')),

                TextInput::make('facebook')
                    ->label(__('attributes.facebook'))
                    ->url(),

                TextInput::make('twitter')
                    ->label(__('attributes.twitter'))
                    ->url(),

                TextInput::make('instagram')
                    ->label(__('attributes.instagram'))
                    ->url(),

                TextInput::make('linkedin')
                    ->label(__('attributes.linkedin'))
                    ->url(),

                SpatieMediaLibraryFileUpload::make('avatar')
                    ->label(__('attributes.avatar'))
                    ->model($this->agent)
                    ->rules(['image', 'max:2048', 'mimes:jpeg,jpg,png'])
                    ->collection('agent_avatar')
                    ->hint(__('attributes.image_hint', ['formats' => 'jpeg, jpg, png', 'size' => '2MB']))
                    ->placeholder(__('attributes.image_placeholder', ['attribute' => __('attributes.avatar')])),

                Fieldset::make('Actions')
                    ->columns(2)
                    ->schema([
                        Toggle::make('is_active')
                            ->label(__('attributes.active'))
                            ->onIcon('heroicon-o-check')
                            ->offIcon('heroicon-o-x')
                            ->default(true),

                        Toggle::make('is_online')
                            ->label(__('attributes.online'))
                            ->onIcon('heroicon-o-check')
                            ->offIcon('heroicon-o-x')
                            ->default(true),
                    ]),

                Repeater::make('phones')
                    ->label(__('attributes.phones'))
                    ->relationship('phones')
                    ->schema([
                        TextInput::make('number')
                            ->label(__('attributes.number'))
                            ->required()
                            ->rules(Rule::unique('phones', 'number')->ignore(auth('agent')->id(), 'phonable_id')->where('phonable_type', auth('agent')->user()->getMorphClass()))
                            ,

                        TextInput::make('country_code')
                            ->label(__('attributes.country_code'))
                            ->required()
                            ->default(Country::active()->where('id', auth('agent')->user()->country_id)->first()->country_code),

                        Select::make('type')
                            ->options([
                                'mobile' => __('attributes.mobile'),
                                'home' => __('attributes.home'),
                                'work' => __('attributes.work'),
                            ])
                            ->required(),

                        Fieldset::make('Actions')
                            ->columns(2)
                            ->schema([
                                Toggle::make('is_primary')
                                    ->label(__('attributes.primary'))
                                    ->default(true),

                                Toggle::make('is_active')
                                    ->label(__('attributes.active'))
                                    ->default(true),
                            ]),
                    ]),
            ]),
        ];
    }

    public function submit()
    {
        $data = $this->form->getState();
        if ($data['password']) {
            $data['password'] = Hash::make($data['password']);
            $data['password_confirmation'] = Hash::make($data['password_confirmation']);
        } else {
            $data = array_diff_key($data, array_flip(['password', 'password_confirmation']));
        }
        $this->agent->update($data);
        $this->form->model($this->agent)->saveRelationships();
        Notification::make()->success()->title(__('notifications.profile_updated_successfully'))->send();
        return redirect()->route('agent.pages.profile');
    }
}
