<x-filament::page>
    <section class="bg-white">
        <div class="relative">
            <div class="h-80 bg-cover bg-center" style="background-image: url('{{ $company->cover }}')"></div>
            <div
                class="absolute h-full flex justify-between items-center px-4 lg:top-28 lg:left-5 lg:right-3 md:top-20 md:left-3 md:right-2 top-2 left-2 right-1">
                <div class="flex items-center">
                    <img
                        class="relative border-4 border-white object-cover object-center lg:h-80 lg:w-80 md:h-40 md:w-40 h-32 w-32 bottom-14"
                        src="{{ $company->logo }}" alt="Profile Photo">
                    <div class="ml-4">
                        <h1 class="text-2xl font-normal text-white">{{ $company->name }}</h1>
                        <p class="text-gray-300 tex-md">{{ $company->email }}</p>
                    </div>
                </div>
                <div class="flex items-center">
                    <a href="{{ route('agent.resources.bookings.create') }}"
                       class="text-white py-2 px-4 mr-4 rounded-full bg-blue-600 hover:bg-blue-700">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor"
                             class="w-5 h-5 inline">
                            <path
                                d="M5.25 12a.75.75 0 01.75-.75h.01a.75.75 0 01.75.75v.01a.75.75 0 01-.75.75H6a.75.75 0 01-.75-.75V12zM6 13.25a.75.75 0 00-.75.75v.01c0 .414.336.75.75.75h.01a.75.75 0 00.75-.75V14a.75.75 0 00-.75-.75H6zM7.25 12a.75.75 0 01.75-.75h.01a.75.75 0 01.75.75v.01a.75.75 0 01-.75.75H8a.75.75 0 01-.75-.75V12zM8 13.25a.75.75 0 00-.75.75v.01c0 .414.336.75.75.75h.01a.75.75 0 00.75-.75V14a.75.75 0 00-.75-.75H8zM9.25 10a.75.75 0 01.75-.75h.01a.75.75 0 01.75.75v.01a.75.75 0 01-.75.75H10a.75.75 0 01-.75-.75V10zM10 11.25a.75.75 0 00-.75.75v.01c0 .414.336.75.75.75h.01a.75.75 0 00.75-.75V12a.75.75 0 00-.75-.75H10zM9.25 14a.75.75 0 01.75-.75h.01a.75.75 0 01.75.75v.01a.75.75 0 01-.75.75H10a.75.75 0 01-.75-.75V14zM12 9.25a.75.75 0 00-.75.75v.01c0 .414.336.75.75.75h.01a.75.75 0 00.75-.75V10a.75.75 0 00-.75-.75H12zM11.25 12a.75.75 0 01.75-.75h.01a.75.75 0 01.75.75v.01a.75.75 0 01-.75.75H12a.75.75 0 01-.75-.75V12zM12 13.25a.75.75 0 00-.75.75v.01c0 .414.336.75.75.75h.01a.75.75 0 00.75-.75V14a.75.75 0 00-.75-.75H12zM13.25 10a.75.75 0 01.75-.75h.01a.75.75 0 01.75.75v.01a.75.75 0 01-.75.75H14a.75.75 0 01-.75-.75V10zM14 11.25a.75.75 0 00-.75.75v.01c0 .414.336.75.75.75h.01a.75.75 0 00.75-.75V12a.75.75 0 00-.75-.75H14z"/>
                            <path fill-rule="evenodd"
                                  d="M5.75 2a.75.75 0 01.75.75V4h7V2.75a.75.75 0 011.5 0V4h.25A2.75 2.75 0 0118 6.75v8.5A2.75 2.75 0 0115.25 18H4.75A2.75 2.75 0 012 15.25v-8.5A2.75 2.75 0 014.75 4H5V2.75A.75.75 0 015.75 2zm-1 5.5c-.69 0-1.25.56-1.25 1.25v6.5c0 .69.56 1.25 1.25 1.25h10.5c.69 0 1.25-.56 1.25-1.25v-6.5c0-.69-.56-1.25-1.25-1.25H4.75z"
                                  clip-rule="evenodd"/>
                        </svg>
                        {{ __('frontend.agent_dashboard.create_booking') }}
                    </a>
                    <a href="{{ route('agent.pages.edit-company-profile') }}"
                       class="text-white py-2 px-4 border-2 border-white rounded-full hover:bg-white hover:text-gray-800 bg-transparent">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor"
                             class="w-5 h-5 inline">
                            <path
                                d="M2.695 14.763l-1.262 3.154a.5.5 0 00.65.65l3.155-1.262a4 4 0 001.343-.885L17.5 5.5a2.121 2.121 0 00-3-3L3.58 13.42a4 4 0 00-.885 1.343z"/>
                        </svg>
                        {{ __('frontend.agent_dashboard.edit_company_profile') }}
                    </a>
                </div>
            </div>
        </div>
        <div class="mb-4 border-b border-gray-200 dark:border-gray-700">
            <ul class="flex flex-wrap -mb-px font-medium text-center" id="myTab"
                data-tabs-toggle="#myTabContent" role="tablist">
                <li class="mx-auto" role="presentation">
                    <button class="inline-block p-4 border-b-2 rounded-t-lg text-blue-500 border-blue-500"
                            id="describtion-tab" data-tabs-target="#about" type="button" role="tab"
                            aria-controls="about" aria-selected="false">Place New Order
                    </button>
                </li>
            </ul>
        </div>
        <div id="myTabContent">
            <div class="p-4 pb-12 rounded-lg" id="about" role="tabpanel" aria-labelledby="about-tab">
                <div class="container mx-auto px-32">
                    {{--                    <div class="grid grid-cols-2 gap-6">--}}
                    {{--                        <div class="flex justify-center items-center mt-12 mb-8">--}}
                    {{--                            <span--}}
                    {{--                                class="text-gray-400 text-sm text-center mr-2">Event Details</span>--}}
                    {{--                            <hr class="border-gray-200 w-[calc(100%-6rem)]">--}}
                    {{--                        </div>--}}
                    {{--                        <div class="flex justify-center items-center mt-12 mb-8">--}}
                    {{--                            <span--}}
                    {{--                                class="text-gray-400 text-sm text-center mr-2">Calendar of Event</span>--}}
                    {{--                            <hr class="border-gray-200 w-[calc(100%-8rem)]">--}}
                    {{--                        </div>--}}
                    {{--                    </div>--}}
                    {{-- Search Input --}}
                    <div class="flex flex-row justify-between items-center mb-4">
                        <span>Events</span>
                        <div class="relative">
                            <input type="text" wire:model="search"
                                   class="bg-gray-100 dark:bg-gray-800 border-gray-300 dark:border-gray-700 focus:ring-blue-500 focus:border-blue-500 block w-full pl-12 pr-4 py-2 rounded-md sm:text-sm dark:text-gray-300"
                                   placeholder="Search">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <svg class="h-5 w-5 text-gray-400 dark:text-gray-300" fill="currentColor"
                                     viewBox="0 0 20 20">
                                    <path fill-rule="evenodd"
                                          d="M9.25 2.5a6.75 6.75 0 015.656 10.344l4.28 4.28a.75.75 0 11-1.06 1.06l-4.28-4.28A6.75 6.75 0 119.25 2.5zm0 1.5a5.25 5.25 0 100 10.5 5.25 5.25 0 000-10.5z"
                                          clip-rule="evenodd"></path>
                                </svg>
                            </div>
                        </div>
                    </div>
                    @if(isset($events) && count($events) > 0)
                        <x-filament::form wire:submit.prevent="create">
                            {{--                        {{ $this->form }}--}}

                            @include('filament.pages.agent.new-event-radio')
{{--                            <div class="flex flex-col space-y-4 justify-center items-center mt-12">--}}
{{--                                <x-filament::button type="submit"--}}
{{--                                                    class="bg-success-500 text-white px-4 py-2"--}}
{{--                                                    style="border-radius: 9999px;">--}}
{{--                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"--}}
{{--                                         stroke-width="1.5" stroke="currentColor" class="w-5 h-5 inline">--}}
{{--                                        <path stroke-linecap="round" stroke-linejoin="round"--}}
{{--                                              d="M14.857 17.082a23.848 23.848 0 005.454-1.31A8.967 8.967 0 0118 9.75v-.7V9A6 6 0 006 9v.75a8.967 8.967 0 01-2.312 6.022c1.733.64 3.56 1.085 5.455 1.31m5.714 0a24.255 24.255 0 01-5.714 0m5.714 0a3 3 0 11-5.714 0M3.124 7.5A8.969 8.969 0 015.292 3m13.416 0a8.969 8.969 0 012.168 4.5"/>--}}
{{--                                    </svg>--}}
{{--                                    Create Order--}}
{{--                                </x-filament::button>--}}
{{--                            </div>--}}
                        </x-filament::form>
                    @else
                        <div class="flex flex-col space-y-4 justify-center items-center">
                            <span class="text-gray-400 text-xl text-center mr-2">{{ __('frontend.agent_dashboard.no_events') }}</span>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </section>
</x-filament::page>
