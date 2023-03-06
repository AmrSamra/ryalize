<?php

if (!function_exists('base_path')) {
    /**
     * Get the path to the base of the install.
     *
     * @param  string  $path
     * @return string
     */
    function base_path($path = '')
    {
        return __DIR__ . "/../../{$path}";
    }
}

if (!function_exists('config_path')) {
    /**
     * Get the configuration path.
     *
     * @param  string  $path
     * @return string
     */
    function config_path($path = '')
    {
        return base_path() . 'config' . ($path ? '/' . $path : $path);
    }
}

if (!function_exists('resource_path')) {
    /**
     * Get the path to the resources directory.
     *
     * @param  string  $path
     * @return string
     */
    function resource_path($path = '')
    {
        return base_path() . 'resources' . ($path ? '/' . $path : $path);
    }
}

if (!function_exists('public_path')) {
    /**
     * Get the path to the public directory.
     *
     * @param  string  $path
     * @return string
     */
    function public_path($path = '')
    {
        return base_path() . 'public' . ($path ? '/' . $path : $path);
    }
}

if (!function_exists('routes_path')) {
    /**
     * Get the path to the routes directory.
     *
     * @param  string  $path
     * @return string
     */
    function routes_path($path = '')
    {
        return base_path() . 'routes' . ($path ? '/' . $path : $path);
    }
}

if (!function_exists('storage_path')) {
    /**
     * Get the path to the storage directory.
     *
     * @param  string  $path
     * @return string
     */
    function storage_path($path = '')
    {
        return base_path() . 'storage' . ($path ? '/' . $path : $path);
    }
}
