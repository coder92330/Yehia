<x-filament::page>
    @if((!empty($rooms) && count($rooms) > 0) || !empty($search))
        <div class="grid grid-cols-11 bg-white">@yield('content')</div>
    @else
        <div class="flex flex-col items-center justify-center h-screen bg-white">
            <div class="text-center">
                <h1 class="text-3xl font-bold text-gray-700">{{ __('messages.chat.no_chat_yet') }}</h1>
                <p class="text-gray-500">@yield('no_chat_description', __('messages.chat.no_chat_yet_description'))</p>
            </div>
        </div>
    @endif
</x-filament::page>

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

@push('scripts')
    <script>
        const el = document.getElementById('messages')
        el.scrollTop = el.scrollHeight

        import Echo from 'laravel-echo';
        import Pusher from 'pusher-js';

        window.Pusher = Pusher;
        window.Echo = new Echo({
            broadcaster: 'pusher',
            key: import.meta.env.VITE_PUSHER_APP_KEY,
            cluster: import.meta.env.VITE_PUSHER_APP_CLUSTER,
            forceTLS: true
        });
    </script>
@endpush
