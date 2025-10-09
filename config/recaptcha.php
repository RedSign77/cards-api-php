<?php
/**
 * Webtech-solutions 2025, All rights reserved.
 */

return [
    'site_key' => env('RECAPTCHA_SITE_KEY'),
    'secret_key' => env('RECAPTCHA_SECRET_KEY'),
    'verify_url' => 'https://recaptchaenterprise.googleapis.com/v1/projects/cards-api-php-1738854009542/assessments',
    'score_threshold' => env('RECAPTCHA_SCORE_THRESHOLD', 0.5),
];
