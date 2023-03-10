<?php

namespace App\Exceptions;

use App\Infrastructure\Exception;


class ValidationException extends Exception
{
    protected $code = 422;
    protected string $title = '422 Unprocessable Entity';
}
