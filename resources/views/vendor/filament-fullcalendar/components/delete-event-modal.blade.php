<x-filament::form wire:submit.prevent="onDeleteEventSubmit">
    <x-filament::modal id="fullcalendar--delete-event-modal" :width="$this->getModalWidth()">
        <x-slot name="header">
            <x-filament::modal.heading>
                {{ $this->getDeleteEventModalTitle() }}
            </x-filament::modal.heading>
        </x-slot>

        {{-- Confirm delete event --}}
        <div class="mb-4">
            <p class="text-gray-600">
                {{ $this->getDeleteEventModalConfirmMessage() }}
            </p>
        </div>

        <x-slot name="footer">
            @if(!$this->editEventForm->isDisabled())
                <x-filament::button type="submit" form="onDeleteEventSubmit">
                    {{ $this->getDeleteEventModalSubmitButtonLabel() }}
                </x-filament::button>
            @endif

            @if($this->isListeningCancelledEditModal())
                <x-filament::button color="secondary"
                                    x-on:click="isOpen = false; Livewire.emit('cancelledFullcalendarEditEventModal')">
                    {{ $this->getEditEventModalCloseButtonLabel() }}
                </x-filament::button>
            @else
                <x-filament::button color="secondary" x-on:click="isOpen = false">
                    {{ $this->getEditEventModalCloseButtonLabel() }}
                </x-filament::button>
            @endif
        </x-slot>
    </x-filament::modal>
</x-filament::form>
