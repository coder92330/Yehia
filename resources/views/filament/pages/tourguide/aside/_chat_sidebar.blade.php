<div class="h-full w-full lg:col-span-3 col-span-2">
    <div class="w-full p-6 border-b-2 border-gray-200">
        <div class="relative lg:block hidden">
            <input type="text" wire:model="search"
                   class="lg:block hidden w-full h-10 px-3 pr-10 text-sm border-0 placeholder-gray-500 focus:shadow-outline-none focus:outline-none focus:ring-0"
                   placeholder="Search...">
        </div>
    </div>
    @foreach($rooms as $room)
        @php
            $reciver  = $room->members()->whereNotTourguide()->whereIsEnabled(true)->first();
            $sideRoom = !is_null($room->event) ? $room->event : $reciver;
        @endphp
        @if($sideRoom)
            @if(!is_null($room->event))
                <div class="flex justify-between py-4 border-gray-200 cursor-pointer
                        @if(isset($current_room) && ($current_room->id === $room->id)) border-b-1 bg-gray-50 @else border-b-2 @endif"
                     wire:click="selectRoom({{ $room->id }})">
                    <div class="relative flex items-center space-x-4 ml-5">
                        <div class="relative">
                            <img
                                src="{{ $sideRoom->cover ?? 'https://ui-avatars.com/api/?name=' . urlencode(__('attributes.deleted_event')) . '&color=FFFFFF&background=111827' }}"
                                alt="{{ $sideRoom->name ?? __('attributes.deleted_event') }}"
                                class="w-10 lg:w-11 h-10 lg:h-11 rounded-full">
                        </div>
                        <div class="lg:flex flex-col leading-tight hidden">
                            <div class="text-lg mt-1 flex items-center">
                                <span class="text-gray-700 mr-3">{{ $sideRoom->name ?? __('attributes.deleted_event') }}</span>
                            </div>
                            @if(isset($room->members) && $room->members()->whereAgent()->exists())
                                <div class="text-sm text-gray-600">
                                    <span class="mr-3">Booked by {{$room->members()->whereAgent()->first()->memberable->company->name }} Agency</span>
                                </div>
                            @endif
                        </div>
                    </div>
                    <div class="lg:flex flex-col items-center space-y-4 mr-5">
                        @if($reciver->messages()->whereRoomId($room->id)->exists())
                            <span
                                class="text-xs text-gray-500">{{ $sideRoom->memberable->messages()->whereRoomId($room->id)->latest()->first()->created_at->diffForHumans() }}</span>
                        @endif
                        <span
                            class="text-xs text-white bg-primary-500 rounded-full px-2 py-1 @if($room->unread_messages <= 0) hidden @endif">{{ $room->unread_messages }}</span>
                    </div>
                </div>
            @else
                <div class="flex justify-between py-4 border-gray-200 cursor-pointer
                        @if(isset($current_room) && ($current_room->id === $room->id)) border-b-1 bg-gray-50 @else border-b-2 @endif"
                     wire:click="selectRoom({{ $room->id }})">
                    <div class="relative flex items-center space-x-4 ml-5">
                        <div class="relative">
                            <img
                                src="{{ $sideRoom->memberable->avatar ?? 'https://ui-avatars.com/api/?name=' . urlencode(__('attributes.deleted_user')) . '&color=FFFFFF&background=111827' }}"
                                alt=""
                                class="w-10 lg:w-11 h-10 lg:h-11 rounded-full">
                        </div>
                        <div class="lg:flex flex-col leading-tight hidden">
                            <div class="text-lg mt-1 flex items-center">
                                <span
                                    class="text-gray-700 mr-3">{{ $sideRoom->memberable->full_name ?? __('attributes.deleted_user') }}</span>
                            </div>
                            <div class="text-sm text-gray-600">
                                <span class="mr-3">{{ $sideRoom->memberable->email ?? __('attributes.deleted_user') }}</span>
                            </div>
                        </div>
                    </div>
                    <div class="lg:flex flex-col items-center space-y-4 mr-5">
                        @if($room->messages()->whereRoomId($room->id)->exists())
                            <span
                                class="text-xs text-gray-500">{{ $room->messages()->whereRoomId($room->id)->latest()->first()->created_at->diffForHumans() }}</span>
                        @endif
                        <span
                            class="text-xs text-white bg-primary-500 rounded-full px-2 py-1 @if($room->unread_messages <= 0) hidden @endif">{{ $room->unread_messages }}</span>
                    </div>
                </div>
            @endif
        @endif
    @endforeach
</div>
