<?php

namespace Database\Seeders;

use App\Models\LandingPage\LandingPageKey;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class LandingPageContentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $this->createSlider();
        $this->createAboutUs();
        $this->createServices();
        $this->createSubscribe();
        $this->createFooter();
        $this->createContactUs();
    }

    private function createSlider(): void
    {
        LandingPageKey::where('key', 'Slider')->first()->contents()->createMany([
            [
                'name'    => ['en' => 'slider_hero', 'ar' => 'slider_hero'],
                'title'   => ['en' => 'Find Your Tour Guide Now', 'ar' => 'ابحث عن مرشدك السياحي الآن'],
                'content' => ['en' => 'Expert Guidance at Your Fingertips: Find Your Ideal Tour Guide Today', 'ar' => 'الإرشاد الخبير عند بعد , ابحث عن مرشدك السياحي المثالي اليوم'],
            ],
            [
                'name'        => ['en' => 'slider_tourguide', 'ar' => 'slider_tourguide'],
                'title'       => ['en' => 'Tour Guide Login', 'ar' => 'تسجيل دخول المرشد السياحي'],
                'content'     => ['en' => 'Login to your account to manage your tourguide profile.', 'ar' => 'قم بتسجيل الدخول إلى حسابك لإدارة ملفك الشخصي للمرشد السياحي.'],
                'button_text' => ['en' => 'Tour Guide Login', 'ar' => 'تسجيل دخول المرشد السياحي'],
            ],
            [
                'name'        => ['en' => 'slider_ourservice', 'ar' => 'slider_ourservice'],
                'title'       => ['en' => 'Training Programs', 'ar' => 'برامج التدريب'],
                'content'     => ['en' => 'Embark on a Journey of Learning with Our Training Programs', 'ar' => 'انطلق في رحلة التعلم مع برامج التدريب الخاصة بنا'],
                'button_text' => ['en' => 'Explore Now', 'ar' => 'استكشف الآن'],
            ],
            [
                'name'        => ['en' => 'slider_agent', 'ar' => 'slider_agent'],
                'title'       => ['en' => 'Travel Agent Login', 'ar' => 'تسجيل دخول وكيل السفر'],
                'content'     => ['en' => 'Login to your account to manage your tourguide profile.', 'ar' => 'قم بتسجيل الدخول إلى حسابك لإدارة ملفك الشخصي للمرشد السياحي.'],
                'button_text' => ['en' => 'Travel Agent Login', 'ar' => 'تسجيل دخول وكيل السفر'],
            ]
        ]);
    }

    private function createAboutUs(): void
    {
        LandingPageKey::where('key', 'About Us')->first()->contents()->create([
            'title'       => ['en' => 'Guides Navigator is the world’s first company of its kind.', 'ar' => 'Guides Navigator هي أول شركة من نوعها في العالم.'],
            'content'     => ['en' => 'We are providing individually tailored, comprehensive services for the two pillars of tourism, Guides and Travel agents, based on our hands-on experience and expertise.', 'ar' => 'نحن نقدم خدمات مخصصة بشكل فردي وشاملة لركائز السياحة ، وهما المرشدون ووكلاء السفر ، استنادًا إلى خبرتنا العملية وخبرتنا.'],
            'button_url'  => '#',
            'button_text' => ['en' => 'Read More', 'ar' => 'اقرأ أكثر'],
        ]);
    }

    private function createServices(): void
    {
        LandingPageKey::where('key', 'Our Services')->first()->contents()->create([
            'title'   => ['en' => 'Our Services', 'ar' => 'خدماتنا'],
            'content' => ['en' => 'We are proud to provide the ideal work environment for all our partners in the tourism sector, including tour guides and tourism companies.', 'ar' => 'نحن فخورون بتوفير بيئة عمل مثالية لجميع شركائنا في قطاع السياحة ، بما في ذلك المرشدين السياحيين وشركات السياحة.'],
        ]);
    }

    private function createSubscribe(): void
    {
        LandingPageKey::where('key', 'Subscribe')->first()->contents()->create([
            'title'       => ['en' => 'Subscribe to our newsletter.', 'ar' => 'اشترك في النشرة الإخبارية.'],
            'content'     => ['en' => 'We will send you best offers, greatest discounts and most exciting news.', 'ar' => 'سنرسل لك أفضل العروض وأكبر الخصومات وأكثر الأخبار إثارة.'],
            'button_text' => ['en' => 'Subscribe', 'ar' => 'اشترك'],
        ]);
    }

    private function createFooter(): void
    {
        LandingPageKey::where('key', 'Footer')->first()->contents()->createMany([
            [
                'name'    => ['en' => 'footer_main_section', 'ar' => 'footer_main_section'],
                'title'   => ['en' => 'Guides Navigator', 'ar' => 'Guides Navigator'],
                'content' => ['en' => 'Guides Navigator is the world’s first company of its kind. We are talking Travel business to a new era', 'ar' => 'Guides Navigator هي أول شركة من نوعها في العالم. نحن نتحدث عن أعمال السفر إلى عصر جديد'],
            ],
            [
                'name'    => ['en' => 'footer_social_media_facebook', 'ar' => 'footer_social_media_facebook'],
                'title'   => ['en' => 'Facebook', 'ar' => 'Facebook'],
                'content' => 'https://www.facebook.com/profile.php?id=100065082036521&mibextid=ZbWKwL',
            ],
            [
                'name'    => ['en' => 'footer_social_media_instagram', 'ar' => 'footer_social_media_instagram'],
                'title'   => ['en' => 'Instagram', 'ar' => 'Instagram'],
                'content' => 'https://www.instagram.com',
            ],
            [
                'name'    => ['en' => 'footer_social_media_linkedin', 'ar' => 'footer_social_media_linkedin'],
                'title'   => ['en' => 'Linkedin', 'ar' => 'Linkedin'],
                'content' => 'https://www.linkedin.com/company/guides-navigator',
            ],
            [
                'name'    => ['en' => 'footer_useful_links', 'ar' => 'footer_useful_links'],
                'title'   => ['en' => 'Home', 'ar' => 'الصفحة الرئيسية'],
                'content' => '#',
            ],
            [
                'name'    => ['en' => 'footer_useful_links', 'ar' => 'footer_useful_links'],
                'title'   => ['en' => 'About Us', 'ar' => 'معلومات عنا'],
                'content' => '#',
            ],
            [
                'name'    => ['en' => 'footer_useful_links', 'ar' => 'footer_useful_links'],
                'title'   => ['en' => 'Our Services', 'ar' => 'خدماتنا'],
                'content' => '#',
            ],
            [
                'name'    => ['en' => 'footer_useful_links', 'ar' => 'footer_useful_links'],
                'title'   => ['en' => 'Contact Us', 'ar' => 'اتصل بنا'],
                'content' => '#',
            ],
            [
                'name'    => ['en' => 'footer_useful_links', 'ar' => 'footer_useful_links'],
                'title'   => ['en' => 'Privacy Policy', 'ar' => 'سياسة الخصوصية'],
                'content' => '#',
            ],
            [
                'name'    => ['en' => 'footer_useful_links', 'ar' => 'footer_useful_links'],
                'title'   => ['en' => 'Terms & Conditions', 'ar' => 'الأحكام والشروط'],
                'content' => '#',
            ],
            [
                'name'    => ['en' => 'footer_contact_info_phone', 'ar' => 'footer_contact_info_phone'],
                'title'   => ['en' => 'Phone', 'ar' => 'هاتف'],
                'content' => '+2 251 866 77',
            ],
            [
                'name'    => ['en' => 'footer_contact_info_phone_2', 'ar' => 'footer_contact_info_phone_2'],
                'title'   => ['en' => 'Second Phone', 'ar' => 'الهاتف الثاني'],
                'content' => '010 504 801 88',
            ],
            [
                'name'    => ['en' => 'footer_contact_info_address', 'ar' => 'footer_contact_info_address'],
                'title'   => ['en' => 'Address', 'ar' => 'عنوان'],
                'content' => ['en' => '5 Building Street 295, New Maadi, Cairo, Egypt', 'ar' => '5 شارع المبنى 295 ، المعادي الجديدة ، القاهرة ، مصر'],
            ],
            [
                'name'    => ['en' => 'footer_download_our_app_content', 'ar' => 'footer_download_our_app_content'],
                'title'   => ['en' => 'Download Our App', 'ar' => 'حمل تطبيقنا'],
                'content' => ['en' => 'Download our free app and stay up to date with all our latest updates.', 'ar' => 'قم بتنزيل تطبيقنا المجاني وكن على اطلاع دائم بجميع آخر التحديثات.']
            ],
            [
                'name'    => ['en' => 'footer_download_our_app_app_store_link', 'ar' => 'footer_download_our_app_app_store_link'],
                'title'   => ['en' => 'App Store Link', 'ar' => 'رابط متجر App Store'],
                'content' => '#',
            ],
            [
                'name'    => ['en' => 'footer_download_our_app_google_play_link', 'ar' => 'footer_download_our_app_google_play_link'],
                'title'   => ['en' => 'Google Play Link', 'ar' => 'رابط Google Play'],
                'content' => '#',
            ],
            [
                'name'    => ['en' => 'footer_download_our_app_google_play_link', 'ar' => 'footer_download_our_app_google_play_link'],
                'title'   => ['en' => 'Google Play Link', 'ar' => 'رابط Google Play'],
                'content' => '#',
            ]
        ]);
    }

    private function createContactUs(): void
    {
        LandingPageKey::where('key', 'Contact Us')->first()->contents()->create([
            'title'   => ['en' => 'Contact Us', 'ar' => 'اتصل بنا'],
            'content' => ['en' => "Have more questions? We'll find the answers. Wether you're ready to book a trip now or simply want to learn more about what Guides Navigator has to offer, our consultants are available to help you.", 'ar' => 'هل لديك المزيد من الأسئلة؟ سنجد الإجابات. سواء كنت على استعداد لحجز رحلة الآن أو ترغب فقط في معرفة المزيد عن ما يقدمه Guides Navigator ، فإن مستشارينا متاحون لمساعدتك.'],
        ]);
    }
}
