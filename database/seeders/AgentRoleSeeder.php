<?php

namespace Database\Seeders;

use App\Models\Company;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class AgentRoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Permission::firstOrCreate(['name' => 'Company Chat']);
        Permission::firstOrCreate(['name' => 'Company Chat', 'guard_name' => 'agent']);

        Role::firstOrCreate(['name' => 'super_admin', 'guard_name' => 'web'])->givePermissionTo(Permission::where([['name', 'Company Chat'], ['guard_name', 'web']])->first());
        Role::firstOrCreate(['name' => 'super_admin', 'guard_name' => 'agent'])->syncPermissions(Permission::where('guard_name', 'agent')->get()->pluck('name')->toArray());

        Company::all()->each(function ($company) {
            if ($company->agents->first()) {
                $company->agents->first()
                    ->removeRole(Role::findByName('admin', 'agent'))
                    ->assignRole(Role::findByName('super_admin', 'agent'));
            }
        });
    }
}
