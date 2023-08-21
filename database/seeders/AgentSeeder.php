<?php

namespace Database\Seeders;

use App\Models\Agent;
use App\Models\Setting;
use App\Models\Style;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class AgentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $admin = Agent::create([
            'first_name' => "Agent",
            "last_name" => "Admin",
            "username" => "agentadmin",
            "email" => "agentadmin@gmail.com",
            "company_id" => 1,
            "country_id" => 1,
            "password" => Hash::make("password"),
            "is_online" => true,
            "is_active" => true,
            "style_id" => Style::defaultStyleId(),
        ])
            ->assignRole(Role::findByName("admin", "agent"))
            ->syncPermissions(Permission::whereGuardName("agent")->get());

        $admin->settings()->syncWithPivotValues(
            Setting::whereNotIn("key", ["terms_and_conditions", "privacy_policy", "about_us", "contact_us", "faq"])->pluck("id")->toArray(),
            ["value" => 1]);

        $staff = Agent::create([
            'first_name' => "Agent",
            "last_name" => "Staff",
            "username" => "agentstaff",
            "email" => "agentstaff@gmail.com",
            "company_id" => 1,
            "country_id" => 1,
            "password" => Hash::make("password"),
            "is_online" => true,
            "is_active" => true,
            "style_id" => Style::defaultStyleId(),
        ])
            ->assignRole(Role::findByName("user", "agent"))
            ->syncPermissions(Role::findByName("user", "agent")->permissions);

        $staff->settings()->syncWithPivotValues(
            Setting::whereNotIn("key", ["terms_and_conditions", "privacy_policy", "about_us", "contact_us", "faq"])->pluck("id")->toArray(),
            ["value" => 1]);
    }
}
