<?php

namespace App\Http\Middleware;

use App\Exceptions\UnauthorizeException;
use App\Models\AuthToken;
use Psr\Http\Message\ResponseInterface as IResponse;
use Psr\Http\Message\ServerRequestInterface as IRequest;
use Psr\Http\Server\RequestHandlerInterface as IRequestHandler;
use Slim\Psr7\Request;

class AuthorizedMiddleware extends Middleware
{
    /**
     * @param \Psr\Http\Message\ServerRequestInterface $request
     * @param \Psr\Http\Message\RequestHandlerInterface $handler
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function __invoke(IRequest $request, IRequestHandler $handler): IResponse
    {
        // get token from request and extract Bearer
        if ($token = $this->getToken($request)) {
            // check if token is valid
            $token = (new AuthToken())->valid()->where('token', $token)->first();

            // check if token exists
            if ($token && $token->user_id) {

                // get user from token
                $user = $token->user()->first();

                // check if user exists
                if ($user && $user->id) {
                    // set token and user to request
                    $request = $request->withAttribute('token', $token->token);
                    $request = $request->withAttribute('user', $user);
                    $this->container->set(Request::class, $request);

                    // return response
                    return $handler->handle($request);
                }
            }
        }
        // throw 401 unauthorized
        throw new UnauthorizeException($request);
    }
}
