<?php

namespace Tests\Feature;

use Illuminate\Support\Facades\Artisan;
use Tests\TestCase;

class RegisterTest extends TestCase
{

    protected function setUp()
    {
        parent::setUp();
        Artisan::call('passport:install', ['-vvv' => true]);
    }

    /**
     * Register user with email and password
     *
     * @return void
     */
    public function testEmailPasswordSuccess()
    {
        $user        = factory(\App\Models\User::class)->make();
        $credentials = [
            'email'    => $user->email,
            'password' => 'secret'
        ];

        $response = $this->json('POST', '/api/v1/register', $credentials);
        $response
            ->assertStatus(200)
            ->assertJsonStructure(['token']);

        $this->assertDatabaseHas('users', [
            'email' => $user->email
        ]);
    }

    /**
     * Register user with phone number
     */
    public function testPhoneSuccess() {
        $user        = factory(\App\Models\User::class)->make();
        $credentials = [
            'phone' => $user->phone
        ];

        $response = $this->json('POST', '/api/v1/register', $credentials);
        $response
            ->assertStatus(200)
            ->assertJsonStructure(['token']);

        $this->assertDatabaseHas('users', [
            'phone' => $user->phone
        ]);
    }

    /**
     * Test with email that already has been taken
     */
    public function testEmailPasswordError() {
        $user        = factory(\App\Models\User::class)->create();
        $credentials = [
            'email'    => $user->email,
            'password' => 'secret'
        ];
        $response = $this->json('POST', '/api/v1/register', $credentials);
        $response
            ->assertStatus(422)
            ->assertJsonStructure([
                'message',
                'errors'
            ]);
    }

    /**
     * Test with phone that already has been taken
     */
    public function testPhoneError() {
        $user        = factory(\App\Models\User::class)->create();
        $credentials = [
            'phone' => $user->phone
        ];

        $response = $this->json('POST', '/api/v1/register', $credentials);
        $response
            ->assertStatus(422)
            ->assertJsonStructure([
                'message',
                'errors'
            ]);
    }
}
