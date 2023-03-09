<?php

namespace App\Infrastructure;

class Validator extends ValidatorRules
{
    /**
     * Filter data by keys
     * @param array $data
     * @param array $keys
     * @return array
     */
    public static function only(array $data, array $keys): array
    {
        $result = [];
        foreach ($keys as $key) {
            $result[$key] = $data[$key] ?? null;
        }
        return $result;
    }

    /**
     * Validate data by rules
     * @param array $data
     * @param array $rules
     * @return array of Errors if not valid
     */
    public static function validate(array $data, array $rules): array
    {
        $errors = [];

        foreach ($rules as $key => $rule) {
            $rules = explode('|', $rule);
            foreach ($rules as $stringRule) {
                $rule = explode(':', $stringRule);
                switch ($rule[0]) {
                    case 'required':
                        $validator = Self::required($key, $data[$key]);
                        break;
                    case 'string':
                        $validator = Self::string($key, $data[$key]);
                        break;
                    case 'email':
                        $validator = Self::email($key, $data[$key]);
                        break;
                    case 'number':
                        $validator = Self::number($key, $data[$key]);
                        break;
                    case 'alpha_num':
                        $validator = Self::alpha_num($key, $data[$key]);
                        break;
                    case 'min':
                        $validator = Self::min($key, $data[$key], $rule[1]);
                        break;
                    case 'unique':
                        $validator = Self::unique($key, $data[$key], $rule[1], $rule[2] ?? $key);
                        break;
                    case 'exists':
                        $validator = Self::exists($key, $data[$key], $rule[1], $rule[2] ?? $key);
                        break;
                    case 'regex':
                        $validator = Self::regex($key, $data[$key], $rule[1]);
                        break;
                    default:
                        $validator = Self::response($key, true);
                        break;
                }
                if (!$validator['success']) {
                    $errors[$key][] = $validator['message'];
                    continue;
                }
            }
        }

        return $errors;
    }
}
