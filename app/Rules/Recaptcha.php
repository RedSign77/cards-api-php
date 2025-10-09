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
    /**
     * Run the validation rule.
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $secretKey = config('recaptcha.secret_key');

        if (empty($secretKey)) {
            // If reCAPTCHA is not configured, skip validation
            return;
        }

        if (empty($value)) {
            $fail('The reCAPTCHA verification is required.');
            return;
        }

        $response = Http::asForm()->post(config('recaptcha.verify_url'), [
            'secret' => $secretKey,
            'response' => $value,
            'remoteip' => request()->ip(),
        ]);

        if (!$response->successful()) {
            $fail('Unable to verify reCAPTCHA. Please try again.');
            return;
        }

        $result = $response->json();

        if (!isset($result['success']) || $result['success'] !== true) {
            $fail('The reCAPTCHA verification failed. Please try again.');
        }
    }
}
