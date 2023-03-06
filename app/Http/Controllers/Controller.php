<?php

namespace App\Http\Controllers;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

class Controller
{
    public function __invoke(Request $request, Response $response, ...$args): Response
    {
        $response->getBody()->write(":: __invoke ::");
        return $response;
    }

    public function index(Request $request, Response $response, ...$args): Response
    {
        $response->getBody()->write(":: index ::");
        return $response;
    }

    public function show(Request $request, Response $response, ...$args): Response
    {
        $response->getBody()->write(":: show ::");
        return $response;
    }

    public function store(Request $request, Response $response, ...$args): Response
    {
        $response->getBody()->write(":: store ::");
        return $response;
    }

    public function update(Request $request, Response $response, ...$args): Response
    {
        $response->getBody()->write(":: update ::");
        return $response;
    }

    public function destroy(Request $request, Response $response, ...$args): Response
    {
        $response->getBody()->write(":: destroy ::");
        return $response;
    }
}
