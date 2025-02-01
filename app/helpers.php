<?php

use App\Models\Setting;

if (!function_exists('setting')) {
    /**
     * Get / set the specified setting value.
     *
     * If an array is passed, we'll assume you want to set settings.
     *
     * @param  string|array  $key
     * @param  mixed  $default
     * @return mixed
     */
    function setting(string|array $key, mixed $default = null): mixed
    {
        if (is_array($key)) {
            foreach ($key as $k => $value) {
                Setting::set($k, $value);
            }
            return true;
        }

        return Setting::get($key, $default);
    }
}
