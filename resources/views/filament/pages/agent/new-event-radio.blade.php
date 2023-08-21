@forelse($events as $event)
    <div class="col-span-1 flex-1 w-full block p-4 rounded border mb-6 shadow">
        <div class="flex gap-3 items-center">
            {{--            <input--}}
            {{--                class="block border-gray-300 shadow-sm text-primary-600 outline-none focus:ring focus:ring-primary-200 focus:ring-opacity-50 filament-tables-record-checkbox absolute top-3 right-3 rtl:right-auto rtl:left-3 md:relative md:top-0 md:right-0 rtl:md:left-0"--}}
            {{--                wire:model="selectedRecord" value="{{ $event['id'] }}" type="radio"/>--}}
            <div class="col-span-1 flex-1 w-full">
                <div class="flex flex-col items-start">
                    <div class="col-span-1 flex-1 w-full">
                        <span class="text-lg">{{ $event['name'] }}</span>
                    </div>

                    <div class="col-span-1 lex-1 w-full">
                        <p class="text-xs text-gray-500 py-1">
                            <svg xmlns="http://www.w3.org/2000/svg"
                                 viewBox="0 0 24 24"
                                 fill="currentColor"
                                 class="w-4 h-4 inline">
                                <path
                                    d="M12.75 12.75a.75.75 0 11-1.5 0 .75.75 0 011.5 0zM7.5 15.75a.75.75 0 100-1.5.75.75 0 000 1.5zM8.25 17.25a.75.75 0 11-1.5 0 .75.75 0 011.5 0zM9.75 15.75a.75.75 0 100-1.5.75.75 0 000 1.5zM10.5 17.25a.75.75 0 11-1.5 0 .75.75 0 011.5 0zM12 15.75a.75.75 0 100-1.5.75.75 0 000 1.5zM12.75 17.25a.75.75 0 11-1.5 0 .75.75 0 011.5 0zM14.25 15.75a.75.75 0 100-1.5.75.75 0 000 1.5zM15 17.25a.75.75 0 11-1.5 0 .75.75 0 011.5 0zM16.5 15.75a.75.75 0 100-1.5.75.75 0 000 1.5zM15 12.75a.75.75 0 11-1.5 0 .75.75 0 011.5 0zM16.5 13.5a.75.75 0 100-1.5.75.75 0 000 1.5z"/>
                                <path fill-rule="evenodd"
                                      d="M6.75 2.25A.75.75 0 017.5 3v1.5h9V3A.75.75 0 0118 3v1.5h.75a3 3 0 013 3v11.25a3 3 0 01-3 3H5.25a3 3 0 01-3-3V7.5a3 3 0 013-3H6V3a.75.75 0 01.75-.75zm13.5 9a1.5 1.5 0 00-1.5-1.5H5.25a1.5 1.5 0 00-1.5 1.5v7.5a1.5 1.5 0 001.5 1.5h13.5a1.5 1.5 0 001.5-1.5v-7.5z"
                                      clip-rule="evenodd"/>
                            </svg>
                            {{ \Carbon\Carbon::parse($event['start_at'])->format('M d, Y') }}
                        </p>

                        @if(isset($event['city']))
                            <p class="text-xs text-gray-500 py-1">
                                <svg xmlns="http://www.w3.org/2000/svg"
                                     viewBox="0 0 24 24"
                                     fill="currentColor"
                                     class="w-4 h-4 inline">
                                    <path fill-rule="evenodd"
                                          d="M11.54 22.351l.07.04.028.016a.76.76 0 00.723 0l.028-.015.071-.041a16.975 16.975 0 001.144-.742 19.58 19.58 0 002.683-2.282c1.944-1.99 3.963-4.98 3.963-8.827a8.25 8.25 0 00-16.5 0c0 3.846 2.02 6.837 3.963 8.827a19.58 19.58 0 002.682 2.282 16.975 16.975 0 001.145.742zM12 13.5a3 3 0 100-6 3 3 0 000 6z"
                                          clip-rule="evenodd"/>
                                </svg>
                                {{ $event['city']['name'] }}
                            </p>
                        @endif
                    </div>
                </div>
            </div>

            <div class="col-span-1 flex-1 w-full">
                <div class="flex gap-3 items-center">
                    <div class="col-span-1">
                        <div class="filament-tables-column-wrapper">
                            <div
                                class="flex w-full justify-start text-start">
                                <div
                                    class="filament-tables-image-column">
                                    <div style="height: 25px; width: 25px;"
                                         class="overflow-hidden rounded-full">
                                        <img
                                            src="{{ $event['agent']['company']['logo'] }}"
                                            style="height: 25px; width: 25px;"
                                            class="object-cover object-center">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-span-1 flex-1 w-full">
                        <div class="filament-tables-column-wrapper">
                            <div
                                class="flex w-full justify-start text-start">
                                <div
                                    class="inline-flex items-center space-x-1 rtl:space-x-reverse">
                                    <span class="">{{ $event['agent']['company']['name'] }}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-span-1 flex-1 w-full">
                <div class="filament-tables-column-wrapper">
                    <div class="flex w-full justify-start text-start">
                        <div
                            class="inline-flex items-center space-x-1 rtl:space-x-reverse">
                            <span>{{ \Carbon\Carbon::parse($event['start_at'])->format('M d, Y') }}</span>
                        </div>
                    </div>
                </div>
            </div>

            @if($tourguide->doesntBookedFromAgent(auth('agent')->id(), $event['id']))
                <div class="col-span-1 flex-1 w-full justify-end">
                    <div class="filament-tables-column-wrapper">
                        <div class="flex w-full justify-start text-start">
                            <div
                                class="inline-flex items-center space-x-1 rtl:space-x-reverse">
                                <button class="bg-success-500 text-white px-3 py-2 rounded-full text-sm"
                                        wire:click.prevent="create('{{ $event['id'] }}')">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                         stroke-width="1.5" stroke="currentColor" class="w-4 h-4 inline">
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                              d="M16.5 6v.75m0 3v.75m0 3v.75m0 3V18m-9-5.25h5.25M7.5 15h3M3.375 5.25c-.621 0-1.125.504-1.125 1.125v3.026a2.999 2.999 0 010 5.198v3.026c0 .621.504 1.125 1.125 1.125h17.25c.621 0 1.125-.504 1.125-1.125v-3.026a2.999 2.999 0 010-5.198V6.375c0-.621-.504-1.125-1.125-1.125H3.375z"/>
                                    </svg>
                                    {{ __('frontend.agent_dashboard.request_a_booking') }}
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </div>
@empty
    <div class="col-span-1 flex-1 w-full">
        <div class="filament-tables-column-wrapper">
            <div class="flex w-full justify-start text-start">
                <div
                    class="inline-flex items-center space-x-1 rtl:space-x-reverse">
                    <span class="">{{ __('frontend.agent_dashboard.no_events') }}</span>
                </div>
            </div>
        </div>
    </div>
@endforelse

<div class="flex w-full justify-center">
    {{ $events->links() }}
</div>
