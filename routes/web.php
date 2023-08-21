<?php

use App\Http\Controllers\HomeController;
use App\Http\Controllers\PageController;
use BezhanSalleh\FilamentLanguageSwitch\Http\Middleware\SwitchLanguageLocale;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Str;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::middleware(SwitchLanguageLocale::class)->group(function () {
    Route::get('/', [HomeController::class, 'index'])->name('home');

    Route::get('/contact-us', [HomeController::class, 'contact'])->name('contact-us');

    Route::post('/contact-us', [HomeController::class, 'contactSubmit'])->name('contact-us.submit');

    Route::post('/subscribe', [HomeController::class, 'subscribe'])->name('subscribe');

    Route::view('submit-form', 'pages.submit-form')->name('submit-form');

    Route::post('agent-submit-form', [HomeController::class, 'agentSubmitForm'])->name('agent-submit-form');

    Route::post('tourguide-submit-form', [HomeController::class, 'tourGuideSubmitForm'])->name('tourguide-submit-form');

    Route::get('page/{slug}', [PageController::class, 'show'])->name('page.show');

    Route::get('set-locale/{locale}', function ($locale) {
        if (array_key_exists($locale, config('filament-language-switch.locales')) || in_array($locale, config('app.locales'), true)) {
            app()->setLocale($locale);
            session()->put('locale', $locale);
        }
        return redirect()->back();
    })->name('setLocale');
});

