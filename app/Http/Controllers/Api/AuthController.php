<?php

namespace App\Http\Controllers\Api;

use App\Infrastructure\Validator;
use App\Models\User;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

class AuthController extends ApiController
{
    /**
     * Login
     * @param \Psr\Http\Message\ServerRequestInterface $request
     * @param \Psr\Http\Message\ResponseInterface $response
     * @param array $args
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function login(Request $request, Response $response, ...$args): Response
    {
        // Filter only required inputs
        $inputs = Validator::only(
            $request->getParsedBody(),
            ['email', 'password']
        );

        // Validate inputs
        $errors = Validator::validate($inputs, [
            'email'     => 'required|email|exists:users',
            'password'  => 'required|alpha_num'
        ]);

        // Return errors if any
        if (count($errors) > 0) {
            return json($response, 'Unprocessable entities', $errors, 422);
        }

        // Check if user exists
        $user = (new User())->where('email', $inputs['email'])->first();

        // Check if password is correct
        if (!password_verify($inputs['password'], $user->password)) {
            return json($response, 'Invalid Credentials', [
                'email' => ['The email or password is incorrect.']
            ], 422);
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
        $inputs = Validator::only(
            $request->getParsedBody(),
            ['name', 'email', 'password']
        );

        // Validate inputs
        $errors = Validator::validate($inputs, [
            'name'      => 'required|alpha_num|min:3',
            'email'     => 'required|email|unique:users',
            'password'  => 'required|alpha_num|min:6'
        ]);

        // Return errors if any
        if (count($errors) > 0) {
            return json($response, 'Unprocessable entities', $errors, 422);
        }

        // Create user
        $user = (new User())->create([
            'name'      => $inputs['name'],
            'email'     => $inputs['email'],
            'password'  => password_hash($inputs['password'], PASSWORD_DEFAULT)
        ]);

        // Generate token
        $token = $user->generateToken();

        return json($response, "Register Success", [
            'token' => $token->token
        ]);
    }
}
