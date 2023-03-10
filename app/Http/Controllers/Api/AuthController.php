<?php

namespace App\Http\Controllers\Api;

use App\Exceptions\ValidationException;
use App\Http\Requests\LoginRequest;
use App\Http\Requests\RegisterRequest;
use App\Models\User;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

class AuthController extends ApiController
{
    /**
     * Login
     * @param \Psr\Http\Message\RequestInterface $request
     * @param \Psr\Http\Message\ResponseInterface $response
     * @param array $args
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function login(Request $request, Response $response, ...$args): Response
    {
        $request = new LoginRequest($request);

        $inputs = $request->only('email', 'password');

        // Check if user exists
        $user = (new User())->where('email', $inputs['email'])->first();

        // Check if password is correct
        if (!bcrypt_check($inputs['password'], $user->password)) {
            throw new ValidationException($request, [
                'email' => ['The email or password is incorrect.']
            ]);
        }

        // Generate token
        $token = $user->generateToken();

        return json($response, "Login Success", [
            'token' => $token->token
        ]);
    }

    /**
     * Logout
     * @param \Psr\Http\Message\ServerRequestInterface $request
     * @param \Psr\Http\Message\ResponseInterface $response
     * @param array $args
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function logout(Request $request, Response $response, ...$args): Response
    {
        $token = $request->getAttribute('token');
        $user = $request->getAttribute('user');

        $user->authTokens()->where('token', $token)->delete();

        return json($response, "Logout Success");
    }

    /**
     * Register
     * @param \Psr\Http\Message\ServerRequestInterface $request
     * @param \Psr\Http\Message\ResponseInterface $response
     * @param array $args
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function register(Request $request, Response $response, ...$args): Response
    {
        // Filter only required inputs
        $request = new RegisterRequest($request);

        $inputs = $request->only('name', 'email', 'password');

        // Create user
        $user = (new User($inputs))->save();

        // Generate token
        $token = $user->generateToken();

        return json($response, "Register Success", [
            'token' => $token->token
        ]);
    }
}
