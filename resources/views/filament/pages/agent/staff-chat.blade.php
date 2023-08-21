@if(count($staffs) > 0 || !empty($search))
    <x-filament::page>
        <div class="grid grid-cols-11 bg-white">
            @include('filament.pages.agent.aside._staff_chat_sidebar')
            @include('filament.pages.agent.aside._staff_chat_body')
        </div>
    </x-filament::page>
@else
    <div class="flex flex-col items-center justify-center h-screen bg-white">
        <div class="text-center">
            <h1 class="text-3xl font-bold text-gray-700">{{ __('messages.chat.no_chat_selected') }}</h1>
            <p class="text-gray-500">{{ __('messages.chat.waiting_for_any_chat') }}</p>
        </div>
    </div>
@endif

{{-- Styles --}}
@push('styles')
    <style>
        .scrollbar-w-2::-webkit-scrollbar {
            width: 0.25rem;
            height: 0.25rem;
        }

        .scrollbar-track-blue-lighter::-webkit-scrollbar-track {
            --bg-opacity: 1;
            background-color: #f7fafc;
            background-color: rgba(247, 250, 252, var(--bg-opacity));
        }

        .scrollbar-thumb-blue::-webkit-scrollbar-thumb {
            --bg-opacity: 1;
            background-color: #edf2f7;
            background-color: rgba(237, 242, 247, var(--bg-opacity));
        }

        .scrollbar-thumb-rounded::-webkit-scrollbar-thumb {
            border-radius: 0.25rem;
        }
    </style>
@endpush
