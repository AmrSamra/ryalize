<?php

namespace Tests\Feature;

use App\Models\AuthToken;
use Tests\FeatureTestCase;

class LoginTest extends FeatureTestCase
{
    public function testValidationNoFields()
    {
        $response = $this->post('/api/auth/login');

        $this->assertStatus($response, 422);
    }

    public function testValidationInvalidEmail()
    {
        $response = $this->post('/api/auth/login', [
            'email'     => 'invalid',
            'password'  => 'password',
        ]);

        $this->assertStatus($response, 422);
    }

    public function testValidationInvalidPassword()
    {
        $response = $this->post('/api/auth/login', [
            'email'     => 'abc@xyz.com',
            'password'  => 'ddsa',
        ]);

        $this->assertStatus($response, 422);
    }

    public function testValidationNotExistsEmail()
    {
        $response = $this->post('/api/auth/login', [
            'email'     => 'abc@xyz.com',
            'password'  => 'password',
        ]);

        $this->assertStatus($response, 422);
    }

    public function testWrongPassword()
    {
        $user = $this->user();

        $response = $this->post('/api/auth/login', [
            'email'     => $user->email,
            'password'  => 'WRONG PASSWORD',
        ]);

        $this->assertStatus($response, 422);
    }

    public function testLogin()
    {
        $user = $this->user();

        // Assert that the user has no token
        $token = (new AuthToken())->where('user_id', $user->id);
        $this->assertNull($token->first());

        $response = $this->post('/api/auth/login', [
            'email'     => $user->email,
            'password'  => 'password',
        ]);
        $this->assertOk($response);


        // Assert that the user has a token
        $token = $token->first();
        $this->assertNotNull($token->id);
        $this->assertEquals($token->user_id, $user->id);
    }
}
