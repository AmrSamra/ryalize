<?php

namespace Tests\Feature;

use Tests\FeatureTestCase;

class TransactionTest extends FeatureTestCase
{
    public function testIndexUnAuthorized()
    {
        $response = $this->get('/api/transactions');

        $this->assertStatus($response, 401);
    }

    public function testIndex()
    {
        $user = $this->user();

        $token = $user->generateToken();

        $response = $this->get('/api/transactions', [], [
            'Authorization' => 'Bearer ' . $token->token
        ]);

        $this->assertOk($response);
    }
}
