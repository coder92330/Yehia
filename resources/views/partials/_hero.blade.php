<div id="carouselExampleCrossfade" class="relative" data-te-carousel-init data-te-carousel-slide>
    <!--Carousel indicators-->
    <div class="absolute inset-x-0 bottom-0 z-40 mx-[15%] mb-4 flex list-none justify-center p-0"
         data-te-carousel-indicators>
        @forelse($slider->contents()->where('name->' . app()->getLocale(), 'slider_hero')->first()->hero_slider as $key => $item)
            <button
                class="mx-[3px] box-content h-[3px] w-[30px] flex-initial cursor-pointer border-0 border-y-[10px] border-solid border-transparent bg-white bg-clip-padding p-0 -indent-[999px] opacity-50 transition-opacity duration-[600ms] ease-[cubic-bezier(0.25,0.1,0.25,1.0)] motion-reduce:transition-none"
                type="button" data-te-target="#carouselExampleCrossfade" data-te-slide-to="{{ $key }}" data-te-carousel-active
                aria-current="true" aria-label="Slide {{ ++$key }}"></button>
        @empty
            <button
                class="mx-[3px] box-content h-[3px] w-[30px] flex-initial cursor-pointer border-0 border-y-[10px] border-solid border-transparent bg-white bg-clip-padding p-0 -indent-[999px] opacity-50 transition-opacity duration-[600ms] ease-[cubic-bezier(0.25,0.1,0.25,1.0)] motion-reduce:transition-none"
                type="button" data-te-target="#carouselExampleCrossfade" data-te-slide-to="0" data-te-carousel-active
                aria-current="true" aria-label="Slide 1"></button>
            <button
                class="mx-[3px] box-content h-[3px] w-[30px] flex-initial cursor-pointer border-0 border-y-[10px] border-solid border-transparent bg-white bg-clip-padding p-0 -indent-[999px] opacity-50 transition-opacity duration-[600ms] ease-[cubic-bezier(0.25,0.1,0.25,1.0)] motion-reduce:transition-none"
                type="button" data-te-target="#carouselExampleCrossfade" data-te-slide-to="1"
                aria-label="Slide 2"></button>
        @endforelse
    </div>

    <!--Carousel items-->
    <div
        class="relative w-full overflow-hidden after:clear-both after:block after:content-['']">
        @forelse($slider->contents()->where('name->' . app()->getLocale(), 'slider_hero')->first()->hero_slider as $image)
            <div
                class="md:h-[600px] lg:h-[700px] h-[30rem] relative float-left -mr-[100%] w-full !transform-none opacity-0 transition-opacity duration-[800ms] ease-in-out motion-reduce:transition-none"
                data-te-carousel-fade data-te-carousel-item data-te-carousel-active>
                <img
                    src="{{ $image }}"
                    class="block w-full bg-cover bg-center h-[100%] object-cover"
                    alt="Wild Landscape"/>
            </div>
        @empty
            <!--First item-->
            <div
                class="md:h-[600px] lg:h-[700px] h-[30rem] relative float-left -mr-[100%] w-full !transform-none opacity-0 transition-opacity duration-[600ms] ease-in-out motion-reduce:transition-none"
                data-te-carousel-fade data-te-carousel-item data-te-carousel-active>
                <img
                    src="{{ asset('images/abouts-1.jpeg') }}"
                    class="block w-full bg-cover bg-center h-[100%] object-cover"
                    alt="First slide"/>
            </div>
            <!--Second item-->
            <div
                class="md:h-[600px] lg:h-[700px] h-[30rem] relative float-left -mr-[100%] hidden w-full !transform-none opacity-0 transition-opacity duration-[600ms] ease-in-out motion-reduce:transition-none"
                data-te-carousel-fade
                data-te-carousel-item>
                <img
                    src="{{ asset('images/abouts-2.jpeg') }}"
                    class="block w-full bg-cover bg-center h-[100%] object-cover"
                    alt="Second slide"/>
            </div>
        @endforelse

        <div class="absolute inset-0 z-30 flex items-center justify-center w-full h-full bg-black bg-opacity-50">
            <div class="container max-w-7xl mx-auto">
                <h1 class="mb-4 text-4xl font-bold text-center text-white lg:text-6xl">
                    {{ $slider->contents()->firstWhere('name->' . app()->getLocale(), 'slider_hero')->title }}</h1>
                <p class="mb-8 font-normal text-center text-gray-300 lg:text-md sm:px-16 lg:px-48">
                    {{ $slider->contents()->firstWhere('name->' . app()->getLocale(), 'slider_hero')->content  }}</p>
                @if(Auth::guard('agent')->check() || Auth::check())
                    <form class="w-[80%] mx-auto mt-12" action="#">
                        <div class="flex items-center w-full">
                            <div class="relative w-1/3">
                                <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                         stroke-width="1.5" stroke="currentColor" class="w-5 h-5">
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                              d="M15 10.5a3 3 0 11-6 0 3 3 0 016 0z"/>
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                              d="M19.5 10.5c0 7.142-7.5 11.25-7.5 11.25S4.5 17.642 4.5 10.5a7.5 7.5 0 1115 0z"/>
                                    </svg>
                                </div>
                                <select id="where" name="where"
                                    @class([
                                        "block w-full p-4 border-0 outline-0 pl-9 placeholder:text-black text-sm border border-gray-300 bg-gray-50 focus:ring-blue-500 focus:border-blue-500",
                                        "rounded-tl-full rounded-bl-full" => app()->getLocale() === 'en',
                                        "rounded-tr-full rounded-br-full" => app()->getLocale() === 'ar',
                                    ])>
                                    @foreach(\App\Models\City::active()->get() as $city)
                                        <option value="{{ $city->id }}">{{ $city->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="relative w-1/3">
                                <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                         stroke-width="1.5" stroke="currentColor" class="w-5 h-5 ">
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                              d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 012.25-2.25h13.5A2.25 2.25 0 0121 7.5v11.25m-18 0A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75m-18 0v-7.5A2.25 2.25 0 015.25 9h13.5A2.25 2.25 0 0121 11.25v7.5m-9-6h.008v.008H12v-.008zM12 15h.008v.008H12V15zm0 2.25h.008v.008H12v-.008zM9.75 15h.008v.008H9.75V15zm0 2.25h.008v.008H9.75v-.008zM7.5 15h.008v.008H7.5V15zm0 2.25h.008v.008H7.5v-.008zm6.75-4.5h.008v.008h-.008v-.008zm0 2.25h.008v.008h-.008V15zm0 2.25h.008v.008h-.008v-.008zm2.25-4.5h.008v.008H16.5v-.008zm0 2.25h.008v.008H16.5V15z"/>
                                    </svg>
                                </div>
                                <input datepicker type="text" id="from" name="from"
                                       class="block w-full p-4 border-0 outline-0 placeholder:text-black pl-9 text-sm text-gray-900 border border-gray-300 bg-gray-50 focus:ring-blue-500 focus:border-blue-500"
                                       placeholder="{{ __('frontend.from') }}">
                            </div>
                            <div class="relative w-1/3">
                                <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                         stroke-width="1.5" stroke="currentColor" class="w-5 h-5 ">
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                              d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 012.25-2.25h13.5A2.25 2.25 0 0121 7.5v11.25m-18 0A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75m-18 0v-7.5A2.25 2.25 0 015.25 9h13.5A2.25 2.25 0 0121 11.25v7.5m-9-6h.008v.008H12v-.008zM12 15h.008v.008H12V15zm0 2.25h.008v.008H12v-.008zM9.75 15h.008v.008H9.75V15zm0 2.25h.008v.008H9.75v-.008zM7.5 15h.008v.008H7.5V15zm0 2.25h.008v.008H7.5v-.008zm6.75-4.5h.008v.008h-.008v-.008zm0 2.25h.008v.008h-.008V15zm0 2.25h.008v.008h-.008v-.008zm2.25-4.5h.008v.008H16.5v-.008zm0 2.25h.008v.008H16.5V15z"/>
                                    </svg>
                                </div>
                                <input datepicker type="text" id="to" name="to"
                                       @class([
                                            "block w-full p-4 border-0 outline-0 placeholder:text-black pl-9 text-sm text-gray-900 border border-gray-300 bg-gray-50 focus:ring-blue-500 focus:border-blue-500",
                                            "rounded-tr-full rounded-br-full" => app()->getLocale() === 'en',
                                            "rounded-tl-full rounded-bl-full" => app()->getLocale() === 'ar',
                                        ]) placeholder="{{ __('frontend.to') }}">
                                <button type="submit"
                                    @class([
                                        "absolute bottom-2.5 px-4 py-1.5 text-sm font-medium text-white bg-blue-600 rounded-full hover:bg-blue-500 focus:outline-none focus:bg-blue-500",
                                        "right-2.5" => app()->getLocale() === 'en',
                                        "left-2.5" => app()->getLocale() === 'ar',
                                    ])>
                                    {{ __('frontend.search') }}
                                </button>
                            </div>
                        </div>
                    </form>
                @endif
            </div>
        </div>
    </div>

    <!--Carousel controls - prev item-->
    <button
        class="absolute bottom-0 left-0 top-0 z-30 flex w-[15%] items-center justify-center border-0 bg-none p-0 text-center text-white opacity-50 transition-opacity duration-150 ease-[cubic-bezier(0.25,0.1,0.25,1.0)] hover:text-white hover:no-underline hover:opacity-90 hover:outline-none focus:text-white focus:no-underline focus:opacity-90 focus:outline-none motion-reduce:transition-none"
        type="button"
        data-te-target="#carouselExampleCrossfade"
        data-te-slide="prev">
    <span class="inline-block h-8 w-8">
      <svg
          xmlns="http://www.w3.org/2000/svg"
          fill="none"
          viewBox="0 0 24 24"
          stroke-width="1.5"
          stroke="currentColor"
          class="h-6 w-6">
        <path
            stroke-linecap="round"
            stroke-linejoin="round"
            d="M15.75 19.5L8.25 12l7.5-7.5"/>
      </svg>
    </span>
        <span
            class="!absolute !-m-px !h-px !w-px !overflow-hidden !whitespace-nowrap !border-0 !p-0 ![clip:rect(0,0,0,0)]"
        >Previous</span
        >
    </button>
    <!--Carousel controls - next item-->
    <button
        class="absolute bottom-0 right-0 top-0 z-30 flex w-[15%] items-center justify-center border-0 bg-none p-0 text-center text-white opacity-50 transition-opacity duration-150 ease-[cubic-bezier(0.25,0.1,0.25,1.0)] hover:text-white hover:no-underline hover:opacity-90 hover:outline-none focus:text-white focus:no-underline focus:opacity-90 focus:outline-none motion-reduce:transition-none"
        type="button"
        data-te-target="#carouselExampleCrossfade"
        data-te-slide="next">
    <span class="inline-block h-8 w-8">
      <svg
          xmlns="http://www.w3.org/2000/svg"
          fill="none"
          viewBox="0 0 24 24"
          stroke-width="1.5"
          stroke="currentColor"
          class="h-6 w-6">
        <path
            stroke-linecap="round"
            stroke-linejoin="round"
            d="M8.25 4.5l7.5 7.5-7.5 7.5"/>
      </svg>
    </span>
        <span
            class="!absolute !-m-px !h-px !w-px !overflow-hidden !whitespace-nowrap !border-0 !p-0 ![clip:rect(0,0,0,0)]"
        >Next</span
        >
    </button>
</div>
