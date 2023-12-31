<x-filament::page>
    <!-- component -->
    <div class="flex-1 p:2 sm:p-6 justify-between flex flex-col h-screen">
        <div class="flex sm:items-center justify-between py-3 border-b-2 border-gray-200">
            <div class="relative flex items-center space-x-4">
                <div class="relative">

                    <span class="absolute text-green-500 right-0 bottom-0">
                        <svg width="20" height="20">
                            <circle cx="8" cy="8" r="8" fill="currentColor"></circle>
                        </svg>
                    </span>

                    <img src="#" alt="" class="w-10 sm:w-16 h-10 sm:h-16 rounded-full">
                </div>
                <div class="flex flex-col leading-tight">
                    <div class="text-2xl mt-1 flex items-center">
                        <span class="text-gray-700 mr-3">
                                ///
                        </span>
                    </div>
                </div>
            </div>
            <div class="flex items-center space-x-2">
                <button type="button"
                    class="inline-flex items-center justify-center rounded-lg border h-10 w-10 transition duration-500 ease-in-out text-gray-500 hover:bg-gray-300 focus:outline-none">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                        class="h-6 w-6">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                    </svg>
                </button>

            </div>
        </div>
        <div id="messages"
            class="flex flex-col space-y-4 p-3 overflow-y-auto scrollbar-thumb-blue scrollbar-thumb-rounded scrollbar-track-blue-lighter scrollbar-w-2 scrolling-touch">
            @foreach ($messages as $message)
            @php
                $sender = $message['sender'];
            @endphp
                @if (get_class($sender) == $auth_class)
                    <div class="chat-message">
                        <div class="flex items-end justify-end">
                            <div class="flex flex-col space-y-2 text-xs max-w-xs mx-2 order-1 items-end">
                                <div><span
                                        class="px-4 py-2 rounded-lg inline-block rounded-br-none bg-blue-600 text-gray-700">
                                        {!! $message->body !!}
                                    </span></div>
                            </div>
                            <img src="{{ $auth_avatar }}"
                                alt="My profile" class="w-6 h-6 rounded-full order-2">
                        </div>
                    </div>
                @else
                    <div class="chat-message">
                        <div class="flex items-end">
                            {{ $sender->full_name }}
                            <div class="flex flex-col space-y-2 text-xs max-w-xs mx-2 order-2 items-start">
                                <div>
                                    <span
                                        class="px-4 py-2 rounded-lg inline-block rounded-bl-none bg-gray-300 text-gray-600">
                                        {!! $message->body !!}
                                    </span>
                                </div>
                            </div>
                            <img src="{{ $sender->avatar }}"
                                alt="My profile" class="w-6 h-6 rounded-full order-1">
                        </div>
                    </div>
                @endif
            @endforeach

        </div>
        <div class="border-t-2 border-gray-200 px-4 mt-4 pt-4 mb-2 sm:mb-0">
            <div class="relative flex">
                <input type="text" wire:model="msg" wire:keydown.enter="sendMsg()" placeholder="Write your message!"
                    class="w-full focus:outline-none focus:placeholder-gray-400 text-gray-600 placeholder-gray-600 pl-12 bg-gray-200 rounded-md py-3">
                <div class="absolute right-0 items-center inset-y-0 hidden sm:flex">
                    <button type="button" wire:click="sendMsg()"
                        class="inline-flex items-center justify-center rounded-lg px-4 py-3 transition duration-500 ease-in-out text-white bg-blue-500 hover:bg-blue-400 focus:outline-none">
                        <span class="font-bold">Send</span>
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor"
                            class="h-6 w-6 ml-2 transform rotate-90">
                            <path
                                d="M10.894 2.553a1 1 0 00-1.788 0l-7 14a1 1 0 001.169 1.409l5-1.429A1 1 0 009 15.571V11a1 1 0 112 0v4.571a1 1 0 00.725.962l5 1.428a1 1 0 001.17-1.408l-7-14z">
                            </path>
                        </svg>
                    </button>
                </div>
            </div>
        </div>
    </div>
</x-filament::page>
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

<script>
    const el = document.getElementById('messages')
    el.scrollTop = el.scrollHeight
</script>
