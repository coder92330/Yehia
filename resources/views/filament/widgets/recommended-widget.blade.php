<x-filament::widget>
    <x-filament::card>
        <div class="h-12 flex items-center space-x-4 rtl:space-x-reverse">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-warning-500 fill-current" fill="true"
                 viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M12 2l3.09 6.89L22 9.5l-5 4.5 1.09 6.89L12 17.5 6.91 22l1.09-6.89L2 9.5l6.91-1.61L12 2z"/>
            </svg>
            <h2 class="text-lg sm:text-xl font-bold tracking-tight">
                {{ __('filament::widgets/information.recommended', ['by' => config('app.name')]) }}
            </h2>
        </div>
    </x-filament::card>
</x-filament::widget>
