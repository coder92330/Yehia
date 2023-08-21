<!doctype html>
<html lang="{{ app()->getLocale() }}" class="scroll-smooth"
      style="direction: {{ app()->getLocale() === 'ar' ? 'rtl' : 'ltr' }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    @if ($favicon = config('filament.favicon'))
        <link rel="icon" href="{{ asset($favicon) }}">
    @endif

    <title>@yield('title', config('app.name'))</title>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/flowbite/1.6.5/datepicker.min.js"></script>

    <!-- Styles -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    @stack('styles')
</head>
<body>
@includeUnless(request()->mobile, 'includes.header')

@includeUnless(request()->mobile, 'includes.messages')

@yield('content')

@includeUnless(request()->mobile, 'includes.footer')

<!-- button -->
<button id="topButton"
        class="fixed z-10 hidden p-3 bg-gray-100 rounded-full shadow-md bottom-10 right-10 animate-bounce">
    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"
         xmlns="http://www.w3.org/2000/svg">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 10l7-7m0 0l7 7m-7-7v18">
        </path>
    </svg>
</button>

<script>
    // Get the button
    let mybutton = document.getElementById("topButton");

    // When the user scrolls down 20px from the top of the document, show the button
    window.onscroll = function () {
        scrollFunction()
    };

    function scrollFunction() {
        if (document.body.scrollTop > 20 || document.documentElement.scrollTop > 20) {
            mybutton.classList.remove("hidden");
        } else {
            mybutton.classList.add("hidden");
        }
    }

    // When the user clicks on the button, scroll to the top of the document
    mybutton.addEventListener("click", function () {
        window.scrollTo({
            top: 0,
            behavior: "smooth"
        });
    });
</script>

<!-- Scripts -->
@stack('scripts')

</body>
</html>
