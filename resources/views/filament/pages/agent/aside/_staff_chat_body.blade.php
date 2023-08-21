<div class="lg:col-span-8 col-span-9 h-full w-full">
    @if(auth('agent')->check() && $staff)
        <div
            class="lg:p-3 p-4 justify-between flex flex-col h-screen bg-white border-l-2 border-gray-200 w-full">
            <div
                class="flex sm:items-center justify-between lg:py-3 sm:pb-2 border-b-2 border-gray-200">
                <div class="relative flex items-center space-x-4 ml-5">
                    @php $receiver = $staff->members()->whereTourguide()->first()->memberable; @endphp
                    <div class="relative">
                        <span class="absolute right-0 bottom-0
                            @if($receiver?->is_online) text-success-500 @else text-danger-500 @endif">
                            <svg width=" 20" height="20">
                                <circle cx="15" cy="12" r="5" fill="currentColor"></circle>
                            </svg>
                        </span>
                        <img
                            src="{{ $receiver->avatar ?? 'https://ui-avatars.com/api/?name=' . urlencode(__('attributes.chat.deleted_user')) . '&color=FFFFFF&background=111827' }}"
                            alt="{{ $receiver->full_name ?? __('attributes.chat.deleted_user') }}"
                            class="w-10 lg:w-11 h-10 lg:h-11 rounded-full">
                    </div>
                    <div class="flex flex-col leading-tight">
                        <div class="text-lg mt-1 flex items-center">
                            <span
                                class="text-gray-700 mr-3">{{ $receiver->full_name ?? __('attributes.chat.deleted_user') }}</span>
                        </div>
                        <div class="text-sm text-gray-600">
                            <span class="mr-3">{{ $receiver->email ?? __('attributes.chat.deleted_user') }}</span>
                        </div>
                    </div>
                </div>
            </div>
            <div id="messages"
                 class="h-full flex flex-col space-y-4 p-3 overflow-y-auto scrollbar-thumb-blue scrollbar-thumb-rounded scrollbar-track-blue-lighter scrollbar-w-2 scrolling-touch">
                @foreach ($messages as $message)
                    @if ($message->sender->memberable_type === auth()->user()->getMorphClass())
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
                                <img src="{{ auth()->user()->avatar }}"
                                     alt="My profile" class="my-auto w-6 h-6 rounded-full order-2">
                            </div>
                        </div>
                    @else
                        <div class="chat-message">
                            <div class="flex items-start">
                                <img
                                    src="{{ $message->sender->memberable?->avatar ?? 'https://ui-avatars.com/api/?name=' . urlencode(__('attributes.chat.deleted_user')) . '&color=FFFFFF&background=111827' }}"
                                    alt="My profile"
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
