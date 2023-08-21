<x-filament::page>
    <div class="container mx-auto bg-white h-full">
        <div class="grid grid-cols-7 gap-6">
            <div class="p-6 lg:col-span-2 md:col-span-3 col-span-7">
                <div class="justify-center items-center">
                    <span class="text-gray-700 font-bold text-sm text-center">Filter By:</span>
                    <hr class="border-gray-200 mt-2">
                </div>
                <x-filament::form wire:submit.prevent="filterTourguides" class="mt-6">
                    {{ $this->form }}
                    <div class="flex justify-center mt-4">
                        <x-filament::button type="submit" class="text-white font-bold py-2 px-4"
                                            style="border-radius: 9999px;">Apply Filter
                        </x-filament::button>
                    </div>
                </x-filament::form>
            </div>
            <div class="flex flex-col justify-between lg:col-span-5 md:col-span-4 col-span-7">
                <div>
                    <div class="flex justify-center items-center mt-8 mb-4 py-2">
                        <span class="text-gray-700 text-2xl font-semibold text-center mr-2">Tour Guides</span>
                        <hr class="border-gray-200 w-[calc(100%-10rem)]">
                    </div>
                    @if($view === 'grid')
                        {{-- Grid View --}}
                        @include('filament.pages.agent.aside._tourguides_grid_view')
                    @else
                        {{-- List View --}}
                        @include('filament.pages.agent.aside._tourguides_list_view')
                    @endif
                </div>
                <div class="mt-4 mb-8">
                    @if(isset($tourguides->links))
                    @endif
                    <div class="flex justify-center space-x-2">
                        @if($tourguides instanceof \Illuminate\Pagination\LengthAwarePaginator)
                            {{ $tourguides->links() }}
                        @endif
                        <div
                            class="flex items-center space-x-2 filament-tables-pagination-records-per-page-selector rtl:space-x-reverse">
                            <label>
                                <select wire:model="perPage"
                                        class="h-8 text-sm pr-8 leading-none transition duration-75 border-gray-300 rounded-lg shadow-sm outline-none focus:border-primary-500 focus:ring-1 focus:ring-inset focus:ring-primary-500">
                                    <option value="12">12</option>
                                    <option value="15">15</option>
                                    <option value="18">18</option>
                                    <option value="21">21</option>
                                    <option value="24">24</option>
                                    <option value="all">All</option>
                                </select>
                                <span class="text-sm font-medium">per page</span>
                            </label>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-filament::page>

@push('scripts')
    <script>
        document.querySelectorAll('.chat-button').forEach(function (button) {
            button.addEventListener('click', function () {
                let tourguideId = button.dataset.tourguide_id;
                document.getElementById(`type_${tourguideId}`).addEventListener('change', function () {
                    if (this.value === 'event') {
                        document.getElementById(`events_${tourguideId}`).classList.remove('hidden');
                        document.getElementById(`events_${tourguideId}`).classList.add('block');
                        document.getElementById(`events_${tourguideId}`).required = true;
                        document.getElementById(`events_${tourguideId}`).previousElementSibling.classList.remove('hidden');
                    } else {
                        document.getElementById(`events_${tourguideId}`).classList.add('hidden');
                        document.getElementById(`events_${tourguideId}`).classList.remove('block');
                        document.getElementById(`events_${tourguideId}`).required = false;
                        document.getElementById(`events_${tourguideId}`).previousElementSibling.classList.add('hidden');
                    }
                });
            });
        });
    </script>
@endpush
