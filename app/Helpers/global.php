<?php

use Psr\Http\Message\ResponseInterface as Response;

if (!function_exists('dd')) {
    /**
     * Dump the passed variables and end the script.
     *
     * @param  mixed  $args
     * @return void
     */
    function dd(...$args): void
    {
        $print = [];
        foreach ($args as $i => $arg) {
            if (is_object($arg)) {
                $print["arg_{$i}"] = serialize($arg);
            }
            $print["arg_{$i}"] = $arg;
        }
        echo '<pre><p style="background-color: black; color:#30fa02">', json_encode($print, JSON_PRETTY_PRINT), '<p></pre>';

        die();
    }
}

if (!function_exists('class_basename')) {
    /**
     * Get the class "basename" of the given object / class.
     *
     * @param  string|object  $class
     * @return string
     */
    function class_basename($class): string
    {
        $class = is_object($class) ? get_class($class) : $class;

        return basename(str_replace('\\', '/', $class));
    }
}

if (!function_exists('config')) {
    /**
     * Get / set the specified configuration value.
     *
     * If an array is passed as the key, we will assume you want to set an array of values.
     *
     * @param  array|string  $key
     * @param  mixed  $default
     * @return mixed
     */
    function config(string $key, mixed $default = null): mixed
    {
        $keys = explode('.', $key);

        $file = config_path() . '/' . $keys[0] . '.php';

        $value = $default;

        if (file_exists($file)) {
            $config = include $file;
            $tree = $config;
            unset($keys[0]);
            foreach ($keys as $key) {
                if (!isset($tree[$key])) {
                    $tree = $default;
                    break;
                }
                $tree = $tree[$key];
            }
            $value = $tree;
        }
        return $value;
    }
}

if (!function_exists('json')) {
    /**
     * Return a json response
     *
     * @param  Response  $response
     * @param  string  $message
     * @param  array  $data
     * @param  int  $code
     * @return Response
     */
    function json(Response $response, string $message = 'OK', array $data = [], int $code = 200): Response
    {
        $body = [
            'message'   => $message,
            $code == 200 ? 'data' : 'error' => $data,
        ];

        $response->getBody()->write(json_encode($body));
        return $response->withHeader('Content-Type', 'application/json');
    }
}
