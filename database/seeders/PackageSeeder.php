<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PackageSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        collect([
            [
                'name' => ['en' => 'Silver', 'ar' => 'فضي'],
                'description' => ['en' => 'Silver Package', 'ar' => 'باقة فضية'],
                'price' => 100,
                'duration' => 1,
                'duration_type' => ['en' => 'month', 'ar' => 'شهر'],
                'users_limit' => 1,
                'is_active' => 1,
                'start_at' => now(),
                'end_at' => now()->addMonth(),
            ],
            [
                'name' => ['en' => 'Gold', 'ar' => 'ذهبي'],
                'description' => ['en' => 'Gold Package', 'ar' => 'باقة ذهبية'],
                'price' => 200,
                'duration' => 3,
                'duration_type' => ['en' => 'month', 'ar' => 'شهر'],
                'users_limit' => 3,
                'is_active' => 1,
                'start_at' => now(),
                'end_at' => now()->addMonths(3),
            ],
            [
                'name' => ['en' => 'Platinum', 'ar' => 'بلاتينيوم'],
                'description' => ['en' => 'Platinum Package', 'ar' => 'باقة بلاتينية'],
                'price' => 300,
                'duration' => 6,
                'duration_type' => ['en' => 'month', 'ar' => 'شهر'],
                'users_limit' => 5,
                'is_active' => 1,
                'start_at' => now(),
                'end_at' => now()->addMonths(6),
            ],
        ])->each(function ($package) {
            \App\Models\Package::create($package);
        });
    }
}
