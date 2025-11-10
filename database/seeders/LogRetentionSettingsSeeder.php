<?php
/**
 * Webtech-solutions 2025, All rights reserved.
 */

namespace Database\Seeders;

use App\Models\WebsiteSetting;
use Illuminate\Database\Seeder;

class LogRetentionSettingsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $settings = [
            [
                'key' => 'logs.user_activity_retention_days',
                'value' => '30',
                'type' => 'number',
                'group' => 'logs',
                'label' => 'User Activity Logs Retention (days)',
                'description' => 'Number of days to keep user activity logs before automatic deletion',
                'order' => 1,
            ],
            [
                'key' => 'logs.supervisor_activity_retention_days',
                'value' => '90',
                'type' => 'number',
                'group' => 'logs',
                'label' => 'Supervisor Activity Logs Retention (days)',
                'description' => 'Number of days to keep supervisor activity logs before automatic deletion',
                'order' => 2,
            ],
            [
                'key' => 'logs.completed_jobs_retention_days',
                'value' => '7',
                'type' => 'number',
                'group' => 'logs',
                'label' => 'Completed Jobs Retention (days)',
                'description' => 'Number of days to keep completed job records before automatic deletion',
                'order' => 3,
            ],
            [
                'key' => 'logs.sessions_retention_days',
                'value' => '7',
                'type' => 'number',
                'group' => 'logs',
                'label' => 'Sessions Retention (days)',
                'description' => 'Number of days to keep expired session records before automatic deletion',
                'order' => 4,
            ],
            [
                'key' => 'logs.card_audit_retention_days',
                'value' => '180',
                'type' => 'number',
                'group' => 'logs',
                'label' => 'Card Audit Logs Retention (days)',
                'description' => 'Number of days to keep card audit logs before automatic deletion',
                'order' => 5,
            ],
        ];

        foreach ($settings as $setting) {
            WebsiteSetting::updateOrCreate(
                ['key' => $setting['key']],
                $setting
            );
        }

        $this->command->info('Log retention settings seeded successfully!');
    }
}
