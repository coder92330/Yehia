<x-filament::page xmlns:x-filament="http://www.w3.org/1999/html">
    <h1 class="text-2xl font-semibold" xmlns:x-filament="http://www.w3.org/1999/html">{{ $record->event->name }}</h1>
    <span class="text-sm text-gray-500">
        <small>
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                 style="display: inline"
                 stroke="currentColor" class="w-4 h-4" preserveAspectRatio="none">
              <path stroke-linecap="round" stroke-linejoin="round"
                    d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 012.25-2.25h13.5A2.25 2.25 0 0121 7.5v11.25m-18 0A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75m-18 0v-7.5A2.25 2.25 0 015.25 9h13.5A2.25 2.25 0 0121 11.25v7.5m-9-6h.008v.008H12v-.008zM12 15h.008v.008H12V15zm0 2.25h.008v.008H12v-.008zM9.75 15h.008v.008H9.75V15zm0 2.25h.008v.008H9.75v-.008zM7.5 15h.008v.008H7.5V15zm0 2.25h.008v.008H7.5v-.008zm6.75-4.5h.008v.008h-.008v-.008zm0 2.25h.008v.008h-.008V15zm0 2.25h.008v.008h-.008v-.008zm2.25-4.5h.008v.008H16.5v-.008zm0 2.25h.008v.008H16.5V15z"/>
            </svg>

            {{ $record->event->start_at->format('M d, Y') }} to {{ $record->event->end_at->format('M d, Y') }}</small>
    </span>
    <div class="grid grid-cols-4 gap-4 mt-4">
        <div class="col-span-3">
            <div class="max-h-96 overflow-hidden rounded-2xl mb-4">
                <img src="{{ $record->event->cover }}" alt="{{ $record->event->name }}"
                     class="rounded-2xl w-full object-cover">
            </div>
            <div class="mb-4 border-b border-gray-200 dark:border-gray-700">
                <ul class="flex flex-wrap -mb-px text-sm font-medium text-center" id="myTab"
                    data-tabs-toggle="#myTabContent" role="tablist">
                    <li class="mr-2" role="presentation">
                        <button
                            class="inline-block p-4 border-b-2 rounded-t-lg aria-selected:text-primary-500 aria-selected:border-primary-500"
                            id="describtion-tab" data-tabs-target="#describtion" type="button" role="tab"
                            aria-controls="describtion" aria-selected="false">Description
                        </button>
                    </li>
                    <li role="presentation"
                        class="mr-2 @if(isset($record->event) && $record->event->days->count() <= 0) hidden @endif">
                        <button
                            class="inline-block p-4 border-b-2 border-transparent rounded-t-lg hover:text-gray-600 hover:border-gray-300 aria-selected:text-primary-500 aria-selected:border-primary-500"
                            id="calendar-tab" data-tabs-target="#calendar" type="button" role="tab"
                            aria-controls="calendar" aria-selected="false">Calendar of Event
                        </button>
                    </li>
                    <li class="mr-2" role="presentation">
                        <button
                            class="inline-block p-4 border-b-2 border-transparent rounded-t-lg hover:text-gray-600 hover:border-gray-300 aria-selected:text-primary-500 aria-selected:border-primary-500"
                            id="meetingpoint-tab" data-tabs-target="#meetingpoint" type="button" role="tab"
                            aria-controls="meetingpoint" aria-selected="false">Meeting Point
                        </button>
                    </li>
                    @if($record->tourguides()->where([['status', '!=', 'rejected'],['agent_status', '!=', 'rejected']])->count() > 0)
                        <li class="mr-2" role="presentation">
                            <button
                                class="inline-block p-4 border-b-2 border-transparent rounded-t-lg hover:text-gray-600 hover:border-gray-300 aria-selected:text-primary-500 aria-selected:border-primary-500"
                                id="tourguides-tab" data-tabs-target="#tourguides" type="button" role="tab"
                                aria-controls="tourguides" aria-selected="false">Tour Guides
                            </button>
                        </li>
                    @endif
                </ul>
            </div>
            <div id="myTabContent">
                <div class="p-4 rounded-lg" id="describtion" role="tabpanel" aria-labelledby="describtion-tab">
                    <p class="text-sm text-gray-500 dark:text-gray-400">{{ $record->event->description }}</p>
                </div>
                <div id="calendar" role="tabpanel" aria-labelledby="calendar-tab"
                     class="p-4 rounded-lg @if(isset($record->event) && $record->event->days->count() <= 0) hidden @endif">
                    <h1 class="text-xl font-semibold mb-3">Calendar of Event</h1>
                    @foreach($record->event->days as $day)
                        <div class="my-6">
                            <h1 class="text-md font-semibold border-b border-primary-500 p-2 w-32 text-center">
                                {{ \Carbon\Carbon::parse($day->start_at)->format('M d') }}</h1>
                            <div class="grid grid-cols-4 gap-4">
                                @foreach($day->sessions as $session)
                                    <div class="col-span-1 p-4">
                                        <p class="text-sm text-gray-500 dark:text-gray-400">
                                            {{ \Carbon\Carbon::parse($session->start_at)->format('h:i A') }}
                                            - {{ \Carbon\Carbon::parse($session->end_at)->format('h:i A') }}
                                        </p>
                                        <p class="text-sm text-gray-500 dark:text-gray-400">
                                            {{ $session->location }}
                                        </p>
                                    </div>
                                    <div class="colspan-3">
                                        <p class="text-sm text-gray-500 dark:text-gray-400">{{ $session->description }}</p>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endforeach
                </div>
                <div class="p-4 rounded-lg" id="meetingpoint" role="tabpanel" aria-labelledby="meetingpoint-tab">
                    {{ $this->form }}
                </div>
                @if($record->tourguides()->count() > 0)
                    <div class="p-4 rounded-lg" id="tourguides" role="tabpanel" aria-labelledby="tourguides-tab">
                        @foreach($record->tourguides()->where([['status', '!=', 'rejected'],['agent_status', '!=', 'rejected']])->get() as $tourguide)
                            <div class="flex flex-col gap-2 items-center mb-2">
                                <div class="flex items-center border border-gray-200 rounded-lg p-6 bg-white w-full">
                                    <img
                                        src="{{ $tourguide->avatar }}"
                                        class="w-14 h-14 rounded-full" alt="">
                                    <div class="ml-4">
                                        <p class="text-lg font-medium">{{ $tourguide->full_name }}</p>
                                        @empty(!$tourguide->phones)
                                            <p class="text-sm text-gray-500 block">{{ implode(', ', $tourguide->phones->pluck('number')->toArray()) }}</p>
                                        @endempty
                                        <p class="text-sm text-gray-500 block">{{ $tourguide->email }}</p>
                                        <p class="text-sm text-gray-500 block">{{ $tourguide->address }}</p>
                                    </div>

                                    @if($tourguide->pivot->agent_status === 'pending')
                                        <div class="ml-auto">
                                            @if($tourguide->pivot->status !== 'pending')
                                                <button
                                                    class="px-2 py-1 rounded-full bg-success-600 text-white hover:bg-success-500 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-success-500"
                                                    wire:click="changeOrderStatus({{ $tourguide->id }}, 'approved')">
                                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none"
                                                         viewBox="0 0 24 24"
                                                         stroke-width="1.5" stroke="currentColor"
                                                         class="w-4 h-4 inline">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                              d="M5 13l4 4L19 7"/>
                                                    </svg>
                                                    Accept
                                                </button>

                                                <button
                                                    class="px-2 py-1 rounded-full bg-danger-600 text-white hover:bg-danger-500 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-danger-500"
                                                    wire:click="changeOrderStatus({{ $tourguide->id }} , 'rejected')">
                                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none"
                                                         viewBox="0 0 24 24"
                                                         stroke-width="1.5" stroke="currentColor"
                                                         class="w-4 h-4 inline">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                              d="M6 18L18 6M6 6l12 12"/>
                                                    </svg>
                                                    Reject
                                                </button>
                                            @endif

                                            @if($tourguide->pivot->status !== 'rejected')
                                                <button
                                                    class="px-2 py-1 rounded-full bg-danger-600 text-white hover:bg-danger-500 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-danger-500"
                                                    wire:click="unassignTourguide({{ $tourguide->id }})">
                                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none"
                                                         viewBox="0 0 24 24"
                                                         stroke-width="1.5" stroke="currentColor"
                                                         class="w-4 h-4 inline">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                              d="M14.74 9l-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 01-2.244 2.077H8.084a2.25 2.25 0 01-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 00-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 013.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 00-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 00-7.5 0"/>
                                                    </svg>
                                                    Unassign
                                                </button>
                                            @endif

                                        </div>
                                    @endif
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>
        </div>
        <div class="col-span-1">
            <div class="flex flex-col gap-2 items-center">
                <div class="flex items-center border border-gray-200 rounded-lg p-6 bg-white w-full">
                    <img
                        src="https://ui-avatars.com/api/?name={{$record->event->company->name}}&color=7F9CF5&background=EBF4FF"
                        class="w-14 h-14 rounded-full" alt="">
                    <div class="ml-4">
                        <small class="text-gray-500">Presented By</small>
                        <span class="text-sm font-semibold block">{{ $record->event->company->name }}</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-filament::page>
