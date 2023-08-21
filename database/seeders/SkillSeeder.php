<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class SkillSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $skills = [
            ['en' => 'web development', 'ar' => 'تطوير الويب'],
            ['en' => 'mobile development', 'ar' => 'تطوير الهواتف المحمولة'],
            ['en' => 'design', 'ar' => 'تصميم'],
            ['en' => 'writing', 'ar' => 'كتابة'],
            ['en' => 'translation', 'ar' => 'ترجمة'],
            ['en' => 'video & animation', 'ar' => 'فيديو ورسوم متحركة'],
            ['en' => 'music & audio', 'ar' => 'موسيقى وصوت'],
            ['en' => 'programming & tech', 'ar' => 'برمجة وتقنية'],
            ['en' => 'business', 'ar' => 'أعمال'],
            ['en' => 'lifestyle', 'ar' => 'نمط الحياة'],
            ['en' => 'fitness', 'ar' => 'لياقة بدنية'],
            ['en' => 'health', 'ar' => 'صحة'],
            ['en' => 'cooking', 'ar' => 'طبخ'],
            ['en' => 'gaming', 'ar' => 'ألعاب'],
            ['en' => 'education & training', 'ar' => 'تعليم وتدريب'],
            ['en' => 'marketing', 'ar' => 'تسويق'],
            ['en' => 'sales', 'ar' => 'مبيعات'],
            ['en' => 'finance', 'ar' => 'مالية'],
            ['en' => 'legal', 'ar' => 'قانوني'],
            ['en' => 'travel & local', 'ar' => 'سفر ومحلي'],
            ['en' => 'real estate', 'ar' => 'عقارات'],
            ['en' => 'engineering & architecture', 'ar' => 'هندسة وهندسة معمارية'],
            ['en' => 'science', 'ar' => 'علم'],
            ['en' => 'art & crafts', 'ar' => 'فنون وحرف يدوية'],
            ['en' => 'other', 'ar' => 'آخر']
        ];

        foreach ($skills as $skill) {
            \App\Models\Skill::create([
                'name' => $skill,
            ]);
        }
    }
}
