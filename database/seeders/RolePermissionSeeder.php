<?php

namespace Database\Seeders;

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

        $permissions = [
            'dashboard.view',
            'catalog.manage',
            'user.manage',

            'request.view',
            'request.create',
            'request.update',
            'request.delete',

            'payment.view',
            'payment.record',

            'sample.view',
            'sample.receive',

            'job.view',
            'job.assign',

            'result.input',
            'result.edit',
            'result.validate',
            'result.revise',

            'report.generate',
            'report.issue',
            'report.print',
            'report.handover',

            'audit.view',
        ];

        foreach ($permissions as $permissionName) {
            Permission::firstOrCreate([
                'name' => $permissionName,
                'guard_name' => $guard,
            ]);
        }

        // 1. Administrator
        $admin = Role::firstOrCreate(['name' => 'admin', 'guard_name' => $guard]);
        $admin->syncPermissions(Permission::where('guard_name', $guard)->get());

        // 2. Petugas Pelayanan
        $serviceOfficer = Role::firstOrCreate(['name' => 'service_officer', 'guard_name' => $guard]);
        $serviceOfficer->syncPermissions([
            'dashboard.view',
            'request.view',
            'request.create',
            'request.update',
            'payment.view',
            'payment.record',
            'sample.view',
            'report.print',
            'report.handover',
        ]);

        // 3. Petugas Penerimaan Sampel
        $sampleReceiver = Role::firstOrCreate(['name' => 'sample_receiver', 'guard_name' => $guard]);
        $sampleReceiver->syncPermissions([
            'dashboard.view',
            'request.view',
            'sample.view',
            'sample.receive',
        ]);

        // 4. Petugas Laboratorium / Analis & Validator
        $labOfficer = Role::firstOrCreate(['name' => 'lab_officer', 'guard_name' => $guard]);
        $labOfficer->syncPermissions([
            'dashboard.view',
            'sample.view',
            'job.view',
            'job.assign',
            'result.input',
            'result.edit',
            'result.validate',
            'result.revise',
            'report.generate',
            'report.issue',
        ]);

        // 5. Pimpinan
        $leader = Role::firstOrCreate(['name' => 'leader', 'guard_name' => $guard]);
        $leader->syncPermissions([
            'dashboard.view',
            'request.view',
            'sample.view',
            'job.view',
            'report.print',
            'audit.view',
        ]);
    }
}
