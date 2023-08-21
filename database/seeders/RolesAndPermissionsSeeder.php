<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Str;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RolesAndPermissionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        $this->createFilamentPermissions();
        $this->createAgentPermission();

        Role::create(['name' => 'admin']);
        Role::create(['name' => 'super_admin'])->syncPermissions(Permission::where('guard_name', 'web')->get());
        Role::create(['name' => 'admin', "guard_name" => "agent"])->syncPermissions(Permission::where('guard_name', 'agent')->get()->pluck('name')->toArray());
        Role::create(['name' => 'user', "guard_name" => "agent"])->syncPermissions(Permission::whereIn('name', $this->defultPermissions())->where('guard_name', 'agent')->get()->pluck('name')->toArray());
    }

    private function createFilamentPermissions(): void
    {
        collect(Route::getRoutes()->getRoutesByName())
            ->keys()
            ->filter(function ($route) {
                return Str::startsWith($route, ['filament.pages.', 'filament.resources.'])
                    && !Str::endsWith($route, ['.login', '.logout', '.register', '.password.request', '.password.reset',
                        '.password.update', '.permissions.index', '.permissions.create', '.permissions.edit', 'dashboard']);
            })
            ->map(function ($route) {
                return $this->permissions(Str::replace(['filament.pages.', 'filament.resources.'], '', $route));
            })
            ->each(function ($route) {
                Permission::create(['name' => $route]);
            });
    }

    private function createAgentPermission()
    {
        collect(Route::getRoutes()->getRoutesByName())
            ->keys()
            ->filter(function ($route) {
                return Str::startsWith($route, ['agent.pages.', 'agent.resources.'])
                    && !Str::endsWith($route, ['.login', '.logout', '.register', '.password.request', '.password.reset',
                        '.password.update', '.permissions.index', '.permissions.create', '.permissions.edit', 'dashboard']);
            })
            ->map(function ($route) {
                return $this->permissions(Str::replace(['agent.pages.', 'agent.resources.'], '', $route));
            })
            ->each(function ($route) {
                Permission::create(['name' => $route, "guard_name" => "agent"]);
            });
    }

    private function permissions($route)
    {
        $route = Str::replace(['.', 'index', 'create', 'edit'], ['.', 'List', 'Create', 'Edit'], $route);
        $route = Str::contains($route, '-') && Str::contains($route, '.')
            ? Str::replace(['.', '-'], ['.', ' '], $route)
            : $route;

        return Str::contains($route, '-')
            ? ucwords(implode(' ', explode('-', $route)))
            : ucwords(implode(' ', array_reverse(explode('.', $route))));
    }

    private function defultPermissions(): array
    {
        return [
            'About',
            'Calendar',
            'Chat',
            'New Event',
            'Profile',
            'List Bookings',
            'Create Bookings',
            'View Bookings',
            'Edit Bookings',
            'List Favourites',
            'List Confirmed Bookings',
            'Create Confirmed Bookings',
            'View Confirmed Bookings',
            'Edit Confirmed Bookings',
            'List Tourguides',
            'View Tourguides',
        ];
    }
}
