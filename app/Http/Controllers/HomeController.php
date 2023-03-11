<?php

namespace App\Http\Controllers;

use GuzzleHttp\Psr7\LazyOpenStream;
use Psr\Http\Message\RequestInterface as IRequest;
use Psr\Http\Message\ResponseInterface as IResponse;

class HomeController
{
    public function index(IRequest $request, IResponse $response): IResponse
    {
        $newStream = new LazyOpenStream(resource_path('views/index.html'), 'r');
        return $response->withBody($newStream);
    }
}
