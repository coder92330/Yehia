<x-filament::page xmlns:x-filament="http://www.w3.org/1999/html">
    <section class="bg-white dark:bg-gray-800">
        <div class="relative">
            <div class="h-80 bg-cover bg-center" style="background-image: url('{{ $record->cover }}')"></div>
            <div
                class="absolute h-full flex justify-between items-center px-4 lg:top-28 lg:left-5 lg:right-3 md:top-20 md:left-3 md:right-2 top-2 left-2 right-1">
                <div class="flex items-center">
                    <img
                        class="border-4 border-white shadow object-cover object-center lg:h-55 lg:w-55 md:h-40 md:w-40 h-32 w-32"
                        src="{{ $record->logo }}" alt="Profile Photo">
                    <div class="ml-4">
                        <h1 class="text-2xl font-normal text-white">{{ $record->name }}</h1>
                        <p class="text-gray-300 tex-md">{{ $record->email }}</p>
                    </div>
                </div>
            </div>
        </div>
        <div class="mt-6 mb-4 border-b border-gray-200 dark:border-gray-700">
            <ul class="flex flex-wrap -mb-px text-sm font-medium text-center" id="myTab"
                data-tabs-toggle="#myTabContent" role="tablist">
                <li class="mr-2" role="presentation">
                    <button class="inline-block p-4 border-b-2 rounded-t-lg text-primary-500 border-primary-500"
                            id="describtion-tab" data-tabs-target="#about" type="button" role="tab"
                            aria-controls="about" aria-selected="false">About Company
                    </button>
                </li>
            </ul>
        </div>
        <div id="myTabContent">
            <div class="p-4 rounded-lg" id="about" role="tabpanel" aria-labelledby="about-tab">
                <p class="text-sm text-gray-500 dark:text-gray-400">{{ $record->description }}</p>
                <section class="my-5">
                    <h3 class="text-lg font-medium text-gray-500 border-b border-gray-200">Specialties</h3>
                    <p class="mt-3 max-w-full text-sm text-gray-500">{{ $record->specialties }}</p>
                </section>
                <section class="my-5">
                    <h3 class="text-lg font-medium text-gray-500 border-b border-gray-200">Contact</h3>
                    <div class="grid lg:grid-cols-2 gap-4 mt-3 grid-cols-1">
                        <div class="col-span-1 flex flex-row space-x-20">
                            <label for="phone" class="inline text-sm font-medium text-gray-700">Phone:</label>
                            <p class="mt-1 text-sm text-blue-400">{{ $record->phone }}</p>
                        </div>
                        <div class="col-span-1 flex flex-row space-x-20">
                            <label for="email" class="inline text-sm font-medium text-gray-700">Email:</label>
                            <p class="mt-1 text-sm text-sky-400">{{ $record->email }}</p>
                        </div>
                        <div class="col-span-1 flex flex-row space-x-14">
                            <label for="website" class="inline text-sm font-medium text-gray-700">Website:</label>
                            <p class="mt-1 text-sm text-gray-500">{{ $record->website }}</p>
                        </div>
                        <div class="col-span-1 flex flex-row space-x-14">
                            <label for="address" class="inline text-sm font-medium text-gray-700">Address:</label>
                            <p class="mt-1 text-sm text-gray-500">{{ $record->address }}</p>
                        </div>
                    </div>
                </section>
            </div>
        </div>
    </section>
</x-filament::page>
