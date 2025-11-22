<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Role;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class SuperAdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $this->command->info('Starting SuperAdminSeeder...');

        // Find the admin role, or abort if not found
        $adminRole = Role::where('slug', 'admin')->first();

        if (!$adminRole) {
            $this->command->error('Admin role not found. Please run RolePermissionSeeder first.');
            return;
        }

        // Create the super admin user
        $superAdmin = User::firstOrCreate(
            ['email' => 'superadmin@example.com'],
            [
                'name' => 'Super Admin',
                'password' => Hash::make('password'), // Change this in production!
                'email_verified_at' => now(),
            ]
        );

        // Assign the admin role to the super admin user
        if (!$superAdmin->hasRole('admin')) {
            $superAdmin->assignRole($adminRole);
            $this->command->info('Super admin user created and assigned admin role.');
        } else {
            $this->command->info('Super admin user already exists and has admin role.');
        }

        $this->command->info('✓ SuperAdminSeeder finished.');
        $this->command->info('---------------------------------');
        $this->command->info('Super Admin Credentials:');
        $this->command->info('Email: superadmin@example.com');
        $this->command->info('Password: password');
        $this->command->info('---------------------------------');
    }
}
