<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create roles
        $roles = [
            'admin' => [
                'description' => 'System administrator with full access',
                'permissions' => ['*'],
            ],
            'agent' => [
                'description' => 'Property agent who can appraise properties',
                'permissions' => [
                    'view_properties',
                    'create_appraisals',
                    'view_appraisals',
                    'edit_appraisals',
                ],
            ],
            'owner' => [
                'description' => 'Property owner who can list properties',
                'permissions' => [
                    'create_properties',
                    'view_properties',
                    'edit_properties',
                    'delete_properties',
                    'upload_documents',
                    'view_documents',
                ],
            ],
            'renter' => [
                'description' => 'User who can rent properties',
                'permissions' => [
                    'view_properties',
                    'upload_documents',
                    'view_documents',
                    'create_transactions',
                    'view_transactions',
                ],
            ],
        ];

        foreach ($roles as $name => $config) {
            $role = Role::create(['name' => $name, 'description' => $config['description']]);

            // For admin, we don't create specific permissions since they have wildcard access
            if ($name !== 'admin') {
                foreach ($config['permissions'] as $permission) {
                    Permission::firstOrCreate(['name' => $permission]);
                }
                $role->syncPermissions($config['permissions']);
            }
        }
    }
}
