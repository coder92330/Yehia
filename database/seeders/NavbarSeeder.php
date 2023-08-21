<?php

namespace Database\Seeders;

use App\Models\LandingPage\LandingPageKey;
use App\Models\Navbar;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class NavbarSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $navbar = LandingPageKey::firstOrCreate(['key' => 'navbar']);
        $this->createNabar($navbar);
        $this->createChilderenNavbar($navbar);
    }

    private function createNabar(LandingPageKey $navbar): void
    {
        $navbar->navbars()->createMany([
            [
                'title' => ['en' => 'Home', 'ar' => 'الرئيسية'],
                'url' => '/',
                'order' => 1,
                'is_active' => true,
            ],
            [
                'title' => ['en' => 'About', 'ar' => 'من نحن'],
                'order' => 2,
                'is_active' => true,
            ],
            [
                'title' => ['en' => 'Services', 'ar' => 'خدماتنا'],
                'url' => '#services',
                'order' => 3,
                'is_active' => true,
            ],
            [
                'title' => ['en' => 'Training Programs', 'ar' => 'برامج التدريب'],
                'url' => '#training-programs',
                'order' => 4,
                'is_active' => true,
            ],
            [
                'title' => ['en' => 'Contact Us', 'ar' => 'تواصل معنا'],
                'url' => '/contact-us',
                'order' => 5,
                'is_active' => true,
            ]
        ]);
    }

    private function createChilderenNavbar(LandingPageKey $navbar): void
    {
        $parent_id = Navbar::firstWhere('title->en', 'About')->id;
        $navbar->navbars()->createMany([
            [
                'title' => ['en' => 'Our Team', 'ar' => 'فريق العمل'],
                'url' => '/our-team',
                'order' => 1,
                'is_active' => true,
                'parent_id' => $parent_id,
            ],
            [
                'title' => ['en' => 'Assessment Committee', 'ar' => 'لجنة التقييم'],
                'url' => '/assessment-committee',
                'order' => 2,
                'is_active' => true,
                'parent_id' => $parent_id,
            ],
            [
                'title' => ['en' => 'Training Committee', 'ar' => 'لجنة التدريب'],
                'url' => '/training-committee',
                'order' => 3,
                'is_active' => true,
                'parent_id' => $parent_id,
            ],
            [
                'title' => ['en' => 'Accreditation', 'ar' => 'الاعتماد'],
                'url' => '/accreditation',
                'order' => 4,
                'is_active' => true,
                'parent_id' => $parent_id,
            ],
        ]);
    }
}
