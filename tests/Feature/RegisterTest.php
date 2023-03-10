<?php

namespace Tests\Feature;

use App\Models\User;
use Tests\FeatureTestCase;

class RegisterTest extends FeatureTestCase
{
    public function testValidationNoFields()
    {
        $response = $this->post('/api/auth/register');

        $this->assertStatus($response, 422);
    }

    public function testValidationInvalidName()
    {
        $response = $this->post('/api/auth/register', [
            'name'      => 'te@st',
            'email'     => 'abc@xyz.com',
            'password'  => 'password',
        ]);

        $this->assertStatus($response, 422);
    }

    public function testValidationInvalidEmail()
    {
        $response = $this->post('/api/auth/register', [
            'name'      => 'test',
            'email'     => 'invalid',
            'password'  => 'password',
        ]);

        $this->assertStatus($response, 422);
    }

    public function testValidationInvalidPassword()
    {
        $response = $this->post('/api/auth/register', [
            'email'     => 'test',
            'email'     => 'abc@xyz.com',
            'password'  => 'ddsa',
        ]);

        $this->assertStatus($response, 422);
    }

    public function testValidationExistsEmail()
    {
        $user = $this->user();

        $response = $this->post('/api/auth/register', [
            'name'      => $user->name,
            'email'     => $user->email,
            'password'  => 'password',
        ]);

        $this->assertStatus($response, 422);
    }

    public function testRegister()
    {
        $email = 'user_' . uniqid() . '@test.com';

        $response = $this->post('/api/auth/register', [
            'name'      => 'User' . uniqid(),
            'email'     => $email,
            'password'  => 'password',
        ]);

        $this->assertOk($response);

        // Asset user is created
        $user = new User();
        $user = $user->where('email', $email)->first();
        $this->assertNotNull($user);
    }
}
