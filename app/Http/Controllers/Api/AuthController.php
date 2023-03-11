<?php

namespace App\Http\Controllers\Api;

use App\Exceptions\ValidationException;
use App\Http\Requests\LoginRequest;
use App\Http\Requests\RegisterRequest;
use App\Http\Requests\UpdateRequest;
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
        $this->user->authTokens()->where('token', $this->token)->delete();

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
        $user = (new User([
            'name'      => $inputs['name'],
            'email'     => $inputs['email'],
            'password'  => bcrypt($inputs['password'])
        ]))->save();

        // Generate token
        $token = $user->generateToken();

        return json($response, "Register Success", [
            'token' => $token->token
        ]);
    }

    /**
     * Get my profile
     * @param \Psr\Http\Message\ServerRequestInterface $request
     * @param \Psr\Http\Message\ResponseInterface $response
     * @param array $args
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function profile(Request $request, Response $response, ...$args): Response
    {
        return json($response, "My Profile Retrieved", $this->user->toArray());
    }

    /**
     * Update my profile
     * @param \Psr\Http\Message\ServerRequestInterface $request
     * @param \Psr\Http\Message\ResponseInterface $response
     * @param array $args
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function update(Request $request, Response $response, ...$args): Response
    {
        $inputs = (new UpdateRequest($request))->only('name', 'email', 'password');
        $this->user->name = $inputs['name'];
        $this->user->email = $inputs['email'];
        $this->user->password = bcrypt($inputs['password']);
        $this->user->save();

        return json($response, "My Profile Updated", $this->user->toArray());
    }
}
