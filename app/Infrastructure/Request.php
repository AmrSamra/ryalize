<?php

namespace App\Infrastructure;

use App\Exceptions\ValidationException;
use Slim\Psr7\Headers;
use Slim\Psr7\Request as Psr7Request;

class Request extends Psr7Request
{
    public function __construct(Psr7Request $request)
    {
        $header = new Headers($request->getHeaders());
        parent::__construct(
            $request->getMethod(),
            $request->getUri(),
            $header,
            $request->getCookieParams(),
            $request->getServerParams(),
            $request->getBody(),
            $request->getUploadedFiles()
        );
        $this->parsedBody = json_decode($this->getBody()->getContents(), true);
        $this->validate();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string
     */
    public function rules(): array
    {
        return [];
    }

    /**
     * Fetch inputs data from request
     * @return array
     */
    public function inputs(): array
    {
        $body = $this->parsedBody ?? [];

        $query = $this->getQueryParams();

        return array_merge($body, $query);
    }

    /**
     * Get attributes from request
     * @param string $key
     * @return mixed
     */
    public function input(string $key): mixed
    {
        $inputs = $this->inputs();
        return $inputs[$key] ?? null;
    }

    /**
     * Get data from request
     * @param array $keys
     * @return array
     */
    public function only(string ...$keys): array
    {
        $inputs = $this->inputs();

        $result = [];
        foreach ($keys as $key) {
            $result[$key] = $inputs[$key] ?? null;
        }
        return $result;
    }

    /**
     * Validate data by rules
     * @param array $data
     * @param array $rules
     * @return void
     */
    protected function validate(array $data = [], array $rules = []): void
    {
        if (!count($data)) {
            $data = $this->inputs();
        }
        if (!count($rules)) {
            $rules = $this->rules();
        }

        $errors = [];

        foreach ($rules as $key => $rule) {
            $rules = explode('|', $rule);
            $value = $data[$key] ?? null;
            foreach ($rules as $stringRule) {
                $rule = explode(':', $stringRule);
                switch ($rule[0]) {
                    case 'required':
                        $validator = ValidatorRules::required($key, $value);
                        break;
                    case 'string':
                        $validator = ValidatorRules::string($key, $value);
                        break;
                    case 'email':
                        $validator = ValidatorRules::email($key, $value);
                        break;
                    case 'number':
                        $validator = ValidatorRules::number($key, $value);
                        break;
                    case 'alpha_num':
                        $validator = ValidatorRules::alpha_num($key, $value);
                        break;
                    case 'min':
                        $validator = ValidatorRules::min($key, $value, $rule[1]);
                        break;
                    case 'unique':
                        $validator = ValidatorRules::unique($key, $value, $rule[1], $rule[2] ?? $key);
                        break;
                    case 'exists':
                        $validator = ValidatorRules::exists($key, $value, $rule[1], $rule[2] ?? $key);
                        break;
                    case 'regex':
                        $validator = ValidatorRules::regex($key, $value, $rule[1]);
                        break;
                    default:
                        $validator = ValidatorRules::response($key, true);
                        break;
                }
                if (!$validator['success']) {
                    $errors[$key][] = $validator['message'];
                    continue;
                }
            }
        }

        // Throw exception if errors
        if (count($errors)) {
            throw new ValidationException($this, $errors);
        }
    }
}
