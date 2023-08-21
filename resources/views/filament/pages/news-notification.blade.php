<x-filament::page>
    {{ $this->form }}

    <x-filament::button wire:click="sendNotification" wire:loading.attr="disabled">
        {{ __('Send Notification') }}
    </x-filament::button>
</x-filament::page>
