<x-filament::form wire:submit.prevent="onEditEventSubmit">
    <x-filament::modal id="fullcalendar--edit-event-modal" :width="$this->getModalWidth()">
        <x-slot name="header">
            <x-filament::modal.heading>
                {{ $this->getEditEventModalTitle() }}
            </x-filament::modal.heading>
        </x-slot>

        @if($this->isListeningCancelledEditModal())
            <div x-on:close-modal.window="if ($event.detail.id === 'fullcalendar--create-event-modal') Livewire.emit('cancelledFullcalendarEditEventModal')"></div>
        @endif

        {{ $this->editEventForm }}

        <x-slot name="footer">
            @if(!$this->editEventForm->isDisabled())
                <x-filament::button type="submit" form="onEditEventSubmit">
                    {{ $this->getEditEventModalSubmitButtonLabel() }}
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

<x-filament::form wire:submit.prevent="onDeleteEventSubmit">
    <x-filament::modal id="fullcalendar--delete-event-modal" :width="$this->getModalWidth()">
        <x-slot name="header">
            <x-filament::modal.heading>Delete Appointment</x-filament::modal.heading>
        </x-slot>

        <div class="mb-4 text-center">
            <p class="text-gray-600">Are you sure you want to delete this appointment?</p>
        </div>

        <x-slot name="footer">
            <x-filament::button type="submit" form="onDeleteEventSubmit" color="danger">Delete</x-filament::button>
            <x-filament::button color="secondary" x-on:click="isOpen = false">
                {{ $this->getEditEventModalCloseButtonLabel() }}
            </x-filament::button>
        </x-slot>
    </x-filament::modal>
</x-filament::form>
