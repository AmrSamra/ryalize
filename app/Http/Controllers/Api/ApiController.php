<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

class ApiController extends Controller
{
    public function __invoke(Request $request, Response $response, ...$args): Response
    {
        return json($response, ":: invoke ::");
    }

    public function index(Request $request, Response $response, ...$args): Response
    {
        return json($response, ":: index ::");
    }

    public function show(Request $request, Response $response, ...$args): Response
    {
        return json($response, ":: show ::");
    }

    public function store(Request $request, Response $response, ...$args): Response
    {
        return json($response, ":: store ::");
    }

    public function update(Request $request, Response $response, ...$args): Response
    {
        return json($response, ":: update ::");
    }

    public function destroy(Request $request, Response $response, ...$args): Response
    {
        return json($response, ":: destroy ::");
    }
}
