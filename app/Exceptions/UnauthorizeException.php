<?php

namespace App\Exceptions;

use App\Infrastructure\Exception;


class UnauthorizeException extends Exception
{
    protected $code = 401;
    protected string $title = '401 Unauthorized';
}
