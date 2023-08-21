<?php

namespace Database\Seeders;

use App\Models\Tourguide;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class SettingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $settings = [
            [
                'label' => ['en' => 'My Profile Added To Favorites', 'ar' => 'تمت إضافة ملفي الشخصي إلى المفضلة'],
                'key' => 'my_profile_added_to_favorites',
                'type' => 'boolean',
                'description' => ['en' => 'Send an email when a user adds your profile to their favorites', 'ar' => 'إرسال بريد إلكتروني عندما يضيف المستخدم ملفك الشخصي إلى المفضلة'],
                'group' => 'emails',
            ],
            [
                'label' => ['en' => 'Get Rated', 'ar' => 'الحصول على تقييم'],
                'key' => 'get_rated',
                'type' => 'boolean',
                'description' => ['en' => 'Get rated', 'ar' => 'الحصول على تقييم'],
                'group' => 'emails',
            ],
            [
                'label' => ['en' => 'Get a Booking', 'ar' => 'الحصول على حجز'],
                'key' => 'get_a_booking',
                'type' => 'boolean',
                'description' => ['en' => 'Get a booking', 'ar' => 'الحصول على حجز'],
                'group' => 'emails',
            ],
            [
                'label' => ['en' => 'Receive Notifications', 'ar' => 'تلقي الإخطارات'],
                'key' => 'receive_notifications',
                'type' => 'boolean',
                'description' => ['en' => 'Receive notifications', 'ar' => 'تلقي الإخطارات'],
                'group' => 'emails',
            ],
            [
                'label' => ['en' => 'Incoming messages are automatically sent to my personal email address', 'ar' => 'يتم إرسال الرسائل الواردة تلقائيًا إلى عنوان بريدي الإلكتروني الشخصي'],
                'key' => 'incoming_messages_notifications',
                'type' => 'boolean',
                'description' => ['en' => 'Incoming messages are automatically sent to my personal email address', 'ar' => 'يتم إرسال الرسائل الواردة تلقائيًا إلى عنوان بريدي الإلكتروني الشخصي'],
                'group' => 'emails',
            ],
            [
                'label' => ['en' => 'Assign Multi Day Events', 'ar' => 'تعيين الأحداث متعددة الأيام'],
                'key' => 'assign_multi_day_events',
                'type' => 'boolean',
                'description' => ['en' => 'Assign multi day events', 'ar' => 'تعيين الأحداث متعددة الأيام'],
                'group' => 'events',
            ],
            [
                'label' => ['en' => 'Assign Full Day Events', 'ar' => 'تعيين الأحداث طوال اليوم'],
                'key' => 'assign_full_day_events',
                'type' => 'boolean',
                'description' => ['en' => 'Assign full day events', 'ar' => 'تعيين الأحداث طوال اليوم'],
                'group' => 'events',
            ],
            [
                'label' => ['en' => 'Assign Half Day Events', 'ar' => 'تعيين الأحداث نصف اليوم'],
                'key' => 'assign_half_day_events',
                'type' => 'boolean',
                'description' => ['en' => 'Assign half day events', 'ar' => 'تعيين الأحداث نصف اليوم'],
                'group' => 'events',
            ],
//            [
//                'label' => ['en' => 'Terms and conditions', 'ar' => 'الأحكام والشروط'],
//                'key' => 'terms_and_conditions',
//                'type' => 'string',
//                'description' => ['en' => 'Terms and conditions', 'ar' => 'الأحكام والشروط'],
//                'group' => 'pages_and_apps',
//            ],
//            [
//                'label' => ['en' => 'Privacy policy', 'ar' => 'سياسة الخصوصية'],
//                'key' => 'privacy_policy',
//                'type' => 'string',
//                'description' => ['en' => 'Privacy policy', 'ar' => 'سياسة الخصوصية'],
//                'group' => 'pages_and_apps',
//            ],
//            [
//                'label' => ['en' => 'About us', 'ar' => 'معلومات عنا'],
//                'key' => 'about_us',
//                'type' => 'string',
//                'description' => ['en' => 'About us', 'ar' => 'معلومات عنا'],
//                'group' => 'pages_and_apps',
//            ],
//            [
//                'label' => ['en' => 'Contact us', 'ar' => 'اتصل بنا'],
//                'key' => 'contact_us',
//                'type' => 'string',
//                'description' => ['en' => 'Contact us', 'ar' => 'اتصل بنا'],
//                'group' => 'pages_and_apps',
//            ],
//            [
//                'label' => ['en' => 'FAQ', 'ar' => 'التعليمات'],
//                'key' => 'faq',
//                'type' => 'string',
//                'description' => ['en' => 'FAQ', 'ar' => 'التعليمات'],
//                'group' => 'pages_and_apps',
//            ],
//            [
//                'label' => 'Receive Email Notifications',
//                'key' => 'receive_email_notifications',
////                'type' => 'boolean',
//                'description' => 'Receive email notifications',
//            ],
//            [
//                'label' => 'Maximum Orders',
//                'key' => 'maximum_orders',
//                'value' => 'unlimited',
//                'type' => 'string',
//                'description' => 'Maximum orders',
//            ],
//            [
//                'label' => 'Completed Events added on my profile',
//                'key' => 'completed_events_added_on_my_profile',
//                'value' => 12,
//                'type' => 'integer',
//                'description' => 'Completed events added on my profile',
//            ],
//            [
//                'label' => 'Notify me about upcoming events before',
//                'key' => 'notify_me_about_upcoming_events_before',
//                'value' => 7,
//                'type' => 'integer',
//                'description' => 'Notify me about upcoming events before',
//            ],
        ];

        foreach ($settings as $setting) {
            \App\Models\Setting::updateOrCreate(['key' => $setting['key'],], $setting);
        }
    }
}
