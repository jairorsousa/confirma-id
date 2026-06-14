<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;

class RolesAndPermissionsSeeder extends Seeder
{
    /**
     * @var array<string, list<string>>
     */
    private array $rolePermissions = [
        'user' => [
            'verification.view-own',
            'verification.submit-own',
        ],
        'partner' => [
            'partner.query',
        ],
        'admin' => [
            'verification.review',
            'verification.approve',
            'verification.reject',
            'verification.request-correction',
            'verification.block',
            'partner.query',
            'partner.manage',
            'audit.view',
        ],
        'super_admin' => [
            'verification.view-own',
            'verification.submit-own',
            'verification.review',
            'verification.approve',
            'verification.reject',
            'verification.request-correction',
            'verification.block',
            'partner.query',
            'partner.manage',
            'audit.view',
        ],
    ];

    public function run(): void
    {
        app(PermissionRegistrar::class)->forgetCachedPermissions();

        foreach (collect($this->rolePermissions)->flatten()->unique() as $permission) {
            Permission::findOrCreate($permission, 'web');
        }

        foreach ($this->rolePermissions as $roleName => $permissions) {
            Role::findOrCreate($roleName, 'web')->syncPermissions($permissions);
        }

        app(PermissionRegistrar::class)->forgetCachedPermissions();
    }
}
