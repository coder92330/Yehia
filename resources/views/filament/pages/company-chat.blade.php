@if((!empty($rooms) && count($rooms) > 0) || !empty($search))
    <x-filament::page>
        <div class="grid grid-cols-11 bg-white">
            @include('filament.pages.admin.aside._comapny_chat_sidebar')
            @include('filament.pages.admin.aside._comapny_chat_body')
        </div>
    </x-filament::page>
@else
    <div class="flex flex-col items-center justify-center h-screen bg-white">
        <div class="text-center">
            <h1 class="text-3xl font-bold text-gray-700">No messages yet!</h1>
            <p class="text-gray-500">You can start a conversation with your companies by go to companies list on
                sidebar and
                click new chat.</p>
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

{{-- Scripts --}}
<script>
    const el = document.getElementById('messages')
    el.scrollTop = el.scrollHeight

    // Echo Message
    window.onload = function () {
        @if(isset($room->room_id))
        Echo.private("room.{{ $room->room_id }}")
            .listen('MessageSent', (e) => {
                if (e.guard !== 'admin') {
                    const el = document.getElementById('messages')
                    el.scrollTop = el.scrollHeight

                    let audio = new Audio('{{ asset('sounds/message.wav') }}');
                    audio.play();
                }
            });
        @endif
    }
</script>
