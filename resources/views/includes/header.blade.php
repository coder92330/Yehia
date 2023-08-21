@php
    $navbars = \App\Models\Navbar::with(['subNavbars' => fn($query) => $query->active()->orderBy('order')])->parent()->active()->orderBy('order')->get();
    $footer  = \App\Models\LandingPage\LandingPageKey::where('key', 'Footer')->first();
@endphp
<nav class="bg-white fixed w-full z-50 top-0 left-0 border-b border-gray-200 drop-shadow-xl">
    <div class="bg-dark">
        <div class="max-w-screen-xl flex flex-wrap items-center justify-between mx-auto p-1.5">
            <div class="flex-shrink-0">
                {{-- Phone Svg --}}
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                     stroke="currentColor" class="w-3.5 h-3.5 text-gray-400 mr-1 inline-block fill-current">
                    <path stroke-linecap="round" stroke-linejoin="round"
                          d="M2.25 6.75c0 8.284 6.716 15 15 15h2.25a2.25 2.25 0 002.25-2.25v-1.372c0-.516-.351-.966-.852-1.091l-4.423-1.106c-.44-.11-.902.055-1.173.417l-.97 1.293c-.282.376-.769.542-1.21.38a12.035 12.035 0 01-7.143-7.143c-.162-.441.004-.928.38-1.21l1.293-.97c.363-.271.527-.734.417-1.173L6.963 3.102a1.125 1.125 0 00-1.091-.852H4.5A2.25 2.25 0 002.25 4.5v2.25z"/>
                </svg>
               <!-- <span
                    class="text-sm text-gray-400">{{ __('frontend.call_us') . ' | ' . $footer?->contents()->firstWhere('name->' . app()->getLocale(), 'footer_contact_info_phone')?->content . ' - ' . $footer?->contents()->firstWhere('name->' . app()->getLocale(), 'footer_contact_info_phone_2')?->content }}</span> -->
                    
@if($footer?->contents()->firstWhere('name->' . app()->getLocale(), 'footer_contact_info_phone')?->content && $footer?->contents()->firstWhere('name->' . app()->getLocale(), 'footer_contact_info_phone_2')?->content)
    <span class="text-sm text-gray-400">
        <a href="tel:{{ $footer?->contents()->firstWhere('name->' . app()->getLocale(), 'footer_contact_info_phone')?->content }}">
            {{ $footer?->contents()->firstWhere('name->' . app()->getLocale(), 'footer_contact_info_phone')?->content }}
        </a> -
        <a href="tel:{{ $footer?->contents()->firstWhere('name->' . app()->getLocale(), 'footer_contact_info_phone_2')?->content }}">
            {{ $footer?->contents()->firstWhere('name->' . app()->getLocale(), 'footer_contact_info_phone_2')?->content }}
        </a>
    </span>
@else
    @if($footer?->contents()->firstWhere('name->' . app()->getLocale(), 'footer_contact_info_phone')?->content)
        <span class="text-sm text-gray-400">
            <a href="tel:{{ $footer?->contents()->firstWhere('name->' . app()->getLocale(), 'footer_contact_info_phone')?->content }}">
                {{ $footer?->contents()->firstWhere('name->' . app()->getLocale(), 'footer_contact_info_phone')?->content }}
            </a>
        </span>
    @endif
@endif

            </div>
            <div class="text-sm font-medium text-gray-400">
                <div class="flex">
                    <a href="{{ $footer?->contents()->firstWhere('name->' . app()->getLocale(), 'footer_social_media_facebook')?->content }}"
                       class="mr-4" target="_blank">
                        <svg xmlns="http://www.w3.org/2000/svg" data-name="Layer 1"
                             viewBox="0 0 24 24" id="facebook" class="w-3.5 h-3.5 inline fill-current text-gray-400">
                            <path
                                d="M15.12,5.32H17V2.14A26.11,26.11,0,0,0,14.26,2C11.54,2,9.68,3.66,9.68,6.7V9.32H6.61v3.56H9.68V22h3.68V12.88h3.06l.46-3.56H13.36V7.05C13.36,6,13.64,5.32,15.12,5.32Z"></path>
                        </svg>
                    </a>
                    <a href="{{ $footer?->contents()->firstWhere('name->' . app()->getLocale(), 'footer_social_media_instagram')?->content }}"
                       class="mr-4" target="_blank">
                        <svg xmlns="http://www.w3.org/2000/svg" width="2500" height="2500"
                             viewBox="0 0 2476 2476" id="instagram"
                             class="w-3.5 h-3.5 inline fill-current text-gray-400">
                            <path
                                d="M825.4 1238c0-227.9 184.7-412.7 412.6-412.7 227.9 0 412.7 184.8 412.7 412.7 0 227.9-184.8 412.7-412.7 412.7-227.9 0-412.6-184.8-412.6-412.7m-223.1 0c0 351.1 284.6 635.7 635.7 635.7s635.7-284.6 635.7-635.7-284.6-635.7-635.7-635.7S602.3 886.9 602.3 1238m1148-660.9c0 82 66.5 148.6 148.6 148.6 82 0 148.6-66.6 148.6-148.6s-66.5-148.5-148.6-148.5-148.6 66.5-148.6 148.5M737.8 2245.7c-120.7-5.5-186.3-25.6-229.9-42.6-57.8-22.5-99-49.3-142.4-92.6-43.3-43.3-70.2-84.5-92.6-142.3-17-43.6-37.1-109.2-42.6-229.9-6-130.5-7.2-169.7-7.2-500.3s1.3-369.7 7.2-500.3c5.5-120.7 25.7-186.2 42.6-229.9 22.5-57.8 49.3-99 92.6-142.4 43.3-43.3 84.5-70.2 142.4-92.6 43.6-17 109.2-37.1 229.9-42.6 130.5-6 169.7-7.2 500.2-7.2 330.6 0 369.7 1.3 500.3 7.2 120.7 5.5 186.2 25.7 229.9 42.6 57.8 22.4 99 49.3 142.4 92.6 43.3 43.3 70.1 84.6 92.6 142.4 17 43.6 37.1 109.2 42.6 229.9 6 130.6 7.2 169.7 7.2 500.3 0 330.5-1.2 369.7-7.2 500.3-5.5 120.7-25.7 186.3-42.6 229.9-22.5 57.8-49.3 99-92.6 142.3-43.3 43.3-84.6 70.1-142.4 92.6-43.6 17-109.2 37.1-229.9 42.6-130.5 6-169.7 7.2-500.3 7.2-330.5 0-369.7-1.2-500.2-7.2M727.6 7.5c-131.8 6-221.8 26.9-300.5 57.5-81.4 31.6-150.4 74-219.3 142.8C139 276.6 96.6 345.6 65 427.1 34.4 505.8 13.5 595.8 7.5 727.6 1.4 859.6 0 901.8 0 1238s1.4 378.4 7.5 510.4c6 131.8 26.9 221.8 57.5 300.5 31.6 81.4 73.9 150.5 142.8 219.3 68.8 68.8 137.8 111.1 219.3 142.8 78.8 30.6 168.7 51.5 300.5 57.5 132.1 6 174.2 7.5 510.4 7.5 336.3 0 378.4-1.4 510.4-7.5 131.8-6 221.8-26.9 300.5-57.5 81.4-31.7 150.4-74 219.3-142.8 68.8-68.8 111.1-137.9 142.8-219.3 30.6-78.7 51.6-168.7 57.5-300.5 6-132.1 7.4-174.2 7.4-510.4s-1.4-378.4-7.4-510.4c-6-131.8-26.9-221.8-57.5-300.5-31.7-81.4-74-150.4-142.8-219.3C2199.4 139 2130.3 96.6 2049 65c-78.8-30.6-168.8-51.6-300.5-57.5-132-6-174.2-7.5-510.4-7.5-336.3 0-378.4 1.4-510.5 7.5"></path>
                        </svg>
                    </a>
                    <a href="{{ $footer?->contents()->firstWhere('name->' . app()->getLocale(), 'footer_social_media_linkedin')?->content }}"
                       class="mr-4" target="_blank">
                        <svg xmlns="http://www.w3.org/2000/svg" width="2500" height="2389"
                             viewBox="0 5 1036 990" id="linkedin" class="w-3.5 h-3.5 inline fill-current text-gray-400">
                            <path
                                d="M0 120c0-33.334 11.667-60.834 35-82.5C58.333 15.833 88.667 5 126 5c36.667 0 66.333 10.666 89 32 23.333 22 35 50.666 35 86 0 32-11.333 58.666-34 80-23.333 22-54 33-92 33h-1c-36.667 0-66.333-11-89-33S0 153.333 0 120zm13 875V327h222v668H13zm345 0h222V622c0-23.334 2.667-41.334 8-54 9.333-22.667 23.5-41.834 42.5-57.5 19-15.667 42.833-23.5 71.5-23.5 74.667 0 112 50.333 112 151v357h222V612c0-98.667-23.333-173.5-70-224.5S857.667 311 781 311c-86 0-153 37-201 111v2h-1l1-2v-95H358c1.333 21.333 2 87.666 2 199 0 111.333-.667 267.666-2 469z"></path>
                        </svg>
                    </a>
                </div>
            </div>
        </div>
    </div>
    <div class="max-w-screen-xl flex flex-wrap items-center justify-between mx-auto p-2">
        <a href="{{ route('home') }}" class="flex items-center">
            <img src="{{ asset('images/logo.png') }}" alt="Flowbite" class="lg:w-16 lg:h-12 md:w-14 md:h-10 w-8 h-8">
            <span class="lg:text-lg md:text-md text-sm text-gray-800 ml-2">Guides Navigator</span>
        </a>
        <div class="flex md:order-2">
            @if(!(Auth::check() || Auth::guard('agent')->check() || Auth::guard('tourguide')->check()))
                <a href="{{ route('submit-form') }}"
                   class="text-white bg-gray-500 hover:bg-gray-700 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-4 py-2 text-center mr-3 md:mr-0">{{ __('frontend.submit_forms') }}</a>
            @endif
            @if(Auth::check() || Auth::guard('agent')->check() || Auth::guard('tourguide')->check())
                @php
                    $user = auth()->user() ?? auth('agent')->user() ?? auth('tourguide')->user();
                    $type = match (true) {
                        auth('agent')->check() => 'agent',
                        auth('tourguide')->check() => 'tour-guide',
                        default => 'filament'
                    };
                @endphp
                <div class="flex items-center md:order-2">
                    <button type="button"
                            class="flex mr-3 text-sm bg-gray-800 rounded-full md:mr-0 focus:ring-4 focus:ring-gray-300 dark:focus:ring-gray-600"
                            id="user-menu-button" aria-expanded="false" data-dropdown-toggle="user-dropdown"
                            data-dropdown-placement="bottom">
                        <img class="w-8 h-8 rounded-full" src="{{ asset($user->avatar) }}"
                             alt="{{ $user->full_name ?? $user->name }}">
                    </button>
                    <div
                        class="z-50 hidden my-4 text-base list-none bg-white divide-y divide-gray-100 rounded-lg shadow dark:bg-gray-700 dark:divide-gray-600"
                        id="user-dropdown">
                        <div class="px-4 py-3">
                                <span
                                    class="block text-sm text-gray-900 dark:text-white">{{ ucwords($user->full_name ?? $user->name) }}</span>
                            <span
                                class="block text-sm  text-gray-500 truncate dark:text-gray-400">{{ $user->email }}</span>
                        </div>
                        <ul class="py-2" aria-labelledby="user-menu-button">
                            <li>
                                <a href="{{ route("$type.pages.dashboard") }}"
                                    @class([
                                         "block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 dark:hover:bg-gray-600 dark:text-gray-200 dark:hover:text-white",
                                         "text-left" => app()->getLocale() === 'en',
                                         "text-right" => app()->getLocale() === 'ar',
                                    ])>{{ __('frontend.dashboard') }}</a>
                            </li>
                            <li>
                                <form class="w-full"
                                      action="{{ route($type === 'filament' ? "$type.auth.logout" : "$type.logout")  }}"
                                      method="POST">
                                    @csrf
                                    <button type="submit"
                                        @class([
                                            "w-full block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 dark:hover:bg-gray-600 dark:text-gray-200 dark:hover:text-white",
                                            "text-left" => app()->getLocale() === 'en',
                                            "text-right" => app()->getLocale() === 'ar',
                                        ])> {{ __('frontend.logout') }}
                                    </button>
                                </form>
                            </li>
                        </ul>
                    </div>
                    <button data-collapse-toggle="mobile-menu-2" type="button"
                            class="inline-flex items-center p-2 ml-1 text-sm text-gray-500 rounded-lg md:hidden hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-gray-200 dark:text-gray-400 dark:hover:bg-gray-700 dark:focus:ring-gray-600"
                            aria-controls="mobile-menu-2" aria-expanded="false">
                        <svg class="w-6 h-6" aria-hidden="true" fill="currentColor" viewBox="0 0 20 20"
                             xmlns="http://www.w3.org/2000/svg">
                            <path fill-rule="evenodd"
                                  d="M3 5a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zM3 10a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zM3 15a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1z"
                                  clip-rule="evenodd"></path>
                        </svg>
                    </button>
                </div>
            @endif
        </div>
        <div class="items-center justify-between hidden w-full md:flex md:w-auto md:order-1" id="navbar-sticky">
            <ul class="flex flex-col font-medium border border-gray-100 md:flex-row md:border-0 md:bg-white">
                @forelse($navbars as $navbar)
                    @if($navbar->subNavbars->isNotEmpty())
                        <li>
                            <button id="dropdownNavbarLink" data-dropdown-toggle="dropdownNavbar"
                                    class="mx-4 flex items-center justify-between w-full text-gray-700 rounded hover:bg-gray-100 md:hover:bg-transparent md:border-0 md:hover:text-blue-700 md:p-0 md:w-auto dark:text-gray-400 dark:hover:text-white dark:focus:text-white dark:border-gray-700 dark:hover:bg-gray-700 md:dark:hover:bg-transparent">
                                {{ $navbar->title }}
                                <svg class="w-5 h-5 ml-1" aria-hidden="true" fill="currentColor" viewBox="0 0 20 20"
                                     xmlns="http://www.w3.org/2000/svg">
                                    <path fill-rule="evenodd"
                                          d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                                          clip-rule="evenodd"></path>
                                </svg>
                            </button>

                            <div id="dropdownNavbar"
                                 class="z-10 hidden font-normal bg-white divide-y divide-gray-100 rounded-lg shadow w-44 dark:bg-gray-700 dark:divide-gray-600">
                                <ul class="py-2 text-sm text-gray-700 dark:text-gray-400"
                                    aria-labelledby="dropdownLargeButton">
                                    @foreach($navbar->subNavbars as $subNavbar)
                                        <li>
                                            <a href="{{ $subNavbar->url }}"
                                               class="block px-4 py-2 hover:bg-gray-100 dark:hover:bg-gray-600 dark:hover:text-white">{{ $subNavbar->title }}</a>
                                        </li>
                                    @endforeach
                                </ul>
                            </div>
                        </li>
                    @else
                        <li>
                            <a href="{{ $navbar->url }}" @class(['block md:bg-transparent md:hover:bg-transparent md:hover:text-blue-700 mx-4',
                                'text-blue-700' => URL::current() === $navbar->url, 'text-gray-900' => URL::current() !== $navbar->url]) aria-current="page">{{ $navbar->title }}</a>
                        </li>
                    @endif
                @empty
                    <li>
                        <a href="{{ route('home') }}"
                           class="@if(Route::is('home')) text-blue-700 @else text-gray-900 @endif block py-2 pl-3 pr-4 md:bg-transparent md:hover:bg-transparent md:hover:text-blue-700 md:p-0"
                           aria-current="page">Home</a>
                    </li>
                    <li>
                        <button id="dropdownNavbarLink" data-dropdown-toggle="dropdownNavbar"
                                class="flex items-center justify-between w-full py-2 pl-3 pr-4  text-gray-700 rounded hover:bg-gray-100 md:hover:bg-transparent md:border-0 md:hover:text-blue-700 md:p-0 md:w-auto dark:text-gray-400 dark:hover:text-white dark:focus:text-white dark:border-gray-700 dark:hover:bg-gray-700 md:dark:hover:bg-transparent">
                            About
                            <svg class="w-5 h-5 ml-1" aria-hidden="true" fill="currentColor" viewBox="0 0 20 20"
                                 xmlns="http://www.w3.org/2000/svg">
                                <path fill-rule="evenodd"
                                      d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                                      clip-rule="evenodd"></path>
                            </svg>
                        </button>
                        <!-- Dropdown menu -->
                        <div id="dropdownNavbar"
                             class="z-10 hidden font-normal bg-white divide-y divide-gray-100 rounded-lg shadow w-44 dark:bg-gray-700 dark:divide-gray-600">
                            <ul class="py-2 text-sm text-gray-700 dark:text-gray-400"
                                aria-labelledby="dropdownLargeButton">
                                <li>
                                    <a href="#"
                                       class="block px-4 py-2 hover:bg-gray-100 dark:hover:bg-gray-600 dark:hover:text-white">Our
                                        Team</a>
                                </li>
                                <li>
                                    <a href="#"
                                       class="block px-4 py-2 hover:bg-gray-100 dark:hover:bg-gray-600 dark:hover:text-white">Assessment
                                        Committee</a>
                                </li>
                                <li>
                                    <a href="#"
                                       class="block px-4 py-2 hover:bg-gray-100 dark:hover:bg-gray-600 dark:hover:text-white">Training
                                        Committee</a>
                                </li>
                                <li>
                                    <a href="#"
                                       class="block px-4 py-2 hover:bg-gray-100 dark:hover:bg-gray-600 dark:hover:text-white">Accreditation</a>
                                </li>
                            </ul>
                        </div>
                    </li>
                    <li>
                        <a href="#services"
                           class="block py-2 pl-3 pr-4 text-gray-900 md:hover:bg-transparent md:hover:text-blue-700 md:p-0">Services</a>
                    </li>
                    <li class="hidden">
                        <a href="#testimonials"
                           class="block py-2 pl-3 pr-4 text-gray-900 md:hover:bg-transparent md:hover:text-blue-700 md:p-0">Testimonials</a>
                    </li>
                    <li>
                        <a href="#training-programs"
                           class="block py-2 pl-3 pr-4 text-gray-900 md:hover:bg-transparent md:hover:text-blue-700 md:p-0">Training
                            Programs</a>
                    </li>
                    <li>
                        <a href="{{ route('contact-us') }}"
                           class="@if(Route::is('contact-us')) text-blue-700 @else text-gray-900 @endif block py-2 pl-3 pr-4 rounded hover:bg-gray-100 md:hover:bg-transparent md:hover:text-blue-700 md:p-0">Contact</a>
                    </li>
                @endforelse

                {{-- Language Switcher --}}
                <li>
                    <button id="languageDropdownNavbarLink" data-dropdown-toggle="languageDropdownNavbar"
                            class="mx-4 flex items-center justify-between w-full text-gray-700 rounded hover:bg-gray-100 md:hover:bg-transparent md:border-0 md:hover:text-blue-700 md:p-0 md:w-auto dark:text-gray-400 dark:hover:text-white dark:focus:text-white dark:border-gray-700 dark:hover:bg-gray-700 md:dark:hover:bg-transparent">
                        @if(app()->getLocale() === 'en')
                            <img src="{{ asset('images/flags/united-kingdom.png') }}" alt="English Flag"
                                 class="w-5 h-5 inline-block mr-1"> English
                        @elseif(app()->getLocale() === 'ar')
                            <img src="{{ asset('images/flags/egypt.png') }}" alt="Arabic Flag"
                                 class="w-5 h-5 inline-block ml-1"> العربية
                        @endif
                        <svg class="w-5 h-5 ml-1" aria-hidden="true" fill="currentColor" viewBox="0 0 20 20"
                             xmlns="http://www.w3.org/2000/svg">
                            <path fill-rule="evenodd"
                                  d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                                  clip-rule="evenodd"></path>
                        </svg>
                    </button>

                    <div id="languageDropdownNavbar"
                         class="z-10 hidden font-normal bg-white divide-y divide-gray-100 rounded-lg shadow w-44 dark:bg-gray-700 dark:divide-gray-600">
                        <ul class="py-2 text-sm text-gray-700 dark:text-gray-400"
                            aria-labelledby="dropdownLargeButton">
                            @foreach(config('app.locales') as $locale)
                                <li>
                                    <a href="{{ route('setLocale', $locale) }}"
                                       class="block px-4 py-2 hover:bg-gray-100 dark:hover:bg-gray-600 dark:hover:text-white">
                                        @if($locale === 'en')
                                            <img src="{{ asset('images/flags/united-kingdom.png') }}" alt="English Flag"
                                                 class="w-5 h-5 inline-block mr-2"> English
                                        @elseif($locale === 'ar')
                                            <img src="{{ asset('images/flags/egypt.png') }}" alt="Arabic Flag"
                                                 class="w-5 h-5 inline-block mr-2"> العربية
                                        @endif
                                    </a>
                                </li>
                            @endforeach
                            {{--                            @foreach($navbar->subNavbars as $subNavbar)--}}
                            {{--                                <li>--}}
                            {{--                                    <a href="{{ $subNavbar->url }}"--}}
                            {{--                                       class="block px-4 py-2 hover:bg-gray-100 dark:hover:bg-gray-600 dark:hover:text-white">{{ $subNavbar->title }}</a>--}}
                            {{--                                </li>--}}
                            {{--                            @endforeach--}}
                        </ul>
                    </div>
                </li>
            </ul>
        </div>
    </div>
</nav>
