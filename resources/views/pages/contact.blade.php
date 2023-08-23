@extends('layouts.default')

@section('title', __('frontend.contact_us'))
@section('content')
    {{-- Map Content --}}
    <section style="height: 30rem">
        <!--https://www.google.com/maps/embed/v1/place?key=AIzaSyAXiZips25sPg3rb_BO_yVlZBmTXB_9ONo&q=29.979225158691406,31.28079605102539&zoom=20-->
        <iframe
            src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d432.0007828128315!2d31.280633931587204!3d29.979250021655638!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x1458397fd7bbdeb5%3A0x6200ece7e59cd2e3!2sGuides%20Navigator!5e0!3m2!1sen!2seg!4v1692404331724!5m2!1sen!2seg"
            width="100%"
            height="100%"
            loading="lazy">
        </iframe>
        
    </section>

    {{-- Section With two columns --}}
    <section class="bg-gray-100">
        <div class="container mx-auto lg:px-10">
            <div class="mx-auto grid lg:grid-cols-2 lg:gap-20 md:grid-cols-1">
                <div class="col-span-1 pt-20 lg:px-0 px-8">
                    <h1 class="text-5xl font-bold text-gray-500">{{ \App\Models\LandingPage\LandingPageKey::where('key', 'Contact Us')->first()?->contents()?->latest()?->first()->title }}</h1>
                    <p class="text-gray-500 my-8">{{ \App\Models\LandingPage\LandingPageKey::where('key', 'Contact Us')->first()?->contents()?->latest()?->first()->content }}
                    </p>
                    <form class="w-full mb-16" action="{{ route('contact-us.submit') }}" method="post">
                        @csrf
                        <div class="grid md:grid-cols-2 md:gap-6">
                            <div class="relative z-0 w-full mb-6 group">
                                <input type="text" name="first_name" id="first_name"
                                       class="block @error('first_name') border border-red-500 @enderror py-2.5 px-0 w-full text-sm text-gray-900 bg-transparent border-0 border-b-2 border-gray-300 appearance-none dark:text-white dark:border-gray-600 dark:focus:border-blue-500 focus:outline-none focus:ring-0 focus:border-blue-600 peer"
                                       placeholder=" " required/>
                                @error('first_name')
                                <p class="mt-2 text-sm text-red-600 dark:text-red-500"><span class="font-medium">{{ __('frontend.error') }}</span> {{ $message }}
                                </p>
                                @enderror
                                <label for="first_name"
                                       class="peer-focus:font-medium absolute text-sm text-gray-500 dark:text-gray-400 duration-300 transform -translate-y-6 scale-75 top-3 -z-10 origin-[0] peer-focus:left-0 peer-focus:text-blue-600 peer-focus:dark:text-blue-500 peer-placeholder-shown:scale-100 peer-placeholder-shown:translate-y-0 peer-focus:scale-75 peer-focus:-translate-y-6">{{ __('frontend.first_name') }}</label>
                            </div>
                            <div class="relative z-0 w-full mb-6 group">
                                <input type="text" name="last_name" id="last_name"
                                       class="block @error('last_name') border border-red-500 @enderror py-2.5 px-0 w-full text-sm text-gray-900 bg-transparent border-0 border-b-2 border-gray-300 appearance-none dark:text-white dark:border-gray-600 dark:focus:border-blue-500 focus:outline-none focus:ring-0 focus:border-blue-600 peer"
                                       placeholder=" " required/>
                                @error('last_name')
                                <p class="mt-2 text-sm text-red-600 dark:text-red-500"><span class="font-medium">{{ __('frontend.error') }}</span> {{ $message }}
                                </p>
                                @enderror
                                <label for="last_name"
                                       class="peer-focus:font-medium absolute text-sm text-gray-500 dark:text-gray-400 duration-300 transform -translate-y-6 scale-75 top-3 -z-10 origin-[0] peer-focus:left-0 peer-focus:text-blue-600 peer-focus:dark:text-blue-500 peer-placeholder-shown:scale-100 peer-placeholder-shown:translate-y-0 peer-focus:scale-75 peer-focus:-translate-y-6">{{ __('frontend.last_name') }}</label>
                            </div>
                        </div>
                        <div class="grid md:grid-cols-2 md:gap-6">
                            <div class="relative z-0 w-full mb-6 group">
                                <input type="email" name="email" id="email"
                                       class="block @error('email') border border-red-500 @enderror py-2.5 px-0 w-full text-sm text-gray-900 bg-transparent border-0 border-b-2 border-gray-300 appearance-none dark:text-white dark:border-gray-600 dark:focus:border-blue-500 focus:outline-none focus:ring-0 focus:border-blue-600 peer"
                                       placeholder=" " required/>
                                @error('email')
                                <p class="mt-2 text-sm text-red-600 dark:text-red-500"><span class="font-medium">{{ __('frontend.error') }}</span> {{ $message }}
                                </p>
                                @enderror
                                <label for="email"
                                       class="peer-focus:font-medium absolute text-sm text-gray-500 dark:text-gray-400 duration-300 transform -translate-y-6 scale-75 top-3 -z-10 origin-[0] peer-focus:left-0 peer-focus:text-blue-600 peer-focus:dark:text-blue-500 peer-placeholder-shown:scale-100 peer-placeholder-shown:translate-y-0 peer-focus:scale-75 peer-focus:-translate-y-6">{{ __('frontend.email_address') }}</label>
                            </div>
                            <div class="relative z-0 w-full mb-6 group">
                                <input type="tel" pattern="[0-9]{11}" name="phone"
                                       id="phone"
                                       class="block @error('phone') border border-red-500 @enderror py-2.5 px-0 w-full text-sm text-gray-900 bg-transparent border-0 border-b-2 border-gray-300 appearance-none dark:text-white dark:border-gray-600 dark:focus:border-blue-500 focus:outline-none focus:ring-0 focus:border-blue-600 peer"
                                       placeholder=" " required/>
                                @error('phone')
                                <p class="mt-2 text-sm text-red-600 dark:text-red-500"><span class="font-medium">{{ __('frontend.error') }}</span> {{ $message }}
                                </p>
                                @enderror
                                <label for="phone"
                                       class="peer-focus:font-medium absolute text-sm text-gray-500 dark:text-gray-400 duration-300 transform -translate-y-6 scale-75 top-3 -z-10 origin-[0] peer-focus:left-0 peer-focus:text-blue-600 peer-focus:dark:text-blue-500 peer-placeholder-shown:scale-100 peer-placeholder-shown:translate-y-0 peer-focus:scale-75 peer-focus:-translate-y-6">{{ __('frontend.phone_number') }}</label>
                            </div>
                        </div>
                        <div class="relative z-0 w-full mb-6 group">
                            <textarea name="message" id="message" rows="6"
                                      class="block @error('message') border border-red-500 @enderror py-2.5 px-0 w-full text-sm text-gray-900 bg-transparent border-0 border-b-2 border-gray-300 appearance-none dark:text-white dark:border-gray-600 dark:focus:border-blue-500 focus:outline-none focus:ring-0 focus:border-blue-600 peer"
                                      placeholder=" " required></textarea>
                            @error('message')
                            <p class="mt-2 text-sm text-red-600 dark:text-red-500"><span
                                    class="font-medium">{{ __('frontend.error') }}</span> {{ $message }}
                            </p>
                            @enderror
                            <label for="message"
                                   class="peer-focus:font-medium absolute text-sm text-gray-500 dark:text-gray-400 duration-300 transform -translate-y-6 scale-75 top-3 -z-10 origin-[0] peer-focus:left-0 peer-focus:text-blue-600 peer-focus:dark:text-blue-500 peer-placeholder-shown:scale-100 peer-placeholder-shown:translate-y-0 peer-focus:scale-75 peer-focus:-translate-y-6">{{ __('frontend.message') }}</label>
                        </div>
                        <button type="submit"
                                class="text-white bg-gray-500 hover:bg-gray-700 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm w-full sm:w-auto px-5 py-2.5 text-center dark:bg-gray-500 dark:hover:bg-gray-700 dark:focus:ring-gray-700">
                            {{ __('actions.send') }}
                        </button>
                    </form>

                </div>
                <div class="col-span-1">
                    <img src="{{ asset('images/guides-navigator-contact-us.jpg') }}" alt="Contact Image" class="w-full h-full object-cover">
                </div>
            </div>
        </div>
    </section>
@endsection
