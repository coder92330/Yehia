<?php

namespace App\Filament\Pages;

use App\Models\Country;
use App\Models\Style;
use Filament\Forms\Components\Card;
use Filament\Forms\Components\Fieldset;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class Profile extends Page
{

    protected static ?string $navigationIcon = 'heroicon-o-user';

    protected static string $view = 'filament.pages.profile';

    protected static ?string $slug = 'profile';

//    protected static bool $shouldRegisterNavigation = false;

    protected function getTitle(): string
    {
        return __('navigation.titles.profile');
    }

    protected static function getNavigationLabel(): string
    {
        return __('navigation.labels.my_profile');
    }

    protected function getBreadcrumbs(): array
    {
        return [
            url()->current() => __('navigation.labels.my_profile'),
        ];
    }

    public function mount()
    {
        $this->form->fill([
            'name' => auth()->user()->name,
            'email' => auth()->user()->email,
            'country_id' => auth()->user()->country_id,
            'style_id' => auth()->user()->style_id,
            'phones' => auth()->user()->phones->toArray(),
        ]);
    }

    protected function getFormSchema(): array
    {
        return [
            Card::make()->schema([
                TextInput::make('name')
                    ->label(__('attributes.name'))
                    ->rules(['required', 'string', 'max:80'])
                    ->string()
                    ->required(),

                TextInput::make('email')
                    ->label(__('attributes.email'))
                    ->email()
                    ->rules(['required', 'email', "unique:users,email," . auth()->id() . ",id"])
                    ->required(),

                TextInput::make('password')
                    ->label(__('attributes.password'))
                    ->rules(['min:8', 'confirmed'])
                    ->password()
                    ->confirmed(),

                TextInput::make('password_confirmation')
                    ->label(__('attributes.password_confirmation'))
                    ->requiredWith('password')
                    ->rules('min:8')
                    ->password(),

                Select::make('country_id')
                    ->label(__('attributes.country'))
                    ->options(fn() => Country::active()->get()->pluck('name', 'id'))
                    ->searchable()
                    ->rules(['required', 'exists:countries,id'])
                    ->required(),

                Select::make('style_id')
                    ->label(__('attributes.style'))
                    ->placeholder(__('attributes.select', ['field' => __('attributes.style')]))
                    ->options(fn() => Style::orderByRaw("FIELD(name, 'violet') DESC")
                        ->selectRaw('if(name = "violet", "Default", CONCAT(UPPER(SUBSTRING(name,1,1)),SUBSTRING(name,2))) as name, id')
                        ->pluck('name', 'id'))
                    ->searchable()
                    ->default(Style::defaultStyleId()),

                Repeater::make('phones')
                    ->label(__('attributes.phones'))
                    ->schema([
                        TextInput::make('number')
                            ->label(__('attributes.phone.number'))
                            ->required()
                            ->rules(['required', 'numeric', Rule::unique('phones', 'number')->ignore(auth()->id(), 'phonable_id')->where('phonable_type', auth()->user()->getMorphClass())])
                            ,

                        TextInput::make('country_code')
                            ->label(__('attributes.phone.country_code'))
                            ->required()
                            ->default(Country::active()->where('id', auth()->user()->country_id)->first()->country_code),

                        Select::make('type')
                            ->label(__('attributes.phone.type'))
                            ->options([
                                'mobile' => __('attributes.mobile'),
                                'home' => __('attributes.home'),
                                'work' => __('attributes.work'),
                            ])
                            ->required(),

                        Fieldset::make('Actions')
                            ->label(__('attributes.actions'))
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
        try {
            $values = $this->form->getState();
            auth()->user()->update([
                'name' => $values['name'],
                'email' => $values['email'],
                'country_id' => $values['country_id'],
                'style_id' => $values['style_id'],
            ]);

            if ($values['password']) {
                auth()->user()->update([
                    'password' => Hash::make($values['password']),
                ]);
            }

            auth()->user()->phones()->delete();
            auth()->user()->phones()->createMany($values['phones']);

            Notification::make()->success()->title(__('messages.updated_successfully', ['field' => __('attributes.profile')]))->send();
            return redirect()->route('filament.pages.profile');
        } catch (\Exception $e) {
            Notification::make()->danger()->title(__('messages.something_went_wrong'))->send();
        }
    }
}
