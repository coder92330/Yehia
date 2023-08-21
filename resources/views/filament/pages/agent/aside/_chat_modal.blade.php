<button title="New Chat" data-tourguide_id="{{ $tourguide->id }}"
        x-on:click="$dispatch('open-modal', {id: 'chat-modal_{{ $tourguide->id }}'})"
        class="chat-button bg-success-600 text-xs text-white px-2 py-1 rounded-full">
    <svg xmlns="http://www.w3.org/2000/svg" fill="none"
         viewBox="0 0 24 24" stroke-width="1.5"
         stroke="currentColor"
         class="w-4 h-4">
        <path stroke-linecap="round" stroke-linejoin="round"
              d="M7.5 8.25h9m-9 3H12m-9.75 1.51c0 1.6 1.123 2.994 2.707 3.227 1.129.166 2.27.293 3.423.379.35.026.67.21.865.501L12 21l2.755-4.133a1.14 1.14 0 01.865-.501 48.172 48.172 0 003.423-.379c1.584-.233 2.707-1.626 2.707-3.228V6.741c0-1.602-1.123-2.995-2.707-3.228A48.394 48.394 0 0012 3c-2.392 0-4.744.175-7.043.513C3.373 3.746 2.25 5.14 2.25 6.741v6.018z"/>
    </svg>
</button>
<x-filament::modal id="chat-modal_{{ $tourguide->id }}">
    <x-slot name="header">
        Chat with {{ $tourguide->full_name }}
    </x-slot>
    <div>
        <x-filament::form wire:submit.prevent="chat({{ $tourguide->id }})">
            <label for="type_{{ $tourguide->id }}" class="block text-sm font-medium text-gray-900 dark:text-white">Select Chat Type</label>
            <select id="type_{{ $tourguide->id }}" name="type" style="margin: 10px 0" required wire:model.defer="chat_type"
                    class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
                <option value="">Select Chat Type</option>
                <option value="tourguide">Private Chat With TourGuide</option>
                <option value="event">To Book Event</option>
            </select>
            <label for="events_{{ $tourguide->id }}" class="text-sm font-medium text-gray-900 dark:text-white hidden">Select Chat Event</label>
            <select id="events_{{ $tourguide->id }}" name="events" wire:model.defer="event_id" style="margin-top: 5px"
                    class="chat-event hidden bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
                <option value="">Select Event</option>
                @foreach($events as $event)
                    <option wire:key="event-{{ $event->id }}" value="{{ $event->id }}">{{ $event->name }}</option>
                @endforeach
            </select>
            <div class="flex flex-row justify-center">
                <button
                    class="text-white bg-success-600 hover:bg-success-800 focus:ring-4 focus:outline-none focus:ring-success-300 dark:focus:ring-success-800 font-medium rounded-lg text-sm inline-flex items-center px-5 py-1.5 text-center mr-2"
                    type="submit">Chat
                </button>
                <button
                    x-on:click="$dispatch('close-modal', {id: 'chat-modal_{{ $tourguide->id }}'})" type="button"
                    class="text-gray-500 bg-white hover:bg-gray-100 focus:ring-4 focus:outline-none focus:ring-gray-200 rounded-lg border border-gray-200 text-sm font-medium px-5 py-1.5 hover:text-gray-900 focus:z-10 dark:bg-gray-700 dark:text-gray-300 dark:border-gray-500 dark:hover:text-white dark:hover:bg-gray-600 dark:focus:ring-gray-600">
                    No, cancel
                </button>
            </div>
        </x-filament::form>
    </div>
</x-filament::modal>
