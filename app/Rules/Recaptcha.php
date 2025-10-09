<?php
/**
 * Webtech-solutions 2025, All rights reserved.
 */

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Support\Facades\Http;

class Recaptcha implements ValidationRule
{
    protected $action;

    public function __construct($action = 'submit')
    {
        $this->action = $action;
    }

    /**
     * Run the validation rule.
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $secretKey = config('recaptcha.secret_key');
        $siteKey = config('recaptcha.site_key');

        if (empty($secretKey) || empty($siteKey)) {
            // If reCAPTCHA is not configured, skip validation
            return;
        }

        if (empty($value)) {
            $fail('The reCAPTCHA verification is required.');
            return;
        }

        // reCAPTCHA Enterprise API
        $response = Http::withHeaders([
            'Content-Type' => 'application/json',
        ])->post(config('recaptcha.verify_url') . '?key=' . $secretKey, [
            'event' => [
                'token' => $value,
                'expectedAction' => $this->action,
                'siteKey' => $siteKey,
            ]
        ]);

        if (!$response->successful()) {
            $fail('Unable to verify reCAPTCHA. Please try again.');
            return;
        }

        $result = $response->json();

        // Check if token is valid
        if (!isset($result['tokenProperties']['valid']) || $result['tokenProperties']['valid'] !== true) {
            $fail('The reCAPTCHA verification failed. Please try again.');
            return;
        }

        // Check action matches
        if (isset($result['tokenProperties']['action']) && $result['tokenProperties']['action'] !== $this->action) {
            $fail('The reCAPTCHA verification failed. Invalid action.');
            return;
        }

        // Check risk score (0.0 is very likely a bot, 1.0 is very likely a human)
        $score = $result['riskAnalysis']['score'] ?? 0;
        $threshold = config('recaptcha.score_threshold', 0.5);

        if ($score < $threshold) {
            $fail('The reCAPTCHA verification failed. Please try again.');
        }
    }
}
