<?php

namespace App\Filament\Resources\LandingPage\LandingPageResource\Pages;

use App\Filament\Resources\LandingPage\LandingPageResource;
use Filament\Forms\Components\Card;
use Filament\Forms\Components\Fieldset;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\SpatieMediaLibraryFileUpload;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Pages\Actions;
use Filament\Resources\Pages\EditRecord;
use Illuminate\Support\Str;

class EditLandingPage extends EditRecord
{
    use EditRecord\Concerns\Translatable;

    protected static string $resource = LandingPageResource::class;

    protected static string|array $middlewares = ['permission:Edit Home Page'];

    public function mount($record): void
    {
        parent::mount($record);
        $this->form->fill([
            'key' => $this->record->key,
            'footer_social_media_facebook' => $this->record->contents()->where('name->' . app()->getLocale(), 'footer_social_media_facebook')->first()->content ?? null,
            'footer_social_media_instagram' => $this->record->contents()->where('name->' . app()->getLocale(), 'footer_social_media_instagram')->first()->content ?? null,
            'footer_social_media_linkedin' => $this->record->contents()->where('name->' . app()->getLocale(), 'footer_social_media_linkedin')->first()->content ?? null,
            'footer_contact_info_email' => $this->record->contents()->where('name->' . app()->getLocale(), 'footer_contact_info_email')->first()->content ?? null,
            'footer_contact_info_phone' => $this->record->contents()->where('name->' . app()->getLocale(), 'footer_contact_info_phone')->first()->content ?? null,
            'footer_contact_info_phone_2' => $this->record->contents()->where('name->' . app()->getLocale(), 'footer_contact_info_phone_2')->first()->content ?? null,
            'footer_contact_info_address' => $this->record->contents()->where('name->' . app()->getLocale(), 'footer_contact_info_address')->first()->content ?? null,
            'footer_download_our_app_content' => $this->record->contents()->where('name->' . app()->getLocale(), 'footer_download_our_app_content')->first()->content ?? null,
            'footer_download_our_app_app_store_link' => $this->record->contents()->where('name->' . app()->getLocale(), 'footer_download_our_app_app_store_link')->first()->content ?? null,
            'footer_download_our_app_google_play_link' => $this->record->contents()->where('name->' . app()->getLocale(), 'footer_download_our_app_google_play_link')->first()->content ?? null,
        ]);
    }

    protected function getFormSchema(): array
    {
        return [
            Card::make([
                TextInput::make('key')
                    ->label(__('attributes.landing_page.key'))
                    ->placeholder(__('attributes.landing_page.key'))
                    ->disabled(),

                ...$this->{"get" . str_replace(' ', '', ucwords($this->record->key)) . "FormSchema"}(),
            ])
        ];
    }

    private function getSliderFormSchema(): array
    {
        return [
            Repeater::make('contents')
                ->relationship('contents')
                ->label(__('attributes.landing_page.contents'))
                ->minItems(4)
                ->maxItems(4)
                ->schema([
                    Select::make('name')
                        ->label(__('attributes.landing_page.name'))
                        ->required()
                        ->reactive()
                        ->options([
                            'slider_hero' => 'Hero Section',
                            'slider_tourguide' => 'Tour Guide Section',
                            'slider_agent' => 'Travel Agent Section',
                            'slider_ourservice' => 'Our Service Section',
                        ]),

                    TextInput::make('title')
                        ->label(__('attributes.landing_page.title'))
                        ->required()
                        ->placeholder(__('attributes.landing_page.title')),

                    TextInput::make('content')
                        ->label(__('attributes.landing_page.content'))
//                        ->required()
                        ->placeholder(__('attributes.landing_page.content')),

                    TextInput::make('button_text')
                        ->label(__('attributes.landing_page.button_text'))
                        ->hidden(fn(callable $get) => in_array($get('name'), ['slider_hero', '', null], true))
                        ->required()
                        ->placeholder(__('attributes.landing_page.button_text')),

                    SpatieMediaLibraryFileUpload::make("images")
                        ->image()
                        ->enableReordering()
                        ->multiple(fn(callable $get) => in_array($get('name'), ['slider_hero', '', null], true))
                        ->enableOpen()
                        ->helperText(fn(callable $get) => in_array($get('name'), ['slider_hero', '', null], true) ? __('messages.landing_page.upload_multiple_images') : __('messages.landing_page.upload_single_image'))
                        ->hint(__('attributes.image_hint', ['formats' => 'jpeg, jpg, png', 'size' => '2MB']))
                        ->placeholder(__('attributes.image_placeholder', ['attribute' => __('attributes.landing_page.section_image')]))
                        ->rules(['image', 'max:2048', 'mimes:jpeg,jpg,png'])
                        ->label(__('attributes.landing_page.section_image'))
                        ->collection(fn($record, callable $get) => $get('name') === 'slider_hero' ? "landing_page_slider_hero" : "landing_page_slider"),
                ])
        ];
    }

    private function getAboutUsFormSchema(): array
    {
        return [
            Repeater::make('contents')
                ->relationship('contents')
                ->label(__('attributes.landing_page.content'))
                ->hint(__('attributes.landing_page.add_only_one_content_or_will_be_overwritten', ['attribute' => $this->record->key]))
                ->minItems(1)
                ->maxItems(1)
                ->schema([
                    TextInput::make('title')
                        ->label(__('attributes.landing_page.title'))
                        ->required()
                        ->placeholder(__('attributes.landing_page.title')),

                    TextInput::make('content')
                        ->label(__('attributes.landing_page.content'))
                        ->required()
                        ->placeholder(__('attributes.landing_page.content')),

                    TextInput::make('button_text')
                        ->label(__('attributes.landing_page.button_text'))
                        ->required()
                        ->placeholder(__('attributes.landing_page.button_text')),

                    TextInput::make('button_url')
                        ->label(__('attributes.landing_page.button_url'))
                        ->required()
                        ->placeholder(__('attributes.landing_page.button_url')),

                    SpatieMediaLibraryFileUpload::make('images')
                        ->required()
                        ->multiple()
                        ->maxFiles(3)
                        ->image()
                        ->enableReordering()
                        ->enableOpen()
                        ->label(__('attributes.landing_page.images'))
                        ->hint(__('attributes.image_hint', ['formats' => 'jpeg, jpg, png', 'size' => '2MB']))
                        ->placeholder(__('attributes.upload_images', ['count' => 3]))
                        ->rules(['required', 'image', 'max:3072', 'mimes:jpeg,jpg,png'])
                        ->helperText(__('attributes.landing_page.upload_multiple_images_or_will_be_overwritten', ['count' => 3]))
                        ->collection('landing_page_about_us'),
                ]),
        ];
    }

    private function getOurServicesFormSchema(): array
    {
        return [
            Repeater::make('contents')
                ->relationship('contents')
                ->hint(__('attributes.landing_page.add_only_one_content_or_will_be_overwritten', ['attribute' => $this->record->key]))
                ->minItems(1)
                ->maxItems(1)
                ->label(__('attributes.landing_page.content'))
                ->schema([
                    TextInput::make('title')
                        ->label(__('attributes.landing_page.title'))
                        ->required()
                        ->placeholder(__('attributes.landing_page.title')),

                    TextInput::make('content')
                        ->label(__('attributes.landing_page.content'))
                        ->required()
                        ->placeholder(__('attributes.landing_page.content')),
                ]),

            Repeater::make('services')
                ->relationship('services')
                ->label(__('attributes.landing_page.services'))
                ->schema([
                    TextInput::make('title')
                        ->label(__('attributes.landing_page.title'))
                        ->placeholder(__('attributes.landing_page.title'))
                        ->required(),

                    Textarea::make('content')
                        ->label(__('attributes.landing_page.content'))
                        ->placeholder(__('attributes.landing_page.content'))
                        ->required(),

                    SpatieMediaLibraryFileUpload::make('icon')
                        ->image()
                        ->label(__('attributes.landing_page.icon'))
                        ->collection('services')
                        ->hint(__('attributes.image_hint', ['formats' => 'jpeg, jpg, png', 'size' => '2MB']))
                        ->placeholder(__('attributes.image_placeholder', ['attribute' => __('attributes.landing_page.icon')]))
                        ->rules(['required', 'image', 'max:2048', 'mimes:jpeg,jpg,png']),

                    Fieldset::make('Actions')
                        ->schema([
                            Toggle::make('is_published')
                                ->label(__('attributes.landing_page.published'))
                                ->onIcon('heroicon-o-check')
                                ->offIcon('heroicon-o-x'),
                        ]),
                ]),
        ];
    }

    private function getSubscribeFormSchema(): array
    {
        return [
            Repeater::make('contents')
                ->relationship('contents')
                ->label(__('attributes.landing_page.content'))
                ->hint(fn($record) => __('attributes.landing_page.add_only_one_content_or_will_be_overwritten', ['attribute' => $record->key]))
                ->minItems(1)
                ->maxItems(1)
                ->schema([
                    TextInput::make('title')
                        ->label(__('attributes.landing_page.title'))
                        ->required()
                        ->placeholder(__('attributes.landing_page.title')),

                    TextInput::make('content')
                        ->label(__('attributes.landing_page.content'))
                        ->required()
                        ->placeholder(__('attributes.landing_page.content')),

                    TextInput::make('button_text')
                        ->label(__('attributes.landing_page.button_text'))
                        ->required()
                        ->placeholder(__('attributes.landing_page.button_text')),
                ]),
        ];
    }

    private function getSponsorsFormSchema(): array
    {
        return [
            SpatieMediaLibraryFileUpload::make('sponsors')
                ->required()
                ->multiple()
                ->image()
                ->enableReordering()
                ->enableOpen()
                ->hint(__('attributes.image_hint', ['formats' => 'jpeg, jpg, png', 'size' => '2MB']))
                ->placeholder(__('attributes.image_placeholder', ['attribute' => __('attributes.landing_page.sponsors')]))
                ->rules(['required', 'image', 'max:3072', 'mimes:jpeg,jpg,png'])
                ->label(__('attributes.landing_page.sponsors'))
                ->helperText(__('attributes.landing_page.upload_multiple_sponsors'))
                ->collection('landing_page_sponsors'),
        ];
    }

    private function getContactUsFormSchema(): array
    {
        return [
            Repeater::make('contents')
                ->relationship('contents')
                ->label(__('attributes.landing_page.content'))
                ->hint(fn($record) => __('attributes.landing_page.add_only_one_content_or_will_be_overwritten', ['attribute' => $record->key]))
                ->minItems(1)
                ->maxItems(1)
                ->schema([
                    TextInput::make('title')
                        ->label(__('attributes.landing_page.title'))
                        ->required()
                        ->placeholder(__('attributes.landing_page.title')),

                    TextInput::make('content')
                        ->label(__('attributes.landing_page.content'))
                        ->required()
                        ->placeholder(__('attributes.landing_page.content')),

                    TextInput::make('button_text')
                        ->label(__('attributes.landing_page.button_text'))
                        ->required()
                        ->placeholder(__('attributes.landing_page.button_text')),

                    SpatieMediaLibraryFileUpload::make("image")
                        ->required()
                        ->image()
                        ->enableOpen()
                        ->label(__('attributes.landing_page.section_image'))
                        ->hint(__('attributes.image_hint', ['formats' => 'jpeg, jpg, png', 'size' => '2MB']))
                        ->placeholder(__('attributes.image_placeholder', ['attribute' => __('attributes.landing_page.section_image')]))
                        ->helperText(__('attributes.landing_page.upload_only_one_image'))
                        ->rules(['image', 'max:2048', 'mimes:jpeg,jpg,png'])
                        ->collection(fn($record) => "landing_page_contact_us"),
                ]),
        ];
    }

    private function getFooterFormSchema(): array
    {
        return [
            Section::make('Main Section')
                ->label(__('attributes.landing_page.main_section'))
                ->relationship('footerMainSection')
                ->schema([
                    TextInput::make('title')
                        ->label(__('attributes.landing_page.title'))
                        ->placeholder(__('attributes.landing_page.title'))
                        ->required(),

                    TextInput::make('content')
                        ->label(__('attributes.landing_page.content'))
                        ->placeholder(__('attributes.landing_page.content'))
                        ->required(),
                ]),

            Section::make('Social Media')
                ->label(__('attributes.landing_page.social_media'))
                ->schema([
                    TextInput::make('footer_social_media_facebook')
                        ->label(__('attributes.facebook'))
                        ->placeholder(__('attributes.place', ['field' => __('attributes.facebook')])),

                    TextInput::make('footer_social_media_instagram')
                        ->label(__('attributes.instagram'))
                        ->placeholder(__('attributes.place', ['field' => __('attributes.instagram')])),

                    TextInput::make('footer_social_media_linkedin')
                        ->label(__('attributes.linkedin'))
                        ->placeholder(__('attributes.place', ['field' => __('attributes.linkedin')])),
                ]),

            Section::make('Contact Info')
                ->label(__('attributes.landing_page.contact_info'))
                ->schema([
                    TextInput::make('footer_contact_info_email')
                        ->label(__('attributes.email'))
                        ->email()
                        ->placeholder(__('attributes.place', ['field' => __('attributes.company.email')])),

                    TextInput::make('footer_contact_info_phone')
                        ->label(__('attributes.phone.title'))
                        ->placeholder(__('attributes.place', ['field' => __('attributes.company.phone')])),

                    TextInput::make('footer_contact_info_phone_2')
                        ->label(__('attributes.phone_2'))
                        ->placeholder(__('attributes.place', ['field' => __('attributes.company.phone_2')])),

                    TextInput::make('footer_contact_info_address')
                        ->label(__('attributes.address'))
                        ->placeholder(__('attributes.place', ['field' => __('attributes.company.address')])),
                ]),

            Section::make('Download Our App')
                ->label(__('attributes.landing_page.download_our_app'))
                ->schema([
                    TextInput::make('footer_download_our_app_content')
                        ->label(__('attributes.landing_page.content'))
                        ->placeholder(__('attributes.place', ['field' => __('attributes.landing_page.content')])),

                    TextInput::make('footer_download_our_app_app_store_link')
                        ->label(__('attributes.landing_page.app_store_link'))
                        ->placeholder(__('attributes.place', ['field' => __('attributes.landing_page.app_store_link')])),

                    TextInput::make('footer_download_our_app_google_play_link')
                        ->label(__('attributes.landing_page.google_play_link'))
                        ->placeholder(__('attributes.place', ['field' => __('attributes.landing_page.google_play_link')])),
                ]),

            Section::make('Useful Links')
                ->label(__('attributes.landing_page.useful_links'))
                ->schema([
                    Repeater::make('contents')
                        ->disableLabel()
                        ->maxItems(8)
                        ->relationship('contents', fn($query) => $query->where('name->' . app()->getLocale(), 'footer_useful_links'))
                        ->mutateRelationshipDataBeforeCreateUsing(function ($data) {
                            $data['name'] = 'footer_useful_links';
                            return $data;
                        })
                        ->schema([
                            TextInput::make('title')
                                ->label(__('attributes.landing_page.title'))
                                ->placeholder(__('attributes.place', ['field' => __('attributes.landing_page.title')])),

                            TextInput::make('content')
                                ->label(__('attributes.landing_page.link'))
                                ->required()
                                ->default('#')
                                ->placeholder(__('attributes.place', ['field' => __('attributes.landing_page.link')])),
                        ]),
                ]),
        ];
    }

    private function getNavbarFormSchema(): array
    {
        return [
            Repeater::make('navbars')
                ->label(__('attributes.landing_page.navbars'))
                ->relationship('navbars', fn($query) => $query->where('parent_id', null))
                ->schema([
                    TextInput::make('title')
                        ->label(__('attributes.landing_page.title'))
                        ->placeholder(__('attributes.landing_page.title'))
                        ->required()
                        ->reactive()
                        ->afterStateUpdated(fn($set, $state) => $set('url', "#" . Str::slug($state))),

                    TextInput::make('url')
                        ->label(__('attributes.landing_page.url'))
                        ->placeholder(__('attributes.landing_page.url'))
                        ->required()
                        ->default("#" . Str::slug($this->record->title)),

                    TextInput::make('order')
                        ->label(__('attributes.landing_page.order'))
                        ->placeholder(__('attributes.landing_page.order'))
                        ->required(),

                    Toggle::make('is_active')
                        ->label(__('attributes.landing_page.active'))
                        ->default(true),

                    Repeater::make('subNavbars')
                        ->label(__('attributes.landing_page.links_in_dropdown'))
                        ->mutateRelationshipDataBeforeSaveUsing(function ($data) {
                            $data['landing_page_key_id'] = $this->record->id;
                            return $data;
                        })
                        ->mutateRelationshipDataBeforeCreateUsing(function ($data) {
                            $data['landing_page_key_id'] = $this->record->id;
                            return $data;
                        })
                        ->relationship('subNavbars', fn($query) => $query->whereNotNull('parent_id'))
                        ->schema([
                            TextInput::make('title')
                                ->label(__('attributes.landing_page.title'))
                                ->placeholder(__('attributes.landing_page.title'))
                                ->required()
                                ->reactive()
                                ->afterStateUpdated(fn($set, $state) => $set('url', Str::slug($state))),

                            TextInput::make('url')
                                ->label(__('attributes.landing_page.url'))
                                ->placeholder(__('attributes.landing_page.url'))
                                ->required()
                                ->default("#" . Str::slug($this->record->title)),

                            TextInput::make('order')
                                ->label(__('attributes.landing_page.order'))
                                ->placeholder(__('attributes.landing_page.order'))
                                ->required(),

                            Toggle::make('is_active')
                                ->label(__('attributes.landing_page.active'))
                                ->default(true),
                        ]),
                ]),
        ];
    }

    protected function mutateFormDataBeforeSave(array $data): array
    {
        foreach ($data as $key => $value) {
            if (in_array($key, [null, '', ' ', [], 'key'], true)) {
                unset($data[$key]);
            }
        }

        foreach ($data as $key => $value) {
            $this->record->contents()->where('name->' . app()->getLocale(), $key)->first()
                ? $this->record->contents()->where('name->' . app()->getLocale(), $key)->first()->update(['content' => $value])
                : $this->record->contents()->create(['name' => $key, 'content' => $value]);
        }

        return $data;
    }

    protected function getActions(): array
    {
        return [
            Actions\LocaleSwitcher::make(),
        ];
    }
}
