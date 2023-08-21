<?php

namespace App\TourGuide\Pages;

use App\Models\Country;
use App\Models\Language;
use App\Models\Skill;
use App\Models\Style;
use App\Models\Tourguide;
use Filament\Forms\Components\Card;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Fieldset;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\SpatieMediaLibraryFileUpload;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Iotronlab\FilamentMultiGuard\Concerns\ContextualPage;

class Profile extends Page
{
    use ContextualPage;

    protected static ?string $navigationIcon = 'heroicon-o-user';

    protected static string $view = 'filament.pages.profile';

    protected static ?string $slug = 'profile-ss';

    protected static bool $shouldRegisterNavigation = false;

    public Tourguide $tourguide;

    protected function getTitle(): string
    {
        return __('navigation.title.edit_profile');
    }

    protected static function getNavigationLabel(): string
    {
        return __('navigation.labels.profile');
    }

    public static function getRouteName(): string
    {
        return 'tour-guide.pages.edit-profile';
    }

    protected function getBreadcrumbs(): array
    {
        return [
            url()->current() => 'Profile',
        ];
    }

    protected function getFormModel(): Model|string|null
    {
        return Tourguide::class;
    }

    public function mount()
    {
        $this->tourguide = auth('tourguide')->user();
        $this->form->fill([
            ...auth('tourguide')->user()->toArray(),
            'certificates' => auth('tourguide')->user()->certificates,
            'work_experiences' => auth('tourguide')->user()->work_experiences,
            'phones' => auth('tourguide')->user()->phones,
            'skills' => auth('tourguide')->user()->skills->pluck('pivot')->toArray(),
            'languages' => auth('tourguide')->user()->languages->pluck('pivot')->toArray(),
        ]);
    }

    protected function getFormSchema(): array
    {
        return [
            Card::make()->schema([
                TextInput::make('first_name')
                    ->label(__('attributes.first_name'))
                    ->rules(['required', 'string', 'max:80'])
                    ->required(),

                TextInput::make('last_name')
                    ->label(__('attributes.last_name'))
                    ->rules(['required', 'string', 'max:80'])
                    ->required(),

                TextInput::make('email')
                    ->label(__('attributes.email'))
                    ->email()
                    ->rules(['required', 'email', 'unique:tourguides,email,' . auth('tourguide')->id()])
                    ->afterStateUpdated(fn(\Closure $set, $state) => $set('username', explode('@', $state)[0]))
                    ->required(),

                TextInput::make('username')
                    ->label(__('attributes.username'))
                    ->rules(['required', 'string', 'max:80', 'unique:tourguides,username,' . auth('tourguide')->id()])
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
                    ->placeholder(__('attributes.select', ['field' => __('attributes.country')]))
                    ->options(fn() => Country::active()->get()->pluck('name', 'id'))
                    ->rules(['required', 'integer', 'exists:countries,id'])
                    ->searchable()
                    ->required(),

                Select::make('style_id')
                    ->label(__('attributes.style'))
                    ->placeholder(__('attributes.select', ['field' => __('attributes.style')]))
                    ->options(fn() => Style::orderByRaw("FIELD(name, 'violet') DESC")
                        ->selectRaw('if(name = "violet", "Default", CONCAT(UPPER(SUBSTRING(name,1,1)),SUBSTRING(name,2))) as name, id')
                        ->pluck('name', 'id'))
                    ->searchable()
                    ->default(Style::defaultStyleId()),

                DatePicker::make('birthdate')
                    ->label(__('attributes.birthdate'))
                    ->before(now()->subYears(18)),

                TextInput::make('education')
                    ->label(__('attributes.education'))
                    ->rules(['string', 'max:100']),

                TextInput::make('age')
                    ->label(__('attributes.age'))
                    ->integer()
                    ->rules(['integer']),

                TextInput::make('years_of_experience')
                    ->label(__('attributes.years_of_experience'))
                    ->integer(),

                TextInput::make('facebook')
                    ->label(__('attributes.facebook'))
                    ->rules(['nullable', 'url'])
                    ->url(),

                TextInput::make('twitter')
                    ->label(__('attributes.twitter'))
                    ->rules(['nullable', 'url'])
                    ->url(),

                TextInput::make('instagram')
                    ->label(__('attributes.instagram'))
                    ->rules(['nullable', 'url'])
                    ->url(),

                TextInput::make('linkedin')
                    ->label(__('attributes.linkedin'))
                    ->rules(['nullable', 'url'])
                    ->url(),

                Textarea::make('bio')
                    ->label(__('attributes.bio'))
                    ->rules(['nullable', 'string', 'max:500']),

                SpatieMediaLibraryFileUpload::make('avatar')
                    ->label(__('attributes.avatar'))
                    ->hint(__('attributes.image_hint', ['formats' => 'jpeg, jpg, png', 'size' => '2MB']))
                    ->placeholder(__('attributes.image_placeholder', ['attribute' => __('attributes.avatar')]))
                    ->model($this->tourguide)
                    ->collection('tourguide_avatar'),

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

                Repeater::make('certificates')
                    ->schema([
                        TextInput::make('title')
                            ->label(__('attributes.certificate.title'))
                            ->required(),

                        TextInput::make('authority')
                            ->label(__('attributes.certificate.authority'))
                            ->required(),

                        DatePicker::make('date')
                            ->label(__('attributes.certificate.date'))
                            ->required(),

                        TextInput::make('description')
                            ->label(__('attributes.certificate.description'))
                            ->nullable(),
                    ]),

                Repeater::make('work_experiences')
                    ->schema([
                        TextInput::make('title')
                            ->label(__('attributes.work_experience.job_title'))
                            ->required(),

                        TextInput::make('company')
                            ->label(__('attributes.work_experience.company'))
                            ->required(),

                        TextInput::make('location')
                            ->label(__('attributes.work_experience.location'))
                            ->required(),

                        TextInput::make('description')
                            ->label(__('attributes.work_experience.description'))
                            ->nullable(),

                        DatePicker::make('start_date')
                            ->label(__('attributes.work_experience.start_date'))
                            ->required(),

                        DatePicker::make('end_date')
                            ->label(__('attributes.work_experience.end_date'))
                            ->required()
                            ->before(now())
                            ->default(now()),

                        Toggle::make('is_current')
                            ->label(__('attributes.work_experience.current_job'))
                            ->onIcon('heroicon-o-check')
                            ->offIcon('heroicon-o-x')
                            ->default(true),
                    ]),

                Repeater::make('skills')
                    ->schema([
                        Select::make('skill_id')
                            ->label(__('attributes.skill'))
                            ->placeholder(__('attributes.select', ['field' => __('attributes.skill')]))
                            ->required()
                            ->searchable()
                            ->rules(['required', 'integer', 'exists:skills,id'])
                            ->options(fn() => Skill::all()->pluck('name', 'id')),

                        Select::make('level')
                            ->label(__('attributes.level'))
                            ->placeholder(__('attributes.select', ['field' => __('attributes.level')]))
                            ->required()
                            ->default('basic')
                            ->options([
                                'beginner' => __('attributes.beginner'),
                                'intermediate' => __('attributes.intermediate'),
                                'advanced' => __('attributes.advanced'),
                            ]),
                    ]),

                Repeater::make('languages')
                    ->schema([
                        Select::make('language_id')
                            ->label(__('attributes.language'))
                            ->placeholder(__('attributes.select', ['field' => __('attributes.language')]))
                            ->required()
                            ->searchable()
                            ->rules(['required', 'integer', 'exists:languages,id'])
                            ->options(fn() => Language::all()->pluck('name', 'id')),

                        Select::make('level')
                            ->label(__('attributes.level'))
                            ->placeholder(__('attributes.select', ['field' => __('attributes.level')]))
                            ->required()
                            ->default('basic')
                            ->options([
                                'beginner' => __('attributes.beginner'),
                                'intermediate' => __('attributes.intermediate'),
                                'advanced' => __('attributes.advanced'),
                            ]),

                        Toggle::make('is_default')
                            ->label(__('attributes.native_language'))
                            ->hint(__('attributes.native_language_hint'))
                            ->onIcon('heroicon-o-check')
                            ->offIcon('heroicon-o-x')
                            ->required()
                            ->default(false),
                    ]),

                Repeater::make('phones')
                    ->schema([
                        TextInput::make('number')
                            ->label(__('attributes.phone.number'))

                            ->required(),

                        TextInput::make('country_code')
                            ->label(__('attributes.phone.country_code'))
                            ->default(auth('tourguide')->user()->country?->country_code)
                            ->required(),

                        Select::make('type')
                            ->label(__('attributes.phone.type'))
                            ->placeholder(__('attributes.select', ['field' => __('attributes.phone.type')]))
                            ->required()
                            ->default('mobile')
                            ->options([
                                'mobile' => __('attributes.mobile'),
                                'home' => __('attributes.home'),
                                'work' => __('attributes.work'),
                            ]),

                        Fieldset::make('Actions')
                            ->columns(2)
                            ->schema([
                                Toggle::make('is_primary')
                                    ->label(__('attributes.primary'))
                                    ->onIcon('heroicon-o-check')
                                    ->offIcon('heroicon-o-x')
                                    ->default(true),

                                Toggle::make('is_active')
                                    ->label(__('attributes.active'))
                                    ->onIcon('heroicon-o-check')
                                    ->offIcon('heroicon-o-x')
                                    ->default(true),
                            ]),
                    ]),
            ]),
        ];
    }

    public function submit()
    {
        try {
            DB::beginTransaction();
            $data = $this->form->getState();
            if ($data['password']) {
                $data['password'] = Hash::make($data['password']);
                $data['password_confirmation'] = Hash::make($data['password_confirmation']);
            } else {
                $data = array_diff_key($data, array_flip(['password', 'password_confirmation']));
            }
            $this->tourguide->update($data);

            if (isset($data['phones'])) {
                $this->tourguide->phones()->delete();
                $this->tourguide->phones()->createMany($data['phones']);
            }

            if (isset($data['languages'])) {
                $this->tourguide->languages()->sync($data['languages']);
            }

            if (isset($data['skills'])) {
                $this->tourguide->skills()->sync(collect($data['skills'])->pluck('pivot')->map(function ($item) {
                    unset($item['created_at'], $item['updated_at']);
                    return $item;
                })->toArray());
            }

            if (isset($data['work_experiences'])) {
                $this->tourguide->work_experiences()->delete();
                $this->tourguide->work_experiences()->createMany($data['work_experiences']);
            }

            if (isset($data['certificates'])) {
                $this->tourguide->certificates()->delete();
                $this->tourguide->certificates()->createMany($data['certificates']);
            }

            Notification::make()->success()->title('Profile updated successfully!')->send();
            DB::commit();
            return redirect()->route('tour-guide.pages.profile');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::channel('tourguide')->error("Error in Profile@submit: {$e->getMessage()} in File: {$e->getFile()} on Line: {$e->getLine()}");
            Notification::make()->danger()->title('Something went wrong!')->send();
        }
    }
}
