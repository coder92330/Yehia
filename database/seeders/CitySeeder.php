<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CitySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $cities = [
            ['en' => 'Great Cairo', 'ar' => 'القاهرة الكبرى'],
            ['en' => 'Alexandria', 'ar' => 'الإسكندرية'],
            ['en' => 'Marsa Alam', 'ar' => 'مرسى علم'],
            ['en' => 'Hurghada', 'ar' => 'الغردقة'],
            ['en' => 'Sharm El-Shaikh', 'ar' => 'شرم الشيخ'],
            ['en' => 'Aswan', 'ar' => 'أسوان'],
            ['en' => 'Luxor', 'ar' => 'الأقصر'],
            ['en' => 'Minya', 'ar' => 'المنيا'],
            ['en' => 'Qalyubia', 'ar' => 'القليوبية'],
            ['en' => 'Giza', 'ar' => 'الجيزة'],
        ];

        foreach ($cities as $city) {
            \App\Models\City::create([
                'country_id' => 1,
                'name'       => $city,
                'is_active'  => true
            ]);
        }
    }
}
