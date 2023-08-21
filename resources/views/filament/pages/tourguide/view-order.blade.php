<x-filament::page>
    <h1 class="text-2xl font-semibold" xmlns:x-filament="http://www.w3.org/1999/html">{{ $record->event->name }}</h1>
    <span class="text-sm text-gray-500">
        <small>
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                 style="display: inline"
                 stroke="currentColor" class="w-4 h-4" preserveAspectRatio="none">
              <path stroke-linecap="round" stroke-linejoin="round"
                    d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 012.25-2.25h13.5A2.25 2.25 0 0121 7.5v11.25m-18 0A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75m-18 0v-7.5A2.25 2.25 0 015.25 9h13.5A2.25 2.25 0 0121 11.25v7.5m-9-6h.008v.008H12v-.008zM12 15h.008v.008H12V15zm0 2.25h.008v.008H12v-.008zM9.75 15h.008v.008H9.75V15zm0 2.25h.008v.008H9.75v-.008zM7.5 15h.008v.008H7.5V15zm0 2.25h.008v.008H7.5v-.008zm6.75-4.5h.008v.008h-.008v-.008zm0 2.25h.008v.008h-.008V15zm0 2.25h.008v.008h-.008v-.008zm2.25-4.5h.008v.008H16.5v-.008zm0 2.25h.008v.008H16.5V15z"/>
            </svg>

            {{ \Carbon\Carbon::parse($record->event->start_at)->format('M d, Y') }} to {{ \Carbon\Carbon::parse($record->event->end_at)->format('M d, Y') }}</small>
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

                @php
                    $orderTourguide = \App\Models\OrderTourguide::where([
                        'order_id'     => $record->id,
                        'tourguide_id' => auth('tourguide')->id()
                    ])->first();
                @endphp

                <div
                    class="@if($orderTourguide->approvedByBoth() || $orderTourguide->tourguideTakedAction()) hidden @endif
                         items-center justify-center flex flex-col bg-white border border-gray-200 rounded-lg p-6 w-full">

                    @if(!in_array($orderTourguide->status ,['approved', 'rejected']) && $orderTourguide->agentStatus('pending'))
                        <x-filament::button
                            class="bg-success-500 text-white px-4 py-2 rounded-2xl mb-2 w-full"
                            wire:click="changeStatus('approved')"
                            wire:loading.attr="disabled"
                        > Accept
                        </x-filament::button>
                    @endif

                    @if($orderTourguide->pendingByBoth() || $orderTourguide->tourguideTakedAction())
                        <x-filament::button
                            class="bg-danger-500 text-white px-4 py-2 rounded-2xl mb-2 w-full"
                            style="background-color: #ef4444"
                            wire:click="changeStatus('rejected')"
                            wire:loading.attr="disabled"
                            wire:target="changeStatus('rejected')"
                        > Reject
                        </x-filament::button>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-filament::page>
