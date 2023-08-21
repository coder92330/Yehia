<?php

namespace Database\Seeders;

use App\Models\LandingPage\LandingPageKey;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class LandingPageKeySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
//        foreach (['Slider', 'About Us', 'Our Services', 'Divider', 'Subscribe', 'Sponsors', 'Contact Us', 'Footer'] as $key) {
        foreach (['Slider', 'About Us', 'Our Services', 'Subscribe', 'Sponsors', 'Contact Us', 'Footer'] as $key) {
            LandingPageKey::create(['key' => $key]);
        }
    }
}
