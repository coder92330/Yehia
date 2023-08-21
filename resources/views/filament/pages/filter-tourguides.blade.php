<x-filament::page>
    <div class="container mx-auto bg-white h-full">
        <div class="grid grid-cols-7 gap-6">
            <div class="p-6 lg:col-span-2 md:col-span-3 col-span-7">
                <div class="justify-center items-center">
                    <span class="text-gray-700 font-bold text-sm text-center">Filter By:</span>
                    <hr class="border-gray-200 mt-2">
                </div>

                <x-filament::form wire:submit.prevent="filterTourguides" class="mt-6">
                    {{ $this->form }}
                    <div class="flex justify-center mt-4">
                        <x-filament::button type="submit" class="text-white font-bold py-2 px-4"
                                            style="border-radius: 9999px;">Apply Filter
                        </x-filament::button>
                    </div>
                </x-filament::form>
            </div>
            <div class="flex flex-col justify-between lg:col-span-5 md:col-span-4 col-span-7">
                <div>
                    <div class="flex justify-center items-center mt-8 mb-4 py-2">
                        <span class="text-gray-700 text-2xl font-semibold text-center mr-2">Tour Guides</span>
                        <hr class="border-gray-200 w-[calc(100%-10rem)]">
                    </div>
                    <div class="px-4 grid lg:grid-cols-3 md:grid-cols-2 sm:grid-cols-1 gap-6">
                        @forelse($tourguides as $tourguide)
                            <div class="col-span-1">
                                <div
                                    class="max-w-sm bg-white border border-gray-200 rounded-lg shadow dark:bg-gray-800 dark:border-gray-700">
                                    <div
                                        class="relative block overflow-hidden bg-cover bg-center bg-no-repeat rounded-t-lg"
                                        style="background-image: url('{{ $tourguide->avatar }}'); height: 180px;">
                                        <div class="relative p-4 sm:p-6 lg:p-8 h-full">
                                            <div class="flex flex-col justify-between h-full">
                                                <div class="flex items-start justify-between">
                                                <span
                                                    class="px-4 py-0.5 rounded-full bg-primary-600 text-xs text-white">Featured</span>
                                                    <x-filament::button class="w-5 h-5"
                                                                        style="background-color: gray; color: white; border-radius: 99999px"
                                                                        wire:click="toggleFavorites({{ $tourguide->id }})">
                                                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"
                                                             fill="currentColor"
                                                             class="w-5 h-5 inline @if(auth()->user()->favourites()->where(['favouritable_id' => $tourguide->id,'favouritable_type' => \App\Models\Tourguide::class])->exists()) text-danger-500 @else text-white @endif">
                                                            <path
                                                                d="M9.653 16.915l-.005-.003-.019-.01a20.759 20.759 0 01-1.162-.682 22.045 22.045 0 01-2.582-1.9C4.045 12.733 2 10.352 2 7.5a4.5 4.5 0 018-2.828A4.5 4.5 0 0118 7.5c0 2.852-2.044 5.233-3.885 6.82a22.049 22.049 0 01-3.744 2.582l-.019.01-.005.003h-.002a.739.739 0 01-.69.001l-.002-.001z"/>
                                                        </svg>
                                                    </x-filament::button>
                                                </div>

                                                <div class="flex flex-col items-center">
                                                    <a href="{{ route($route, $tourguide->id) }}"
                                                       class="text-md text-white">{{ $tourguide->full_name }}</a>
                                                    <span class="text-xs text-gray-400 ">{{ $tourguide->email }}</span>
                                                    <span
                                                        class="text-xs text-gray-400 ">{{ $tourguide->country?->name }}</span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <p class="px-6 text-center py-4 font-normal text-sm text-gray-700 dark:text-gray-400">
                                        &ldquo; Every hundred feet the world changes &ldquo;</p>
                                    <div class="border-t-2 px-6 py-2">
                                        <div class="flex justify-between">
                                            <div class="inline-flex space-x-2">
                                                <a href="{{ route($edit_route, $tourguide->id) }}"
                                                   title="Edit Tourguide"
                                                   class="bg-primary-600 text-xs text-white px-2 py-1 rounded-full">
                                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none"
                                                         viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"
                                                         class="w-4 h-4">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                              d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L10.582 16.07a4.5 4.5 0 01-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 011.13-1.897l8.932-8.931zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0115.75 21H5.25A2.25 2.25 0 013 18.75V8.25A2.25 2.25 0 015.25 6H10"/>
                                                    </svg>
                                                </a>
                                                <button title="New Chat"
                                                        data-modal-target="chat-modal_{{ $tourguide->id }}"
                                                        data-modal-toggle="chat-modal_{{ $tourguide->id }}"
                                                        class="bg-success-600 text-xs text-white px-2 py-1 rounded-full">
                                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none"
                                                         viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"
                                                         class="w-4 h-4">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                              d="M7.5 8.25h9m-9 3H12m-9.75 1.51c0 1.6 1.123 2.994 2.707 3.227 1.129.166 2.27.293 3.423.379.35.026.67.21.865.501L12 21l2.755-4.133a1.14 1.14 0 01.865-.501 48.172 48.172 0 003.423-.379c1.584-.233 2.707-1.626 2.707-3.228V6.741c0-1.602-1.123-2.995-2.707-3.228A48.394 48.394 0 0012 3c-2.392 0-4.744.175-7.043.513C3.373 3.746 2.25 5.14 2.25 6.741v6.018z"/>
                                                    </svg>
                                                </button>
                                                <div id="chat-modal_{{ $tourguide->id }}" tabindex="-1"
                                                     class="fixed top-0 left-0 right-0 z-50 hidden p-4 overflow-x-hidden overflow-y-auto md:inset-0 h-[calc(100%-1rem)] max-h-full">
                                                    <div class="relative w-full max-w-md max-h-full">
                                                        <div
                                                            class="relative bg-white rounded-lg shadow dark:bg-gray-700">
                                                            <button type="button"
                                                                    class="absolute top-3 right-2.5 text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm p-1.5 ml-auto inline-flex items-center dark:hover:bg-gray-800 dark:hover:text-white"
                                                                    data-modal-hide="chat-modal_{{ $tourguide->id }}">
                                                                <svg aria-hidden="true" class="w-5 h-5"
                                                                     fill="currentColor" viewBox="0 0 20 20"
                                                                     xmlns="http://www.w3.org/2000/svg">
                                                                    <path fill-rule="evenodd"
                                                                          d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z"
                                                                          clip-rule="evenodd"></path>
                                                                </svg>
                                                                <span class="sr-only">Close modal</span>
                                                            </button>
                                                            <div class="p-6 flex flex-col space-y-4">
                                                                <x-filament::form
                                                                    wire:submit.prevent="chat({{ $tourguide->id }})">
                                                                    <label for="events"
                                                                           class="block text-sm font-medium text-gray-900 dark:text-white">Select Chat Type</label>
                                                                    <select id="type" name="type"
                                                                            style="margin: 10px 0" required
                                                                            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
                                                                        <option value="">Select Chat Type</option>
                                                                        <option value="tourguide">Private Chat With TourGuide</option>
                                                                        <option value="event">To Book Event</option>
                                                                    </select>

                                                                    <label for="events"
                                                                           class="text-sm font-medium text-gray-900 dark:text-white hidden">
                                                                        Select Chat Event</label>
                                                                    <select id="events" name="events"
                                                                            wire:model.defer="event_id"
                                                                            style="margin-top: 5px" required
                                                                            class="hidden bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
                                                                        <option value="">Select Event</option>
                                                                        @foreach($events as $event)
                                                                            <option wire:key="event-{{ $event->id }}"
                                                                                    value="{{ $event->id }}">{{ $event->name }}</option>
                                                                        @endforeach
                                                                    </select>
                                                                    <div class="flex flex-row justify-center">
                                                                        <button
                                                                            data-modal-hide="chat-modal_{{ $tourguide->id }}"
                                                                            type="submit"
                                                                            class="text-white bg-success-600 hover:bg-success-800 focus:ring-4 focus:outline-none focus:ring-success-300 dark:focus:ring-success-800 font-medium rounded-lg text-sm inline-flex items-center px-5 py-2.5 text-center mr-2">
                                                                            Chat with {{ $tourguide->full_name }}
                                                                        </button>
                                                                        <button
                                                                            data-modal-hide="chat-modal_{{ $tourguide->id }}"
                                                                            type="button"
                                                                            class="text-gray-500 bg-white hover:bg-gray-100 focus:ring-4 focus:outline-none focus:ring-gray-200 rounded-lg border border-gray-200 text-sm font-medium px-5 py-2.5 hover:text-gray-900 focus:z-10 dark:bg-gray-700 dark:text-gray-300 dark:border-gray-500 dark:hover:text-white dark:hover:bg-gray-600 dark:focus:ring-gray-600">
                                                                            No, cancel
                                                                        </button>
                                                                    </div>
                                                                </x-filament::form>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <span class="inline-flex items-center text-xs">
                                                @for($i = 0; $i < 5; $i++)
                                                    <svg aria-hidden="true"
                                                         class="w-5 h-5 @if($tourguide->rate > $i) text-yellow-400 @else text-gray-500 @endif"
                                                         fill="currentColor"
                                                         viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><title>First star</title><path
                                                            d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path></svg>
                                                @endfor
                                               ({{ $tourguide->rate }})
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @empty
                            {{-- No tourguides found --}}
                            <div class="h-screen flex items-center justify-center col-span-full">
                                <div class="text-center">
                                    <h2 class="text-2xl font-bold text-gray-500 dark:text-gray-400">
                                        No tourguides found
                                    </h2>
                                    <p class="text-gray-500 dark:text-gray-400">
                                        Try adjusting your search or filter to find what you're looking for.
                                    </p>
                                </div>
                            </div>
                        @endforelse
                    </div>
                </div>
                <div class="flex justify-center mt-4 mb-8">{{ $tourguides->links() }}</div>
            </div>
        </div>
    </div>

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
