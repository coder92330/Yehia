<?php

namespace Database\Seeders;

use App\Models\Country;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CountrySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
            Country::create([
                'name'         => ['en' => 'Egypt', 'ar' => 'مصر'],
                'is_active'    => true,
                'country_code' => '20',
            ]);
    }
}
