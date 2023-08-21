<?php

namespace App\Tourguide\Resources\Profile;

use App\Models\City;
use App\Models\Country;
use App\Models\Language;
use App\Models\Skill;
use App\Models\Style;
use App\Tourguide\Resources\Profile\ProfileResource\Pages;
use App\Tourguide\Resources\Profile\ProfileResource\RelationManagers;
use App\Models\Tourguide;
use App\Tourguide\Resources\Profile\ProfileResource\RelationManagers\LanguagesRelationManager;
use App\Tourguide\Resources\Profile\ProfileResource\RelationManagers\SkillsRelationManager;
use Filament\Forms;
use Filament\Forms\Components\Card;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Fieldset;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\SpatieMediaLibraryFileUpload;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Resources\Concerns\Translatable;
use Filament\Resources\Form;
use Filament\Resources\Resource;
use Filament\Resources\Table;
use Filament\Tables;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Iotronlab\FilamentMultiGuard\Concerns\ContextualResource;

class ProfileResource extends Resource
{
    use ContextualResource, Translatable;

    public static function getTranslatableLocales(): array
    {
        return config('app.locales');
    }

    protected static ?string $model = Tourguide::class;

    protected static ?string $slug = 'profile';

    protected static ?string $navigationIcon = 'heroicon-o-user-circle';

//    protected static bool $shouldRegisterNavigation = false;

    protected static function getNavigationLabel(): string
    {
        return __('navigation.labels.my_profile');
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
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

                    Select::make('city_id')
                        ->label(__('attributes.location'))
                        ->placeholder(__('attributes.select', ['field' => __('attributes.location')]))
                        ->options(fn() => City::active()->get()->pluck('name', 'id'))
                        ->rules(['required', 'integer', 'exists:cities,id'])
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

                    Forms\Components\Textarea::make('bio')
                        ->label(__('attributes.bio'))
                        ->rules(['nullable', 'string', 'max:500']),

                    SpatieMediaLibraryFileUpload::make('avatar')
                        ->label(__('attributes.avatar'))
                        ->hint(__('attributes.image_hint', ['formats' => 'jpeg, jpg, png', 'size' => '2MB']))
                        ->placeholder(__('attributes.image_placeholder', ['attribute' => __('attributes.avatar')]))
                        ->rules(['image', 'max:2048', 'mimes:jpeg,jpg,png'])
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
                        ->relationship('certificates')
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
                        ->relationship('work_experiences')
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

                    Repeater::make('phones')
                        ->label(__('attributes.phone.title'))
                        ->relationship('phones')
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
            ]);
    }

    public static function getRelations(): array
    {
        return [
            LanguagesRelationManager::class,
            SkillsRelationManager::class
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListProfiles::route('/'),
            'edit' => Pages\EditProfile::route('/{record}/edit'),
        ];
    }
}
