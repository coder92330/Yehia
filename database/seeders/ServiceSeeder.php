<?php

namespace Database\Seeders;

use App\Models\LandingPage\LandingPageKey;
use App\Models\Service;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ServiceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $landingPageKey = LandingPageKey::where('key', 'Our Services')->first();
        collect([
            [
                'landing_page_key_id' => $landingPageKey->id,
                'title'               => ['en' => 'Guides Assessment', 'ar' => 'تقييم المرشدين'],
                'content'             => ['en' => 'Assessment to every applying guide to mark and enhance your skills.', 'ar' => 'تقييم لكل مرشد يتقدم للعمل لتحديد وتحسين مهاراتك.'],
            ],
            [
                'landing_page_key_id' => $landingPageKey->id,
                'title'               => ['en' => 'Guides Training', 'ar' => 'تدريب المرشدين'],
                'content'             => ['en' => 'Comprehensive training program offered to our approved guides, to certify service quality for ultimate client satisfaction.', 'ar' => 'برنامج تدريب شامل يقدم لمرشدينا المعتمدين لضمان جودة الخدمة لرضا العملاء النهائي.'],
            ],
            [
                'landing_page_key_id' => $landingPageKey->id,
                'title'               => ['en' => 'Our Ground Aduit', 'ar' => 'تدقيقنا الأرضي'],
                'content'             => ['en' => 'Our expert quality assurance team will run a weekly audit on our listed guides, this is to confirm that our quality-of-service standards is adhered to.', 'ar' => 'سيقوم فريق ضمان الجودة المتخصص لدينا بإجراء تدقيق أسبوعي على مرشدينا المدرجين ، وذلك للتأكد من التزامنا بمعايير جودة الخدمة.'],
            ],
            [
                'landing_page_key_id' => $landingPageKey->id,
                'title'               => ['en' => 'Guides Listing', 'ar' => 'قائمة المرشدين'],
                'content'             => ['en' => 'We are committed to create positive working environment to ensure personal and professional development.', 'ar' => 'نحن ملتزمون بتوفير بيئة عمل إيجابية لضمان التطور الشخصي والمهني.'],
            ],
            [
                'landing_page_key_id' => $landingPageKey->id,
                'title'               => ['en' => 'Monthly Evaluation', 'ar' => 'التقييم الشهري'],
                'content'             => ['en' => 'Monthly evaluations are what all Tour Guides work towards in order to give their best performance and receive a high ranking.', 'ar' => 'التقييمات الشهرية هي ما يعمل عليه جميع المرشدين السياحيين من أجل تقديم أفضل أداء لهم والحصول على تصنيف عالي.'],
            ],
            [
                'landing_page_key_id' => $landingPageKey->id,
                'title'               => ['en' => 'Travel Agents', 'ar' => 'وكلاء السفر'],
                'content'             => ['en' => 'We collaborate with the finest guides in the market, covering a great range of different languages.', 'ar' => 'نحن نتعاون مع أفضل المرشدين في السوق ، والذين يغطون مجموعة كبيرة من اللغات المختلفة.'],
            ],
        ])->each(function ($service) {
            Service::create($service);
        });
    }
}
