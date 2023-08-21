<?php

namespace App\Agent\Resources\Tourguide\TourguideResource\Pages;

use App\Agent\Resources\Tourguide\TourguideResource;
use App\Models\City;
use App\Models\Country;
use App\Models\Event;
use App\Models\Language;
use App\Models\Skill;
use App\Models\Tourguide;
use App\Models\User;
use Carbon\Carbon;
use Filament\Forms\Components\Checkbox;
use Filament\Forms\Components\CheckboxList;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Placeholder;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Notifications\Notification;
use Filament\Pages\Actions;
use Filament\Pages\Actions\LocaleSwitcher;
use Filament\Resources\Pages\ListRecords;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\HtmlString;

class ListTourguides extends ListRecords
{
    use ListRecords\Concerns\Translatable;

    protected static string $resource = TourguideResource::class;
    protected static string|array $middlewares = 'permission:List Tourguides';
    protected static string $view = 'filament.pages.agent.filter-tourguides';

    public $perPage;
    public $search;
    public $is_online;
    public $added_new;
    public $recommended;
    public $years_of_experience;
    public $languages;
    public $skills;
    public $gender;
    public $age;
    public $rates;
    public $location;
//    public $education;
    public $start_at;
    public $end_at;
    public $event_id;
    public $type;
    public $chat_type;

    protected function getActions(): array
    {
        return [
            LocaleSwitcher::make(),
            // List View Action
            Actions\Action::make('list')
                ->label(__('attributes.list_view'))
                ->icon('heroicon-o-view-list')
                ->hidden(fn() => session()->get('tourguides_view') === 'list')
                ->action(fn() => session()->put('tourguides_view', 'list')),

            // Grid View Action
            Actions\Action::make('grid')
                ->label(__('attributes.grid_view'))
                ->icon('heroicon-o-view-grid')
                ->hidden(fn() => in_array(session()->get('tourguides_view'), ['grid', null], true))
                ->action(fn() => session()->put('tourguides_view', 'grid')),
        ];
    }

    public function mount(): void
    {
        parent::mount();
        if (request()->has('where')) {
            $this->location = request()->where;
        }

        if (request()->has('from')) {
            $this->start_at = request()->from;
        }

        if (request()->has('to')) {
            $this->end_at = request()->to;
        }

        $this->form->fill([
//            'have_a_photo' => $this->have_a_photo,
            'is_online' => $this->is_online,
            'years_of_experience' => $this->years_of_experience,
            'languages' => $this->languages,
            'skills' => $this->skills,
            'gender' => $this->gender,
            'age' => $this->age,
            'rates' => $this->rates,
            'location' => $this->location,
//            'education' => $this->education,
            'start_at' => $this->start_at,
            'end_at' => $this->end_at,
            'type' => $this->type,
        ]);
    }

    protected function getFormSchema(): array
    {
        return [

            TextInput::make('search')
                ->placeholder(__('attributes.search', ['field' => __('attributes.tourguide')]))
                ->label(__('attributes.search', ['field' => __('attributes.tourguide')])),

            Checkbox::make('is_online')->label(__('attributes.online')),

            Checkbox::make('added_new')->label(__('attributes.added_new')),

            Checkbox::make('recommended')->label(__('attributes.recommended_by', ['field' => __('attributes.guides_navigator')])),

            CheckboxList::make('years_of_experience')
                ->label(__('attributes.years_of_experience'))
                ->columns(3)
                ->options([
                    '3' => __('attributes.years', ['field' => '+3']),
                    '7' => __('attributes.years', ['field' => '+7']),
                    '10' => __('attributes.years', ['field' => '+10']),
                ]),

            CheckboxList::make('languages')
                ->label(__('attributes.languages'))
                ->columns(3)
                ->options(Language::all()->pluck('name', 'id')),

            CheckboxList::make('gender')
                ->label(__('attributes.gender'))
                ->columns(2)
                ->options([
                    'male' => __('attributes.male'),
                    'female' => __('attributes.female'),
                ]),

            CheckboxList::make('age')
                ->label(__('attributes.age'))
                ->columns(4)
                ->options([
                    '22-30' => '22-30',
                    '30-40' => '30-40',
                    '+40' => '+40',
                ]),

//            Grid::make([
//                'default' => 2,
//                'lg' => 2,
//                'md' => 2,
//                'sm' => 1,
//            ])
//                ->schema([
//                    CheckboxList::make('rates')
//                        ->options([
//                            '1' => '1 Star',
//                            '2' => '2 Stars',
//                            '3' => '3 Stars',
//                            '4' => '4 Stars',
//                            '5' => '5 Stars',
//                        ]),
//                    Placeholder::make('rates')
//                        ->disableLabel()
//                        ->content((new HtmlString('
//                            <div class="flex flex-col space-y-2 mt-6">
//                                <span class="flex items-center">
//                                    <svg aria-hidden="true" class="w-5 h-5 text-yellow-400" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><title>First star</title><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path></svg>
//                                    <svg aria-hidden="true" class="w-5 h-5 text-gray-300" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><title>Second star</title><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path></svg>
//                                    <svg aria-hidden="true" class="w-5 h-5 text-gray-300" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><title>Third star</title><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path></svg>
//                                    <svg aria-hidden="true" class="w-5 h-5 text-gray-300" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><title>Fourth star</title><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path></svg>
//                                    <svg aria-hidden="true" class="w-5 h-5 text-gray-300 dark:text-gray-500" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><title>Fifth star</title><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path></svg>
//                                </span>
//                                <span class="flex items-center">
//                                    <svg aria-hidden="true" class="w-5 h-5 text-yellow-400" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><title>First star</title><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path></svg>
//                                    <svg aria-hidden="true" class="w-5 h-5 text-yellow-400" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><title>Second star</title><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path></svg>
//                                    <svg aria-hidden="true" class="w-5 h-5 text-gray-300" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><title>Third star</title><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path></svg>
//                                    <svg aria-hidden="true" class="w-5 h-5 text-gray-300" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><title>Fourth star</title><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path></svg>
//                                    <svg aria-hidden="true" class="w-5 h-5 text-gray-300 dark:text-gray-500" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><title>Fifth star</title><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path></svg>
//                                </span>
//                                <span class="flex items-center">
//                                    <svg aria-hidden="true" class="w-5 h-5 text-yellow-400" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><title>First star</title><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path></svg>
//                                    <svg aria-hidden="true" class="w-5 h-5 text-yellow-400" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><title>Second star</title><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path></svg>
//                                    <svg aria-hidden="true" class="w-5 h-5 text-yellow-400" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><title>Third star</title><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path></svg>
//                                    <svg aria-hidden="true" class="w-5 h-5 text-gray-300" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><title>Fourth star</title><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path></svg>
//                                    <svg aria-hidden="true" class="w-5 h-5 text-gray-300 dark:text-gray-500" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><title>Fifth star</title><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path></svg>
//                                </span>
//                                <span class="flex items-center">
//                                    <svg aria-hidden="true" class="w-5 h-5 text-yellow-400" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><title>First star</title><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path></svg>
//                                    <svg aria-hidden="true" class="w-5 h-5 text-yellow-400" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><title>Second star</title><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path></svg>
//                                    <svg aria-hidden="true" class="w-5 h-5 text-yellow-400" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><title>Third star</title><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path></svg>
//                                    <svg aria-hidden="true" class="w-5 h-5 text-yellow-400" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><title>Fourth star</title><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path></svg>
//                                    <svg aria-hidden="true" class="w-5 h-5 text-gray-300 dark:text-gray-500" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><title>Fifth star</title><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path></svg>
//                                </span>
//                                <span class="flex items-center">
//                                    <svg aria-hidden="true" class="w-5 h-5 text-yellow-400" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><title>First star</title><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path></svg>
//                                    <svg aria-hidden="true" class="w-5 h-5 text-yellow-400" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><title>Second star</title><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path></svg>
//                                    <svg aria-hidden="true" class="w-5 h-5 text-yellow-400" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><title>Third star</title><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path></svg>
//                                    <svg aria-hidden="true" class="w-5 h-5 text-yellow-400" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><title>Fourth star</title><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path></svg>
//                                    <svg aria-hidden="true" class="w-5 h-5 text-yellow-400 dark:text-gray-500" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><title>Fifth star</title><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path></svg>
//                                </span>
//                            </div>'))),
//                ]),

            CheckboxList::make('skills')
                ->label(__('attributes.skills'))
                ->columns(2)
                ->options(Skill::all()->pluck('name', 'id')),

            Select::make('location')
                ->searchable()
                ->label(__('attributes.location'))
                ->placeholder(__('attributes.select', ['field' => __('attributes.tourguide_location')]))
                ->options(City::active()->get()->pluck('name', 'id')),

//            Select::make('education')
//                ->searchable()
//                ->label('Education')
//                ->placeholder('Select education level')
//                ->options([
//                    'high_school' => 'High School',
//                    'bachelor' => 'Bachelor',
//                    'master' => 'Master',
//                    'phd' => 'PhD',
//                ]),

            Placeholder::make('event_types')
                ->inlineLabel()
                ->label(__('attributes.event_types')),

            Select::make('type')
                ->label(__('attributes.booking_days_type'))
                ->placeholder(__('attributes.select', ['field' => __('attributes.booking_days_type')]))
                ->options([
                    'half_day' => __('attributes.half_day'),
                    'full_day' => __('attributes.full_day'),
                    'multi_day' => __('attributes.multi_days'),
                ]),

            Placeholder::make('Booking Date')
                ->inlineLabel()
                ->label(__('attributes.booking_date')),

            DatePicker::make('start_at')
                ->label(__('attributes.from'))
                ->format('Y-m-d')
                ->reactive()
                ->placeholder(__('attributes.select', ['field' => __('attributes.from')])),

            DatePicker::make('end_at')
                ->label(__('attributes.to'))
                ->minDate(fn(callable $get) => Carbon::parse($get('start_at'))->addDay()->format('Y-m-d'))
                ->placeholder(__('attributes.select', ['field' => __('attributes.to')])),
        ];
    }

    public function filterTourguides()
    {
        return Tourguide::query()
//            ->when($this->have_a_photo, fn($query) => $query->whereHas('media', fn($query) => $query->where('collection_name', 'tourguide_avatar')))
            ->when($this->search, fn($query) => $query->where(DB::raw("CONCAT(first_name, ' ', last_name)"), 'like', "%$this->search%"))
            ->when($this->is_online, fn($query) => $query->where('is_online', true))
            ->when($this->added_new, fn($query) => $query->whereDate('created_at', '>=', now()->subDays(7)))
            ->when($this->recommended, fn($query) => $query->whereHas('favourites', function ($query) {
                $query->where([['favouritable_type', Tourguide::class], ['favouriter_type', User::class]]);
            }))
            ->when($this->years_of_experience, fn($query) => $query->whereHas('work_experiences', function ($query) {
                $query->when($this->years_of_experience === 3, fn($query) => $query->where('years_of_experience', '>=', 3))
                    ->when($this->years_of_experience === 7, fn($query) => $query->where('years_of_experience', '>=', 7))
                    ->when($this->years_of_experience === 10, fn($query) => $query->where('years_of_experience', '>=', 10));
            }))
            ->when($this->age, function ($query) {
                $query->when(in_array('18-25', $this->age, true), fn($query) => $query->whereBetween('age', [18, 25]))
                    ->when(in_array('26-35', $this->age, true), fn($query) => $query->whereBetween('age', [26, 35]))
                    ->when(in_array('36-45', $this->age, true), fn($query) => $query->whereBetween('age', [36, 45]))
                    ->when(in_array('+45', $this->age, true), fn($query) => $query->where('age', '>=', 45));
            })
            ->when($this->rates, function ($query) {
                $query->when(in_array('1', $this->rates, true), fn($query) => $query->whereBetween('rates', [1, 2]))
                    ->when(in_array('2', $this->rates, true), fn($query) => $query->whereBetween('rates', [2, 3]))
                    ->when(in_array('3', $this->rates, true), fn($query) => $query->whereBetween('rates', [3, 4]))
                    ->when(in_array('4', $this->rates, true), fn($query) => $query->whereBetween('rates', [4, 5]))
                    ->when(in_array('5', $this->rates, true), fn($query) => $query->where('rates', '>=', 5));
            })
            ->when($this->skills, fn($query) => $query->whereHas('skills', fn($query) => $query->whereIn('name', $this->skills)))
            ->when($this->languages, fn($query) => $query->whereHas('languages', fn($query) => $query->whereIn('name', $this->languages)))
//            ->when($this->education, fn($query) => $query->where('education', $this->education))
            ->when($this->location, fn($query) => $query->whereHas('city', fn($query) => $query->where('id', $this->location) || $query->where('name', 'like', "%$this->location%")))
            ->when($this->gender, fn($query) => $query->where('gender', $this->gender))
            ->when($this->start_at && $this->end_at, fn($query) => $query->whereHas('appointments', fn($query) => $query->whereDate('start_at', '>=', $this->start_at)->whereDate('start_at', '<=', $this->end_at)))
            ->when($this->start_at && !$this->end_at, fn($query) => $query->whereHas('appointments', fn($query) => $query->whereDate('start_at', '>=', $this->start_at)))
            ->when(!$this->start_at && $this->end_at, fn($query) => $query->whereHas('appointments', fn($query) => $query->whereDate('end_at', '<=', $this->end_at)))
            ->when($this->type, function ($query) {
                $query->when($this->type === 'half_day', fn($query) => $query->whereHas('settings', fn($query) => $query->where([['key', 'assign_half_day_events'], ['value', true]])))
                    ->when($this->type === 'full_day', fn($query) => $query->whereHas('settings', fn($query) => $query->where([['key', 'assign_full_day_events'], ['value', true]])))
                    ->when($this->type === 'multi_day', fn($query) => $query->whereHas('settings', fn($query) => $query->where([['key', 'assign_multi_day_events'], ['value', true]])));
            })
            ->when($this->perPage, fn($query) => $this->perPage !== "all" ? $query->paginate($this->perPage) : $query->get(),
                fn($query) => $query->paginate(12));
    }

    public function toggleFavorites(Tourguide $tourguide)
    {
//        return redirect()->route('agent.resources.tourguides.view', $tourguide->id);
        if (auth('agent')->user()->favourites()
            ->where('favouritable_id', $tourguide->id)
            ->where('favouritable_type', Tourguide::class)
            ->exists()) {
            auth('agent')->user()->favourites()->where('favouritable_id', $tourguide->id)
                ->where('favouritable_type', Tourguide::class)->delete();

            Notification::make()->success()
                ->title(__('attributes.tourguide_removed_from_favourites', ['tourguide' => $tourguide->full_name]))
                ->send();
        } else {
            auth('agent')->user()->favourites()->create([
                'favouritable_id' => $tourguide->id,
                'favouritable_type' => Tourguide::class,
            ]);
            Notification::make()->success()
                ->title(__('attributes.tourguide_added_to_favourites', ['tourguide' => $tourguide->full_name]))
                ->send();
        }
    }

    protected function getViewData(): array
    {
        return [
            'events' => Event::all(),
            'tourguides' => $this->filterTourguides(),
            'route' => 'agent.resources.tourguides.view',
            'view' => session()->get('tourguides_view') ?? 'grid',
        ];
    }

    public function chat($tourguide_id)
    {
        return $this->event_id && $this->chat_type === 'event'
            ? $this->redirectRoute('agent.pages.chat', ['tourguide_id' => $tourguide_id, 'event_id' => $this->event_id])
            : $this->redirectRoute('agent.pages.chat', ['tourguide_id' => $tourguide_id]);
    }
}
