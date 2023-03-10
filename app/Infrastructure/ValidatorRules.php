<?php

namespace App\Infrastructure;

class ValidatorRules
{
    protected static $messages = [
        'required'  => 'The :0 field is required.',
        'string'    => 'The :0 field must be a string',
        'email'     => 'The :0 field must be a valid email address.',
        'number'    => 'The :0 field must be a number.',
        'alpha_num' => 'The :0 field must be only letters, numbers and spaces.',
        'min'       => 'The :0 field must be at least :1 characters.',
        'unique'    => 'The :0 field has been taken.',
        'exists'    => 'The :0 field is invalid.',
        'regex'     => 'The :0 field is invalid.',
    ];

    /**
     * Required field
     * @param string $field
     * @param mixed $value
     * @return array
     */
    public static function required(string $field, mixed $value): array
    {
        $result = !empty($value);
        return Self::response('required', $result, $field);
    }

    /**
     * String field
     * @param string $field
     * @param mixed $value
     * @return array
     */
    public static function string(string $field, mixed $value): array
    {
        $result = is_string($value);
        return Self::response('string', $result, $field);
    }

    /**
     * Email field
     * @param string $field
     * @param mixed $value
     * @return array
     */
    public static function email(string $field, mixed $value): array
    {
        $result = filter_var($value, FILTER_VALIDATE_EMAIL);
        return Self::response('email', $result, $field);
    }

    /**
     * Number field
     * @param string $field
     * @param mixed $value
     * @return array
     */
    public static function number(string $field, mixed $value): array
    {
        $result = is_numeric($value);
        return Self::response('number', $result, $field);
    }

    /**
     * Alpha numeric field
     * @param string $field
     * @param mixed $value
     * @return array
     */
    public static function alpha_num(string $field, mixed $value): array
    {
        $result =  Self::regex($field, (string) $value, '/^[a-zA-Z 0-9]+$/');
        return Self::response('alpha_num', $result['success'], $field);
    }

    /**
     * Min field
     * @param string $field
     * @param mixed $value
     * @param int $length
     * @return array
     */
    public static function min(string $field, mixed $value, int $length): array
    {
        $result = strlen($value) >= $length;
        return Self::response('min', $result, $field, $length);
    }

    /**
     * Unique field
     * @param string $field
     * @param mixed $value
     * @param string $table
     * @param string $column
     * @return array
     */
    public static function unique(string $field, mixed $value, string $table, string $column, int $ignoreId = 0): array
    {
        $exists = DB::table($table)->where([$column, '=', $value]);
        if ($ignoreId) {
            $exists->where(['id', '!=', $ignoreId]);
        }
        $result = !$exists->exists();
        return Self::response('unique', $result, $field);
    }

    /**
     * Exists field
     * @param string $field
     * @param mixed $value
     * @param string $table
     * @param string $column
     * @return array
     */
    public static function exists(string $field, mixed $value, string $table, string $column): array
    {
        $result = DB::table($table)->where([$column, '=', $value])->exists();
        return Self::response('exists', $result, $field);
    }

    /**
     * Regex field
     * @param string $field
     * @param mixed $value
     * @param string $pattern
     * @return array
     */
    public static function regex(string $field, mixed $value, string $pattern): array
    {
        $result = preg_match($pattern, $value);
        return Self::response('regex', $result, $field);
    }

    /**
     * Response
     * @param string $role
     * @param bool $success
     * @param mixed ...$attributes
     * @return array
     */
    public static function response(string $role, bool $success = true, ...$attributes): array
    {
        $message = null;
        if (!$success) {
            // Get message
            $message = Self::$messages[$role];

            // Replace attributes in message for ex. :0, :1, :2, ...
            foreach ($attributes as $key => $attribute) {
                $message = str_replace(":$key", $attribute, $message);
            }
        }

        return [
            'success' => $success, // true or false
            'message' => $message, // message or null
        ];
    }
}
