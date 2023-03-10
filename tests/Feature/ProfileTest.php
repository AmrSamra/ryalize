<?php

namespace Tests\Feature;

use Tests\FeatureTestCase;

class ProfileTest extends FeatureTestCase
{
    /**
     * Test profile
     * @return void
     */
    public function testUnAuthorized(): void
    {
        $response = $this->get('/api/auth/profile');

        $this->assertStatus($response, 401);
    }

    /**
     * Test profile
     * @return void
     */
    public function testAuthorized(): void
    {
        $user = $this->user();

        $token = $user->generateToken();

        $response = $this->get('/api/auth/profile', [], [
            'Authorization' => 'Bearer ' . $token->token
        ]);

        $this->assertStatus($response, 200);
    }
}
