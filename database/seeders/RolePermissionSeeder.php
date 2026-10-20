<?php

namespace Database\Seeders;

use App\Enums\PermissionEnum;
use App\Enums\RoleEnum;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;

class RolePermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Reset cached roles and permissions
        app()[PermissionRegistrar::class]->forgetCachedPermissions();

        $guard = 'employee';

        // 1. Seed all permissions from PermissionEnum
        foreach (PermissionEnum::cases() as $permission) {
            Permission::firstOrCreate([
                'name' => $permission->value,
                'guard_name' => $guard,
            ]);
        }

        // 2. Seed all roles from RoleEnum and sync default permissions
        foreach (RoleEnum::cases() as $roleEnum) {
            $role = Role::firstOrCreate([
                'name' => $roleEnum->value,
                'guard_name' => $guard,
            ]);

            $permissionNames = array_map(
                static fn (PermissionEnum $perm): string => $perm->value,
                $roleEnum->defaultPermissions()
            );

            $role->syncPermissions($permissionNames);
        }
    }
}
