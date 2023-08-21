<x-filament::page xmlns:x-filament="http://www.w3.org/1999/html">
    <section class="bg-white dark:bg-gray-800">
        <div class="relative">
            <div class="h-80 bg-cover bg-center" style="background-image: url('{{ $record->cover }}')"></div>
            <div
                class="absolute h-full flex justify-between items-center px-4 lg:top-28 lg:left-5 lg:right-3 md:top-20 md:left-3 md:right-2 top-2 left-2 right-1">
                <div class="flex items-center">
                    <img
                        class="border-4 border-white shadow object-cover object-center lg:h-55 lg:w-55 md:h-40 md:w-40 h-32 w-32"
                        src="{{ $record->logo }}" alt="Profile Photo">
                    <div class="ml-4">
                        <h1 class="text-2xl font-normal text-white">{{ $record->name }}</h1>
                        <p class="text-gray-300 tex-md">{{ $record->email }}</p>
                    </div>
                </div>
                <div class="flex items-center space-x-2">
                    <x-filament::button
                        class="text-white py-2 px-4 mr-4"
                        @class(['hidden' => ! auth('agent')->user()->hasPermissionTo('Create Bookings')])
                        style="background-color: #3B86FF !important; border-radius: 9999px !important;"
                        wire:click="redirectToRoute('agent.resources.bookings.create')"
                        wire:loading.attr="disabled">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor"
                             class="w-5 h-5 inline">
                            <path
                                d="M5.25 12a.75.75 0 01.75-.75h.01a.75.75 0 01.75.75v.01a.75.75 0 01-.75.75H6a.75.75 0 01-.75-.75V12zM6 13.25a.75.75 0 00-.75.75v.01c0 .414.336.75.75.75h.01a.75.75 0 00.75-.75V14a.75.75 0 00-.75-.75H6zM7.25 12a.75.75 0 01.75-.75h.01a.75.75 0 01.75.75v.01a.75.75 0 01-.75.75H8a.75.75 0 01-.75-.75V12zM8 13.25a.75.75 0 00-.75.75v.01c0 .414.336.75.75.75h.01a.75.75 0 00.75-.75V14a.75.75 0 00-.75-.75H8zM9.25 10a.75.75 0 01.75-.75h.01a.75.75 0 01.75.75v.01a.75.75 0 01-.75.75H10a.75.75 0 01-.75-.75V10zM10 11.25a.75.75 0 00-.75.75v.01c0 .414.336.75.75.75h.01a.75.75 0 00.75-.75V12a.75.75 0 00-.75-.75H10zM9.25 14a.75.75 0 01.75-.75h.01a.75.75 0 01.75.75v.01a.75.75 0 01-.75.75H10a.75.75 0 01-.75-.75V14zM12 9.25a.75.75 0 00-.75.75v.01c0 .414.336.75.75.75h.01a.75.75 0 00.75-.75V10a.75.75 0 00-.75-.75H12zM11.25 12a.75.75 0 01.75-.75h.01a.75.75 0 01.75.75v.01a.75.75 0 01-.75.75H12a.75.75 0 01-.75-.75V12zM12 13.25a.75.75 0 00-.75.75v.01c0 .414.336.75.75.75h.01a.75.75 0 00.75-.75V14a.75.75 0 00-.75-.75H12zM13.25 10a.75.75 0 01.75-.75h.01a.75.75 0 01.75.75v.01a.75.75 0 01-.75.75H14a.75.75 0 01-.75-.75V10zM14 11.25a.75.75 0 00-.75.75v.01c0 .414.336.75.75.75h.01a.75.75 0 00.75-.75V12a.75.75 0 00-.75-.75H14z"/>
                            <path fill-rule="evenodd"
                                  d="M5.75 2a.75.75 0 01.75.75V4h7V2.75a.75.75 0 011.5 0V4h.25A2.75 2.75 0 0118 6.75v8.5A2.75 2.75 0 0115.25 18H4.75A2.75 2.75 0 012 15.25v-8.5A2.75 2.75 0 014.75 4H5V2.75A.75.75 0 015.75 2zm-1 5.5c-.69 0-1.25.56-1.25 1.25v6.5c0 .69.56 1.25 1.25 1.25h10.5c.69 0 1.25-.56 1.25-1.25v-6.5c0-.69-.56-1.25-1.25-1.25H4.75z"
                                  clip-rule="evenodd"/>
                        </svg>
                        CREATE BOOKING
                    </x-filament::button>
                    <x-filament::button
                        class="text-white py-2 px-4"
                        @class(['hidden' => ! auth('agent')->user()->hasPermissionTo('Edit Company Profile')])
                        style="border: 2px solid white; border-radius: 9999px; background-color: transparent;"
                        wire:click="redirectToRoute('agent.resources.my-company.edit')"
                        wire:loading.attr="disabled">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor"
                             class="w-5 h-5 inline">
                            <path
                                d="M2.695 14.763l-1.262 3.154a.5.5 0 00.65.65l3.155-1.262a4 4 0 001.343-.885L17.5 5.5a2.121 2.121 0 00-3-3L3.58 13.42a4 4 0 00-.885 1.343z"/>
                        </svg>
                        {{ strtoupper(__('attributes.edit-company-profile')) }}
                    </x-filament::button>
                </div>
            </div>
        </div>
        <div class="mt-6 mb-4 border-b border-gray-200 dark:border-gray-700 px-8">
            <ul class="flex flex-wrap -mb-px text-sm font-medium text-center" id="myTab"
                data-tabs-toggle="#myTabContent" role="tablist">
                <li class="mr-2" role="presentation">
                    <button class="inline-block p-4 border-b-2 rounded-t-lg text-primary-500 border-primary-500"
                            id="describtion-tab" data-tabs-target="#about" type="button" role="tab"
                            aria-controls="about" aria-selected="false">{{ __('attributes.about_company')}}
                    </button>
                </li>
            </ul>
        </div>
        <div id="myTabContent" class="px-8">
            <div class="p-4 rounded-lg" id="about" role="tabpanel" aria-labelledby="about-tab">
                <p class="text-sm text-gray-500 dark:text-gray-400">{{ $record->description }}</p>
                <section class="my-5">
                    <h3 class="text-lg font-medium text-gray-500 border-b border-gray-200">{{ __('attributes.specialties') }}</h3>
                    <p class="mt-3 max-w-full text-sm text-gray-500">{{ $record->specialties }}</p>
                </section>
                <section class="my-5">
                    <h3 class="text-lg font-medium text-gray-500 border-b border-gray-200">{{ __('attributes.contact') }}</h3>
                    <div class="grid lg:grid-cols-2 gap-4 mt-3 grid-cols-1">
                        <div class="col-span-1 flex flex-row space-x-20">
                            <label for="phone" class="inline text-sm font-medium text-gray-700">{{ __('attributes.phone.title') }}:</label>
                            <p class="mt-1 text-sm text-blue-400">{{ $record->phone }}</p>
                        </div>
                        <div class="col-span-1 flex flex-row space-x-20">
                            <label for="email" class="inline text-sm font-medium text-gray-700">{{ __('attributes.email') }}:</label>
                            <p class="mt-1 text-sm text-sky-400">{{ $record->email }}</p>
                        </div>
                        <div class="col-span-1 flex flex-row space-x-14">
                            <label for="website" class="inline text-sm font-medium text-gray-700">{{ __('attributes.website') }}:</label>
                            <p class="mt-1 text-sm text-gray-500">{{ $record->website }}</p>
                        </div>
                        <div class="col-span-1 flex flex-row space-x-14">
                            <label for="address" class="inline text-sm font-medium text-gray-700">{{ __('attributes.address') }}:</label>
                            <p class="mt-1 text-sm text-gray-500">{{ $record->address }}</p>
                        </div>
                    </div>
                </section>
            </div>
        </div>
    </section>
</x-filament::page>
