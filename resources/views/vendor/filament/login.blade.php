<form wire:submit.prevent="authenticate" class="space-y-8">
    {{ $this->form }}

    @if(!\Route::is('filament.auth.login'))
        <div class="w-full flex justify-center">
            <x-filament::button type="submit" form="authenticate" class="w-1/2"
                @style(['background-color: #3B86FF; color: white; border: 1px solid #3b82f6; hover:background-color: #3b82f6; hover:border: 1px solid #3b82f6;'])>
                {{ __('filament::login.buttons.submit.label') }}
            </x-filament::button>
        </div>
    @else
        <x-filament::button type="submit" form="authenticate" class="w-full">
            {{ __('filament::login.buttons.submit.label') }}
        </x-filament::button>
    @endif

    {{-- Tour Guide login instead of Filament login --}}
    @if(\Route::is('agent.auth.login'))
        <div class="w-full flex justify-center">
            <h1 class="text-sm font-bold tracking-tight text-center text-gray-500">{{ __('frontend.pleaseUse', ['name' => __('frontend.tourguide')]) }}</h1>
            <a href="{{ route('tour-guide.auth.login') }}"
                @class([
                     'text-sm text-blue-400 hover:text-blue-600',
                     'ml-1' => app()->getLocale() === 'en',
                     'mr-1' => app()->getLocale() === 'ar',
                ])>
                {{ __('frontend.tourguide_login')}}
            </a>
        </div>
    @else
        <div class="w-full flex justify-center">
            <h1 class="text-sm font-bold tracking-tight text-center text-gray-500">{{ __('frontend.pleaseUse', ['name' => __('frontend.travel_agent')]) }}</h1>
            <a href="{{ route('agent.auth.login') }}"
                @class([
                     'text-sm text-blue-400 hover:text-blue-600',
                     'ml-1' => app()->getLocale() === 'en',
                     'mr-1' => app()->getLocale() === 'ar',
                ])>
                {{ __('frontend.travel_agent_login')}}
            </a>
        </div>
    @endif
</form>
