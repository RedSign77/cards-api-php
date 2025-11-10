<?php
/**
 * Webtech-solutions 2025, All rights reserved.
 */

namespace Database\Seeders;

use App\Models\Currency;
use Illuminate\Database\Seeder;

class CurrencySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $currencies = [
            [
                'code' => 'USD',
                'name' => 'US Dollar',
                'symbol' => '$',
                'exchange_rate' => 1.000000,
                'is_active' => true,
                'is_base' => true,
                'sort_order' => 0,
            ],
            [
                'code' => 'EUR',
                'name' => 'Euro',
                'symbol' => '€',
                'exchange_rate' => 0.920000,
                'is_active' => true,
                'is_base' => false,
                'sort_order' => 1,
            ],
            [
                'code' => 'GBP',
                'name' => 'British Pound',
                'symbol' => '£',
                'exchange_rate' => 0.790000,
                'is_active' => true,
                'is_base' => false,
                'sort_order' => 2,
            ],
            [
                'code' => 'HUF',
                'name' => 'Hungarian Forint',
                'symbol' => 'Ft',
                'exchange_rate' => 360.000000,
                'is_active' => true,
                'is_base' => false,
                'sort_order' => 3,
            ],
            [
                'code' => 'JPY',
                'name' => 'Japanese Yen',
                'symbol' => '¥',
                'exchange_rate' => 149.000000,
                'is_active' => true,
                'is_base' => false,
                'sort_order' => 4,
            ],
            [
                'code' => 'CAD',
                'name' => 'Canadian Dollar',
                'symbol' => 'CA$',
                'exchange_rate' => 1.360000,
                'is_active' => true,
                'is_base' => false,
                'sort_order' => 5,
            ],
            [
                'code' => 'AUD',
                'name' => 'Australian Dollar',
                'symbol' => 'A$',
                'exchange_rate' => 1.530000,
                'is_active' => true,
                'is_base' => false,
                'sort_order' => 6,
            ],
        ];

        foreach ($currencies as $currency) {
            Currency::updateOrCreate(
                ['code' => $currency['code']],
                $currency
            );
        }

        $this->command->info('Currencies seeded successfully!');
    }
}
