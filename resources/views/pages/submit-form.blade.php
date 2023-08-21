@extends('layouts.default')

@section('title', __('frontend.submit_forms'))

@section('content')
    <section class="bg-gray-100">
        <div class="container mx-auto lg:px-10">
            <div class="mx-auto grid lg:grid-cols-2 lg:gap-20 md:grid-cols-1 py-32">
                <div class="col-span-1 lg:px-0 px-8 pt-20">
                    <h1 class="text-5xl font-bold text-gray-500">{{ __('frontend.contact_us') }}</h1>
                    <p class="text-gray-500 mt-4 mb-4">{{ __('frontend.contact_us_description') }}</p>
                    <p class="text-gray-800 mb-2"><span
                            class="font-bold text-gray-500">{{ __('frontend.email') }}:</span> info@guidesnavigator.com
                    </p>
                    <p class="text-gray-800 mb-2"><span
                            class="font-bold text-gray-500">{{ __('frontend.phone') }}:</span> +2 251 866 77 - 010 504
                        801 88</p>
                    <p class="text-gray-800 mb-2"><span
                            class="font-bold text-gray-500">{{ __('frontend.address') }}:</span> 5 Building Street 295,
                        New Maadi, Cairo, Egypt</p>
                </div>
                <div class="col-span-1 px-8 bg-white rounded-xl drop-shadow-lg">
                    <div class="mb-4 border-b border-gray-200 dark:border-gray-700">
                        <ul class="flex flex-wrap -mb-px text-sm font-medium text-center" id="myTab"
                            data-tabs-toggle="#myTabContent" role="tablist">
                            <li class="mr-2" role="presentation">
                                <button class="inline-block p-4 border-b-2 rounded-t-lg" id="tourguide-tab"
                                        data-tabs-target="#profile" type="button" role="tab" aria-controls="profile"
                                        aria-selected="false">{{ __('frontend.tour_guide') }}
                                </button>
                            </li>
                            <li class="mr-2" role="presentation">
                                <button
                                    class="inline-block p-4 border-b-2 border-transparent rounded-t-lg hover:text-gray-600 hover:border-gray-300 dark:hover:text-gray-300"
                                    id="agent-tab" data-tabs-target="#dashboard" type="button" role="tab"
                                    aria-controls="dashboard" aria-selected="false">{{ __('frontend.travel_agent') }}
                                </button>
                            </li>
                        </ul>
                    </div>
                    <div id="myTabContent">
                        <div class="hidden p-4 rounded-lg" id="profile" role="tabpanel"
                             aria-labelledby="tourguide-tab">
                            <form class="w-full mb-16" action="{{ route('tourguide-submit-form') }}" method="post">
                                @csrf
                                <div class="relative z-0 w-full mb-6 group">
                                    <label for="full_name"
                                           class="block mb-2 text-sm text-gray-500">{{ __('frontend.full_name') }}</label>
                                    <input type="text" name="full_name" id="full_name"
                                           class="@error('full_name') border border-red-500 @enderror bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-md focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                                           placeholder="{{ __('frontend.full_name') }}" required/>
                                    @error('full_name')
                                    <p class="mt-2 text-sm text-red-600 dark:text-red-500"><span
                                            class="font-medium">{{ __('frontend.error') }}!</span> {{ $message }}
                                    </p>
                                    @enderror
                                </div>

                                <div class="relative z-0 w-full mb-6 group">
                                    <label for="cell_phone"
                                           class="block mb-2 text-sm text-gray-500">{{ __('frontend.cell_phone') }}</label>
                                    <div class="flex">
                                  <span
                                      @class([
                                        'inline-flex items-center px-3 text-sm text-gray-900 bg-gray-50 border-gray-300 dark:bg-gray-600 dark:text-gray-400 dark:border-gray-600',
                                        'border border-l-0 rounded-r-md' => app()->getLocale() === 'ar',
                                        'border border-r-0 rounded-l-md' => app()->getLocale() === 'en'
                                    ])>
                                    +20
                                  </span>
                                        <input type="text" id="cell_phone" name="phone"
                                               @class([
                                                    'rounded-none bg-gray-50 border border-gray-300 text-gray-900 focus:ring-blue-500 focus:border-blue-500 block flex-1 min-w-0 w-full text-sm p-2.5  dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500',
                                                    'rounded-l-lg' => app()->getLocale() === 'ar',
                                                    'rounded-r-lg' => app()->getLocale() === 'en',
                                                    'border border-red-500' => $errors->has('cell_phone')
                                                ]) placeholder="{{ __('frontend.cell_phone') }}">
                                    </div>
                                </div>

                                <div class="grid md:grid-cols-2 md:gap-6">
                                    <div class="relative z-0 w-full mb-6 group">
                                        <label for="date_of_birth"
                                               class="block mb-2 text-sm text-gray-500">{{ __('frontend.date_of_birth') }}</label>
                                        <input type="date" name="date_of_birth" id="date_of_birth"
                                               class="@error('date_of_birth') border border-red-500 @enderror bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-md focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                                               placeholder="{{ __('frontend.full_name') }}" required/>
                                        @error('date_of_birth')
                                        <p class="mt-2 text-sm text-red-600 dark:text-red-500"><span
                                                class="font-medium">{{ __('frontend.error') }}!</span> {{ $message }}
                                        </p>
                                        @enderror
                                    </div>
                                    <div class="relative z-0 w-full mb-6 group">
                                        <label for="full_name"
                                               class="block mb-2 text-sm text-gray-500">{{ __('frontend.gender') }}</label>
                                        <select name="gender" id="gender"
                                                class="@error('gender') border border-red-500 @enderror bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-md focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                                                placeholder="Gender" required>
                                            <option value="male">{{ __('frontend.male') }}</option>
                                            <option value="female">{{ __('frontend.female') }}</option>
                                        </select>
                                        @error('gender')
                                        <p class="mt-2 text-sm text-red-600 dark:text-red-500"><span
                                                class="font-medium">{{ __('frontend.error') }}!</span> {{ $message }}
                                        </p>
                                        @enderror
                                    </div>
                                </div>

                                <div class="relative z-0 w-full mb-6 group">
                                    <label for="email"
                                           class="block mb-2 text-sm text-gray-500">{{ __('frontend.email_address') }}</label>
                                    <input type="email" name="email" id="email"
                                           class="@error('email') border border-red-500 @enderror bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-md focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                                           placeholder=" " required/>
                                    @error('email')
                                    <p class="mt-2 text-sm text-red-600 dark:text-red-500"><span
                                            class="font-medium">{{ __('frontend.error') }}!</span> {{ $message }}
                                    </p>
                                    @enderror
                                </div>

                                <div class="relative z-0 w-full mb-6 group">
                                    <label for="address"
                                           class="block mb-2 text-sm text-gray-500">{{ __('frontend.address') }}</label>
                                    <input type="address" name="address" id="address"
                                           class="@error('address') border border-red-500 @enderror bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-md focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                                           placeholder=" " required/>
                                    @error('address')
                                    <p class="mt-2 text-sm text-red-600 dark:text-red-500"><span
                                            class="font-medium">{{ __('frontend.error') }}!</span> {{ $message }}
                                    </p>
                                    @enderror
                                </div>

                                <div class="relative z-0 w-full mb-6 group">
                                    <label for="address"
                                           class="block mb-2 text-sm text-gray-500">{{ __('frontend.languages') }}</label>
                                    <select name="languages[]" id="languages"
                                            class="@error('languages') border border-red-500 @enderror bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-md focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                                            placeholder=" " required multiple>
                                        @foreach(\App\Models\Language::all() as $language)
                                            <option value="{{ $language->id }}">{{ $language->name }}</option>
                                        @endforeach
                                    </select>
                                    @error('languages')
                                    <p class="mt-2 text-sm text-red-600 dark:text-red-500"><span
                                            class="font-medium">{{ __('frontend.error') }}!</span> {{ $message }}
                                    </p>
                                    @enderror
                                </div>

                                <div class="w-full">
                                    <button type="submit"
                                            class="w-full px-4 py-2 text-sm font-medium leading-5 text-white transition-colors duration-150 bg-gray-500 border border-transparent rounded-lg active:bg-blue-600 hover:bg-gray-700 focus:outline-none focus:shadow-outline-blue">
                                        Send
                                    </button>
                                </div>
                            </form>
                        </div>
                        <div class="hidden p-4 rounded-lg" id="dashboard" role="tabpanel"
                             aria-labelledby="agent-tab">
                            <form class="w-full mb-16" action="{{ route('agent-submit-form') }}" method="post">
                                @csrf
                                <div class="relative z-0 w-full mb-6 group">
                                    <label for="full_name"
                                           class="block mb-2 text-sm text-gray-500">{{ __('frontend.company_name') }}</label>
                                    <input type="text" name="full_name" id="full_name"
                                           class="@error('full_name') border border-red-500 @enderror bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-md focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                                           placeholder="{{ __('frontend.full_name') }}" required/>
                                    @error('full_name')
                                    <p class="mt-2 text-sm text-red-600 dark:text-red-500"><span
                                            class="font-medium">{{ __('frontend.error') }}!</span> {{ $message }}
                                    </p>
                                    @enderror
                                </div>

                                <div class="relative z-0 w-full mb-6 group">
                                    <label for="cell_phone"
                                           class="block mb-2 text-sm text-gray-500">{{ __('frontend.cell_phone') }}</label>
                                    <div class="flex">
                                  <span
                                      @class([
                                        'inline-flex items-center px-3 text-sm text-gray-900 bg-gray-50 border-gray-300 dark:bg-gray-600 dark:text-gray-400 dark:border-gray-600',
                                        'border border-l-0 rounded-r-md' => app()->getLocale() === 'ar',
                                        'border border-r-0 rounded-l-md' => app()->getLocale() === 'en'
                                    ])>
                                    +20
                                  </span>
                                        <input type="text" id="cell_phone" name="phone"
                                               @class([
                                                'rounded-none bg-gray-50 border border-gray-300 text-gray-900 focus:ring-blue-500 focus:border-blue-500 block flex-1 min-w-0 w-full text-sm p-2.5  dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500',
                                                'rounded-l-lg' => app()->getLocale() === 'ar',
                                                'rounded-r-lg' => app()->getLocale() === 'en',
                                                'border border-red-500' => $errors->has('cell_phone')
                                              ]) placeholder="{{ __('frontend.cell_phone') }}">
                                    </div>
                                </div>

                                <div class="relative z-0 w-full mb-6 group">
                                    <label for="email"
                                           class="block mb-2 text-sm text-gray-500">{{ __('frontend.email_address') }}</label>
                                    <input type="email" name="email" id="email"
                                           class="@error('email') border border-red-500 @enderror bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-md focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                                           placeholder=" " required/>
                                    @error('email')
                                    <p class="mt-2 text-sm text-red-600 dark:text-red-500"><span
                                            class="font-medium">{{ __('frontend.error') }}!</span> {{ $message }}
                                    </p>
                                    @enderror
                                </div>

                                <div class="relative z-0 w-full mb-6 group">
                                    <label for="address"
                                           class="block mb-2 text-sm text-gray-500">{{ __('frontend.company_address') }}</label>
                                    <input type="address" name="address" id="address"
                                           class="@error('address') border border-red-500 @enderror bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-md focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                                           placeholder=" " required/>
                                    @error('address')
                                    <p class="mt-2 text-sm text-red-600 dark:text-red-500"><span
                                            class="font-medium">{{ __('frontend.error') }}!</span> {{ $message }}
                                    </p>
                                    @enderror
                                </div>
                                <div class="relative z-0 w-full mb-6 group">
                                    <label for="website"
                                           class="block mb-2 text-sm text-gray-500">{{ __('frontend.website') }}</label>
                                    <input type="website" name="website" id="website"
                                           class="@error('website') border border-red-500 @enderror bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-md focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                                           placeholder=" " required/>
                                    @error('website')
                                    <p class="mt-2 text-sm text-red-600 dark:text-red-500"><span
                                            class="font-medium">{{ __('frontend.error') }}!</span> {{ $message }}
                                    </p>
                                    @enderror
                                </div>

                                <div class="w-full">
                                    <button type="submit"
                                            class="w-full px-4 py-2 text-sm font-medium leading-5 text-white transition-colors duration-150 bg-gray-500 border border-transparent rounded-lg active:bg-blue-600 hover:bg-gray-700 focus:outline-none focus:shadow-outline-blue">
                                        {{ __('actions.send') }}
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
