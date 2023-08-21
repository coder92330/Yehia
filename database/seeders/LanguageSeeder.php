<?php

namespace Database\Seeders;

use App\Models\Language;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class LanguageSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $languages = [
            ['en' => 'Arabic', 'ar' => 'العربية'],
            ['en' => 'Bengali', 'ar' => 'البنغالية'],
            ['en' => 'Chinese', 'ar' => 'الصينية'],
            ['en' => 'English', 'ar' => 'الإنجليزية'],
            ['en' => 'French', 'ar' => 'الفرنسية'],
            ['en' => 'German', 'ar' => 'الألمانية'],
            ['en' => 'Hindi', 'ar' => 'الهندية'],
            ['en' => 'Indonesian', 'ar' => 'الأندونيسية'],
            ['en' => 'Italian', 'ar' => 'الإيطالية'],
            ['en' => 'Japanese', 'ar' => 'اليابانية'],
            ['en' => 'Korean', 'ar' => 'الكورية'],
            ['en' => 'Malay', 'ar' => 'الماليزية'],
            ['en' => 'Portuguese', 'ar' => 'البرتغالية'],
            ['en' => 'Russian', 'ar' => 'الروسية'],
            ['en' => 'Spanish', 'ar' => 'الإسبانية'],
            ['en' => 'Thai', 'ar' => 'التايلاندية'],
            ['en' => 'Turkish', 'ar' => 'التركية'],
            ['en' => 'Urdu', 'ar' => 'الأردية'],
            ['en' => 'Vietnamese', 'ar' => 'الفيتنامية'],
            ['en' => 'Other', 'ar' => 'أخرى'],
        ];

        foreach ($languages as $language) {
            Language::create([
                'name' => $language
            ]);
        }
    }
}
