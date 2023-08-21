<?php

namespace Database\Seeders;

use App\Models\Style;
use Illuminate\Database\Seeder;

class PackageStyleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        \App\Models\Package::all()->each(function ($package) {
            $styles = \App\Models\Style::all()->random(3);
            if (!$styles->contains(Style::defaultStyle())) {
                $styles->prepend(Style::defaultStyle());
            }
            $package->styles()->attach($styles);
        });
    }
}
