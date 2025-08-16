<?php

if (!function_exists('setting')) {
    /**
     * Get website setting value by key
     *
     * @param string $key
     * @param mixed $default
     * @return mixed
     */
    function setting($key, $default = null)
    {
        return \App\Models\WebsiteSetting::getValue($key, $default);
    }
}

if (!function_exists('site_name')) {
    /**
     * Get site name
     *
     * @return string
     */
    function site_name()
    {
        return setting('site_name', 'LOA Management System');
    }
}

if (!function_exists('site_logo')) {
    /**
     * Get site logo URL
     *
     * @return string|null
     */
    function site_logo()
    {
        $logo = setting('logo');
        return $logo ? \Illuminate\Support\Facades\Storage::url($logo) : null;
    }
}

if (!function_exists('site_favicon')) {
    /**
     * Get site favicon URL
     *
     * @return string|null
     */
    function site_favicon()
    {
        $favicon = setting('favicon');
        return $favicon ? \Illuminate\Support\Facades\Storage::url($favicon) : null;
    }
}
