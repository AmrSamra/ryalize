<?php

namespace App\Http\Middleware;

use App\Infrastructure\Validator;
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
        // Get the current path
        $path = $request->getUri()->getPath();

        // Convert the path to array
        $path = Self::arrayUrl($path);

        // Check if the path is in except array
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
    protected function isJson(IRequest $request): bool
    {
        return $request->getHeader('content-type') == ['application/json'];
    }

    /**
     * Get the token from the request after validation.
     *
     * @param \Psr\Http\Message\ServerRequestInterface  $request
     * @return string|null
     */
    protected function getToken(IRequest $request): ?string
    {
        // Get the token from the request
        $authorization = $request->getHeader('Authorization');

        $token = $authorization[0] ?? null;

        // Check if the token is valid
        if ($token && preg_match('/Bearer\s(\S+)/', $token)) {
            $token = explode(' ', $token);

            // clean the token
            $token = trim($token[1]);

            // validate the token
            $errors = Validator::validate([
                'token' => $token
            ], [
                'token' => 'required|alpha_num|exists:auth_tokens'
            ]);

            // return the token if no errors
            if (!count($errors)) {
                return $token;
            }
        }
        // return null if token is invalid
        return null;
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
