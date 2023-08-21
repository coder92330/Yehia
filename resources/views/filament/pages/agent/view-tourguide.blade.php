<x-filament::page>
    <section class="bg-white">
        <div class="bg-gray-50">
            <div class="mx-auto py-10 px-4 sm:py-6 sm:px-6 lg:px-8">
                <div class="grid grid-cols-1 lg:grid-cols-4 gap-4 md:gap-10 items-center">
                    <div class="col-span-1 flex flex-col items-center justify-center">
                        <img src="{{ $record->avatar }}" alt="Your Image"
                             class="relative lg:-bottom-14 lg:w-80 lg:h-80 md:w-64 md:h-64 h-64 w-64 rounded-full border-4 border-white shadow-lg">
                    </div>
                    <div class="flex flex-col md:flex-row items-center col-span-3">
                        <div class="flex flex-col space-y-3 w-full">
                            <div class="flex flex-row justify-between">
                                <h1 class="text-2xl md:text-3xl font-bold text-gray-900 dark:text-gray-100">{{ $record->full_name }}</h1>
                                @if($record->hasSetting('my_profile_added_to_favorites', true)->exists())
                                    <div class="flex flex-col items-center">
                                        <x-filament::button class="w-10 h-10"
                                                            style="background-color: gray; color: white; border-radius: 99999px"
                                                            wire:click="toggleFavorite">
                                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"
                                                 fill="currentColor"
                                                 class="w-5 h-5 inline
                                                 @if(auth('agent')->user()->favourites()->where([
                                                        'favouritable_id'   => $record->id,
                                                        'favouritable_type' => \App\Models\Tourguide::class
                                                    ])->exists()) text-danger-500 @else text-white @endif">
                                                <path
                                                    d="M9.653 16.915l-.005-.003-.019-.01a20.759 20.759 0 01-1.162-.682 22.045 22.045 0 01-2.582-1.9C4.045 12.733 2 10.352 2 7.5a4.5 4.5 0 018-2.828A4.5 4.5 0 0118 7.5c0 2.852-2.044 5.233-3.885 6.82a22.049 22.049 0 01-3.744 2.582l-.019.01-.005.003h-.002a.739.739 0 01-.69.001l-.002-.001z"/>
                                            </svg>
                                        </x-filament::button>
                                        <small class="text-gray-500 dark:text-gray-400 mt-2">{{ auth('agent')->user()->favourites()->where([
                                                        'favouritable_id'   => $record->id,
                                                        'favouritable_type' => \App\Models\Tourguide::class
                                                    ])->exists() ? __('frontend.agent_dashboard.remove_from_favorites') : __('frontend.agent_dashboard.add_to_favorites') }}</small>
                                    </div>
                                @endif
                            </div>
                            <p class="text-gray-500 dark:text-gray-400">{{ $record->rating }}</p>
                            <div class="flex flex-row space-x-5">
                                <p class="text-gray-500 dark:text-gray-400">
                                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor"
                                         class="w-5 h-5 inline">
                                        <path fill-rule="evenodd"
                                              d="M9.69 18.933l.003.001C9.89 19.02 10 19 10 19s.11.02.308-.066l.002-.001.006-.003.018-.008a5.741 5.741 0 00.281-.14c.186-.096.446-.24.757-.433.62-.384 1.445-.966 2.274-1.765C15.302 14.988 17 12.493 17 9A7 7 0 103 9c0 3.492 1.698 5.988 3.355 7.584a13.731 13.731 0 002.273 1.765 11.842 11.842 0 00.976.544l.062.029.018.008.006.003zM10 11.25a2.25 2.25 0 100-4.5 2.25 2.25 0 000 4.5z"
                                              clip-rule="evenodd"/>
                                    </svg>
                                    {{ __('frontend.agent_dashboard.from') }}
                                </p>
                                <p class="font-semibold text-gray-500 dark:text-gray-400">{{ $record->country?->name }}</p>
                            </div>
                            <div class="flex flex-row space-x-5">
                                <p class="text-gray-500 dark:text-gray-400">
                                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor"
                                         class="w-5 h-5 inline">
                                        <path
                                            d="M7.75 2.75a.75.75 0 00-1.5 0v1.258a32.987 32.987 0 00-3.599.278.75.75 0 10.198 1.487A31.545 31.545 0 018.7 5.545 19.381 19.381 0 017 9.56a19.418 19.418 0 01-1.002-2.05.75.75 0 00-1.384.577 20.935 20.935 0 001.492 2.91 19.613 19.613 0 01-3.828 4.154.75.75 0 10.945 1.164A21.116 21.116 0 007 12.331c.095.132.192.262.29.391a.75.75 0 001.194-.91c-.204-.266-.4-.538-.59-.815a20.888 20.888 0 002.333-5.332c.31.031.618.068.924.108a.75.75 0 00.198-1.487 32.832 32.832 0 00-3.599-.278V2.75z"/>
                                        <path fill-rule="evenodd"
                                              d="M13 8a.75.75 0 01.671.415l4.25 8.5a.75.75 0 11-1.342.67L15.787 16h-5.573l-.793 1.585a.75.75 0 11-1.342-.67l4.25-8.5A.75.75 0 0113 8zm2.037 6.5L13 10.427 10.964 14.5h4.073z"
                                              clip-rule="evenodd"/>
                                    </svg>
                                    {{ __('frontend.agent_dashboard.languages') }}
                                </p>
                                <p class="font-semibold text-gray-500 dark:text-gray-400">{{ implode(', ', $record->languages->pluck('name')->toArray()) }}</p>
                            </div>
                            <div class="flex flex-row space-x-5">
                                <p class="text-gray-500 dark:text-gray-400">
                                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor"
                                         class="w-5 h-5 inline">
                                        <path fill-rule="evenodd"
                                              d="M10 2c-2.236 0-4.43.18-6.57.524C1.993 2.755 1 4.014 1 5.426v5.148c0 1.413.993 2.67 2.43 2.902.848.137 1.705.248 2.57.331v3.443a.75.75 0 001.28.53l3.58-3.579a.78.78 0 01.527-.224 41.202 41.202 0 005.183-.5c1.437-.232 2.43-1.49 2.43-2.903V5.426c0-1.413-.993-2.67-2.43-2.902A41.289 41.289 0 0010 2zm0 7a1 1 0 100-2 1 1 0 000 2zM8 8a1 1 0 11-2 0 1 1 0 012 0zm5 1a1 1 0 100-2 1 1 0 000 2z"
                                              clip-rule="evenodd"/>
                                    </svg>
                                    {{ __('frontend.agent_dashboard.last_active') }}
                                </p>
                                <p class="font-semibold text-gray-500 dark:text-gray-400">{{ $record->last_active }}</p>
                            </div>
                            <div class="flex lg:flex-row flex-col lg:justify-between lg:space-y-0 space-y-4 pt-6">
                                <div class="flex flex-row space-x-2">
                                    <button title="New Chat" data-modal-target="chat-modal"
                                            data-modal-toggle="chat-modal"
                                            class="border border-gray-500 bg-transparent rounded-full px-3 py-1 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-300 hover:bg-gray-200 dark:hover:bg-gray-700">
                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none"
                                             viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"
                                             class="w-4 h-4 inline">
                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                  d="M7.5 8.25h9m-9 3H12m-9.75 1.51c0 1.6 1.123 2.994 2.707 3.227 1.129.166 2.27.293 3.423.379.35.026.67.21.865.501L12 21l2.755-4.133a1.14 1.14 0 01.865-.501 48.172 48.172 0 003.423-.379c1.584-.233 2.707-1.626 2.707-3.228V6.741c0-1.602-1.123-2.995-2.707-3.228A48.394 48.394 0 0012 3c-2.392 0-4.744.175-7.043.513C3.373 3.746 2.25 5.14 2.25 6.741v6.018z"/>
                                        </svg>
                                        {{ __('frontend.agent_dashboard.send_message') }}
                                    </button>
                                    <div id="chat-modal" tabindex="-1"
                                         class="fixed top-0 left-0 right-0 z-50 hidden p-4 overflow-x-hidden overflow-y-auto md:inset-0 h-[calc(100%-1rem)] max-h-full">
                                        <div class="relative w-full max-w-md max-h-full">
                                            <div
                                                class="relative bg-white rounded-lg shadow dark:bg-gray-700">
                                                <button type="button"
                                                        class="absolute top-3 right-2.5 text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm p-1.5 ml-auto inline-flex items-center dark:hover:bg-gray-800 dark:hover:text-white"
                                                        data-modal-hide="chat-modal">
                                                    <svg aria-hidden="true" class="w-5 h-5"
                                                         fill="currentColor" viewBox="0 0 20 20"
                                                         xmlns="http://www.w3.org/2000/svg">
                                                        <path fill-rule="evenodd"
                                                              d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z"
                                                              clip-rule="evenodd"></path>
                                                    </svg>
                                                    <span
                                                        class="sr-only">{{ __('frontend.agent_dashboard.close') }}</span>
                                                </button>
                                                <div class="p-6 flex flex-col space-y-4">
                                                    <x-filament::form
                                                        wire:submit.prevent="chat({{ $record->id }})">
                                                        <label for="events"
                                                               class="block text-sm font-medium text-gray-900 dark:text-white">Select
                                                            Chat Type</label>
                                                        <select id="type" name="type"
                                                                style="margin: 10px 0" required
                                                                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
                                                            <option
                                                                value="">{{ __('frontend.agent_dashboard.select_chat_type') }}</option>
                                                            <option
                                                                value="tourguide">{{ __('frontend.agent_dashboard.private_chat_with_tourguide') }}</option>
                                                            </option>
                                                            <option
                                                                value="event">{{ __('frontend.agent_dashboard.to_book_event') }}</option>
                                                        </select>

                                                        <label for="events"
                                                               class="text-sm font-medium text-gray-900 dark:text-white hidden">
                                                            {{ __('frontend.agent_dashboard.select_chat_event') }}</label>
                                                        <select id="events" name="events"
                                                                wire:model.defer="event_id"
                                                                style="margin-top: 5px" required
                                                                class="hidden bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
                                                            <option
                                                                value="">{{ __('frontend.agent_dashboard.select_event') }}</option>
                                                            @foreach($events as $event)
                                                                <option wire:key="event-{{ $event->id }}"
                                                                        value="{{ $event->id }}">{{ $event->name }}</option>
                                                            @endforeach
                                                        </select>
                                                        <div class="flex flex-row justify-center">
                                                            <button
                                                                data-modal-hide="chat-modal"
                                                                type="submit"
                                                                class="text-white bg-success-600 hover:bg-success-800 focus:ring-4 focus:outline-none focus:ring-success-300 dark:focus:ring-success-800 font-medium rounded-lg text-sm inline-flex items-center px-5 py-2.5 text-center mr-2">
                                                                {{ __('frontend.agent_dashboard.chat_with', ['name' => $record->full_name]) }}
                                                            </button>
                                                            <button
                                                                data-modal-hide="chat-modal"
                                                                type="button"
                                                                class="text-gray-500 bg-white hover:bg-gray-100 focus:ring-4 focus:outline-none focus:ring-gray-200 rounded-lg border border-gray-200 text-sm font-medium px-5 py-2.5 hover:text-gray-900 focus:z-10 dark:bg-gray-700 dark:text-gray-300 dark:border-gray-500 dark:hover:text-white dark:hover:bg-gray-600 dark:focus:ring-gray-600">
                                                                {{ __('frontend.agent_dashboard.no_cancel') }}
                                                            </button>
                                                        </div>
                                                    </x-filament::form>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    @if($record->hasSetting('get_a_booking',true)->exists())
                                        <a href="{{ route('agent.pages.new-event', $record->id) }}"
                                           class="rounded-full text-white bg-success-500 hover:bg-success-600 px-5 py-2">
                                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                                 stroke-width="1.5" stroke="currentColor" class="w-5 h-5 inline">
                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                      d="M14.857 17.082a23.848 23.848 0 005.454-1.31A8.967 8.967 0 0118 9.75v-.7V9A6 6 0 006 9v.75a8.967 8.967 0 01-2.312 6.022c1.733.64 3.56 1.085 5.455 1.31m5.714 0a24.255 24.255 0 01-5.714 0m5.714 0a3 3 0 11-5.714 0M3.124 7.5A8.969 8.969 0 015.292 3m13.416 0a8.969 8.969 0 012.168 4.5"/>
                                            </svg>
                                            {{ __('frontend.agent_dashboard.book_now') }}
                                        </a>
                                    @endif
                                </div>
                                <div class="flex flex-row">
                                    <p class="font-semibold text-gray-500 dark:text-gray-400">{{ __('frontend.agent_dashboard.status') }}
                                        :</p>
                                    <p class="ml-2 text-gray-500 dark:text-gray-400">{{ $record->is_online ? 'online' : 'offline' }}</p>
                                    <svg width="20" height="20" @class([
                                        'text-success-500' => $record->is_online,
                                        'text-danger-500' => !$record->is_online])>
                                        <circle cx="15" cy="12" r="5" fill="currentColor"></circle>
                                    </svg>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <section class="grid grid-cols-1 lg:grid-cols-4 gap-4 md:gap-10 items-center">
            <div class="col-span-1 h-full">
                {{-- Work Experience --}}
                <div class="lg:mt-16 p-4 flex flex-col space-y-4">
                    <div class="flex flex-col space-y-2">
                        <h3 class="text-lg font-medium text-gray-400 border-b border-gray-200">Work Experience:</h3>
                        <div class="flex flex-col space-y-2">
                            @foreach ($record->work_experiences as $workExperience)
                                <div class="flex flex-col space-y-2">
                                    <div class="flex flex-row space-x-2">
                                        <p class="font-semibold text-gray-500 dark:text-gray-400">{{ $workExperience->title }}</p>
                                        <p class="text-gray-500 dark:text-gray-400">({{ $workExperience->start_date }}
                                            - {{ $workExperience->end_date }})</p>
                                    </div>
                                    <p class="text-gray-500 dark:text-gray-400">{{ $workExperience->description }}</p>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>

                {{-- Skills --}}
                <div class="mt-8 p-4 flex flex-col space-y-4">
                    <div class="flex flex-col space-y-2">
                        <h3 class="text-lg font-medium text-gray-400 border-b border-gray-200">Skills:</h3>
                        <div class="flex flex-col space-y-2">
                            @foreach ($record->skills as $skill)
                                <div class="flex flex-row space-x-2">
                                    <p class="font-semibold text-gray-500 dark:text-gray-400">{{ $skill->name }}</p>
                                    <p class="text-gray-500 dark:text-gray-400">({{ $skill->pivot->level }})</p>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-span-3">
                <div class="mb-4 border-b border-gray-200 dark:border-gray-700">
                    <ul class="flex flex-wrap -mb-px text-sm font-medium text-center" id="myTab"
                        data-tabs-toggle="#myTabContent" role="tablist">
                        <li class="mr-2" role="presentation">
                            <button class="inline-block p-4 border-b-2 rounded-t-lg text-primary-500 border-primary-500"
                                    id="describtion-tab" data-tabs-target="#about" type="button" role="tab"
                                    aria-controls="about"
                                    aria-selected="false">{{ __('frontend.agent_dashboard.about') }}
                            </button>
                        </li>
                    </ul>
                </div>
                <div id="myTabContent">
                    <div class="p-4 rounded-lg" id="about" role="tabpanel" aria-labelledby="about-tab">
                        <section class="mb-5">
                            <h3 class="text-lg font-medium text-gray-400 border-b border-gray-200">{{ __('frontend.agent_dashboard.contact_info') }}
                                :</h3>
                            <div class="mt-4">
                                <div class="mt-3 space-x-3">
                                    <label for="phone"
                                           class="inline text-sm font-medium text-gray-700">{{ __('frontend.agent_dashboard.phone') }}
                                        :</label>
                                    <p class="inline mt-1 text-sm text-blue-400">{{ $record->phone }}</p>
                                </div>
                                <div class="mt-3 space-x-5">
                                    <label for="email"
                                           class="inline text-sm font-medium text-gray-700">{{ __('frontend.agent_dashboard.email') }}
                                        :</label>
                                    <p class="inline mt-1 text-sm text-sky-400">{{ $record->email }}</p>
                                </div>
                                <div class="mt-3 space-x-5">
                                    <label for="address"
                                           class="inline text-sm font-medium text-gray-700">{{ __('frontend.agent_dashboard.address') }}
                                        :</label>
                                    <p class="inline mt-1 text-sm text-sky-400">{{ $record->address }}</p>
                                </div>

                                @if($record->facebook || $record->twitter || $record->linkedin || $record->instagram)
                                    <div class="mt-3 space-x-5">
                                        <label for="social"
                                               class="inline text-sm font-medium text-gray-700">{{ __('frontend.agent_dashboard.social') }}
                                            :</label>
                                        <div class="inline text-sm space-x-2">

                                            @if($record->facebook)
                                                <a href="{{ $record->facebook }}"
                                                   class="inline-block text-gray-400 hover:text-gray-500">
                                                    <svg xmlns="http://www.w3.org/2000/svg" data-name="Layer 1"
                                                         viewBox="0 0 24 24" id="facebook" class="w-4 h-4 inline">
                                                        <path
                                                            d="M15.12,5.32H17V2.14A26.11,26.11,0,0,0,14.26,2C11.54,2,9.68,3.66,9.68,6.7V9.32H6.61v3.56H9.68V22h3.68V12.88h3.06l.46-3.56H13.36V7.05C13.36,6,13.64,5.32,15.12,5.32Z"></path>
                                                    </svg>
                                                </a>
                                            @endif

                                            @if($record->twitter)
                                                <a href="{{ $record->twitter }}"
                                                   class="inline-block text-gray-400 hover:text-gray-500">
                                                    <svg xmlns="http://www.w3.org/2000/svg" data-name="Layer 1"
                                                         viewBox="0 0 24 24"
                                                         id="twitter" class="w-4 h-4 inline">
                                                        <path
                                                            d="M22,5.8a8.49,8.49,0,0,1-2.36.64,4.13,4.13,0,0,0,1.81-2.27,8.21,8.21,0,0,1-2.61,1,4.1,4.1,0,0,0-7,3.74A11.64,11.64,0,0,1,3.39,4.62a4.16,4.16,0,0,0-.55,2.07A4.09,4.09,0,0,0,4.66,10.1,4.05,4.05,0,0,1,2.8,9.59v.05a4.1,4.1,0,0,0,3.3,4A3.93,3.93,0,0,1,5,13.81a4.9,4.9,0,0,1-.77-.07,4.11,4.11,0,0,0,3.83,2.84A8.22,8.22,0,0,1,3,18.34a7.93,7.93,0,0,1-1-.06,11.57,11.57,0,0,0,6.29,1.85A11.59,11.59,0,0,0,20,8.45c0-.17,0-.35,0-.53A8.43,8.43,0,0,0,22,5.8Z"></path>
                                                    </svg>
                                                </a>
                                            @endif

                                            @if($record->linkedin)
                                                <a href="{{ $record->linkedin }}"
                                                   class="inline-block text-gray-400 hover:text-gray-500">
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="2500" height="2389"
                                                         viewBox="0 5 1036 990" id="linkedin" class="w-4 h-4 inline">
                                                        <path
                                                            d="M0 120c0-33.334 11.667-60.834 35-82.5C58.333 15.833 88.667 5 126 5c36.667 0 66.333 10.666 89 32 23.333 22 35 50.666 35 86 0 32-11.333 58.666-34 80-23.333 22-54 33-92 33h-1c-36.667 0-66.333-11-89-33S0 153.333 0 120zm13 875V327h222v668H13zm345 0h222V622c0-23.334 2.667-41.334 8-54 9.333-22.667 23.5-41.834 42.5-57.5 19-15.667 42.833-23.5 71.5-23.5 74.667 0 112 50.333 112 151v357h222V612c0-98.667-23.333-173.5-70-224.5S857.667 311 781 311c-86 0-153 37-201 111v2h-1l1-2v-95H358c1.333 21.333 2 87.666 2 199 0 111.333-.667 267.666-2 469z"></path>
                                                    </svg>
                                                </a>
                                            @endif

                                            @if($record->instagram)
                                                <a href="{{ $record->instagram }}"
                                                   class="inline-block text-gray-400 hover:text-gray-500">
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="2500" height="2500"
                                                         viewBox="0 0 2476 2476" id="instagram" class="w-4 h-4 inline">
                                                        <path
                                                            d="M825.4 1238c0-227.9 184.7-412.7 412.6-412.7 227.9 0 412.7 184.8 412.7 412.7 0 227.9-184.8 412.7-412.7 412.7-227.9 0-412.6-184.8-412.6-412.7m-223.1 0c0 351.1 284.6 635.7 635.7 635.7s635.7-284.6 635.7-635.7-284.6-635.7-635.7-635.7S602.3 886.9 602.3 1238m1148-660.9c0 82 66.5 148.6 148.6 148.6 82 0 148.6-66.6 148.6-148.6s-66.5-148.5-148.6-148.5-148.6 66.5-148.6 148.5M737.8 2245.7c-120.7-5.5-186.3-25.6-229.9-42.6-57.8-22.5-99-49.3-142.4-92.6-43.3-43.3-70.2-84.5-92.6-142.3-17-43.6-37.1-109.2-42.6-229.9-6-130.5-7.2-169.7-7.2-500.3s1.3-369.7 7.2-500.3c5.5-120.7 25.7-186.2 42.6-229.9 22.5-57.8 49.3-99 92.6-142.4 43.3-43.3 84.5-70.2 142.4-92.6 43.6-17 109.2-37.1 229.9-42.6 130.5-6 169.7-7.2 500.2-7.2 330.6 0 369.7 1.3 500.3 7.2 120.7 5.5 186.2 25.7 229.9 42.6 57.8 22.4 99 49.3 142.4 92.6 43.3 43.3 70.1 84.6 92.6 142.4 17 43.6 37.1 109.2 42.6 229.9 6 130.6 7.2 169.7 7.2 500.3 0 330.5-1.2 369.7-7.2 500.3-5.5 120.7-25.7 186.3-42.6 229.9-22.5 57.8-49.3 99-92.6 142.3-43.3 43.3-84.6 70.1-142.4 92.6-43.6 17-109.2 37.1-229.9 42.6-130.5 6-169.7 7.2-500.3 7.2-330.5 0-369.7-1.2-500.2-7.2M727.6 7.5c-131.8 6-221.8 26.9-300.5 57.5-81.4 31.6-150.4 74-219.3 142.8C139 276.6 96.6 345.6 65 427.1 34.4 505.8 13.5 595.8 7.5 727.6 1.4 859.6 0 901.8 0 1238s1.4 378.4 7.5 510.4c6 131.8 26.9 221.8 57.5 300.5 31.6 81.4 73.9 150.5 142.8 219.3 68.8 68.8 137.8 111.1 219.3 142.8 78.8 30.6 168.7 51.5 300.5 57.5 132.1 6 174.2 7.5 510.4 7.5 336.3 0 378.4-1.4 510.4-7.5 131.8-6 221.8-26.9 300.5-57.5 81.4-31.7 150.4-74 219.3-142.8 68.8-68.8 111.1-137.9 142.8-219.3 30.6-78.7 51.6-168.7 57.5-300.5 6-132.1 7.4-174.2 7.4-510.4s-1.4-378.4-7.4-510.4c-6-131.8-26.9-221.8-57.5-300.5-31.7-81.4-74-150.4-142.8-219.3C2199.4 139 2130.3 96.6 2049 65c-78.8-30.6-168.8-51.6-300.5-57.5-132-6-174.2-7.5-510.4-7.5-336.3 0-378.4 1.4-510.5 7.5"></path>
                                                    </svg>
                                                </a>
                                            @endif
                                        </div>
                                    </div>
                                @endif
                            </div>
                        </section>
                    </div>
                </div>
            </div>
        </section>
    </section>
</x-filament::page>

@push('scripts')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/flowbite/1.6.5/flowbite.min.js"></script>
    <script>
        document.getElementById('type').addEventListener('change', function () {
            if (this.value === 'event') {
                document.getElementById('events').classList.remove('hidden');
                document.getElementById('events').classList.add('block');
                document.getElementById('events').required = true;
                document.getElementById('events').previousElementSibling.classList.remove('hidden');
            } else {
                document.getElementById('events').classList.remove('block');
                document.getElementById('events').classList.add('hidden');
                document.getElementById('events').required = false;
                document.getElementById('events').previousElementSibling.classList.add('hidden');
            }
        });
    </script>
@endpush
