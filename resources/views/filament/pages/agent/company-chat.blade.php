@if((!empty($rooms) && count($rooms) > 0) || !empty($search))
    <x-filament::page>
        <div class="grid grid-cols-11 bg-white">
            @include('filament.pages.agent.aside._chat_sidebar')
            <div class="lg:col-span-8 col-span-9 h-full w-full">
                @if(auth('agent')->check() && $user)
                    <div
                        class="lg:p-3 p-4 justify-between flex flex-col h-screen bg-white border-l-2 border-gray-200 w-full">
                        @if(!is_null($current_room->event))
                            <div
                                class="flex sm:items-center justify-between lg:py-3 sm:pb-2 border-b-2 border-gray-200">
                                <div class="relative flex items-center space-x-4 ml-5">
                                    <div class="relative">
                                        <img src="{{ $current_room->event->cover }}"
                                             alt="{{ $current_room->event->name }}"
                                             class="w-10 lg:w-11 h-10 lg:h-11 rounded-full">
                                    </div>
                                    <div class="flex flex-col leading-tight">
                                        <div class="text-lg mt-1 flex items-center">
                                            <span class="text-gray-700 mr-3">{{ $current_room->event->name }}</span>
                                        </div>
                                        <div class="text-sm text-gray-600">
                                            <span class="mr-3">Booked by {{ $current_room->event->company->name }} Agency</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @else
                            @php $receiver = $current_room->members()->whereUser()->first()->memberable; @endphp
                            <div
                                class="flex sm:items-center justify-between lg:py-3 sm:pb-2 border-b-2 border-gray-200">
                                <div class="relative flex items-center space-x-4 ml-5">
                                    <div class="relative">
                                        <img src="{{ $receiver->avatar }}" alt="{{ $receiver->name }}"
                                             class="w-10 lg:w-11 h-10 lg:h-11 rounded-full">
                                    </div>
                                    <div class="flex flex-col leading-tight">
                                        <div class="text-lg mt-1 flex items-center">
                                            <span class="text-gray-700 mr-3">{{ $receiver->name }}</span>
                                        </div>
                                        <div class="text-sm text-gray-600">
                                            <span class="mr-3">{{ $receiver->email }}</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endif
                        <div id="messages"
                             class="h-full flex flex-col space-y-4 p-3 overflow-y-auto scrollbar-thumb-blue scrollbar-thumb-rounded scrollbar-track-blue-lighter scrollbar-w-2 scrolling-touch">
                            @foreach ($messages as $message)
                                @if ($message->sender->memberable_type === auth('agent')->user()->getMorphClass())
                                    <div class="chat-message mt-auto">
                                        <div class="flex flex-row items-end justify-end">
                                            <div
                                                class="flex flex-col space-y-2 text-xs max-w-xs mx-2 order-1 items-end">
                                                <div class="flex flex-col items-end">
                                                    <span
                                                        class="px-4 py-2 rounded-lg inline-block rounded-br-none bg-blue-500 text-white text-center">
                                                        {!! $message->body !!}
                                                    </span>
                                                    <span
                                                        class="text-gray-500 text-xs">{{ $message->created_at->format('h:i A') }}</span>
                                                </div>
                                            </div>
                                            <img src="{{ auth('agent')->user()->avatar }}"
                                                 alt="My profile" class="my-auto w-6 h-6 rounded-full order-2">
                                        </div>
                                    </div>
                                @else
                                    <div class="chat-message">
                                        <div class="flex items-start">
                                            <img src="{{ $message->sender->memberable->avatar }}" alt="My profile"
                                                 class="my-auto w-6 h-6 rounded-full order-1">
                                            <div
                                                class="flex flex-col space-y-2 text-xs max-w-xs mx-2 order-2 items-start">
                                                <div class="flex flex-col items-start">
                                                    <span
                                                        class="px-4 py-2 rounded-lg inline-block rounded-bl-none bg-gray-200 text-gray-600">
                                                        {!! $message->body !!}
                                                    </span>
                                                    <span
                                                        class="text-gray-500 text-xs">{{ $message->created_at->format('h:i A') }}</span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endif
                            @endforeach
                        </div>
                        <div class="border-t-2 mt-4 pt-4 mb-0 lg:mb-0">
                            <div class="relative flex bg-gray-50">
                                                                    <textarea rows="5"
                                                                              class="w-full h-full focus:outline-none focus:placeholder-gray-400 text-gray-600 placeholder-gray-600 py-3 outline-0 border-0 focus:ring-0 resize-none bg-gray-50"
                                                                              placeholder="Write your message!"
                                                                              wire:model.defer="msg"
                                                                              wire:keydown.enter="sendMsg"></textarea>
                                <div class="absolute right-1 bottom-2 items-center">
                                    <button type="button" wire:click="sendMsg"
                                            class="inline-flex items-center justify-center rounded-lg mr-1 px-4 py-2 transition duration-500 ease-in-out text-white bg-blue-500 hover:bg-primary-400 focus:outline-none">
                                        <span class="font-semibold">Send</span>
                                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"
                                             fill="currentColor"
                                             class="h-5 w-5 ml-2 transform rotate-90">
                                            <path
                                                d="M10.894 2.553a1 1 0 00-1.788 0l-7 14a1 1 0 001.169 1.409l5-1.429A1 1 0 009 15.571V11a1 1 0 112 0v4.571a1 1 0 00.725.962l5 1.428a1 1 0 001.17-1.408l-7-14z">
                                            </path>
                                        </svg>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                @else
                    <div class="flex flex-col items-center justify-center h-screen border-l-2 border-gray-200"
                         style="padding: 50px">
                        <div class="text-center">
                            <h1 class="text-3xl font-bold text-gray-700">No Chat Selected!</h1>
                            <p class="text-gray-500">You can start a conversation with your companies by go to
                                companies
                                list on sidebar and click new chat, or you can select a chat from the list.</p>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </x-filament::page>
@else
    <div class="flex flex-col items-center justify-center h-screen bg-white">
        <div class="text-center">
            <h1 class="text-3xl font-bold text-gray-700">No messages yet!</h1>
            <p class="text-gray-500">You can start a conversation with your companies by go to companies list on
                sidebar and
                click new chat.</p>
        </div>
    </div>
@endif

{{-- Styles --}}
@push('styles')
    <style>
        .scrollbar-w-2::-webkit-scrollbar {
            width: 0.25rem;
            height: 0.25rem;
        }

        .scrollbar-track-blue-lighter::-webkit-scrollbar-track {
            --bg-opacity: 1;
            background-color: #f7fafc;
            background-color: rgba(247, 250, 252, var(--bg-opacity));
        }

        .scrollbar-thumb-blue::-webkit-scrollbar-thumb {
            --bg-opacity: 1;
            background-color: #edf2f7;
            background-color: rgba(237, 242, 247, var(--bg-opacity));
        }

        .scrollbar-thumb-rounded::-webkit-scrollbar-thumb {
            border-radius: 0.25rem;
        }
    </style>
@endpush

{{-- Scripts --}}
<script>
    const el = document.getElementById('messages')
    el.scrollTop = el.scrollHeight

    // Echo Message
    window.onload = function () {
        @if(isset($room->room_id))
        Echo.private("room.{{ $room->room_id }}")
            .listen('MessageSent', (e) => {
                if (e.guard !== 'admin') {
                    const el = document.getElementById('messages')
                    el.scrollTop = el.scrollHeight

                    let audio = new Audio('{{ asset('sounds/message.wav') }}');
                    audio.play();
                }
            });
        @endif
    }
</script>
