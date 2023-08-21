<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     * @throws \Exception
     */
    public function run()
    {
        $this->call([
            RolesAndPermissionsSeeder::class,
            CountrySeeder::class,
            CitySeeder::class,
            LanguageSeeder::class,
            SkillSeeder::class,
            SettingSeeder::class,
            LandingPageKeySeeder::class,
            LandingPageContentSeeder::class,
            ServiceSeeder::class,
            StyleSeeder::class,
            PackageSeeder::class,
            NavbarSeeder::class,
            CompanySeeder::class,
            UserSeeder::class,
            AgentSeeder::class,
            TourguideSeeder::class,
            AgentRoleSeeder::class,
//            EventSeeder::class,
            PackageStyleSeeder::class,
            PageSeeder::class,
        ]);

//        // Country has states and states has cities
//        Country::factory()->count(10)->create()->each(function ($country) {
//            $country->states()->saveMany(State::factory()->count(10)->make())->each(function ($state) {
//                $state->cities()->saveMany(City::factory()->count(10)->make());
//            });
//        });
    }
}
