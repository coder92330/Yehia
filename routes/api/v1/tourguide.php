<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\V1\Tourguide\{AgentController,
    AppointmentController,
    ChatController,
    EventController,
    MailController,
    NotificationController,
    OrderController,
    ProfileController,
    SettingsController};

// Agent Group
Route::get('agent/{id}', [AgentController::class, 'show'])->name('agent.show');

Route::apiResource('events', EventController::class)->only('index', 'show');
Route::apiResource('orders', OrderController::class)->except('store', 'destroy');
Route::apiResource('mails', MailController::class)->except('update');
Route::apiResource('chats', ChatController::class)->except(['update', 'store']);
Route::post('chats/{room_id}/messages', [ChatController::class, 'store'])->name('chats.store');
Route::apiResource('appointments', AppointmentController::class);

// Notification Group
Route::group(['prefix' => 'notifications', 'as' => 'notifications.'], function () {
    Route::get('unread', [NotificationController::class, 'unreadNotifications'])->name('unread-notifications');
    Route::get('unread-count', [NotificationController::class, 'unreadNotificationsCount'])->name('unread-notifications-count');
    Route::post('mark-all-as-read', [NotificationController::class, 'markAllAsRead'])->name('mark-all-as-read');
    Route::post('delete-all', [NotificationController::class, 'deleteAll'])->name('delete-all');
    Route::post('decvice-key', [NotificationController::class, 'deviceKey'])->name('device-key');
    Route::get('news/{id}', [NotificationController::class, 'newsNotifications'])->name('news-notifications');
});
Route::apiResource('notifications', NotificationController::class)->except('store');

// List Skills , Languages
Route::get('skills', [ProfileController::class, 'skills'])->name('skills');
Route::get('languages', [ProfileController::class, 'languages'])->name('languages');

Route::get('settings', [SettingsController::class, 'index'])->name('settings.index');
Route::put('settings', [SettingsController::class, 'update'])->name('settings.update');
Route::group(['prefix' => 'profile'], function () {
    Route::get('/', [ProfileController::class, 'index'])->name('profile.index');
    Route::put('/update', [ProfileController::class, 'update'])->name('profile.update');
});

// Terms & Conditions , Privacy Policy , About Us , Contact Us , FAQ
Route::get('terms-and-conditions', [SettingsController::class, 'termsAndConditions'])->name('terms-and-conditions');
Route::get('privacy-policy', [SettingsController::class, 'privacyPolicy'])->name('privacy-policy');
Route::get('about-us', [SettingsController::class, 'aboutUs'])->name('about-us');
Route::get('contact-us', [SettingsController::class, 'contactUs'])->name('contact-us');
Route::get('faq', [SettingsController::class, 'faq'])->name('faq');
