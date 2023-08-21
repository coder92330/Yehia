<x-filament::widget class="filament-account-widget">
    <x-filament::card>
        @php
            $user = \Filament\Facades\Filament::auth()->user();
        @endphp

        <div class="h-12 flex items-center space-x-4 rtl:space-x-reverse">
            <div
                @class([
                    'w-10 h-10 rounded-full bg-gray-200 bg-cover bg-center',
                    'dark:bg-gray-900' => config('filament.dark_mode'),
                ])
                style="background-image: url('{{ $user->package?->logo ?? $user->company->logo }}')"
            ></div>

            @if($user->package)
                <div>
                    <h2 class="text-lg sm:text-xl font-bold tracking-tight">
                        {{ __('filament::widgets/package.package_info', ['package_name' => $user->package?->name]) }}
                    </h2>
                    <p class="text-sm sm:text-base text-gray-600 dark:text-gray-400">
                        {{ __('filament::widgets/package.package_info_description', [
                            'duration_name' => $user->package?->duration_name,
                            'end_at' => $user->package?->end_at->diffForHumans(),
                        ]) }}
                    </p>
                </div>
            @else
                <div>
                    <h2 class="text-lg sm:text-xl font-bold tracking-tight">
                        {{ __('filament::widgets/information.agent.title', ['app' => config('app.name')]) }}
                    </h2>

                    <p class="text-sm sm:text-base text-gray-600 dark:text-gray-400">
                        {{ __('filament::widgets/information.agent.subtitle', ['app' => config('app.name')]) }}
                    </p>
                </div>
            @endif
        </div>
    </x-filament::card>
</x-filament::widget>
