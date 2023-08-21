@props([
    'title' => null,
    'width' => 'md',
    'header' => false,
    'footer' => false,
    'vite' => false,
    'name' => 'Tour Guide',
    'image' => 'login',
])

<x-filament::layouts.base :title="$title" :header="$header" :footer="$footer" :vite="$vite">
    <div class="flex items-center justify-between lg:px-24 py-2 bg-white mt-16 border-b-2 border-gray-100 md:px-8 px-4">
        <div class="flex items-center space-x-1">
            <div class="flex-shrink-0">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor"
                     class="w-4 h-4 text-gray-500">
                    <path
                        d="M11.47 3.84a.75.75 0 011.06 0l8.69 8.69a.75.75 0 101.06-1.06l-8.689-8.69a2.25 2.25 0 00-3.182 0l-8.69 8.69a.75.75 0 001.061 1.06l8.69-8.69z"/>
                    <path
                        d="M12 5.432l8.159 8.159c.03.03.06.058.091.086v6.198c0 1.035-.84 1.875-1.875 1.875H15a.75.75 0 01-.75-.75v-4.5a.75.75 0 00-.75-.75h-3a.75.75 0 00-.75.75V21a.75.75 0 01-.75.75H5.625a1.875 1.875 0 01-1.875-1.875v-6.198a2.29 2.29 0 00.091-.086L12 5.43z"/>
                </svg>
            </div>
            <div class="text-sm font-medium text-gray-500">
                / {{ $title }}
            </div>
        </div>
    </div>
    <section class="relative flex flex-wrap lg:h-screen lg:items-center bg-white">
        <div class="relative h-64 w-full sm:h-96 lg:h-full lg:w-1/2">
            <img alt="{{ $name }}" src="{{ asset("images/$image-login.jpg") }}"
                 class="absolute inset-0 h-full w-full object-cover"/>
        </div>
        <div class="w-full px-4 py-12 sm:px-6 sm:py-16 lg:w-1/2 lg:px-8 lg:py-24">
            <div class="mx-auto max-w-lg text-center">
                <h1 class="text-4xl font-bold tracking-tight text-center text-purple-dark">{{ __('frontend.signInGuidesNavigator') }}</h1>
                <p class="text-lg text-center text-gray-400 mt-2">{{ __('frontend.signInGuidesNavigatorDescription', ['name' => $name]) }}</p>
            </div>
            <div {{ $attributes }} class="mx-auto mt-8 mb-0 max-w-md space-y-4">
                {{ $slot }}
            </div>
        </div>
    </section>
    @livewire('notifications')
</x-filament::layouts.base>
