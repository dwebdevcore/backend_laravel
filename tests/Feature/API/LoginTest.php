<?php

namespace Tests\Feature\API;

use App\Models\User;
use Illuminate\Support\Facades\Artisan;
use Tests\TestCase;

class LoginTest extends TestCase
{

    protected function setUp()
    {
        parent::setUp();
        Artisan::call('passport:install', ['-vvv' => true]);
    }

    /**
     * A basic test example.
     *
     * @return void
     */
    public function testLoginLogout()
    {
        $user        = factory(User::class)->create();
        $credentials = ['email' => $user->email, 'password' => 'secret'];

        /**
         * Login
         */
        $response = $this->json('POST', '/api/v1/login', $credentials);
        $response
            ->assertStatus(200)
            ->assertJsonStructure(['token']);

        /**
         * Logout
         */
        $token = $response->json('token');

        $response = $this->json('DELETE', '/api/v1/login', [], [
            'Authorization' => 'Bearer ' . $token
        ]);

        $response->assertStatus(200);
    }
}
