@php
    $color = request()->routeIs('filament.pages.chat') || request()->routeIs('agent.pages.chat') || request()->routeIs('tour-guide.pages.chat');
@endphp

<a href="{{ route($route) }}">
    <x-filament::icon-button
        :label="__('Chat')"
        icon="heroicon-o-chat-alt-2"
        :color="$color ? 'primary' : 'secondary'"
        class="ml-2 -mr-1 rtl:-ml-1 rtl:mr-4"
    />
</a>

