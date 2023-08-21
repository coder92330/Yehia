<div class="relative overflow-x-auto shadow-md sm:rounded-lg mr-4">
    <table class="w-full text-sm text-left text-gray-500 dark:text-gray-400">
        <thead
            class="text-xs text-gray-700 uppercase bg-gray-100 dark:bg-gray-700 dark:text-gray-400">
        <tr>
            {{--                                    <th scope="col" class="p-4">--}}
            {{--                                        <div class="flex items-center">--}}
            {{--                                            <input id="checkbox-all-search" type="checkbox"--}}
            {{--                                                   class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 dark:focus:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600">--}}
            {{--                                            <label for="checkbox-all-search" class="sr-only">checkbox</label>--}}
            {{--                                        </div>--}}
            {{--                                    </th>--}}
            <th scope="col" class="px-6 py-3">Name</th>
            <th scope="col" class="px-6 py-3">Email</th>
            <th scope="col" class="px-6 py-3">Username</th>
            <th scope="col" class="px-6 py-3">Active</th>
            <th scope="col" class="px-6 py-3">Online</th>
            <th scope="col" class="px-6 py-3">Actions</th>
        </tr>
        </thead>
        <tbody>
        @foreach($tourguides as $tourguide)
            <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600">
                {{--                                    <td class="w-4 p-4">--}}
                {{--                                        <div class="flex items-center">--}}
                {{--                                            <input id="checkbox-table-search-1" type="checkbox"--}}
                {{--                                                   class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 dark:focus:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600">--}}
                {{--                                            <label for="checkbox-table-search-1" class="sr-only">checkbox</label>--}}
                {{--                                        </div>--}}
                {{--                                    </td>--}}
                <th scope="row"
                    class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white">{{ $tourguide->full_name }}</th>
                <td class="px-6 py-4">{{ $tourguide->email }}</td>
                <td class="px-6 py-4">{{ $tourguide->username }}</td>
                <td class="px-6 py-4">
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                            @if($tourguide->is_active) bg-green-100 text-green-800 @else bg-danger-100 text-danger-800 @endif">
                                                {{ $tourguide->is_active ? 'Active' : 'Inactive' }}
                                            </span>
                </td>
                <td class="px-6 py-4">
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                            @if($tourguide->is_online) bg-green-100 text-green-800 @else bg-danger-100 text-danger-800 @endif">
                                                {{ $tourguide->is_online ? 'Online' : 'Offline' }}
                                            </span>
                </td>
                <td class="px-6 py-4 space-x-1">
                    @if($tourguide->hasSetting('my_profile_added_to_favorites', true)->exists())
                        <button wire:click="toggleFavorites({{ $tourguide->id }})"
                                class="text-xs bg-danger-500 hover:bg-danger-600 text-white px-1.5 py-1.5 rounded-full inline-flex items-center">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                 stroke-width="1.5" stroke="currentColor"
                                 class="w-4 h-4 @if(auth('agent')->user()->favourites()->where(['favouritable_id' => $tourguide->id,'favouritable_type' => \App\Models\Tourguide::class])->exists()) fill-current @endif">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                      d="M21 8.25c0-2.485-2.099-4.5-4.688-4.5-1.935 0-3.597 1.126-4.312 2.733-.715-1.607-2.377-2.733-4.313-2.733C5.1 3.75 3 5.765 3 8.25c0 7.22 9 12 9 12s9-4.78 9-12z"/>
                            </svg>
                        </button>
                    @endif
                    @include('filament.pages.agent.aside._chat_modal')
                </td>
            </tr>
        @endforeach
        </tbody>
    </table>
</div>
