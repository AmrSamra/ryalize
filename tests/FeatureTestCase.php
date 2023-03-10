<?php

namespace Tests;

use App\Http\Kernel;
use App\Models\User;
use DI\Container;
use Slim\Psr7\Factory\RequestFactory;
use Slim\Psr7\Factory\ResponseFactory;
use Slim\Psr7\Factory\StreamFactory;
use Psr\Http\Message\ResponseInterface as IResponse;
use Slim\App;
use Slim\Factory\AppFactory;
use Slim\Psr7\Factory\UriFactory;

abstract class FeatureTestCase extends TestCase
{
    protected App $app;

    protected function setUp(): void
    {
        // Create Container using PHP-DI
        $container = new Container();

        // Set container to create App with on AppFactory
        AppFactory::setContainer($container);

        $app = AppFactory::create();

        // Load configuration
        $kernel = new Kernel($app);
        $kernel->boot();

        $app->run();

        $this->app = $app;
        parent::setUp();
    }

    protected function tearDown(): void
    {
        parent::tearDown();
    }

    /**
     * Generate new response form factory
     * @param int $code
     * @param string $reasonPhrase
     * @param array $body
     * @return IResponse
     */
    protected function response(int $code = 200, string $reasonPhrase = '', array $body = []): IResponse
    {
        $response = (new ResponseFactory())->createResponse($code, $reasonPhrase);
        if (count($body)) {
            $stream = (new StreamFactory())->createStream(json_encode($body));
            $response = $response->withBody($stream);
        }
        return $response;
    }

    /**
     * Generate new request from factory
     * @param string $method
     * @param string $url
     * @param array $params
     * @param array $body
     * @param array $headers
     * @return IResponse
     */
    protected function request(string $method, string $url, array $params = [], array $body = [], array $headers = []): IResponse
    {
        $uri = (new UriFactory())->createUri($url);

        if (count($params)) {
            $uri = $uri->withQuery(http_build_query($params));
        }

        $request = (new RequestFactory())->createRequest($method, $uri);

        if (count($body)) {
            $stream = (new StreamFactory())->createStream(json_encode($body));
            $request = $request->withBody($stream);
        }

        $headers = array_merge([
            'Accept'        => 'application/json',
            'Content-Type'  => 'application/json',
        ], $headers);

        array_walk($headers, function ($value, $key) use (&$request) {
            $request = $request->withHeader($key, $value);
        });

        return $this->app->handle($request);
    }

    /**
     * Generate new GET request
     * @param string $url
     * @param array $params
     * @param array $headers
     * @return IResponse
     */
    public function get(string $url, array $params = [], array $headers = []): IResponse
    {
        return $this->request('GET', $url, $params, [], $headers);
    }

    /**
     * Generate new POST request
     * @param string $url
     * @param array $body
     * @param array $headers
     * @return IResponse
     */
    public function post(string $url, array $body = [], array $headers = []): IResponse
    {
        return $this->request('POST', $url, [], $body, $headers);
    }

    /**
     * Generate new PUT request
     * @param string $url
     * @param array $body
     * @param array $headers
     * @return IResponse
     */
    public function put(string $url, array $body = [], array $headers = []): IResponse
    {
        return $this->request('PUT', $url, [], $body, $headers);
    }

    /**
     * Generate new DELETE request
     * @param string $url
     * @param array $body
     * @param array $headers
     * @return IResponse
     */
    public function delete(string $url, array $body = [], array $headers = []): IResponse
    {
        return $this->request('DELETE', $url, [], $body, $headers);
    }

    /**
     * Assert response status code is 200
     * @param IResponse $response
     * @return void
     */
    public function assertOk(IResponse $response): void
    {
        $this->assertStatus($response, 200);
    }

    /**
     * Assert response status matches given code
     * @param IResponse $response
     * @param int $code
     * @return void
     */
    public function assertStatus(IResponse $response, int $code): void
    {
        $this->assertEquals($code, $response->getStatusCode());
    }

    /**
     * Generate new user
     * @param array $data
     * @return User
     */
    public function user(array $data = []): User
    {
        if (!count($data)) {
            $data = [
                'name'      => 'User ' . uniqid(),
                'email'     => 'user_' . uniqid() . '@test.com',
                'password'  => bcrypt('password')
            ];
        }
        $user = new User($data);
        return $user->save();
    }
}
