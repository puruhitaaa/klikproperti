<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create admin user
        $admin = User::create([
            'name' => 'Administrator',
            'email' => 'admin@klikproperti.com',
            'password' => Hash::make('password'),
            'email_verified_at' => now(),
            'is_verified' => true,
        ]);
        $admin->assignRole('admin');

        if (app()->environment('local')) {
            // Create test users for each role in local environment
            $testUsers = [
                'agent' => [
                    'name' => 'Test Agent',
                    'email' => 'agent@klikproperti.com',
                ],
                'owner' => [
                    'name' => 'Test Owner',
                    'email' => 'owner@klikproperti.com',
                ],
                'renter' => [
                    'name' => 'Test Renter',
                    'email' => 'renter@klikproperti.com',
                ],
            ];

            foreach ($testUsers as $role => $userData) {
                $user = User::create([
                    'name' => $userData['name'],
                    'email' => $userData['email'],
                    'password' => Hash::make('password'),
                    'email_verified_at' => now(),
                    'is_verified' => true,
                ]);
                $user->assignRole($role);
            }
        }
    }
}
