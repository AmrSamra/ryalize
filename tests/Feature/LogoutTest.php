<?php

namespace Tests\Feature;

use App\Models\AuthToken;
use Tests\FeatureTestCase;

class LogoutTest extends FeatureTestCase
{
    public function testWithMissingToken()
    {
        $response = $this->get('/api/auth/logout');

        $this->assertStatus($response, 401);
    }

    public function testWithInValidToken()
    {
        $response = $this->get('/api/auth/logout', [], [
            'Authorization' => 'Bearer 123456789'
        ]);

        $this->assertStatus($response, 401);
    }

    public function testLogout()
    {
        $user = $this->user();

        $token = $user->generateToken();

        $response = $this->get('/api/auth/logout', [], [
            'Authorization' => 'Bearer ' . $token->token
        ]);

        $this->assertOk($response);


        // Assert token is deleted
        $token = new AuthToken();
        $token = $token->where('token', $token->token)->first();
        $this->assertNull($token);
    }
}
