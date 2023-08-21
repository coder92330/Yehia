{{-- Primary Alert --}}
@if(isset($alert['type']) && $alert['type'] === 'primary')
    <div role="alert" data-te-alert-init data-te-alert-show
         class="hidden w-full items-center rounded-lg bg-primary-100 px-6 py-3 text-base text-primary-800 data-[te-alert-show]:inline-flex">
        <span class="mr-2">
            <svg
                xmlns="http://www.w3.org/2000/svg"
                viewBox="0 0 24 24"
                fill="currentColor"
                class="h-5 w-5">
              <path
                  fill-rule="evenodd"
                  d="M2.25 12c0-5.385 4.365-9.75 9.75-9.75s9.75 4.365 9.75 9.75-4.365 9.75-9.75 9.75S2.25 17.385 2.25 12zm8.706-1.442c1.146-.573 2.437.463 2.126 1.706l-.709 2.836.042-.02a.75.75 0 01.67 1.34l-.04.022c-1.147.573-2.438-.463-2.127-1.706l.71-2.836-.042.02a.75.75 0 11-.671-1.34l.041-.022zM12 9a.75.75 0 100-1.5.75.75 0 000 1.5z"
                  clip-rule="evenodd"/>
            </svg>
        </span>

        {{ $alert['message'] }}

        @if(isset($alert['dismissable']))
            <button
                type="button" data-te-alert-dismiss aria-label="Close"
                class="ml-auto box-content rounded-none border-none p-1 text-primary-900 opacity-50 hover:text-primary-900 hover:no-underline hover:opacity-75 focus:opacity-100 focus:shadow-none focus:outline-none">
            <span
                class="w-[1em] focus:opacity-100 disabled:pointer-events-none disabled:select-none disabled:opacity-25 [&.disabled]:pointer-events-none [&.disabled]:select-none [&.disabled]:opacity-25">
                  <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="h-6 w-6">
                    <path
                        fill-rule="evenodd" clip-rule="evenodd"
                        d="M5.47 5.47a.75.75 0 011.06 0L12 10.94l5.47-5.47a.75.75 0 111.06 1.06L13.06 12l5.47 5.47a.75.75 0 11-1.06 1.06L12 13.06l-5.47 5.47a.75.75 0 01-1.06-1.06L10.94 12 5.47 6.53a.75.75 0 010-1.06z"/>
                  </svg>
            </span>
            </button>
        @endif
    </div>
@endif

{{-- Secondary Alert --}}
@if(isset($alert['type']) && $alert['type'] === 'secondary')
    <div role="alert" data-te-alert-init data-te-alert-show
         class="hidden w-full items-center rounded-lg bg-gray-100 px-6 py-3 text-base text-gray-800 data-[te-alert-show]:inline-flex">
        <span class="mr-2">
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="h-5 w-5">
              <path
                  fill-rule="evenodd"
                  d="M12 2.25c-5.385 0-9.75 4.365-9.75 9.75s4.365 9.75 9.75 9.75 9.75-4.365 9.75-9.75S17.385 2.25 12 2.25zM12.75 9a.75.75 0 00-1.5 0v2.25H9a.75.75 0 000 1.5h2.25V15a.75.75 0 001.5 0v-2.25H15a.75.75 0 000-1.5h-2.25V9z"
                  clip-rule="evenodd"/>
            </svg>
        </span>

        {{ $alert['message'] }}

        @if(isset($alert['dismissable']))
            <button
                type="button" data-te-alert-dismiss aria-label="Close"
                class="ml-auto box-content rounded-none border-none p-1 text-gray-900 opacity-50 hover:text-gray-900 hover:no-underline hover:opacity-75 focus:opacity-100 focus:shadow-none focus:outline-none">
                <span
                    class="w-[1em] focus:opacity-100 disabled:pointer-events-none disabled:select-none disabled:opacity-25 [&.disabled]:pointer-events-none [&.disabled]:select-none [&.disabled]:opacity-25">
                      <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="h-6 w-6">
                        <path
                            fill-rule="evenodd" clip-rule="evenodd"
                            d="M5.47 5.47a.75.75 0 011.06 0L12 10.94l5.47-5.47a.75.75 0 111.06 1.06L13.06 12l5.47 5.47a.75.75 0 11-1.06 1.06L12 13.06l-5.47 5.47a.75.75 0 01-1.06-1.06L10.94 12 5.47 6.53a.75.75 0 010-1.06z"/>
                      </svg>
                </span>
            </button>
        @endif
    </div>
@endif

{{-- Info Alert --}}
@if(isset($alert['type']) && $alert['type'] === 'info')
    <div role="alert" data-te-alert-init data-te-alert-show
         class="hidden w-full items-center rounded-lg bg-indigo-100 px-6 py-3 text-base text-indigo-800 data-[te-alert-show]:inline-flex">
        <span class="mr-2">
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="h-5 w-5">
              <path
                  fill-rule="evenodd"
                  d="M12 2.25c-5.385 0-9.75 4.365-9.75 9.75s4.365 9.75 9.75 9.75 9.75-4.365 9.75-9.75S17.385 2.25 12 2.25zm4.28 10.28a.75.75 0 000-1.06l-3-3a.75.75 0 10-1.06 1.06l1.72 1.72H8.25a.75.75 0 000 1.5h5.69l-1.72 1.72a.75.75 0 101.06 1.06l3-3z"
                  clip-rule="evenodd"/>
            </svg>
        </span>

        {{ $alert['message'] }}

        @if(isset($alert['dismissable']))
            <button
                type="button" data-te-alert-dismiss aria-label="Close"
                class="ml-auto box-content rounded-none border-none p-1 text-indigo-900 opacity-50 hover:text-indigo-900 hover:no-underline hover:opacity-75 focus:opacity-100 focus:shadow-none focus:outline-none">
                <span
                    class="w-[1em] focus:opacity-100 disabled:pointer-events-none disabled:select-none disabled:opacity-25 [&.disabled]:pointer-events-none [&.disabled]:select-none [&.disabled]:opacity-25">
                      <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="h-6 w-6">
                        <path
                            fill-rule="evenodd" clip-rule="evenodd"
                            d="M5.47 5.47a.75.75 0 011.06 0L12 10.94l5.47-5.47a.75.75 0 111.06 1.06L13.06 12l5.47 5.47a.75.75 0 11-1.06 1.06L12 13.06l-5.47 5.47a.75.75 0 01-1.06-1.06L10.94 12 5.47 6.53a.75.75 0 010-1.06z"/>
                      </svg>
                </span>
            </button>
        @endif
    </div>
@endif

{{-- Success Alert --}}
@if(isset($alert['type']) && $alert['type'] === 'success')
    <div role="alert" data-te-alert-init data-te-alert-show
         class="hidden w-full items-center rounded-lg bg-success-100 px-6 py-3 text-base text-success-800 data-[te-alert-show]:inline-flex">
        <span class="mr-2">
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="h-5 w-5">
              <path
                  fill-rule="evenodd"
                  d="M2.25 12c0-5.385 4.365-9.75 9.75-9.75s9.75 4.365 9.75 9.75-4.365 9.75-9.75 9.75S2.25 17.385 2.25 12zm13.36-1.814a.75.75 0 10-1.22-.872l-3.236 4.53L9.53 12.22a.75.75 0 00-1.06 1.06l2.25 2.25a.75.75 0 001.14-.094l3.75-5.25z"
                  clip-rule="evenodd"/>
            </svg>
        </span>

        {{ $alert['message'] }}

        @if(isset($alert['dismissable']))
            <button
                type="button" data-te-alert-dismiss aria-label="Close"
                class="ml-auto box-content rounded-none border-none p-1 text-success-900 opacity-50 hover:text-success-900 hover:no-underline hover:opacity-75 focus:opacity-100 focus:shadow-none focus:outline-none">
                <span
                    class="w-[1em] focus:opacity-100 disabled:pointer-events-none disabled:select-none disabled:opacity-25 [&.disabled]:pointer-events-none [&.disabled]:select-none [&.disabled]:opacity-25">
                      <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="h-6 w-6">
                        <path
                            fill-rule="evenodd" clip-rule="evenodd"
                            d="M5.47 5.47a.75.75 0 011.06 0L12 10.94l5.47-5.47a.75.75 0 111.06 1.06L13.06 12l5.47 5.47a.75.75 0 11-1.06 1.06L12 13.06l-5.47 5.47a.75.75 0 01-1.06-1.06L10.94 12 5.47 6.53a.75.75 0 010-1.06z"/>
                      </svg>
                </span>
            </button>
        @endif
    </div>
@endif

{{-- Warning Alert --}}
@if(isset($alert['type']) && $alert['type'] === 'warning')
    <div role="alert" data-te-alert-init data-te-alert-show
         class="hidden w-full items-center rounded-lg bg-warning-100 px-6 text-base text-warning-800 data-[te-alert-show]:inline-flex
         {{ isset($alert['dismissable']) ? 'py-3' : 'py-4' }}">
        <span class="mr-2">
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="h-5 w-5">
              <path
                  fill-rule="evenodd"
                  d="M9.401 3.003c1.155-2 4.043-2 5.197 0l7.355 12.748c1.154 2-.29 4.5-2.599 4.5H4.645c-2.309 0-3.752-2.5-2.598-4.5L9.4 3.003zM12 8.25a.75.75 0 01.75.75v3.75a.75.75 0 01-1.5 0V9a.75.75 0 01.75-.75zm0 8.25a.75.75 0 100-1.5.75.75 0 000 1.5z"
                  clip-rule="evenodd"/>
            </svg>
        </span>

        {{ $alert['message'] }}

        @if(isset($alert['dismissable']))
            <button
                type="button" data-te-alert-dismiss aria-label="Close"
                class="ml-auto box-content rounded-none border-none p-1 text-warning-900 opacity-50 hover:text-warning-900 hover:no-underline hover:opacity-75 focus:opacity-100 focus:shadow-none focus:outline-none">
                <span
                    class="w-[1em] focus:opacity-100 disabled:pointer-events-none disabled:select-none disabled:opacity-25 [&.disabled]:pointer-events-none [&.disabled]:select-none [&.disabled]:opacity-25">
                      <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="h-6 w-6">
                        <path
                            fill-rule="evenodd" clip-rule="evenodd"
                            d="M5.47 5.47a.75.75 0 011.06 0L12 10.94l5.47-5.47a.75.75 0 111.06 1.06L13.06 12l5.47 5.47a.75.75 0 11-1.06 1.06L12 13.06l-5.47 5.47a.75.75 0 01-1.06-1.06L10.94 12 5.47 6.53a.75.75 0 010-1.06z"/>
                      </svg>
                </span>
            </button>
        @endif
    </div>
@endif

{{-- Danger Alert --}}
@if(isset($alert['type']) && $alert['type'] === 'danger')
    <div role="alert" data-te-alert-init data-te-alert-show
         class="hidden w-full items-center rounded-lg bg-danger-100 px-6 py-3 text-base text-danger-800 data-[te-alert-show]:inline-flex">
        <span class="mr-2">
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="h-5 w-5">
              <path
                  fill-rule="evenodd"
                  d="M12 2.25c-5.385 0-9.75 4.365-9.75 9.75s4.365 9.75 9.75 9.75 9.75-4.365 9.75-9.75S17.385 2.25 12 2.25zm-1.72 6.97a.75.75 0 10-1.06 1.06L10.94 12l-1.72 1.72a.75.75 0 101.06 1.06L12 13.06l1.72 1.72a.75.75 0 101.06-1.06L13.06 12l1.72-1.72a.75.75 0 10-1.06-1.06L12 10.94l-1.72-1.72z"
                  clip-rule="evenodd"/>
            </svg>
        </span>

        {{ $alert['message'] }}

        @if(isset($alert['dismissable']))
            <button
                type="button" data-te-alert-dismiss aria-label="Close"
                class="ml-auto box-content rounded-none border-none p-1 text-danger-900 opacity-50 hover:text-danger-900 hover:no-underline hover:opacity-75 focus:opacity-100 focus:shadow-none focus:outline-none">
                <span
                    class="w-[1em] focus:opacity-100 disabled:pointer-events-none disabled:select-none disabled:opacity-25 [&.disabled]:pointer-events-none [&.disabled]:select-none [&.disabled]:opacity-25">
                      <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="h-6 w-6">
                        <path
                            fill-rule="evenodd" clip-rule="evenodd"
                            d="M5.47 5.47a.75.75 0 011.06 0L12 10.94l5.47-5.47a.75.75 0 111.06 1.06L13.06 12l5.47 5.47a.75.75 0 11-1.06 1.06L12 13.06l-5.47 5.47a.75.75 0 01-1.06-1.06L10.94 12 5.47 6.53a.75.75 0 010-1.06z"/>
                      </svg>
                </span>
            </button>
        @endif
    </div>
@endif

@push('scripts')
    <script>
        document.querySelectorAll('[data-te-alert-dismiss]').forEach(function (element) {
            element.addEventListener('click', function () {
                element.closest('[data-te-alert-init]').remove();
            });
        });
    </script>
@endpush
