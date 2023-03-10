<?php

namespace App\Infrastructure;

use Psr\Http\Message\ServerRequestInterface;
use Slim\Exception\HttpSpecializedException;

class Exception extends HttpSpecializedException
{
    protected $code = 400;

    protected string $title = '400 Bad Request';

    protected array $errors = [];

    public function __construct(ServerRequestInterface $request, array $errors = [])
    {
        $this->errors = $errors;
        parent::__construct($request);
    }

    public function getErrors(): array
    {
        return $this->errors;
    }
}
