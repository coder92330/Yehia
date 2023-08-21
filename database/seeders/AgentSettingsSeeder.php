<?php

namespace Database\Seeders;

use App\Models\Agent;
use App\Models\Setting;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class AgentSettingsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        foreach (Agent::all() as $agent) {
            $agent->settings()->syncWithPivotValues(Setting::all()->pluck("id")->toArray(), ["value" => 1]);
        }
    }
}
