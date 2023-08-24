@php
    $slider        = $page->where('key', 'Slider')->first();
    $about_us      = $page->where('key', 'About Us')->first();
    $our_servicres = $page->where('key', 'Our Services')->first();
    $divider       = $page->where('key', 'Divider')->first();
@endphp

@extends('layouts.default')

@section('title', __('frontend.home'))

@section('content')
    {{-- Hero Section --}}
    @include('partials._hero')

    {{-- Guset View Login Section --}}
    @if(!(Auth::check() || Auth::guard('agent')->check() || Auth::guard('tourguide')->check()))
        <div
            class="relative flex items-center justify-center lg:w-[70%] w-[85%] mx-auto lg:-mt-52 -mt-[8rem] lg:-mb-24 -mb-40 gap-4 z-40"
            id="login">
            <div
                class="flex flex-col justify-center items-center text-center w-full">
                <div class="grid lg:grid-cols-3 md:grid-cols-2 grid-cols-1 gap-4">
                    <div class="max-w-sm overflow-hidden shadow-lg rounded-xl bg-white">
                        <div class="w-full h-[50%]">
                            <img class="w-full h-full object-cover"
                                 src="{{ asset('images/tourguide_login_section.jpg') }}"
                                 alt="{{ $slider->contents()->firstWhere('name->' . app()->getLocale(), 'slider_tourguide')->title }}">
                        </div>
                        <div class="px-8 py-4 mb-8 lg:h-[10rem] md:h-[20rem] h-[20rem]">
                            <h1 class="text-3xl text-gray-500 font-bold mb-6">{{ $slider->contents()->firstWhere('name->' . app()->getLocale(), 'slider_tourguide')->title }}</h1>
                            <!--<p class="text-gray-700 mb-6">{{ $slider->contents()->firstWhere('name->' . app()->getLocale(), 'slider_tourguide')->content }}</p>-->
                            <a href="{{ route('tour-guide.auth.login') }}"
                               class="bg-gray-500 hover:bg-gray-700 text-white font-normal py-2 px-4 rounded-full w-[calc(100%-4rem)]">
                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor"
                                     class="w-5 h-5 inline-block">
                                    <path fill-rule="evenodd"
                                          d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-5.5-2.5a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0zM10 12a5.99 5.99 0 00-4.793 2.39A6.483 6.483 0 0010 16.5a6.483 6.483 0 004.793-2.11A5.99 5.99 0 0010 12z"
                                          clip-rule="evenodd"/>
                                </svg>
                                {{ $slider->contents()->firstWhere('name->' . app()->getLocale(), 'slider_tourguide')->button_text }}
                            </a>
                        </div>
                    </div>
                    <div class="max-w-sm overflow-hidden shadow-lg rounded-xl bg-white">
                        <div class="w-full h-[50%]">
                            <img class="w-full h-full object-cover"
                                 src="{{ asset('images/training_programs_section.jpg') }}"
                                 alt="{{ $slider->contents()->firstWhere('name->' . app()->getLocale(), 'slider_ourservice')->title }}">
                        </div>
                        <div class="px-6 py-4 mb-8 lg:h-[10rem] md:h-[20rem] h-[20rem]">
                            <h1 class="text-3xl text-gray-500 font-bold mb-6">{{ $slider->contents()->firstWhere('name->' . app()->getLocale(), 'slider_ourservice')->title }}</h1>
                            <!--<p class="text-gray-700 mb-6">{{ $slider->contents()->firstWhere('name->' . app()->getLocale(), 'slider_ourservice')->content }}</p>-->
                            <a href="#services"
                               class="bg-gray-500 hover:bg-gray-700 text-white font-normal py-2 px-4 rounded-full w-[calc(100%-4rem)]">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                     stroke-width="1.5" stroke="currentColor" class="w-5 h-5 inline">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                          d="M2.036 12.322a1.012 1.012 0 010-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178z"/>
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                          d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                </svg>
                                {{ $slider->contents()->firstWhere('name->' . app()->getLocale(), 'slider_ourservice')->button_text }}
                            </a>
                        </div>
                    </div>
                    <div class="max-w-sm overflow-hidden shadow-lg rounded-xl bg-white">
                        <div class="w-full h-[50%]">
                            <img class="w-full h-full object-cover" src="{{ asset('images/agent_login_section.jpg') }}"
                                 alt="{{ $slider->contents()->firstWhere('name->' . app()->getLocale(), 'slider_agent')->title }}">
                        </div>
                        <div class="px-6 py-4 mb-8 lg:h-[10rem] md:h-[20rem] h-[20rem]">
                            <h1 class="text-3xl text-gray-500 font-bold mb-6">{{ $slider->contents()->firstWhere('name->' . app()->getLocale(), 'slider_agent')->title }}</h1>
                            <!--<p class="text-gray-700 mb-6">{{ $slider->contents()->firstWhere('name->' . app()->getLocale(), 'slider_agent')->content }}</p>-->
                            <a href="{{ route('agent.auth.login') }}"
                               class="bg-gray-500 hover:bg-gray-700 text-white font-normal py-2 px-4 rounded-full w-[calc(100%-4rem)]">
                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor"
                                     class="w-5 h-5 inline-block">
                                    <path fill-rule="evenodd"
                                          d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-5.5-2.5a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0zM10 12a5.99 5.99 0 00-4.793 2.39A6.483 6.483 0 0010 16.5a6.483 6.483 0 004.793-2.11A5.99 5.99 0 0010 12z"
                                          clip-rule="evenodd"/>
                                </svg>
                                {{ $slider->contents()->firstWhere('name->' . app()->getLocale(), 'slider_agent')->button_text }}
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif

    {{-- About Us Section --}}
    <section class="bg-white pt-64 pb-36" id="about">
        <div class="grid lg:grid-cols-2 grid-cols-1 lg:gap-20 md:gap-20 gap-28 px-4 mx-auto max-w-screen-xl">
            <div class="flex flex-col justify-center items-center space-y-4">
                <div class="grid grid-cols-2 gap-4">
                    <div class="h-full">
                        <img
                            src="{{ $about_us->contents()->latest()->first()->about_us[0] ?? asset('images/abouts-4.jpeg') }}"
                            alt="young-girl-red-dress-visiting-egyptian-temple-nefertari-near-abu-simbel"
                            class="max-w-full h-full object-cover">
                    </div>
                    <div class="grid grid-rows-2 gap-4">
                        <div class="row-span-1">
                            <img
                                src="{{ $about_us->contents()->latest()->first()->about_us[1] ?? asset('images/abouts-1.jpeg') }}"
                                alt="Gallery 2"
                                class="h-full w-full object-cover">
                        </div>
                        <div class="row-span-1">
                            <img
                                src="{{ $about_us->contents()->latest()->first()->about_us[2] ?? asset('images/abouts-2.jpeg') }}"
                                alt="Gallery 3"
                                class="h-full w-full object-cover">
                        </div>
                    </div>
                </div>
            </div>
            <div class="flex flex-col justify-center">
                <h1 class="-mt-20 mb-4 text-xl font-bold tracking-tight leading-none text-gray-500">{{ __('frontend.about_us') }}</h1>
                <h1 class="text-4xl font-bold tracking-tight text-gray-900 md:text-4xl lg:text-5xl">
                    {{ $about_us->contents()->latest()->first()->title }}</h1>
                <p class="mt-4 text-gray-500">{{ $about_us->contents()->latest()->first()->content }}</p>
                <a href="{{ $about_us->contents()->latest()->first()->button_url }}"
                   class="hidden mt-8 bg-gray-500 hover:bg-gray-700 text-white font-bold py-4 px-4 rounded-full text-center lg:w-[calc(100%-22rem)]
                    md:w-[calc(100%-18rem)] w-[calc(100%-10rem)]">
                    {{ $about_us->contents()->latest()->first()->button_text }}
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor"
                         class="w-5 h-5 inline-block">
                        <path fill-rule="evenodd"
                              d="M5 10a.75.75 0 01.75-.75h6.638L10.23 7.29a.75.75 0 111.04-1.08l3.5 3.25a.75.75 0 010 1.08l-3.5 3.25a.75.75 0 11-1.04-1.08l2.158-1.96H5.75A.75.75 0 015 10z"
                              clip-rule="evenodd"/>
                    </svg>
                </a>
            </div>
        </div>
    </section>

    {{-- Services Section --}}
    <section class="bg-gray-100 py-32" id="services">
        <div class="container lg:px-32 md:px-16 px-4 mx-auto">
            <div class="flex flex-col justify-center items-center text-center">
                <h1 class="text-4xl font-bold">{{ $our_servicres->contents()->latest()->first()->title }}</h1>
                <hr class="w-20 mx-auto my-8 h-1 border-gray-800">
                <p class="text-center lg:w-[calc(100%-30rem)] md:w-[calc(100%-20rem)] w-[calc(100%-10rem)]">
                    {{ $our_servicres->contents()->latest()->first()->content }}
                </p>
                <div class="mt-16 grid lg:grid-cols-3 lg:gap-16 md:grid-cols-3 md:gap-12 gap-8 text-center">
                    @if($services)
                        @foreach($services as $service)
                            <div class="flex flex-col justify-center items-center">
                                <div class="rounded-full bg-white w-24 h-24 flex justify-center items-center">
                                    @if($service->image)
                                        <img src="{{ asset($service->image) }}" alt="{{ $service->title }}"
                                             class="object-cover w-full h-full rounded-full">
                                    @else
                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                             stroke-width="1.5"
                                             stroke="currentColor" class="w-11 h-11 text-sky-500">
                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                  d="M20.25 14.15v4.25c0 1.094-.787 2.036-1.872 2.18-2.087.277-4.216.42-6.378.42s-4.291-.143-6.378-.42c-1.085-.144-1.872-1.086-1.872-2.18v-4.25m16.5 0a2.18 2.18 0 00.75-1.661V8.706c0-1.081-.768-2.015-1.837-2.175a48.114 48.114 0 00-3.413-.387m4.5 8.006c-.194.165-.42.295-.673.38A23.978 23.978 0 0112 15.75c-2.648 0-5.195-.429-7.577-1.22a2.016 2.016 0 01-.673-.38m0 0A2.18 2.18 0 013 12.489V8.706c0-1.081.768-2.015 1.837-2.175a48.111 48.111 0 013.413-.387m7.5 0V5.25A2.25 2.25 0 0013.5 3h-3a2.25 2.25 0 00-2.25 2.25v.894m7.5 0a48.667 48.667 0 00-7.5 0M12 12.75h.008v.008H12v-.008z"/>
                                        </svg>
                                    @endif
                                </div>
                                <h1 class="text-xl font-bold mt-4">{{ $service->title }}</h1>
                                <p class="text-center mt-4">{{ $service->content }}</p>
                            </div>
                        @endforeach
                    @endif
                </div>
                <div class="flex justify-center items-center w-full">
                    <a href="#"
                       class="hidden mt-16 bg-gray-500 hover:bg-gray-700 text-white font-bold py-4 px-8 rounded-full text-center">{{ __('frontend.read_more') }}
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor"
                             class="w-5 h-5 inline-block">
                            <path fill-rule="evenodd"
                                  d="M5 10a.75.75 0 01.75-.75h6.638L10.23 7.29a.75.75 0 111.04-1.08l3.5 3.25a.75.75 0 010 1.08l-3.5 3.25a.75.75 0 11-1.04-1.08l2.158-1.96H5.75A.75.75 0 015 10z"
                                  clip-rule="evenodd"/>
                        </svg>
                    </a>
                </div>
            </div>
        </div>
    </section>

    {{-- Image Section --}}
    {{--    <section--}}
    {{--        class="container relative flex flex-wrap bg-white mx-auto lg:h-60 lg:items-center lg:px-32 lg:py-0 pt-8 gap-y-10 hidden">--}}
    {{--        <div class="w-full px-4 sm:px-6 lg:w-1/2 lg:px-8">--}}
    {{--            <div>--}}
    {{--                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor"--}}
    {{--                     class="w-6 h-6 tex inline-block text-yellow-400">--}}
    {{--                    <path fill-rule="evenodd"--}}
    {{--                          d="M10.788 3.21c.448-1.077 1.976-1.077 2.424 0l2.082 5.007 5.404.433c1.164.093 1.636 1.545.749 2.305l-4.117 3.527 1.257 5.273c.271 1.136-.964 2.033-1.96 1.425L12 18.354 7.373 21.18c-.996.608-2.231-.29-1.96-1.425l1.257-5.273-4.117-3.527c-.887-.76-.415-2.212.749-2.305l5.404-.433 2.082-5.006z"--}}
    {{--                          clip-rule="evenodd"/>--}}
    {{--                </svg>--}}
    {{--                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor"--}}
    {{--                     class="w-6 h-6 tex inline-block text-yellow-400">--}}
    {{--                    <path fill-rule="evenodd"--}}
    {{--                          d="M10.788 3.21c.448-1.077 1.976-1.077 2.424 0l2.082 5.007 5.404.433c1.164.093 1.636 1.545.749 2.305l-4.117 3.527 1.257 5.273c.271 1.136-.964 2.033-1.96 1.425L12 18.354 7.373 21.18c-.996.608-2.231-.29-1.96-1.425l1.257-5.273-4.117-3.527c-.887-.76-.415-2.212.749-2.305l5.404-.433 2.082-5.006z"--}}
    {{--                          clip-rule="evenodd"/>--}}
    {{--                </svg>--}}
    {{--                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor"--}}
    {{--                     class="w-6 h-6 tex inline-block text-yellow-400">--}}
    {{--                    <path fill-rule="evenodd"--}}
    {{--                          d="M10.788 3.21c.448-1.077 1.976-1.077 2.424 0l2.082 5.007 5.404.433c1.164.093 1.636 1.545.749 2.305l-4.117 3.527 1.257 5.273c.271 1.136-.964 2.033-1.96 1.425L12 18.354 7.373 21.18c-.996.608-2.231-.29-1.96-1.425l1.257-5.273-4.117-3.527c-.887-.76-.415-2.212.749-2.305l5.404-.433 2.082-5.006z"--}}
    {{--                          clip-rule="evenodd"/>--}}
    {{--                </svg>--}}
    {{--                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor"--}}
    {{--                     class="w-6 h-6 tex inline-block text-yellow-400">--}}
    {{--                    <path fill-rule="evenodd"--}}
    {{--                          d="M10.788 3.21c.448-1.077 1.976-1.077 2.424 0l2.082 5.007 5.404.433c1.164.093 1.636 1.545.749 2.305l-4.117 3.527 1.257 5.273c.271 1.136-.964 2.033-1.96 1.425L12 18.354 7.373 21.18c-.996.608-2.231-.29-1.96-1.425l1.257-5.273-4.117-3.527c-.887-.76-.415-2.212.749-2.305l5.404-.433 2.082-5.006z"--}}
    {{--                          clip-rule="evenodd"/>--}}
    {{--                </svg>--}}
    {{--                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor"--}}
    {{--                     class="w-6 h-6 tex inline-block text-yellow-400">--}}
    {{--                    <path fill-rule="evenodd"--}}
    {{--                          d="M10.788 3.21c.448-1.077 1.976-1.077 2.424 0l2.082 5.007 5.404.433c1.164.093 1.636 1.545.749 2.305l-4.117 3.527 1.257 5.273c.271 1.136-.964 2.033-1.96 1.425L12 18.354 7.373 21.18c-.996.608-2.231-.29-1.96-1.425l1.257-5.273-4.117-3.527c-.887-.76-.415-2.212.749-2.305l5.404-.433 2.082-5.006z"--}}
    {{--                          clip-rule="evenodd"/>--}}
    {{--                </svg>--}}
    {{--            </div>--}}
    {{--            <h1 class="text-4xl font-bold mt-4 text-gray-600">{{ $divider->contents()->latest()->first()->title ?? 'Cruise Around the World' }}</h1>--}}
    {{--            <p class="mt-4">{{ $divider->contents()->latest()->first()->title ?? 'All inclusive packages for your next trip' }}</p>--}}
    {{--        </div>--}}
    {{--        <div class="relative h-64 w-full sm:h-96 lg:h-full lg:w-1/2">--}}
    {{--            <img--}}
    {{--                src="{{ $divider->divider ?? asset('images/ship.jpg') }}"--}}
    {{--                alt="{{ $divider->contents()->latest()->first()->title ?? 'Divider Image' }}"--}}
    {{--                class="w-full h-full object-fit">--}}
    {{--        </div>--}}
    {{--    </section>--}}

    {{-- Testimonials Section --}}
    <section class="bg-gray-100 hidden" id="testimonials">
        <div class="container mx-auto lg:px-32 lg:py-20 lg:w-[80%] py-12 text-center">
            <h1 class="text-4xl font-bold text-center text-gray-600">{{ __('frontend.testimonials') }}</h1>
            <hr class="w-20 mx-auto mt-4 mb-8 h-1 border-gray-400">
            <section class="h-full">
                <div class="mx-auto max-w-screen-xl px-4 py-4 sm:px-6 lg:px-8">
                    <div class="swiper-container !overflow-hidden">
                        <div class="swiper-wrapper">
                            @foreach($testimonials as $testimonial)
                                <div class="swiper-slide">
                                    <blockquote class="bg-white p-8">
                                        <div class="items-center gap-4">
                                            <img alt="{{ $testimonial->name }}" src="{{ $testimonial->avatar }}"
                                                 class="h-16 w-16 rounded-full object-cover mx-auto mb-4"/>
                                            <div class="text-sm">
                                                <p class="text-lg text-blue-400 font-semibold">{{ $testimonial->name }}</p>
                                            </div>
                                        </div>

                                        <p class="relative mt-4 text-gray-500">
                                            <span class="text-xl">&ldquo;</span>

                                            {{ $testimonial->content }}

                                            <span class="text-xl">&rdquo;</span>
                                        </p>
                                    </blockquote>
                                </div>
                            @endforeach
                        </div>
                        <div class="swiper-pagination !relative !bottom-0 mt-12"></div>
                    </div>
                </div>
            </section>
        </div>
    </section>
@endsection

@push('styles')
    <link href="https://unpkg.com/swiper/swiper-bundle.min.css" rel="stylesheet"/>
    <style>
    .mb-8.font-normal.text-center.text-gray-300.lg\:text-md.sm\:px-16.lg\:px-48 {
  display: none;
}
    p.text-gray-700.mb-6{
    opacity: 0;
    margin-bottom: .2rem !important;
    padding-bottom: .2rem !important;
    }
    .relative .py-4.mb-8.lg\:h-\[10rem\].md\:h-\[20rem\].h-\[20rem\]{
        margin-top: .75rem;
    }
    .text-center.mt-4{
        min-height: 72px;
    }
    .grid .flex.flex-col.justify-center.items-center{
        display: inline-block;
    }
    .rounded-full.bg-white.w-24.h-24.flex.justify-center.items-center{
        margin: 0 auto;
    }
    
    @media (min-width: 1024px) {
  .max-w-sm.overflow-hidden.shadow-lg.rounded-xl.bg-white {
  height: 24rem;
}
}

@media (min-width: 768px){
      .max-w-sm.overflow-hidden.shadow-lg.rounded-xl.bg-white {
  height: 24rem;
}
}
.bg-gray-500.hover\:bg-gray-700.text-white.font-normal.py-2.px-4.rounded-full.w-\[calc\(100\%-4rem\)\]{
    padding-bottom: .75rem;
line-height: 2.5em;
padding-left: 1.5rem;
padding-right: 1.5rem;
}
</style>

@endpush


@push('scripts')
    <script src="https://unpkg.com/swiper/swiper-bundle.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            new Swiper('.swiper-container', {
                loop: true,
                slidesPerView: 1,
                spaceBetween: 20,
                autoplay: {
                    delay: 8000,
                },
                pagination: {
                    el: '.swiper-pagination',
                    clickable: true,
                },
                breakpoints: {
                    640: {
                        slidesPerView: 1.5,
                    },
                    1024: {
                        slidesPerView: 3,
                    },
                },
            })
        })
    </script>
@endpush
