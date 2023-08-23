@php
    $subscribe = \App\Models\LandingPage\LandingPageKey::where('key', 'Subscribe')->first();
    $sponsors  = \App\Models\LandingPage\LandingPageKey::where('key', 'Sponsors')->first();
    $footer    = \App\Models\LandingPage\LandingPageKey::where('key', 'Footer')->first();
    $main_section = $footer?->contents()->firstWhere('name->' . app()->getLocale(), 'footer_main_section');
    $useful_links = $footer?->contents()->where('name->' . app()->getLocale(), 'footer_useful_links')->get();
@endphp

{{-- Subscribe Section --}}
<section class="bg-white py-16 block">
    <div class="container mx-auto lg:px-32 md:px-16 md:px-8 px-4">
        <div class="grid lg:grid-cols-3 md:grid-cols-2 grid-cols-1 gap-4">
            <div class="col-span-2">
                <div class="flex flex-col justify-center">
                    <p class="text-3xl font-bold text-gray-600">{{ $subscribe?->contents()?->latest()?->first()->title }}</p>
                    <p class="text-gray-600 mt-4">{{ $subscribe?->contents()?->latest()?->first()->content }}</p>
                </div>
            </div>
            <div class="col-span-1">
                <p class="text-gray-400 mb-4">Subscribe for our newsletter to receive the latest updates</p>
                <form action="{{ route('subscribe') }}" method="post" class="w-full">
                    @csrf
                    <input type="email" name="email" id="email"
                           @class([
                                'border-2 border-gray-300 outline-0 w-[65%]',
                                'rounded-tl-lg rounded-bl-lg mr-[-5px]' => app()->getLocale() === 'en',
                                'rounded-tr-lg rounded-br-lg ml-[-5px]' => app()->getLocale() === 'ar',
                            ]) placeholder="{{ __('frontend.enter_an_email') }}">
                    <button type="submit"
                            @class([
                                'bg-gray-500 text-white lg:px-4 px-2 py-2.5 hover:bg-gray-700 sm:w-[32%]',
                                'rounded-tr-lg rounded-br-lg' => app()->getLocale() === 'en',
                                'rounded-tl-lg rounded-bl-lg' => app()->getLocale() === 'ar',
                            ])>
                        {{ $subscribe?->contents()?->latest()?->first()->button_text ?? "Subscribe" }}
                    </button>
                </form>
            </div>
        </div>
    </div>
</section>

{{-- Sponsors --}}
<section class="bg-dark-2 py-10">
    <div class="container mx-auto px-32">
        <div class="grid lg:grid-cols-6 md:grid-cols-4 grid-cols-1 gap-2">
            <div class="col-span-1 flex my-auto">
                <h1 class="text-lg font-bold text-gray-500">{{ __('frontend.associated_with') }}</h1>
            </div>
            @if($sponsors->sponsors_images)
                @foreach($sponsors->sponsors_images as $sponsor)
                    <div class="col-span-1">
                        <img src="{{ $sponsor }}" class="h-16 w-16" alt="Sponsor">
                    </div>
                @endforeach
            @else
                @php
                    $sponsors_count = $sponsors?->contents()->latest()->first()?->sponsors->count();
                    $width = $sponsors_count > 0
                        ? ($sponsors_count / 4 === 1 ? 'w-full' : 'w-' . $sponsors_count / 4)
                        : "w-1/2";
                @endphp
                <div class="flex col-span-4 justify-center">
                    <div
                        class="flex justify-between {{ $width }}">
                        {{--                        <img src="{{ asset('images/sponsors-1.png') }}" class="h-16 w-16" alt="Sponsor">--}}
                        <img src="{{ asset('images/sponsors-2.png') }}" class="h-16 w-16" alt="Sponsor">
                        <img src="{{ asset('images/sponsors-3.png') }}" class="h-16 w-16" alt="Sponsor">
                    </div>
                </div>
            @endif
        </div>
    </div>
</section>

{{-- Footer --}}
<footer class="bg-dark text-white">
    <div class="mx-auto w-full max-w-screen-xl p-4 lg:py-16">
        <div class="grid lg:grid-cols-4 md:grid-cols-2 sm:grid-cols-1 gap-8">
            <div class="col-span-1">
                <h2 class="mb-6 text-sm font-semibold text-white uppercase">{{ $footer?->contents()->firstWhere('name->' . app()->getLocale(), 'footer_main_section')->title }}</h2>
                <p class="text-gray-700 font-normal">{{ $footer?->contents()->firstWhere('name->' . app()->getLocale(), 'footer_main_section')->content }}
                    .</p>
                <div class="flex mt-4">
                    <a href="{{ $footer?->contents()->firstWhere('name->' . app()->getLocale(), 'footer_social_media_facebook')?->content }}"
                       class="mr-4" target="_blank">
                        <svg xmlns="http://www.w3.org/2000/svg" data-name="Layer 1"
                             viewBox="0 0 24 24" id="facebook" class="w-4 h-4 inline fill-current text-white">
                            <path
                                d="M15.12,5.32H17V2.14A26.11,26.11,0,0,0,14.26,2C11.54,2,9.68,3.66,9.68,6.7V9.32H6.61v3.56H9.68V22h3.68V12.88h3.06l.46-3.56H13.36V7.05C13.36,6,13.64,5.32,15.12,5.32Z"></path>
                        </svg>
                    </a>
                    <a href="{{ $footer?->contents()->firstWhere('name->' . app()->getLocale(), 'footer_social_media_instagram')?->content }}"
                       class="mr-4" target="_blank">
                        <svg xmlns="http://www.w3.org/2000/svg" width="2500" height="2500"
                             viewBox="0 0 2476 2476" id="instagram" class="w-4 h-4 inline fill-current text-white">
                            <path
                                d="M825.4 1238c0-227.9 184.7-412.7 412.6-412.7 227.9 0 412.7 184.8 412.7 412.7 0 227.9-184.8 412.7-412.7 412.7-227.9 0-412.6-184.8-412.6-412.7m-223.1 0c0 351.1 284.6 635.7 635.7 635.7s635.7-284.6 635.7-635.7-284.6-635.7-635.7-635.7S602.3 886.9 602.3 1238m1148-660.9c0 82 66.5 148.6 148.6 148.6 82 0 148.6-66.6 148.6-148.6s-66.5-148.5-148.6-148.5-148.6 66.5-148.6 148.5M737.8 2245.7c-120.7-5.5-186.3-25.6-229.9-42.6-57.8-22.5-99-49.3-142.4-92.6-43.3-43.3-70.2-84.5-92.6-142.3-17-43.6-37.1-109.2-42.6-229.9-6-130.5-7.2-169.7-7.2-500.3s1.3-369.7 7.2-500.3c5.5-120.7 25.7-186.2 42.6-229.9 22.5-57.8 49.3-99 92.6-142.4 43.3-43.3 84.5-70.2 142.4-92.6 43.6-17 109.2-37.1 229.9-42.6 130.5-6 169.7-7.2 500.2-7.2 330.6 0 369.7 1.3 500.3 7.2 120.7 5.5 186.2 25.7 229.9 42.6 57.8 22.4 99 49.3 142.4 92.6 43.3 43.3 70.1 84.6 92.6 142.4 17 43.6 37.1 109.2 42.6 229.9 6 130.6 7.2 169.7 7.2 500.3 0 330.5-1.2 369.7-7.2 500.3-5.5 120.7-25.7 186.3-42.6 229.9-22.5 57.8-49.3 99-92.6 142.3-43.3 43.3-84.6 70.1-142.4 92.6-43.6 17-109.2 37.1-229.9 42.6-130.5 6-169.7 7.2-500.3 7.2-330.5 0-369.7-1.2-500.2-7.2M727.6 7.5c-131.8 6-221.8 26.9-300.5 57.5-81.4 31.6-150.4 74-219.3 142.8C139 276.6 96.6 345.6 65 427.1 34.4 505.8 13.5 595.8 7.5 727.6 1.4 859.6 0 901.8 0 1238s1.4 378.4 7.5 510.4c6 131.8 26.9 221.8 57.5 300.5 31.6 81.4 73.9 150.5 142.8 219.3 68.8 68.8 137.8 111.1 219.3 142.8 78.8 30.6 168.7 51.5 300.5 57.5 132.1 6 174.2 7.5 510.4 7.5 336.3 0 378.4-1.4 510.4-7.5 131.8-6 221.8-26.9 300.5-57.5 81.4-31.7 150.4-74 219.3-142.8 68.8-68.8 111.1-137.9 142.8-219.3 30.6-78.7 51.6-168.7 57.5-300.5 6-132.1 7.4-174.2 7.4-510.4s-1.4-378.4-7.4-510.4c-6-131.8-26.9-221.8-57.5-300.5-31.7-81.4-74-150.4-142.8-219.3C2199.4 139 2130.3 96.6 2049 65c-78.8-30.6-168.8-51.6-300.5-57.5-132-6-174.2-7.5-510.4-7.5-336.3 0-378.4 1.4-510.5 7.5"></path>
                        </svg>
                    </a>
                    <a href="{{ $footer?->contents()->firstWhere('name->' . app()->getLocale(), 'footer_social_media_linkedin')?->content }}"
                       class="mr-4" target="_blank">
                        <svg xmlns="http://www.w3.org/2000/svg" width="2500" height="2389"
                             viewBox="0 5 1036 990" id="linkedin" class="w-4 h-4 inline fill-current text-white">
                            <path
                                d="M0 120c0-33.334 11.667-60.834 35-82.5C58.333 15.833 88.667 5 126 5c36.667 0 66.333 10.666 89 32 23.333 22 35 50.666 35 86 0 32-11.333 58.666-34 80-23.333 22-54 33-92 33h-1c-36.667 0-66.333-11-89-33S0 153.333 0 120zm13 875V327h222v668H13zm345 0h222V622c0-23.334 2.667-41.334 8-54 9.333-22.667 23.5-41.834 42.5-57.5 19-15.667 42.833-23.5 71.5-23.5 74.667 0 112 50.333 112 151v357h222V612c0-98.667-23.333-173.5-70-224.5S857.667 311 781 311c-86 0-153 37-201 111v2h-1l1-2v-95H358c1.333 21.333 2 87.666 2 199 0 111.333-.667 267.666-2 469z"></path>
                        </svg>
                    </a>
                    <a href="https://web.whatsapp.com/send?phone=+201050480188" src="WhatsAppButtonGreenLarge.svg" class="mr-4">
<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="w-4 h-4 inline fill-current text-white" viewBox="0 0 16 16"> <path d="M13.601 2.326A7.854 7.854 0 0 0 7.994 0C3.627 0 .068 3.558.064 7.926c0 1.399.366 2.76 1.057 3.965L0 16l4.204-1.102a7.933 7.933 0 0 0 3.79.965h.004c4.368 0 7.926-3.558 7.93-7.93A7.898 7.898 0 0 0 13.6 2.326zM7.994 14.521a6.573 6.573 0 0 1-3.356-.92l-.24-.144-2.494.654.666-2.433-.156-.251a6.56 6.56 0 0 1-1.007-3.505c0-3.626 2.957-6.584 6.591-6.584a6.56 6.56 0 0 1 4.66 1.931 6.557 6.557 0 0 1 1.928 4.66c-.004 3.639-2.961 6.592-6.592 6.592zm3.615-4.934c-.197-.099-1.17-.578-1.353-.646-.182-.065-.315-.099-.445.099-.133.197-.513.646-.627.775-.114.133-.232.148-.43.05-.197-.1-.836-.308-1.592-.985-.59-.525-.985-1.175-1.103-1.372-.114-.198-.011-.304.088-.403.087-.088.197-.232.296-.346.1-.114.133-.198.198-.33.065-.134.034-.248-.015-.347-.05-.099-.445-1.076-.612-1.47-.16-.389-.323-.335-.445-.34-.114-.007-.247-.007-.38-.007a.729.729 0 0 0-.529.247c-.182.198-.691.677-.691 1.654 0 .977.71 1.916.81 2.049.098.133 1.394 2.132 3.383 2.992.47.205.84.326 1.129.418.475.152.904.129 1.246.08.38-.058 1.171-.48 1.338-.943.164-.464.164-.86.114-.943-.049-.084-.182-.133-.38-.232z"/> </svg>
                    </a>
                </div>
            </div>
            <div class="col-span-1">
                <h2 class="mb-6 text-sm font-semibold text-white uppercase">{{ __('frontend.useful_links') }}</h2>
                {{-- Two columns --}}
                <div class="grid lg:grid-cols-2 md:grid-cols-2 sm:grid-cols-1 gap-2">
                    @if($useful_links)
                        @foreach($useful_links->chunk(round($useful_links->count() / 2)) as $chunk)
                            <ul class="text-gray-500 font-medium">
                                @foreach($chunk as $link)
                                    <li class="mb-4">
                                        <a href="{{ $link->content }}" class="hover:underline">{{ $link->title }}</a>
                                    </li>
                                @endforeach
                            </ul>
                        @endforeach
                    @endif
                </div>
            </div>
            <div class="col-span-1">
                <h2 class="mb-6 text-sm font-semibold text-white uppercase">{{ __('frontend.contact_info') }}</h2>
                <ul class="text-gray-500 font-medium">
                    <li class="mb-4">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                             stroke="currentColor" class="inline-block w-4 h-4 mr-2 text-sky-500">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                  d="M21.75 6.75v10.5a2.25 2.25 0 01-2.25 2.25h-15a2.25 2.25 0 01-2.25-2.25V6.75m19.5 0A2.25 2.25 0 0019.5 4.5h-15a2.25 2.25 0 00-2.25 2.25m19.5 0v.243a2.25 2.25 0 01-1.07 1.916l-7.5 4.615a2.25 2.25 0 01-2.36 0L3.32 8.91a2.25 2.25 0 01-1.07-1.916V6.75"/>
                        </svg>
                        <!--<span>{{ $footer?->contents()->firstWhere('name->' . app()->getLocale(), 'footer_contact_info_email')?->content }}</span>-->
                        
                        <!-- New Code for ad href links to email-->                        
                        <span>
    @if($footer?->contents()->firstWhere('name->' . app()->getLocale(), 'footer_contact_info_email')?->content)
        <a href="mailto:{{ $footer?->contents()->firstWhere('name->' . app()->getLocale(), 'footer_contact_info_email')?->content }}">
            {{ $footer?->contents()->firstWhere('name->' . app()->getLocale(), 'footer_contact_info_email')?->content }}
        </a>
    @endif
</span>

                        
                        
                    </li>
                    <li class="mb-4">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                             stroke="currentColor" class="inline-block w-4 h-4 mr-2 text-sky-500">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                  d="M2.25 6.75c0 8.284 6.716 15 15 15h2.25a2.25 2.25 0 002.25-2.25v-1.372c0-.516-.351-.966-.852-1.091l-4.423-1.106c-.44-.11-.902.055-1.173.417l-.97 1.293c-.282.376-.769.542-1.21.38a12.035 12.035 0 01-7.143-7.143c-.162-.441.004-.928.38-1.21l1.293-.97c.363-.271.527-.734.417-1.173L6.963 3.102a1.125 1.125 0 00-1.091-.852H4.5A2.25 2.25 0 002.25 4.5v2.25z"/>
                        </svg>
                       <!-- @if($footer?->contents()->firstWhere('name->' . app()->getLocale(), 'footer_contact_info_phone')?->content && $footer?->contents()->firstWhere('name->' . app()->getLocale(), 'footer_contact_info_phone_2')?->content)
                            <span>{{ $footer?->contents()->firstWhere('name->' . app()->getLocale(), 'footer_contact_info_phone')?->content . ' - ' . $footer?->contents()->firstWhere('name->' . app()->getLocale(), 'footer_contact_info_phone_2')?->content }}</span>
                        @endif -->
<!-- New Code for ad href links to phone numbers-->                        
@if($footer?->contents()->firstWhere('name->' . app()->getLocale(), 'footer_contact_info_phone')?->content && $footer?->contents()->firstWhere('name->' . app()->getLocale(), 'footer_contact_info_phone_2')?->content)
    <span>
        <a href="tel:{{ $footer?->contents()->firstWhere('name->' . app()->getLocale(), 'footer_contact_info_phone')?->content }}">
            {{ $footer?->contents()->firstWhere('name->' . app()->getLocale(), 'footer_contact_info_phone')?->content }}
        </a> -
        <a href="tel:{{ $footer?->contents()->firstWhere('name->' . app()->getLocale(), 'footer_contact_info_phone_2')?->content }}">
            {{ $footer?->contents()->firstWhere('name->' . app()->getLocale(), 'footer_contact_info_phone_2')?->content }}
        </a>
    </span>
@else
    @if($footer?->contents()->firstWhere('name->' . app()->getLocale(), 'footer_contact_info_phone')?->content)
        <span>
            <a href="tel:{{ $footer?->contents()->firstWhere('name->' . app()->getLocale(), 'footer_contact_info_phone')?->content }}">
                {{ $footer?->contents()->firstWhere('name->' . app()->getLocale(), 'footer_contact_info_phone')?->content }}
            </a>
        </span>
    @endif
@endif


                    </li>
                    <li>
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                             stroke="currentColor" class="inline-block w-4 h-4 mr-2 text-sky-500">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                  d="M15 10.5a3 3 0 11-6 0 3 3 0 016 0z"/>
                            <path stroke-linecap="round" stroke-linejoin="round"
                                  d="M19.5 10.5c0 7.142-7.5 11.25-7.5 11.25S4.5 17.642 4.5 10.5a7.5 7.5 0 1115 0z"/>
                        </svg>
                        <a href="https://goo.gl/maps/bwVjQ57ggTc5PAH46" target="_blank">
                        <span>{{ $footer?->contents()->firstWhere('name->' . app()->getLocale(), 'footer_contact_info_address')?->content }}</span>
                        </a>
                    </li>
                </ul>
            </div>
            <div class="col-span-1">
                <h2 class="mb-6 text-sm font-semibold text-white uppercase">{{ __('frontend.download_our_app') }}</h2>
                <p class="text-gray-500 font-medium mb-4">{{ $footer?->contents()->firstWhere('name->' . app()->getLocale(), 'footer_download_our_app_content')?->content }}</p>
                <div class="flex items-center">
                    <a href="{{ $footer?->contents()->firstWhere('name->' . app()->getLocale(), 'footer_download_our_app_app_store_link')?->content }}"
                       class="mr-4">
                        <img src="{{asset('images/app-store.png')}}" alt="App Store" class="w-24">
                    </a>
                    <a href="{{ $footer?->contents()->firstWhere('name->' . app()->getLocale(), 'footer_download_our_app_google_play_link')?->content }}">
                        <img src="{{asset('images/google-play.png')}}" alt="Google Play" class="w-32">
                    </a>
                </div>
            </div>
        </div>
    </div>
    <div class="w-full mx-auto max-w-screen-xl p-4 md:flex md:items-center md:justify-between border-t border-gray-700">
      <span class="text-sm text-gray-500 sm:text-center dark:text-gray-400">© {{ now()->format('Y') }}
          <a href="https://flowbite.com/" class="hover:underline">{{ env('APP_NAME') }}™</a>. {{ __('frontend.all_rights_reserved') }}.
    </span>
          <span class="text-sm text-gray-500 sm:text-center dark:text-gray-400">© {{ now()->format('Y') }}
          <a href="https://inspiregraphic.com/" class="hover:underline">inspiregraphic.com</a>. {{ __('frontend.all_rights_reserved') }}.
    </span>
</footer>
