<?php

namespace Database\Seeders;

use App\Models\Auth\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     * 
     * Creates admin accounts. Run this via:
     * php artisan db:seed --class=AdminSeeder
     * 
     * Or create admins via artisan command:
     * php artisan ablelink:create-admin
     */
    public function run(): void
    {
        // F2 - Rifat Jahan Roza
        // Create default admin account
        User::updateOrCreate(
            ['email' => 'admin@ablelink.com'],
            [
                'name' => 'Administrator',
                'role' => User::ROLE_ADMIN,
                'email_verified_at' => now(),
                'password' => Hash::make('admin123'), // Change in production!
            ]
        );

        $this->command->info('Admin account created: admin@ablelink.com / admin123');
        $this->command->warn('⚠️  Please change the admin password immediately!');
    }
}
