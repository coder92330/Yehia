<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PageSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $pages = [
            [
                'title' => ['en' => 'Terms and conditions', 'ar' => 'الأحكام والشروط'],
                'slug' => 'terms_and_conditions',
            ],
            [
                'title' => ['en' => 'Privacy policy', 'ar' => 'سياسة الخصوصية'],
                'slug' => 'privacy_policy',
            ],
            [
                'title' => ['en' => 'About us', 'ar' => 'معلومات عنا'],
                'slug' => 'about_us',
            ],
            [
                'title' => ['en' => 'Contact us', 'ar' => 'اتصل بنا'],
                'slug' => 'contact_us',
            ],
            [
                'title' => ['en' => 'FAQ', 'ar' => 'التعليمات'],
                'slug' => 'faq',
            ],
        ];

        foreach ($pages as $page) {
            \App\Models\Page::create($page);
        }
    }
}
