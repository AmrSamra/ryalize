<?php

namespace App\Http\Controllers\Api;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

class AuthController extends ApiController
{
    public function login(Request $request, Response $response, ...$args): Response
    {
        return json($response, ":: login ::");
    }

    public function logout(Request $request, Response $response, ...$args): Response
    {
        return json($response, ":: logout ::");
    }

    public function register(Request $request, Response $response, ...$args): Response
    {
        return json($response, ":: register ::");
    }
}
