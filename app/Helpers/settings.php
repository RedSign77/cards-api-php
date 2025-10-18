<?php
/**
 * Webtech-solutions 2025, All rights reserved.
 */

use App\Models\WebsiteSetting;

if (! function_exists('setting')) {
    /**
     * Get a website setting value
     *
     * @param  string  $key
     * @param  mixed  $default
     * @return mixed
     */
    function setting(string $key, mixed $default = null): mixed
    {
        return WebsiteSetting::get($key, $default);
    }
}
