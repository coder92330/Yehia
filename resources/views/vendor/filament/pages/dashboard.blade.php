<x-filament::page class="filament-dashboard-page">

    @if(isset($alerts) && count($alerts) > 0)
        @each('filament.partials.alerts', $alerts, 'alert')
    @elseif(isset($alert))
        @includeWhen($alert, 'filament.partials.alerts', $alert)
    @endif

    <x-filament::widgets
        :widgets="$this->getWidgets()"
        :columns="$this->getColumns()"
    />
</x-filament::page>
