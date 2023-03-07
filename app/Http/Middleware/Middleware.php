<?php

namespace App\Http\Middleware;

use Slim\Psr7\Response;
use Psr\Http\Message\ResponseInterface as IResponse;
use Psr\Http\Message\ServerRequestInterface as IRequest;
use Psr\Http\Server\RequestHandlerInterface as IRequestHandler;
use Slim\Exception\HttpBadRequestException;

class Middleware
{
    /**
     * The URIs that should be excluded from middleware verification.
     *
     * @var array<int, string>
     */
    protected $except = [
        //
    ];


    protected $exception = [
        HttpBadRequestException::class
    ];

    /**
     * @param \Psr\Http\Message\ServerRequestInterface $request
     * @param \Psr\Http\Server\RequestHandlerInterface $handler
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function __invoke(IRequest $request, IRequestHandler $handler): IResponse
    {
        return $handler->handle($request);
    }

    /**
     * Handle an incoming request.
     *
     * @param \Psr\Http\Message\ServerRequestInterface  $request
     * @return \Psr\Http\Message\ResponseInterface
     */
    protected function handle(IRequest $request): IResponse
    {
        $response = new Response();
        if ($this->expectJson($request)) {
            return json($response, 'Bad Request', [], 400);
        }
        throw new HttpBadRequestException($request);
    }

    /**
     * Determine if the HTTP request uses a ‘read’ verb.
     *
     * @param \Psr\Http\Message\ServerRequestInterface  $request
     * @return bool
     */
    protected function isReading(IRequest $request): bool
    {
        return in_array($request->getMethod(), ['HEAD', 'GET', 'OPTIONS']);
    }

    /**
     * Determine if the request has a URI that should pass through Middleware.
     *
     * @param \Psr\Http\Message\ServerRequestInterface  $request
     * @return bool
     */
    protected function inExceptArray(IRequest $request): bool
    {
        $path = $request->getUri()->getPath();
        $path = Self::arrayUrl($path);
        foreach ($this->except as $except) {
            $except = Self::arrayUrl($except);
            if (Self::isMatch($path, $except)) {
                return true;
            }
        }

        return false;
    }

    /**
     * Determine if the request is sending JSON.
     *
     * @param \Psr\Http\Message\ServerRequestInterface  $request
     * @return bool
     */
    protected function expectJson(IRequest $request): bool
    {
        return $request->getHeader('accept') == ['application/json'];
    }

    /**
     * Map the given url to array
     *
     * @param string $url
     * @return array
     */
    private static function arrayUrl(string $url): array
    {
        $url = trim($url, '/');
        $url = explode('/', trim($url, '/'));
        return $url;
    }

    /**
     * Determine if the request has a URI that matches a pattern.
     * @param array<int, string> $url
     * @param array<int, string> $except
     * @return bool
     */
    private static function isMatch(array $url, array $except): bool
    {
        $count = count($except);
        if ($count > count($url)) {
            return false;
        }
        for ($i = 0; $i < $count; $i++) {
            if ($except[$i] != $url[$i] && $except[$i] != '*') {
                return false;
            }
        }
        return true;
    }
}
