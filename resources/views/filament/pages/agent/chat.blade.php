@extends('filament.layouts.base-chat')

@section('content')
    @include('filament.pages.agent.aside._chat_sidebar')
    @include('filament.pages.agent.aside._chat_body')
@endsection

@push('scripts')
    <script>
        window.onload = function () {
            @if(isset($room->id))
            Echo.private("room.{{ $room->id }}")
                .listen('MessageSent', (e) => {
                    if (e.guard !== 'agent') {
                        const el = document.getElementById('messages')
                        el.scrollTop = el.scrollHeight

                        let audio = new Audio('{{ asset('sounds/message.wav') }}');
                        console.log(audio)
                        audio.play();
                    }
                });
            @endif
        }
    </script>
@endpush
