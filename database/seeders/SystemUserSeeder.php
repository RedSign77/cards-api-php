<?php
/**
 * Webtech-solutions 2025, All rights reserved.
 */

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class SystemUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create or update the system user (ID: 1)
        User::updateOrCreate(
            ['id' => 1],
            [
                'name' => 'System',
                'email' => 'system@cardsforge.local',
                'password' => Hash::make('system-user-no-login'),
                'supervisor' => true,
                'approved' => true,
                'email_verified_at' => now(),
            ]
        );
    }
}
