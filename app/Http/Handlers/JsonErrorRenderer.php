<?php

namespace App\Http\Handlers;

use App\Infrastructure\Exception;
use Slim\Interfaces\ErrorRendererInterface;
use Throwable;

class JsonErrorRenderer implements ErrorRendererInterface
{
    public function __invoke(Throwable $e, bool $displayErrorDetails): string
    {
        return json_encode([
            'message'   => $e instanceof Exception ? $e->getTitle() : $e->getMessage(),
            'errors'    => $e instanceof Exception ? $e->getErrors() : [],
        ]);
    }
}
