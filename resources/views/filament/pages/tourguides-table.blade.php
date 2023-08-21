<div class="px-4 grid lg:grid-cols-3 md:grid-cols-2 sm:grid-cols-1 gap-6">
    @forelse($records as $tourguide)
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
                                    class="text-xs text-gray-400 ">{{ $tourguide->country->name }}</span>
                            </div>
                        </div>
                    </div>
                </div>
                <p class="px-6 text-center py-4 font-normal text-sm text-gray-700 dark:text-gray-400">
                    &ldquo; Every hundred feet the world changes &ldquo;</p>
                <div class="border-t-2 px-6 py-2">
                    <div class="flex justify-between">
                        <a href="{{ route($edit_route, $tourguide->id) }}" class="bg-primary-600 text-sm text-white px-4 py-1 rounded-full">Edit</a>
                        <span class="inline-flex items-center text-xs">
                                                <svg aria-hidden="true" class="w-5 h-5 text-yellow-400"
                                                     fill="currentColor"
                                                     viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><title>First star</title><path
                                                        d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path></svg>
                                                <svg aria-hidden="true" class="w-5 h-5 text-yellow-400"
                                                     fill="currentColor"
                                                     viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><title>Second star</title><path
                                                        d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path></svg>
                                                <svg aria-hidden="true" class="w-5 h-5 text-yellow-400"
                                                     fill="currentColor"
                                                     viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><title>Third star</title><path
                                                        d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path></svg>
                                                <svg aria-hidden="true" class="w-5 h-5 text-yellow-400"
                                                     fill="currentColor"
                                                     viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><title>Fourth star</title><path
                                                        d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path></svg>
                                                <svg aria-hidden="true"
                                                     class="w-5 h-5 text-yellow-400 dark:text-gray-500"
                                                     fill="currentColor" viewBox="0 0 20 20"
                                                     xmlns="http://www.w3.org/2000/svg"><title>Fifth star</title><path
                                                        d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path></svg>
                                                (24)
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
