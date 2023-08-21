@if (filled($brand = config('filament.brand')))
    <div @class([
        'filament-brand text-xl font-bold tracking-tight flex items-center',
        'dark:text-white' => config('filament.dark_mode'),
    ])>
        <img src="{{ asset('images/logo.png') }}" alt="Flowbite" class="w-10 h-8 mr-2">
        <span>{{ $brand }}</span>
    </div>
@endif
