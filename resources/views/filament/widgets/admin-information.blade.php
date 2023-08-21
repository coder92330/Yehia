<x-filament::widget>
    <x-filament::card>
        <div class="h-12 flex items-center space-x-4 rtl:space-x-reverse">
            <div
                @class([
                    'w-10 h-10 rounded-full bg-gray-200 bg-cover bg-center',
                    'dark:bg-gray-900' => config('filament.dark_mode'),
                ])
                style="background-image: url('{{ asset('images/logo.png') ?? "https://ui-avatars.com/api/?name=" . urlencode(config('app.name')) . "&color=FFFFFF&background=111827" }}')"
            ></div>
            <div>
                <h2 class="text-lg sm:text-xl font-bold tracking-tight">
                    {{ __('filament::widgets/information.admin.title', ['app' => config('app.name')]) }}
                </h2>

                <p class="text-sm sm:text-base text-gray-600 dark:text-gray-400">
                    {{ __('filament::widgets/information.admin.subtitle', ['app' => config('app.name')]) }}
                </p>
            </div>
        </div>
    </x-filament::card>
</x-filament::widget>
