<?php

namespace App\Http\Middleware;

use Psr\Http\Message\ResponseInterface as IResponse;
use Psr\Http\Message\ServerRequestInterface as IRequest;
use Psr\Http\Server\RequestHandlerInterface as IRequestHandler;

class ApiMiddleware extends Middleware
{
    /**
     * @param \Psr\Http\Message\ServerRequestInterface $request
     * @param \Psr\Http\Message\RequestHandlerInterface $handler
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function __invoke(IRequest $request, IRequestHandler $handler): IResponse
    {
        if (!$this->inExceptArray($request)) {
            if (!$this->expectJson($request)) {
                return $this->handle($request);
            }
        }
        return $handler->handle($request);
    }
}
