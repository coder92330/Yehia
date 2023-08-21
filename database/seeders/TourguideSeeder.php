<?php

namespace Database\Seeders;

use App\Models\Setting;
use App\Models\Style;
use App\Models\Tourguide;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class TourguideSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        $tourguide = Tourguide::where("email", "tourguide@gmail.com")->first()
            ?? Tourguide::create([
                'first_name' => "Tourguide",
                "last_name" => "User",
                "username" => "tourguide",
                "email" => "tourguide@gmail.com",
                "gender" => "male",
                "city_id" => 1,
                "password" => Hash::make("password"),
                "style_id" => Style::defaultStyleId(),
            ]);

        $tourguide->settings()->syncWithPivotValues(
            Setting::whereNotIn("key", ["terms_and_conditions", "privacy_policy", "about_us", "contact_us", "faq"])->pluck("id")->toArray(),
            ["value" => 1]);
    }
}
