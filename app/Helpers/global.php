<?php

use App\Infrastructure\Model;
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
        $print = ddMapArray($args);
        echo '<pre>', json_encode($print, JSON_PRETTY_PRINT), '</pre>';

        die();
    }

    /**
     * Map array
     *
     * @param  array  $array
     * @return array
     */
    function ddMapArray(array $array): array
    {
        return array_map(function ($arg) {
            if ($arg instanceof Model) {
                return $arg->toArray();
            } else if (is_array($arg)) {
                return ddMapArray($arg);
            } else if (is_object($arg)) {
                try {
                    $object = serialize($arg);
                } catch (\Exception $e) {
                    $object = class_basename($arg);
                }
                return $object;
            }
            return $arg;
        }, $array);
    }
}

if (!function_exists('bcrypt')) {
    /**
     * Hash the given value.
     *
     * @param  string  $value
     * @param  array  $options
     * @return string
     */
    function bcrypt(string $value, array $options = []): string
    {
        return password_hash($value, PASSWORD_BCRYPT, $options);
    }
}

if (!function_exists('bcrypt_check')) {
    /**
     * Check the given plain value against a hash.
     *
     * @param  string  $value
     * @param  string  $hashedValue
     * @return bool
     */
    function bcrypt_check(string $value, string $hashedValue): bool
    {
        return password_verify($value, $hashedValue);
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
    function json(Response $response, string $message = 'OK', array $data = [], array $metadata = []): Response
    {
        $body = [
            'message'   => $message,
            'data'      => $data,
        ];
        if (!empty($metadata)) {
            $body['metadata'] = $metadata;
        }

        $response->getBody()->write(json_encode($body));
        return $response
            ->withStatus(200)
            ->withHeader('Content-Type', 'application/json');
    }
}
