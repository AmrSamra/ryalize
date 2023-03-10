<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use DI\Container;

class ApiController extends Controller
{
    protected ?string $token = null;

    public function __construct(Container $container)
    {
        parent::__construct($container);

        if ($this->request) {
            $this->token = $this->request->getAttribute('token');
        }
    }
}
