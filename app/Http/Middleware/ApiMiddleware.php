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
        // check if request is not in except array
        if (!$this->inExceptArray($request)) {
            // check if request is not expecting json
            if (!$this->expectJson($request)) {
                // return 400 bad request
                return $this->handle($request);
            }
        }
        // check if request is json
        if ($this->isJson($request)) {
            // parse json body
            $contents = json_decode(file_get_contents('php://input'), true);
            // check if json is valid
            if (json_last_error() === JSON_ERROR_NONE) {
                // set parsed body
                $request = $request->withParsedBody($contents);
            }
        }
        // return response
        return $handler->handle($request);
    }
}
