<div class="lg:col-span-8 col-span-9 h-full w-full">
    @if(auth('agent')->check() && $user)
        <div
            class="lg:p-3 p-4 justify-between flex flex-col h-screen bg-white border-l-2 border-gray-200 w-full">
            <div
                class="flex sm:items-center justify-between lg:py-3 sm:pb-2 border-b-2 border-gray-200">
                <div class="relative flex items-center space-x-4 ml-5">
                    @if(!is_null($current_room->event))
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
                                <span class="mr-3">{{ __('attributes.chat.booked_by_agency', ['agency' => $current_room->event->company->name]) }}</span>
                            </div>
                        </div>
                    @else
                        @php $receiver = $current_room->members()->whereTourguide()->first()->memberable; @endphp
                        <div class="relative">
                            <span class="absolute right-0 bottom-0
                                @if($receiver?->is_online) text-success-500 @else text-danger-500 @endif">
                                <svg width=" 20" height="20">
                                    <circle cx="15" cy="12" r="5" fill="currentColor"></circle>
                                </svg>
                            </span>
                            <img src="{{ $receiver->avatar ?? 'https://ui-avatars.com/api/?name=' . urlencode(__('attributes.chat.deleted_user')) . '&color=FFFFFF&background=111827' }}"
                                 alt="{{ $receiver->full_name ?? __('attributes.chat.deleted_user') }}"
                                 class="w-10 lg:w-11 h-10 lg:h-11 rounded-full">
                        </div>
                        <div class="flex flex-col leading-tight">
                            <div class="text-lg mt-1 flex items-center">
                                <span class="text-gray-700 mr-3">{{ $receiver->full_name ?? __('attributes.chat.deleted_user') }}</span>
                            </div>
                            <div class="text-sm text-gray-600">
                                <span class="mr-3">{{ $receiver->email ?? __('attributes.chat.deleted_user') }}</span>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
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

            @if($allow_send_message)
                <div class="border-t-2 mt-4 pt-4 mb-0 lg:mb-0">
                    <div class="relative flex bg-gray-50">
                        <textarea rows="5"
                                  class="w-full h-full focus:outline-none focus:placeholder-gray-400 text-gray-600 placeholder-gray-600 py-3 outline-0 border-0 focus:ring-0 resize-none bg-gray-50"
                                  placeholder="Write your message!" wire:model.defer="msg"
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
            @else
                <div class="border-t-2 mt-4 pt-4 mb-0 lg:mb-0">
                    <p class="text-center text-gray-500">{{ __('messages.chat.you_cannot_send_message') }}</p>
                </div>
            @endif
        </div>
    @else
        <div class="flex flex-col items-center justify-center h-screen border-l-2 border-gray-200"
             style="padding: 50px">
            <div class="text-center">
                <h1 class="text-3xl font-bold text-gray-700">{{ __('messages.chat.no_chat_selected') }}</h1>
                <p class="text-gray-500">{{ __('messages.chat.waiting_for_any_chat') }}</p>
            </div>
        </div>
    @endif
</div>
