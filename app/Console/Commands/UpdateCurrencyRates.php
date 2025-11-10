<?php
/**
 * Webtech-solutions 2025, All rights reserved.
 */

namespace App\Console\Commands;

use App\Models\Currency;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class UpdateCurrencyRates extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'currencies:update-rates';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Update currency exchange rates from FreeCurrencyAPI';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $apiKey = config('services.freecurrencyapi.key');

        if (empty($apiKey)) {
            $this->error('FREECURRENCYAPI_KEY is not set in .env file');
            Log::error('Currency update failed: API key not configured');
            return Command::FAILURE;
        }

        $this->info('Fetching currency exchange rates from FreeCurrencyAPI...');

        // Get base currency
        $baseCurrency = Currency::getBaseCurrency();

        if (!$baseCurrency) {
            $this->error('No base currency set. Please set a base currency first.');
            Log::error('Currency update failed: No base currency configured');
            return Command::FAILURE;
        }

        // Get all active currencies
        $currencies = Currency::active()->get();

        if ($currencies->isEmpty()) {
            $this->error('No active currencies found.');
            return Command::FAILURE;
        }

        // Build currency codes string for API (exclude base currency)
        $currencyCodes = $currencies
            ->pluck('code')
            ->filter(fn ($code) => $code !== $baseCurrency->code)
            ->join(',');

        if (empty($currencyCodes)) {
            $this->info('Only base currency exists, no rates to update.');
            return Command::SUCCESS;
        }

        try {
            // Build the URL with query parameters
            $url = 'https://api.freecurrencyapi.com/v1/latest?apikey=' . $apiKey .
                   '&base_currency=' . $baseCurrency->code .
                   '&currencies=' . urlencode($currencyCodes);

            // Call FreeCurrencyAPI
            $response = Http::get($url);

            if (!$response->successful()) {
                $this->error('API request failed: ' . $response->status());
                Log::error('Currency API request failed', [
                    'status' => $response->status(),
                    'response' => $response->body(),
                ]);
                return Command::FAILURE;
            }

            $data = $response->json();

            if (!isset($data['data'])) {
                $this->error('Invalid API response format');
                Log::error('Invalid currency API response', ['response' => $data]);
                return Command::FAILURE;
            }

            $rates = $data['data'];
            $updatedCount = 0;

            // Update exchange rates for each currency
            foreach ($currencies as $currency) {
                if ($currency->code === $baseCurrency->code) {
                    // Base currency always has rate of 1.0
                    if ($currency->exchange_rate != 1.0) {
                        $currency->exchange_rate = 1.0;
                        $currency->save();
                        $this->info("Updated {$currency->code} (base): 1.000000");
                        $updatedCount++;
                    }
                    continue;
                }

                if (isset($rates[$currency->code])) {
                    $newRate = $rates[$currency->code];
                    $oldRate = $currency->exchange_rate;

                    $currency->exchange_rate = $newRate;
                    $currency->save();

                    $this->info("Updated {$currency->code}: {$oldRate} → {$newRate}");
                    $updatedCount++;
                } else {
                    $this->warn("Rate not found for {$currency->code}");
                }
            }

            $this->info("Successfully updated {$updatedCount} currency rate(s)");
            Log::info('Currency rates updated successfully', [
                'updated_count' => $updatedCount,
                'base_currency' => $baseCurrency->code,
            ]);

            return Command::SUCCESS;

        } catch (\Exception $e) {
            $this->error('Error updating currency rates: ' . $e->getMessage());
            Log::error('Currency update exception', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);
            return Command::FAILURE;
        }
    }
}
