<?php

namespace Database\Seeders;

use App\Models\Style;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Permission;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        User::create([
            "name" => "super admin",
            "email" => "superadmin@admin.com",
            "password" => Hash::make("superadmin"),
            "country_id" => 1,
            "style_id" => Style::defaultStyleId(),
        ])
            ->assignRole("super_admin")
            ->syncPermissions(Permission::whereGuardName("web")->get());
    }
}
