<?php

namespace Tests\Feature;

use App\Models\User;
use Tests\FeatureTestCase;

class UpdateTest extends FeatureTestCase
{
    /**
     * Test profile
     * @return void
     */
    public function testUnAuthorized(): void
    {
        $response = $this->put('/api/auth/update');

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

        $uniqueName = uniqid();

        $response = $this->put('/api/auth/update', [
            'name'      => $uniqueName,
            'email'     => $uniqueName . '@test.com',
            'password'  => '123456'
        ], [
            'Authorization' => 'Bearer ' . $token->token
        ]);

        $this->assertStatus($response, 200);

        $user = (new User())->find($user->id);
        $this->assertEquals($user->name, $uniqueName);
        $this->assertEquals($user->email, $uniqueName . '@test.com');
    }
}
