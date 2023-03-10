<?php

namespace App\Http\Controllers;

use App\Models\User;
use DI\Container;
use Slim\Psr7\Request;

class Controller
{
    protected ?User $user = null;

    protected Container $container;

    protected ?Request $request = null;

    public function __construct(Container $container)
    {
        $this->container = $container;
        try {
            $request = $container->get(Request::class);
        } catch (\Exception $e) {
            // do nothing
        }
        if (isset($request)) {
            $this->request = $request;
            $user = $this->request->getAttribute('user');
            if ($user) {
                $this->user = $user;
            }
        }
    }
}
